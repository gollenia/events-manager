<?php
/**
 * Parent class for single taxonomy objects, which are essentially WP_Term objects.
 * @since 5.7.3.2
 */
class EM_Taxonomy_Term extends EM_Object {
	
	//Class-overridable options
	public $option_name = 'taxonomy';
	
	//Taxonomy Fields
	var $id = '';
	var $term_id;
	var $name;
	var $slug;
	var $term_group;
	var $term_taxonomy_id;
	var $taxonomy; //initially used to supply string representing constant containing name of the specific taxonomy type
	var $description = '';
	var $parent = 0;
	var $count;
	//extra attributes imposed by EM Taxonomies
	var $image_url = '';
	var $color;
	var $link;

	/**
	 * Gets data from POST (default), supplied array, or from the database if an ID is supplied
	 * @param mixed $taxonomy_data
	 */
	public function __construct( $taxonomy_data = false ){
		
		//Initialize
		$this->taxonomy = constant($this->taxonomy);
		$taxonomy = array();
		if( !empty($taxonomy_data) ){
			//Load taxonomy data
			if( is_object($taxonomy_data) && !empty($taxonomy_data->taxonomy) && $taxonomy_data->taxonomy == $this->taxonomy ){
				$taxonomy = $taxonomy_data;
			}elseif( !is_numeric($taxonomy_data) ){
				$taxonomy = get_term_by('slug', $taxonomy_data, $this->taxonomy);
				if( !$taxonomy ){
					$taxonomy = get_term_by('name', $taxonomy_data, $this->taxonomy);				    
				}
			}else{		
				$taxonomy = get_term_by('id', $taxonomy_data, $this->taxonomy);
			}
		}
		if( is_object($taxonomy) || is_array($taxonomy) ){
			foreach($taxonomy as $key => $value){
				$this->$key = $value;
			}
		}
		$this->id = $this->term_id; //backward compatability
		
		do_action('em_'.$this->option_name, $this, $taxonomy_data);
	}

	/**
	 * Returns a single EM_Taxonomy_Term child class instance based on supplied ID or slug and taxonony class name, such as EM_Tag or EM_Category.
	 * 
	 * Shortcut using __construct since it checks globals and any other caches before executing a __construct call.  
	 * 
	 * @param int|string|EM_Taxonomy_Term $id ID or slug. Name is not used for globals check since mixups between terms with same name/slug values.
	 * @param string $taxonomy_class The name of the EM class used for this taxonomy.
	 * @return EM_Taxonomy_Term
	 */
	public static function get( $id, $taxonomy_class = 'EM_Taxonomy_Term' ){
		//check if it's not already global so we don't instantiate again
		$EM_Taxonomy = !empty( $GLOBALS[$taxonomy_class] ) ? $GLOBALS[$taxonomy_class] : '';
		if( is_object($EM_Taxonomy) && get_class($EM_Taxonomy) == $taxonomy_class ){
			if( $EM_Taxonomy->term_id == $id || $EM_Taxonomy->slug == $id ){
				return $EM_Taxonomy;
			}elseif( is_object($id) && $EM_Taxonomy->term_id == $id->term_id ){
				return $EM_Taxonomy;
			}
		}
		if( is_object($id) && get_class($id) == $taxonomy_class ){
			return $id;
		}else{
			return new $taxonomy_class($id);
		}
	}
	
	public function get_url(){
		if( empty($this->link) ){
			$this->link = get_term_link($this->slug, $this->taxonomy);
			if ( is_wp_error($this->link) ) $this->link = '';
		}
		return apply_filters('em_'. $this->option_name .'_get_url', $this->link);
	}

	public function get_ical_url(){
		global $wp_rewrite;
		if( !empty($wp_rewrite) && $wp_rewrite->using_permalinks() ){
			$return = trailingslashit($this->get_url()).'ical/';
		}else{
			$return = add_query_arg(['ical'=>1], $this->get_url());
		}
		return apply_filters('em_'. $this->option_name .'_get_ical_url', $return);
	}

	public function get_rss_url(){
		global $wp_rewrite;
		if( !empty($wp_rewrite) && $wp_rewrite->using_permalinks() ){
			$return = trailingslashit($this->get_url()).'feed/';
		}else{
			$return = add_query_arg(['feed'=>1], $this->get_url());
		}
		return apply_filters('em_'. $this->option_name .'_get_rss_url', $return);
	}
	
	public function get_color(){
		if( empty($this->color) ){
			$color = wp_cache_get($this->term_id, 'em_'.$this->option_name.'_colors');
			if( $color ){
				$this->color = $color;
			}else{
				global $wpdb;
				$color = $wpdb->get_var('SELECT meta_value FROM '.EM_META_TABLE." WHERE object_id='{$this->term_id}' AND meta_key='". $this->option_name ."-bgcolor' LIMIT 1");
				$this->color = ($color != '') ? $color:get_option('dbem_'.$this->option_name.'_default_color', '#FFFFFF');
				wp_cache_set($this->term_id, $this->color, 'em_'.$this->option_name.'_colors');
			}
		}
		return $this->color;
	}
	
	public function get_image_url( $size = 'full' ){
		if( empty($this->image_url) ){
			global $wpdb;
			$image_url = $wpdb->get_var('SELECT meta_value FROM '.EM_META_TABLE." WHERE object_id='{$this->term_id}' AND meta_key='". $this->option_name ."-image' LIMIT 1");
			$this->image_url = ($image_url != '') ? $image_url:'';
		}
		return $this->image_url;
	}
	
	public function get_image_id(){
		if( empty($this->image_id) ){
			global $wpdb;
			$image_id = $wpdb->get_var('SELECT meta_value FROM '.EM_META_TABLE." WHERE object_id='{$this->term_id}' AND meta_key='". $this->option_name ."-image-id' LIMIT 1");
			$this->image_id = ($image_id != '') ? $image_id:'';
		}
		return $this->image_id;
	}
	
	public function output($format, $target="html") {
		preg_match_all('/\{([a-zA-Z0-9_]+)\}([^{]+)\{\/[a-zA-Z0-9_]+\}/', $format, $conditionals);
		if( count($conditionals[0]) > 0 ){
			//Check if the language we want exists, if not we take the first language there
			foreach($conditionals[1] as $key => $condition){
				$format = str_replace($conditionals[0][$key], apply_filters('em_'. $this->option_name .'_output_condition', '', $condition, $conditionals[0][$key], $this), $format);
			}
		}
		$taxonomy_string = $format;		 
	 	preg_match_all("/(#@?_?[A-Za-z0-9_]+)({([^}]+)})?/", $format, $placeholders);
	 	$replaces = array();
	 	$ph = strtoupper($this->option_name);
		foreach($placeholders[1] as $key => $result) {
			$replace = '';
			$full_result = $placeholders[0][$key];
			$placeholder_atts = array($result);
			if( !empty($placeholders[3][$key]) ) $placeholder_atts[] = $placeholders[3][$key];
			switch( $result ){
				case '#_'. $ph .'NAME':
					$replace = $this->name;
					break;
				case '#_'. $ph .'ID':
					$replace = $this->term_id;
					break;
				case '#_'. $ph .'NOTES':
				case '#_'. $ph .'DESCRIPTION':
					$replace = $this->description;
					break;
				case '#_'. $ph .'IMAGEURL':
					$replace = esc_url($this->get_image_url());
					break;
				case '#_'. $ph .'IMAGE':
					if( $this->get_image_url() != ''){
						$image_url = esc_url($this->get_image_url());
						if( empty($placeholders[3][$key]) ){
							$replace = "<img src='".esc_url($this->get_image_url())."' alt='".esc_attr($this->name)."'/>";
						}else{
							$image_size = explode(',', $placeholders[3][$key]);
							if( is_array($image_size) && array_is_list($image_size) && count($image_size) > 1 ){
								if( $this->get_image_id() ){
									//get a thumbnail
									$replace = wp_get_attachment_image($this->get_image_id(), $image_size);
								}
							}else{
								$replace = "<img src='".esc_url($this->get_image_url())."' alt='".esc_attr($this->name)."'/>";
							}
						}
					}
					break;
				case '#_'. $ph .'COLOR':
					$replace = $this->get_color(); 
					break;
				case '#_'. $ph .'LINK':
				case '#_'. $ph .'URL':
					$link = $this->get_url();
					$replace = ($result == '#_'. $ph .'URL') ? $link : '<a href="'.$link.'">'.esc_html($this->name).'</a>';
					break;
				case '#_'. $ph .'ICALURL':
				case '#_'. $ph .'ICALLINK':
					$replace = $this->get_ical_url();
					if( $result == '#_'. $ph .'ICALLINK' ){
						$replace = '<a href="'.esc_url($replace).'">iCal</a>';
					}
					break;
				case '#_'. $ph .'WEBCALURL':
				case '#_'. $ph .'WEBCALLINK':
					$replace = $this->get_ical_url();
					$replace = str_replace(array('http://','https://'), 'webcal://', $replace);
					if( $result == '#_'. $ph .'WEBCALLINK' ){
						$replace = '<a href="'.esc_url($replace).'">Webcal</a>';
					}
					break;
				case '#_'. $ph .'RSSURL':
				case '#_'. $ph .'RSSLINK':
					$replace = $this->get_rss_url();
					if( $result == '#_'. $ph .'RSSLINK' ){
						$replace = '<a href="'.esc_url($replace).'">RSS</a>';
					}
					break;
				case '#_'. $ph .'SLUG':
					$replace = $this->slug;
					break;
				default:
					$replace = $full_result;
					break;
			}
			$replaces[$full_result] = apply_filters('em_'. $this->option_name .'_output_placeholder', $replace, $this, $full_result, $target, $placeholder_atts);
		}
		krsort($replaces);
		foreach($replaces as $full_result => $replacement){
			$taxonomy_string = str_replace($full_result, $replacement , $taxonomy_string );
		}
		return apply_filters('em_'. $this->option_name .'_output', $taxonomy_string, $this, $format, $target);	
	}
	
	public function placeholder_image( $replace, $placeholders, $key ){	
		if( $this->get_image_url() != ''){
			$image_url = esc_url($this->get_image_url());
			if( empty($placeholders[3][$key]) ){
				$replace = "<img src='".esc_url($this->get_image_url())."' alt='".esc_attr($this->name)."'/>";
			}else{
				$image_size = explode(',', $placeholders[3][$key]);
				if( is_array($image_size) && array_is_list($image_size) && count($image_size) > 1 ){
					if( $this->get_image_id() ){
						//get a thumbnail
						$replace = wp_get_attachment_image($this->get_image_id(), $image_size);
					}
				}else{
					$replace = "<img src='".esc_url($this->get_image_url())."' alt='".esc_attr($this->name)."'/>";
				}
			}
		}
		return $replace;
	}
	
	public function can_manage( $capability_owner = 'edit_event_taxonomy', $capability_admin = false, $user_to_check = false ){
		$can_manage = current_user_can($capability_owner);
		return $can_manage;
	}
}
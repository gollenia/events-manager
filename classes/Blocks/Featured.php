<?php
namespace Contexis\Events\Blocks;

use Contexis\Events\Assets;

class Featured {

	public array $args;
	
	public $blockname = 'featured';

    public static function init() {

		$instance = new self;
        $instance->args = Assets::$args;
		
		add_action('init', [$instance, 'register_block']);
        
       
    }

	public function get_block_meta() {
		
		$filename = \Events::DIR . "/src/blocks/featured/block.json";
		
		if(!file_exists($filename)) {    
			return false;
		}
		$string = file_get_contents($filename);
		
		return array_merge(json_decode($string, true), $this->args);
		
	}

	function register_block() {	
		$meta = $this->get_block_meta();
		$meta['render_callback'] = [$this,'render'];
		register_block_type($meta['name'], $meta);
	}     

	public function render($attributes, $content, $full_data) : string {
        $attributes['event'] = $this->get_event($attributes);

		$attributes['speaker'] = false;
		if($attributes['event']->speaker_id) {
			$attributes['speaker'] = $this->get_speaker($attributes['event']->speaker_id);
		}

        $attributes['locations'] = $this->get_locations();
		
        $template = $this->get_template($full_data->name);
        
        return \Timber\Timber::compile($template, $attributes);
        
    }

	public function get_template($name) : string { 
        $filename = substr($name, strpos($name, "/")+1) . ".twig";
        
        if(file_exists(get_template_directory() . "/plugins/events/" . $filename)) {
            return get_template_directory() . '/plugins/events/' . $filename;
        }
		
		if(file_exists(get_stylesheet_directory() . "/plugins/events/templates/blocks/" . $filename)) {
			return get_stylesheet_directory() . '/plugins/events/templates/blocks/' . $filename;
		}

        return \Events::DIR . '/templates/blocks/' . $filename;
    }
    
    private function get_locations() {
        
        $args = [
            'post_type' => 'location',
            'post_status' => ['publish'],
        ];
        return \Timber\Timber::get_posts( $args );
    }

	private function get_speaker($id) {
		$args = [
            'p' => $id,
            'post_type' => 'event-speaker'
        ];

        $speakers = \Timber\Timber::get_posts( $args );
        
        if ($speakers) return $speakers[0];
	}

    private function get_event($attributes) {

		$tax_query = [];



        if($attributes['selectedCategory']) {
            array_push($tax_query, [
                'taxonomy' => 'event-categories',
                'terms' => $attributes['selectedCategory'],
                'field' => 'id'
            ]);
        }

        if($attributes['selectedTags']) {
            array_push($tax_query, [
                'taxonomy' => 'event-tags',
                'terms' => $attributes['selectedTags'],
                'field' => 'id'
            ]);
        }

        $args = [
            'post_type' => 'event',
            'orderby' => '_event_start_date',
            'order' => "DESC",
            'post_status' => ['publish'],
            
            'tax_query' => !empty($tax_query) ? $tax_query : null, 
            'posts_per_page' => 1,

        ];

		$events = \Timber\Timber::get_posts( $args );
		
        return empty($events) ? false : $events[0];

    }

}

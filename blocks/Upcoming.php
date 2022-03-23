<?php
namespace Contexis\Events\Blocks;

class Upcoming {

	public array $args;
	
	public $blockname = 'upcoming';

    public static function init(Assets $assets) {

		$instance = new self;
        $instance->args = $assets->args;
		
		add_action('init', [$instance, 'register_block']);
        
       
    }

	public function get_block_meta() {
		
		$filename = EM_DIR . "/blocks/src/upcoming/block.json";
		
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

	/**
	 * Undocumented function
	 *
	 * @param [type] $attributes
	 * @param [type] $content
	 * @param [type] $full_data
	 * @return string
	 */
    public function render($attributes, $content, $full_data) : string {
        $block_id = uniqid();
        $result = "<div class='events-upcoming-block' data-id='" . $block_id . "'></div>";
		$result .= "<script>";
		$result .= "if (typeof document.event_block_data === 'undefined') { document.event_block_data = {}; }";
		$result .= "document.event_block_data['" . $block_id . "']=" . json_encode($attributes) . "</script>";
		return $result;
    }

}

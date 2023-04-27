<?php

namespace Contexis\Events\Blocks;

use Contexis\Events\Assets;

/**
 * Base class for all blocks
 */
class Block {

	private $args = [];
	public $blockname = '';

	public static function init()
	{
		$instance = new self;
		$instance->args = Assets::$args;
		add_action('init', [$instance, 'register_block']);
	}

	public static function get_block_meta($blockname = "") : array
	{
		if(!$blockname) return [];
		$filename = \Events::DIR . "/src/blocks/" . $blockname . "/block.json";

		if (!file_exists($filename)) {
			var_dump("File not found: " . $filename);
			return false;
		}
		$string = file_get_contents($filename);

		return json_decode($string, true);
	}

	function register_block()
	{
		$meta = array_merge(self::get_block_meta($this->blockname), $this->args);
		$meta['render_callback'] = [$this, 'render'];
		register_block_type($meta['name'], $meta);
	}

	public function render($attributes, $content, $full_data) : string
	{
		return "";
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
}

Upcoming::init();
Featured::init();
Details::init();
Booking::init();
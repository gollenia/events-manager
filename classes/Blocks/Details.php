<?php
namespace Contexis\Events\Blocks;

use Contexis\Events\Intl\Price;
use Contexis\Events\Assets;

class Details extends Block {

	public array $args;
	
	public $blockname = 'details';

    public static function init() {
		$instance = new self;
		$instance->args = Assets::$args;
		add_action('init', [$instance, 'register_block']);
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
        $attributes['event'] = $this->get_event();
		
		$attributes['overwrittenPrice'] = $this->get_overwrite_price($attributes['priceOverwrite']);
		
		$attributes['is_daterange'] = wp_date('jY', $attributes['event']['start']) !== wp_date('jY', $attributes['event']['end']);
		$template = $this->get_template($full_data->name);
        
        return \Timber\Timber::compile($template, $attributes);

    }

	public function get_overwrite_price($price) {
		if(!$price) {
			return "";
		}
		if(is_numeric($price)) {
			$formatter = new Price($price);
			return $formatter->get_format();
		}

		return $price;

	}
     
    private function get_event() {

		global $post;
		$events = \EM_Events::get_rest(['post_id' => $post->id]);
		if(count($events) > 0) {
			return $events[0];
		}
		return [];

    }

}


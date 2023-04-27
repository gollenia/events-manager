<?php
namespace Contexis\Events\Blocks;

use Contexis\Events\Assets;

class Featured extends Block {

	public array $args;
	
	public $blockname = 'featured';

    public static function init() {
		$instance = new self;
		$instance->args = Assets::$args;
		add_action('init', [$instance, 'register_block']);
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

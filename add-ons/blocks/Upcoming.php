<?php
namespace Schedule\Addons;

class Upcoming extends Block {

    public $blocks = [
        "upcoming"
    ];

    public function render($attributes, $content, $full_data) : string {
        $attributes['events'] = $this->get_events($attributes);
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

    private function get_events($attributes) {

        $meta_query = [
            [
                'key' => '_event_start_date',
                'value' => $attributes['fromDate'] ?: date('Y-m-d'),
                'compare' => '>='
            ]
            ];

        if($attributes['toDate']) {
            array_push($meta_query, [
                'key' => '_event_start_date',
                'value' => $attributes['toDate'],
                'compare' => '<'
            ]);
        }

        if($attributes['selectedLocation']) {
            array_push($meta_query, [
                'key' => 'location_id',
                'value' => $attributes['selectedLocation'],
                'compare' => '=='
            ]);
        }

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
            'order' => $attributes['order'],
            'post_status' => ['publish'],
            'meta_query' => $meta_query,
            'tax_query' => !empty($tax_query) ? $tax_query : null, 
            'posts_per_page' => $attributes['limit'],

        ];

        return \Timber\Timber::get_posts( $args );

    }

}

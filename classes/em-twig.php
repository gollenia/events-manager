<?php

class EM_Twig {
    
    private $twig;
    public $locations = [];

    public static function init() {
        $instance = new self;
        $instance->add_locations();

        return $instance;
        
        //$instance->twig->addFunction( new \Twig\TwigFunction( 'option', [$instance,'option'] ) );
    }

    public function option($option) {
        return get_option($option);
    }

    private function add_locations() {
        if(is_dir(get_theme_file_path() . "/plugins/events/")) {
            array_push($this->locations, get_theme_file_path() . '/plugins/events/');
        }
    
        if(is_dir(get_template_directory() . "/plugins/events/")) {
            array_push($this->locations, get_template_directory() . 'events/');
        }
    
        array_push($this->locations, EM_DIR . '/' . 'templates/');
    }
}


<?php
namespace Schedule\Addons;

class TwigExtend {

    public static function add_to_twig($twig) {
        $twig->addFunction( new \Twig\TwigFunction( 'get_event_location', [__CLASS__,'get_event_location'] ) );
        return $twig;
    }

    public static function get_event_location($id) {
        return \EM_Locations::get( $id )[0];
    }
}
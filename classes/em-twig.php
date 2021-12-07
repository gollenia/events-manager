<?php

class EM_Twig {
    
    public static function init($twig) {
        $twig->addFunction( new \Twig\TwigFunction( 'option', [__CLASS__,'option'] ) );
        return $twig;
    }

    public static function option($option) {
        return get_option($option);
    }
}


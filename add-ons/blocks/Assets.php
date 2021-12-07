<?php
namespace Schedule\Addons;

class Assets {

    public static function register() {
        

        add_action( 'init', function() {
            $dir = __DIR__;

            $script_asset_path = "$dir/../../includes/backend.asset.php";
            if ( ! file_exists( $script_asset_path ) ) {
                  throw new \Error(
                          'You need to run `npm start` or `npm run build` for the "create-block/ctx-blocks" block first.'
                 );
            }
            $index_js     = '../../includes/backend.js';
            $script_asset = require( $script_asset_path );
            wp_register_script(
                  'event-manager-blocks-block-editor',
                  plugins_url( $index_js, __FILE__ ),
                  $script_asset['dependencies'],
                  $script_asset['version']
            );
            wp_set_script_translations( 'event-manager-blocks', 'em-pro', plugin_dir_path( __FILE__ ) . '../languages' );
            
            $editor_css = '../../includes/backend.css';

            wp_register_style(
                     'event-manager-blocks-block-editor',
                     plugins_url( $editor_css, __FILE__ ),
                     array(),
                     filemtime( "$dir/$editor_css" )
            );
    
             
    
            
        } );

       

        return [
            'style'         => 'event-manager-blocks-block-style',
            'editor_script' => 'event-manager-blocks-block-editor',
            'editor_style'  => 'event-manager-blocks-block-editor',
        ];
    
    }

   
}

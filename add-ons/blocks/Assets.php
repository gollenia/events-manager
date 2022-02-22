<?php
namespace Schedule\Addons;

class Assets {

    public static function register() {

		$instance = new self;

		if( !is_admin()) add_action('init', [$instance, 'frontend_script']);
		add_action('admin_init', [$instance, 'backend_script']);    

        return [
            'style'         => 'event-manager-blocks-block-style',
            'editor_script' => 'events-block-editor',
            'editor_style'  => 'events-block-style',
        ];
    
    }

	public function frontend_script() {
		$dir = __DIR__;
		$script_asset_path = "$dir/../../includes/frontend.asset.php";
		if ( ! file_exists( $script_asset_path ) ) {
			throw new \Error(
					'You need to run `npm start` or `npm run build` for the events-blocks first.'
			);
		}
		$index_js     = '../../includes/frontend.js';
		$script_asset = require( $script_asset_path );
		wp_enqueue_script(
			'events-block-frontend',
			plugins_url( $index_js, __FILE__ ),
			$script_asset['dependencies'],
			$script_asset['version']
		);
		wp_set_script_translations( 'events-block-frontend', 'events', plugin_dir_path( __FILE__ ) . '../../languages' );

	}

	public function backend_script() {
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
			'events-block-editor',
			plugins_url( $index_js, __FILE__ ),
			$script_asset['dependencies'],
			$script_asset['version']
		);
		wp_set_script_translations( 'events-block-editor', 'events', plugin_dir_path( __FILE__ ) . '../../languages' );
		
		$editor_css = '../../includes/backend.css';

		wp_register_style(
				'events-block-style',
				plugins_url( $editor_css, __FILE__ ),
				array(),
				filemtime( "$dir/$editor_css" )
		);
	}

   
}

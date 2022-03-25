<?php

namespace Contexis\Events\Addons;

class Export {

	var $plugin_name = "PDF Export";

	public static function init() {
		$instance = new self;
		
		//add_action('em_create_events_submenu', [$instance, 'submenu'],10,1);

		
	}



}

Export::init();
require_once('ExportAdmin.php');
require_once('ExportApi.php');
<?php 

class EM_Update {


    public static function init()
    {
        $self = new self();
        add_filter( 'admin_init', ["EM_Update", 'update_location_table'], 10, 3);
    }

    public static function update_location_table(){
        global $wpdb;
		$dbname = $wpdb->dbname;
		$table_name = $wpdb->prefix.'em_locations';
        $has_location_column = $wpdb->get_results(  "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `table_name` = '{$table_name}' AND `TABLE_SCHEMA` = '{$dbname}' AND `COLUMN_NAME` = 'location_link'"  );
        
		if(empty($has_location_column)){
			$add_status_column = "	ALTER TABLE `{$table_name}` ADD `location_link` VARCHAR(400) NULL DEFAULT NULL AFTER `location_longitude`; ";

    		$wpdb->query( $add_status_column );
			
		}
    }
}


EM_Update::init();
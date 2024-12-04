<?php
class EM_Gateway_ML {
    
    public static function init(){
        add_action('em_updated_gateway_options', 'EM_Gateway_ML::em_updated_gateway_options', 10, 2);        
    }
    
    public static function em_updated_gateway_options($options, $EM_Gateway){
    	//multilingual, same as above, should be triggered by action above
    	foreach( $options as $option_name ){
    	    
    	}
    }
}
EM_Gateway_ML::init();
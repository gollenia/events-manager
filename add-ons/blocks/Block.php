<?php

namespace Schedule\Addons;

interface BlockInterface
{
    public function __construct($args);
    public function register() : void;
    public function render($attributes, $content, $full_data) : string;
    public function get_template($name) : string;
    public function get_meta(string $block);
}

class Block implements BlockInterface {

    public $blocks = [
        // no non-dynamic blocks to register yet
    ];

    public $blocks_to_register = [];

    public $args;

    public function __construct($args) {

        $this->args = $args;
        
        foreach($this->blocks as $block) {
            $meta = $this->get_meta($block);
            if($meta) {
                array_push($this->blocks_to_register, $meta); 
            }
        }
    }

    public function get_meta($block) :array{
        
        $filename = plugin_dir_path( __FILE__ ) . "/blocks/" . $block . "/block.json";
        
        if(!file_exists($filename)) {    
            return false;
        }
        $string = file_get_contents($filename);
        return json_decode($string, true);
    }

    public function register() : void {
        $blocks = $this->blocks_to_register;
        $args = $this->args;
        $args['render_callback'] = [$this, "render"];
        
        if(empty($blocks)) {
            return;
        }
        
        add_action( 'init', function() use(&$blocks, &$args){
            for($i = 0; $i < count($blocks); $i++) {
                $args['attributes'] = $blocks[$i]['attributes'];
				$args['api_version'] = 2;
                register_block_type(
                    $blocks[$i]["name"], $args
                );
            }
            
        });    
    }

    public function render($attributes, $content, $full_data) : string {
        $template = $this->get_template($full_data->name);

        $attributes['content'] = $content;

        //echo "<script>console.log(" . json_encode($attributes) . ");</script>";

        if(count($full_data->parsed_block['innerBlocks']) > 0) {
            $attributes['children'] = $full_data->parsed_block['innerBlocks'];
        }
        
        return \Timber\Timber::compile($template, $attributes); 
    }

    /**
     * @todo Repair this function
     *
     * @param string $name
     * @return string
     */
    public function get_template($name) : string {
        $filename = substr($name, strpos($name, "/")+1) . ".twig";
        
        if(file_exists(get_template_directory() . "/plugins/events/" . $filename)) {
            return get_template_directory() . 'events/' . $filename;
        }

        return plugin_dir_path( __FILE__ ) . '../../templates/' . $filename;
    }

}
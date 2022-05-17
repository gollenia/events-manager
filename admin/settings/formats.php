<?php if( !function_exists('current_user_can') || !current_user_can('manage_options') ) return; ?>
<!-- FORMAT OPTIONS -->
<div class="em-menu-formats em-menu-group"  <?php if( !defined('EM_SETTINGS_TABS') || !EM_SETTINGS_TABS) : ?>style="display:none;"<?php endif; ?>>				
	

	
	      
  
    
	
	
	<?php do_action('em_options_page_footer_formats'); ?>
	
</div> <!-- .em-menu-formats -->
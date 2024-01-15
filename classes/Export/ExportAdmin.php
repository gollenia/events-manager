<?php

namespace Contexis\Events\Addons;

use \Contexis\Events\Options;

class ExportAdmin {

	var $plugin_name = "PDF Export";

	public static function init() {
		$instance = new self;
		//add_action('em_options_page_footer_pages', [$instance, 'admin_page'],10,1);
		add_action('admin_enqueue_scripts', [$instance, 'enqueue_admin_scripts']);
	}



	/**
	 * Add settings here
	 *
	 * @return void
	 */
	public function admin_page() {
		?>
		<div class="postbox closed">
			<div class="handlediv" title=""><br></div>
			<h3><?php _e($this->plugin_name) ?></h3>
			<div class="">
				<p class="em-boxheader"><?php _e("Here you can edit the settings for PDF Export") ?></p>
				<table class="form-table"><tbody>
					<tr><th>Logo</th><td><div class="em-media-select" style="display: flex; gap: 1rem;"><input id="em_export_logo" type="text" name="em_export_logo" value="<?php echo get_option('em_export_logo'); ?>" />
					<input id="upload_image_button" type="button" class="button-primary" value="<?php _e("Select Image", "events"); ?>" /></div></td></tr>
					
					<?php
					Options::input( __( 'Font path', 'em-pro' ), 'em_export_font_path',__('Hint: Theme path is ', 'em-pro') . get_stylesheet_directory());
					Options::input( __( 'Regular Font', 'em-pro' ), 'em_export_font_regular');
					Options::input( __( 'Bold Font', 'em-pro' ), 'em_export_font_bold');
					Options::input( __( 'Italic Font', 'em-pro' ), 'em_export_font_italic');
					?>
				</tbody></table>
			</div>
		</div>

		
		<script>
			jQuery(document).ready(function($){
			let mediaUploader;
			$('#upload_image_button').click(function(e) {
				e.preventDefault();
				if (mediaUploader) {
				mediaUploader.open();
				return;
				}
				mediaUploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Image',
				button: {
				text: 'Choose Image'
				}, multiple: false });
				mediaUploader.on('select', function() {
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				$('#background_image').val(attachment.url);
				});
				mediaUploader.open();
			});
			});
		</script>
		<?php
		
	}

	public function enqueue_admin_scripts() {
		wp_enqueue_media();
	}

}

ExportAdmin::init();
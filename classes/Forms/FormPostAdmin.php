<?php

namespace Contexis\Events\Forms;

class Admin {
	public static function init(){
		$instance = new self;
		add_filter( 'manage_bookingform_posts_columns',  [$instance, 'manage_posts_columns'] );
		return $instance;
	}

	function manage_posts_columns($columns) {
		unset( $columns['date'] );
		$columns['type'] = esc_html__( 'Type', 'events-manager' );
		return $columns;
	}

	public static function option_page() {
		echo "<div class='wrap'>";
		echo "<h1 class='wp-heading-inline' style='margin-bottom: 1rem'>" . esc_html__( 'Booking Forms', 'events-manager' ) . "</h1>";
		?><a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=bookingform')); ?>" class="page-title-action"><?php _e("Create", "events-manager") ?></a><?php
		$the_query = new \WP_Query( ['post_type' => 'bookingform'] );
		?>
		<table class="wp-list-table widefat fixed striped table-view-list posts">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Form Name', 'events-manager' ); ?></th>
					<th><?php esc_html_e( 'Description', 'events-manager' ); ?></th>
					<th><?php esc_html_e( 'Date', 'events-manager' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						?>
						<tr>
							<td class="title column-title has-row-actions column-primary page-title"><strong><a class="row-title" href="<?php echo esc_url( admin_url( 'post.php?post=' . get_the_ID() . '&action=edit' ) ); ?>"><?php the_title(); ?></strong>
							<div class="row-actions">
								<span class="edit"><a href="<?php echo esc_url( admin_url( 'post.php?post=' . get_the_ID() . '&action=edit' ) ); ?>" aria-label="bearbeiten"><?php esc_html_e( 'Edit', 'events-manager' ); ?></a> | </span>
								<span class="trash"><a class="submitdelete" href="<?php echo esc_url( admin_url( 'post.php?post=' . get_the_ID() . '&action=trash' ) ); ?>" aria-label="bearbeiten"><?php esc_html_e( 'Delete', 'events-manager' ); ?></a></span>
							</div>
							</td>
							<td><?php echo the_excerpt(); ?></td>
							<td>
								<?php echo get_the_date(); ?>
							</td>
						</tr>
						<?php
					}
				} ?>
		</table>
		<?php
		echo "<h1 class='wp-heading-inline' style='margin-top: 2rem; margin-bottom: 1rem'>" . esc_html__( 'Attendee Forms', 'events-manager' ) . "</h1>";
		?><a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=attendeeform')); ?>" class="page-title-action"><?php _e("Create", "events-manager") ?></a><?php
		$the_query = new \WP_Query( ['post_type' => 'attendeeform'] );
		?>
		<table class="wp-list-table widefat fixed striped table-view-list posts">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Form Name', 'events-manager' ); ?></th>
					<th><?php esc_html_e( 'Description', 'events-manager' ); ?></th>
					<th><?php esc_html_e( 'Date', 'events-manager' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						?>
						<tr>
							<td class="title column-title has-row-actions column-primary page-title"><strong><a class="row-title" href="<?php echo esc_url( admin_url( 'post.php?post=' . get_the_ID() . '&action=edit' ) ); ?>"><?php the_title(); ?></strong>
							<div class="row-actions">
								<span class="edit"><a href="<?php echo esc_url( admin_url( 'post.php?post=' . get_the_ID() . '&action=edit' ) ); ?>" aria-label="bearbeiten"><?php esc_html_e( 'Edit', 'events-manager' ); ?></a> | </span>
								<span class="trash"><a class="submitdelete" href="<?php echo esc_url( admin_url( 'post.php?post=' . get_the_ID() . '&action=trash' ) ); ?>" aria-label="bearbeiten"><?php esc_html_e( 'Delete', 'events-manager' ); ?></a></span>
							</div>
							</td>
							<td><?php echo the_excerpt(); ?></td>
							<td>
								<?php echo get_the_date(); ?>
							</td>
						</tr>
						<?php
					}
				} ?>
		</table><?php
	}
	
}
Admin::init();

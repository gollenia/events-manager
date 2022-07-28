<?php

namespace Contexis\Events\Admin;

class Pagination {

	const LIMIT = 20;

	/**
	 * Creates a wp-admin style navigation.
	 * @param string $link
	 * @param int $total
	 * @param int $limit
	 * @param int $page
	 * @param int $pagesToShow
	 * @return string
	 * @uses paginate_links()
	 * @uses add_query_arg()
	 */
	function paginate($total, $limit, $page=1, $vars=false, $base = false, $format = ''){
		$return = '<div class="tablenav-pages em-tablenav-pagination">';
		$base = !empty($base) ? $base:esc_url_raw(add_query_arg( 'pno', '%#%' ));
		$events_nav = paginate_links( array(
			'base' => $base,
			'format' => $format,
			'total' => ceil($total / $limit),
			'current' => $page,
			'add_args' => $vars
		));
		$return .= sprintf( '<span class="displaying-num">' . __( 'Displaying %1$s&#8211;%2$s of %3$s', 'events-manager') . ' </span>%4$s',
			number_format_i18n( ( $page - 1 ) * $limit + 1 ),
			number_format_i18n( min( $page * $limit, $total ) ),
			number_format_i18n( $total ),
			$events_nav
		);
		$return .= '</div>';
		return apply_filters('em_admin_paginate',$return,$total,$limit,$page,$vars);
	}

}
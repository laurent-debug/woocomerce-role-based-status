<?php
add_action( 'init', function() {
	register_post_status(
		'wc-planzer-transmit',
		[
			'label' => 'Transmettre à Planzer',
			'public' => true,
			'exclude_from_search' => false,
			'show_in_admin_all_list' => true,
			'show_in_admin_status_list' => true,
			'label_count' => _n_noop( 'Transmettre à Planzer (%s)', 'Transmettre à Planzer (%s)' ),
		]
	);
} );

add_filter( 'wc_order_statuses', function( $statuses ) {
	$statuses['wc-planzer-transmit'] = 'Transmettre à Planzer';
	return $statuses;
} );

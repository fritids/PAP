<?php

define( 'APPTHEMES_ORDER_PTYPE', 'transaction' );
define( 'APPTHEMES_ORDER_CONNECTION', 'order-connection' );

add_action( 'init', 'appthemes_orders_init', 11 );
add_action( 'appthemes_orders_init', 'appthemes_register_orders_post_types' );
add_action( 'admin_menu', 'appthemes_remove_orders_meta_boxes' );

function appthemes_orders_init(){
	
	do_action( 'appthemes_orders_init' );
	
}

function appthemes_register_orders_post_types() {

	$labels = array(
		'name' => __( 'Orders', APP_TD ),
		'singular_name' => __( 'Order', APP_TD ),
		'add_new' => __( 'Add New', APP_TD ),
		'add_new_item' => __( 'Add New Order', APP_TD ),
		'edit_item' => __( 'Edit Order', APP_TD ),
		'new_item' => __( 'New Order', APP_TD ),
		'view_item' => __( 'View Order', APP_TD ),
		'search_items' => __( 'Search Orders', APP_TD ),
		'not_found' => __( 'No orders found', APP_TD ),
		'not_found_in_trash' => __( 'No orders found in Trash', APP_TD ),
		'parent_item_colon' => __( 'Parent Order:', APP_TD ),
		'menu_name' => __( 'Orders', APP_TD ),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => false,
		'supports' => array( 'author', 'custom-fields' ),
		'public' => false,
		'publicly_queryable' => true,
		'show_ui' => true,
		'rewrite' => array('slug' => 'order'),
		'capability_type' => 'transaction',
		'map_meta_cap' => true
	);
	
	// Allow themes to modify post type arguments
	$args = apply_filters( 'appthemes_order_ptype_args', $args );

	register_post_type( APPTHEMES_ORDER_PTYPE, $args );
	
}

function appthemes_remove_orders_meta_boxes() {

	remove_meta_box( 'submitdiv', APPTHEMES_ORDER_PTYPE, 'side' );
	remove_meta_box( 'postcustom', APPTHEMES_ORDER_PTYPE, 'normal' );
	remove_meta_box( 'slugdiv', APPTHEMES_ORDER_PTYPE, 'normal' );

}

function appthemes_new_order() {
	return APP_Order_Factory::build_new();
}

function appthemes_get_order( $order_id ) {
	return APP_Order_Factory::retrieve( $order_id );
}

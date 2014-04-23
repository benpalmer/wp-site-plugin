<?php
/**
 * Plugin Name: _ Site-Specific Plugin
 * Description: Functionality added to theme can be changed. WARNING! Disabling this plugin will remove post types, taxonomies and functionality tweaks.
 * Version: 1.0
 * Author: Ben Palmer
 * Author URI: http://madebyreformat.co.uk
 */

/*
Contents
**************************************************************

	1. Housekeeping
		Add post thumbnail support
		Add excerpt support to pages

	2. Add custom sizes to media upload

	3. CPT / Taxonomies
		Project post type
		Service type taxonomy


*/

/*
Housekeeping
**************************************************************/

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
}

add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}

/*
Add Custom Sizes to Media Upload
**************************************************************/

function my_insert_custom_image_sizes( $sizes ) {
  global $_wp_additional_image_sizes;
  if ( empty($_wp_additional_image_sizes) )
    return $sizes;

  foreach ( $_wp_additional_image_sizes as $id => $data ) {
    if ( !isset($sizes[$id]) )
      $sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
  }

  return $sizes;
}
add_filter( 'image_size_names_choose', 'my_insert_custom_image_sizes' );

/*
Custom Post Types
**************************************************************/

add_action( 'init', 'create_post_type' );
function create_post_type() {
  register_post_type( 'project',
    array(
      'labels' => array(
        'name' => __( 'Projects' ),
        'singular_name' => __( 'Project' )
      ),
    'menu_icon' => '',
    'menu_position' => 5,
    'public' => true,
    'has_archive' => true,
    'supports' => array('title','thumbnail','editor','page-attributes')
    )
  );
}

function add_menu_icons_styles(){
?>
 
<style>
#adminmenu .menu-icon-project div.wp-menu-image:before {
  content: "\f119";
}
</style>
 
<?php
}
add_action( 'admin_head', 'add_menu_icons_styles' );

add_action( 'init', 'create_taxonomies' );
function create_taxonomies() {
  
  $labels = array(
    'name'                       => _x( 'Services', 'Taxonomy General Name', 'text_domain' ),
    'singular_name'              => _x( 'Service', 'Taxonomy Singular Name', 'text_domain' ),
    'menu_name'                  => __( 'Services', 'text_domain' ),
    'all_items'                  => __( 'All Services', 'text_domain' ),
    'parent_item'                => __( 'Parent Service', 'text_domain' ),
    'parent_item_colon'          => __( 'Parent Service:', 'text_domain' ),
    'new_item_name'              => __( 'New Service Name', 'text_domain' ),
    'add_new_item'               => __( 'Add New Service', 'text_domain' ),
    'edit_item'                  => __( 'Edit Service', 'text_domain' ),
    'update_item'                => __( 'Update Service', 'text_domain' ),
    'separate_items_with_commas' => __( 'Separate services with commas', 'text_domain' ),
    'search_items'               => __( 'Search services', 'text_domain' ),
    'add_or_remove_items'        => __( 'Add or remove services', 'text_domain' ),
    'choose_from_most_used'      => __( 'Choose from the most used services', 'text_domain' ),
  );

  $args = array(
    'labels'                     => $labels,
    'rewrite'                    => array( 'slug' => 'service' ),
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true
  );

  register_taxonomy( 'service', 'project', $args );

}
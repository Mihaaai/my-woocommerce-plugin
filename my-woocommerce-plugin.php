<?php
   /*
   Plugin Name: Custom css and functionality
   Plugin URI: http://btttravel.ro
   Description: a plugin to create awesomeness and spread joy
   Version: 1.0
   Author: Mihai Ghidoveanu
   Author URI: http://btttravel.ro
   License: GPL2
   */

   /** Register custom syles*/
   add_action('wp_cards_enqueue_scripts', 'load_my_styles');
   function load_my_styles() {
       wp_register_style( 'my-styles', './style.css' );
       wp_enqueue_style( 'my-style' );
   }

   /** START - REMOVE WOOTHEMES NAVIGATION **/
	add_action( 'init', 'remove_primary_navigation_header', 10 );
	function remove_primary_navigation_header () {
		remove_action( 'storefront_header','storefront_primary_navigation_wrapper', 42 );
		remove_action( 'storefront_header','storefront_primary_navigation', 50 );
		remove_action( 'storefront_header','storefront_header_cart', 60 );
		remove_action( 'storefront_header','storefront_primary_navigation_wrapper_close', 68 );

	}
	/** END - REMOVE WOOTHEMES NAVIGATION **/

   /*Filter storefront home page*/
   add_action('init','my_storefront_homepage');
   function my_storefront_homepage(){
      remove_action('storefront_homepage','storefront_homepage_header',10);
   }

   /* Filter and update storefront homepage content*/
   add_action('init','my_storefront_homepage_content',10);
   function my_storefront_homepage_content(){
      // remove functions we don't need
      remove_action( 'homepage', 'storefront_homepage_content',      10 );      
      remove_action( 'homepage', 'storefront_product_categories',    20 );
      remove_action( 'homepage', 'storefront_recent_products',       30 );
      remove_action( 'homepage', 'storefront_featured_products',     40 );
      remove_action( 'homepage', 'storefront_popular_products',      50 );
      remove_action( 'homepage', 'storefront_on_sale_products',      60 );
      remove_action( 'homepage', 'storefront_best_selling_products', 70 );
      // add the updated function we need
      add_action('homepage', 'my_hook_recent_products',30);
   }
   function my_hook_recent_products( $args ) {

      if ( storefront_is_woocommerce_activated() ) {

         $args = apply_filters( 'storefront_recent_products_args', array(
            'limit'        => 6,
            'columns'         => 6,
            'title'           => __( 'Recently Added', 'storefront' ),
         ) );

         $shortcode_content = storefront_do_shortcode( 'recent_products', apply_filters( 'storefront_recent_products_shortcode_args', array(
            'per_page' => intval( $args['limit'] ),
            'columns'  => intval( $args['columns'] ),
         ) ) );

         /**
          * Only display the section if the shortcode returns products
          */
         if ( false !== strpos( $shortcode_content, 'product' ) ) {

            echo '<section class="storefront-product-section storefront-recent-products" aria-label="' . esc_attr__( 'Recent Products', 'storefront' ) . '">';

            do_action( 'storefront_homepage_before_recent_products' );

            echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';

            do_action( 'storefront_homepage_after_recent_products_title' );

            echo $shortcode_content;

            do_action( 'storefront_homepage_after_recent_products' );

            echo '</section>';

         }
      }
   }


// /*Remove product image zoom on the single page*/
// add_action('after_setup_theme','remove_product_image_features',10);
// function remove_product_image_features(){
//    remove_theme_support( 'wc-product-gallery-zoom' );
// }



?>

<?php
/**
* Plugin Name: Alibi Tech Series Embedder
* Plugin URI: https://www.alibitech.com/
* Description: Wordpress plugin for watch, shop, share widget
* Version: 1.0
* Author: Alibi Tech
* Author URI: https://www.linkedin.com/in/mayank-sethi/
* License: GPLv2 or later
**/

/** Create shortcode for series embedder */

function alibi_tech_series_embed( $alibi_widget_attributes ) {
  $props = shortcode_atts( array(
  'id' => 'alibi_widget',
  'host' => '#',
  'series_id' => '#',
  'class' => 'alibiTechWidgetFilmStrip'
  ), $alibi_widget_attributes );

  $series = esc_url( $props['host'] ).'/series-embed/'.esc_attr( $props['series_id']);

  $output = '
   <div class="alibiTechWidgetContainer">
     <iframe frameBorder="0" src="'.esc_url( $series ).'" id="'.esc_attr( $props['id'] ).'" class="'.esc_attr( $props['class'] ).'"/>
   </div>
  ';

  return $output;
 }

/** Enqueue the Stylesheet for the Series Embedder */

function alibi_tech_enqueue_stylesheet() {
  global $post;
  if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'alibi_widget') ) {
  wp_register_style( 'alibi-stylesheet',  plugins_url( '/css/style.css', __FILE__ ));
  wp_enqueue_style( 'alibi-stylesheet' );
  }
 }

 /** Enqueue the Scripts for the Series Embedder */

function alibi_tech_enqueue_scripts() {
  global $post;
  $content = apply_filters( 'the_content', get_the_content() );

  if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'alibi_widget') ) {
    wp_register_script( 'alibi-tech-widget-lib', plugins_url( '/js/main.js', __FILE__ ),array( 'jquery' ));

    wp_enqueue_script( 'alibi-tech-widget-lib');

    wp_localize_script( 'alibi-tech-widget-lib', 'alibi_tech_widget_lib_ajax_object',
      array(
      'id' => 'alibi_widget',
      'post' => $post
      )
    );
  }
}

add_shortcode( 'alibi_widget', 'alibi_tech_series_embed', 10 );
add_action( 'wp_head', 'alibi_tech_enqueue_stylesheet', 1);
add_action( 'wp_enqueue_scripts', 'alibi_tech_enqueue_scripts', 1);

?>

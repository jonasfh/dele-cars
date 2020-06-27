<?php
/**
 * Plugin Name: Dele
 */

//<!DOCTYPE html>

// Add leaflet stylesheet:

// [carmap url="https://app.dele.no/api/search"]
function carmap_func( $atts ) {
  $a = shortcode_atts( array(
    'url' => "https://app.dele.no/api/search",
    'token' => '',
  ), $atts );
  $start = date('Y-m-d\TH:00:00', time()+3600*3);
  $end = date('Y-m-d\TH:00:00',  time()+3600*4);
  $fn="{$a[url]}?start=$start&"
    . "end=$end&location=%7B%22type%22%3A%22Point%22%2C%22"
    . 'coordinates%22%3A%5B5.32055452%2C60.39078164%5D%7D&'
    . 'filters=%7B%22groups%22%3A%5B%5D%2C%22minSeats%22%3A1%2C%22maxSeats'
    . '%22%3A9%2C%22carIds%22%3A%5B%5D%2C%22locationIds%22%3A%5B%5D%7D';
  $json_str=file_get_contents($fn);
  $json = json_decode($json_str);
  if ($json == NULL) {
    $json_str="false";
  }
  $output = "<script type='text/javascript'>"
    ."dele_data=$json_str;"
    ."</script>"
    ."<div id='mapid'></div>"
    ."<script type='text/javascript'>dele_createMap('{$a[token]}')</script>";

  if ($json == NULL) {
    $json_str="false";
  }
  return $output;
}
add_action('wp_enqueue_scripts', 'add_leaflet_map_api');
add_shortcode( 'carmap', 'carmap_func' );

function add_leaflet_map_api() {
  wp_register_style(
    'leaflet_css',
    'https://unpkg.com/leaflet@1.6.0/dist/leaflet.css'
  );
  wp_enqueue_style( 'leaflet_css' );
  wp_register_style(
    'markercluster_css',
    'https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.css'
  );
  wp_enqueue_style( 'markercluster_css' );
  wp_register_style(
    'markercluster_default_css',
    'https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.Default.css'
  );
  wp_enqueue_style( 'markercluster_default_css' );
  wp_register_style('carmap_css', plugins_url('dele/css/carmap.css'));
  wp_enqueue_style( 'carmap_css' );
  wp_enqueue_script(
    'leaflet',
    'https://unpkg.com/leaflet@1.6.0/dist/leaflet.js'
  );
  wp_enqueue_script(
    'markercluster',
    'https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/leaflet.markercluster.js'
  );
  wp_enqueue_script( 'carmap', plugins_url('dele/js/carmap.js'));
}

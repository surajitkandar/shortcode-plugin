<?php 

/**
 * Plugin Name: Shortcode Plugin
 * Description: This is a plugin to show information via a shortcode
 * Author: Surajit Kandar
 * Version: 1.0
 * Author URI: https://github.com/surajitkandar/surajitkandar
 * Plugin URI: https://www.example.com/shortcode-plugin
 */

// Basic shortcode
add_shortcode('message','sp_show_static_message');

function sp_show_static_message(){
  return "Hello this is a shortcode message";
}

// SHortcode with parameters
add_shortcode('student','sp_handle_student_data');

function sp_handle_student_data($att){

  $att = shortcode_atts(array(
    'name'  => 'John doa',
    'email' => 'johndoa@gmail.com'
  ),$att,'student');

  return '<h3>Student Name: '.$att["name"].' and email: '.$att["email"].'</h3>';
}

// shortcode with db operation
add_shortcode('list-post','sp_handle_list_posts');
function sp_handle_list_posts(){
  global $wpdb;

  $table_name = $wpdb->prefix . 'posts';

  // Get post whose post_type = post and post_status = publish not published

  $result = $wpdb->get_results(
    "SELECT post_title FROM {$table_name} WHERE post_type = 'post' AND post_status = 'publish' "
  );

  if(!empty($result)){
    $outputHtml = "<ul>";

    foreach($result as $val){
      $outputHtml .= "<li>".$val->post_title."</li>"; 
    }

    $outputHtml .= "</ul>";

    return $outputHtml;
  }

  // return 'No post found';
  return $table_name;
}

// shortcode with db operation with wp_query
add_shortcode('show-post','sp_handle_show_posts');

function sp_handle_show_posts($attributes){
  $attributes = shortcode_atts(array(
    'number' => '3',
  ),$attributes,'show-post');

  $query  = new WP_Query(
    [
      'post_type'   => 'post',
      'post_status' => 'publish',
      'posts_per_page' => $attributes['number'],
    ]
  );

  if($query->have_posts()){
    $output = "<ul>";
    while($query->have_posts()){
      $query->the_post();
      $output .= "<li><a href='".get_the_permalink()."'>".esc_html(get_the_title())."</a></li>";
    }
    $output .= "</ul>";

    return $output;
  }

  return "No post found";
}
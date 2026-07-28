<?php 
  // rename default post type
  function rename_default_post_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Exhibitions';
    $submenu['edit.php'][5][0] = 'Exhibitions';
    $submenu['edit.php'][10][0] = 'Add Exhibition';
    $submenu['edit.php'][16][0] = 'Exhibitions Tags';
  }
  function rename_default_post_object() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'Exhibitions';
    $labels->singular_name = 'Exhibition';
    $labels->add_new = 'Add Exhibition';
    $labels->add_new_item = 'Add Exhibition';
    $labels->edit_item = 'Edit Exhibition';
    $labels->new_item = 'Exhibitions';
    $labels->view_item = 'View Exhibitions';
    $labels->search_items = 'Search Exhibitions';
    $labels->not_found = 'No Exhibitions found';
    $labels->not_found_in_trash = 'No Exhibitions found in Trash';
    $labels->all_items = 'All Exhibitions';
    $labels->menu_name = 'Exhibitions';
    $labels->name_admin_bar = 'Exhibitions';
  }
   
  add_action( 'admin_menu', 'rename_default_post_label' );
  add_action( 'init', 'rename_default_post_object' );


  $args = [
    'post_type'      => 'exhibition',
    'posts_per_page' => -1,
    'meta_key'       => 'starting_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
  ];

  function orderExhibitions( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_home() ) {
      $query->set( 'posts_per_page', -1 );
      $query->set( 'meta_key', 'starting_date');
      $query->set( 'orderby','meta_value');
      $query->set( 'order','DESC');
      return;
    }
  }
  add_action( 'pre_get_posts', 'orderExhibitions', 1 );
?>
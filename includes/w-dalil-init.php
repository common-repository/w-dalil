<?php
add_action('init', 'dalil_posttype');

function dalil_posttype(){

    $w_dalil_lables = array(
        'name' => __('Dalil Item', 'w_dalil'),
        'singular_name' => __('Dalil Item', 'w_dalil', 'w_dalil'),
        'add_new' => __('Add New Dalil item', 'w_dalil'),
        'add_new_item' => __('Add New Dalil Item', 'w_dalil'),
        'edit_item' => __('Edit Dalil Item', 'w_dalil'),
        'new_item' => __('Add New Dalil Item', 'w_dalil'),
        'all_items' => __('View Dalil Items', 'w_dalil'),
        'view_item' => __('View Dalil Item', 'w_dalil'),
        'search_items' => __('Search Dalil Items', 'w_dalil'),
        'not_found' =>  __('No Items found', 'w_dalil'),
        'not_found_in_trash' => __('No Items found in Trash', 'w_dalil'),
        'parent_item_colon' => '',
        'menu_name' =>  __('Dalil', 'w_dalil')
    );

    $w_dalil_type = array(
        'labels' => $w_dalil_lables,
        'public' => true,
        'query_var' => true,
        'rewrite' =>  array( 'slug' => 'dalil' ),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'map_meta_cap' => true,
        'menu_position' => null,
        'taxonomies' => array('dalil_cat'),
        'supports' => array('title')
    );

    register_post_type('dalil', $w_dalil_type);

    $w_dalil_categories = array(
        'name' => __( 'Dalil Categories', 'w_dalil' ),
        'singular_name' => __( 'Dalil Categorie', 'w_dalil' ),
        'search_items' =>  __( 'Search Dalil Categorie', 'w_dalil' ),
        'all_items' => __( 'All Dalil Categories' , 'w_dalil'),
        'parent_item' => __( 'Parent Dalil Category' , 'w_dalil'),
        'parent_item_colon' => __( 'Parent Dalil Category:' , 'w_dalil'),
        'edit_item' => __( 'Edit Dalil Category' , 'w_dalil'),
        'update_item' => __( 'Update Dalil Category' , 'w_dalil'),
        'add_new_item' => __( 'Add New Dalil Category' , 'w_dalil'),
        'new_item_name' => __( 'New Dalil Name' , 'w_dalil'),
        'menu_name' => __( 'Dalil Categories' , 'w_dalil'),
    );

    $w_dalil_categorie_args = array(
        'hierarchical' => true,
        'labels' => $w_dalil_categories,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'dalil_cat' ),
    );

    register_taxonomy('dalil_cat', array('dalil') , $w_dalil_categorie_args);

    $w_dalil_citries = array(
        'name' => __( 'Dalil Cities', 'w_dalil' ),
        'singular_name' => __( 'Dalil City', 'w_dalil' ),
        'search_items' =>  __( 'Search Dalil City', 'w_dalil' ),
        'all_items' => __( 'All Dalil Cities' , 'w_dalil'),
        'parent_item' => __( 'Country' , 'w_dalil'),
        'parent_item_colon' => __( 'Countries :' , 'w_dalil'),
        'edit_item' => __( 'Edit Dalil City' , 'w_dalil'),
        'update_item' => __( 'Update Dalil City' , 'w_dalil'),
        'add_new_item' => __( 'Add New Dalil City' , 'w_dalil'),
        'new_item_name' => __( 'New Dalil City' , 'w_dalil'),
        'menu_name' => __( 'Dalil Cities' , 'w_dalil'),
    );

    $dalil_city_args = array(
        'hierarchical' => true,
        'labels' => $w_dalil_citries,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'dalil_city' ),
    );

    register_taxonomy('dalil_city', array('dalil'), $dalil_city_args);

    
     register_sidebar(array(
        'id' => 'dalil-sidebar-1',
        'name' => 'dalil-sidebar-1',
        'description' => 'Appears in category+archive+search dalil custom pages',
        'before_widget' => '<div class="fwc-dalil-widget" >',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="fwc-dalil-widget-title" >',
        'after_title' => '</h4>',
    ));    
     register_sidebar(array(
        'id' => 'dalil-sidebar-2',
        'name' => 'dalil-sidebar-2',
        'description' => 'Appears in category+archive+search dalil custom pages',
        'before_widget' => '<div class="fwc-dalil-widget" >',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="fwc-dalil-widget-title" >',
        'after_title' => '</h4>',
    ));
    

}
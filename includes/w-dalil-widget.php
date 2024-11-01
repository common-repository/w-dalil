<?php
class dalil_widget extends WP_Widget {
function __construct() {
    parent::__construct(
        // Base ID of your widget
        'dalil_widget',
        // Widget name will appear in UI

        __('Dalil List Categories', 'w_dalil'),

        // Widget description

        array( 'description' => __( 'Add this widget to list all categories with items in your sidebars', 'w_dalil' ), )

        );

}



// Creating widget front-end

// This is where the action happens

public function widget( $args, $instance ) {

$title = apply_filters( 'widget_title', $instance['title'] );


    ?>
<h2 class="all_cats" ><?php echo __('All Categories','w_dalil'); ?></h2>

<?php
$custom_terms = get_terms('dalil_cat');
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $remove_link = preg_replace('/([?&])wcat=[^&]+(&|$)/','$1',$actual_link);
    $remove_link = preg_replace("/\/page\/\d+/", "", $remove_link);
    if($_GET['ws'] || $_GET['wcity']){
        $getexistes = '&';
    }else{
        $getexistes = '?';
    }
//    $remove_link = preg_replace('/([?&])ws=[^&]+(&|$)/','$1',$remove_link);
    echo '<ul>';
    foreach($custom_terms as $custom_term) {
            echo '<li><a class="listcat_item" href="'.$remove_link.$getexistes.'wcat='.$custom_term->name.'" >'.$custom_term->name.'</a></li>';
    }
    echo '</ul>';
    echo '<hr/>';

}



// Widget Backend

public function form( $instance ) {

if ( isset( $instance[ 'title' ] ) ) {

$title = $instance[ 'title' ];

}

else {

$title = __( 'New title', 'w_dalil' );

}

// Widget admin form

?>

<p>

<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>

<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />

</p>

<?php

}



// Updating widget replacing old instances with new

public function update( $new_instance, $old_instance ) {

$instance = array();

$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

return $instance;

}

} // Class wpb_widget ends here



// Register and load the widget

function wpb_load_widget() {

    register_widget( 'dalil_widget' );

}

add_action( 'widgets_init', 'wpb_load_widget' );

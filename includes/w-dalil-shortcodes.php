<?php
add_shortcode('show-dalil', 'w_dalil_echo');
function w_dalil_echo(){
    require_once('w-dalil-get-informatrion.php');
    get_informatrion_inpage();
}




add_shortcode('search-dalil', 'w_dalil_search');
function w_dalil_search(){?>
<form role="search" method="get" id="dalilsearchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div>
        <span class="glyphicon glyphicon-search"></span>
        <label class="screen-reader-text" for="s"><?php _x( 'Search in dalil', 'label' ); ?></label>
        <input type="hidden" name="post_type" value="dalil" />
        <input required placeholder="<?php echo  __('Search ...','flaty2'); ?>" type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" />
    </div>
</form>
<?php
}



add_shortcode('list-cat-dalil', 'w_dalil_list_cat');
function w_dalil_list_cat(){
    ?>
<h2 class="all_cats" ><?php echo __('All Categories','w_dalil'); ?></h2>

<?php
    $custom_terms = get_terms('dalil_cat');
    echo '<ul>';
    foreach($custom_terms as $custom_term) {
        $childs = get_term_children( $custom_term->term_id ,'dalil_cat' );
        if($custom_term->parent == 0) {
            echo '<li><a class="listcat_item" href="'.get_term_link ($custom_term->term_id ).'" >'.$custom_term->name.'</a></li>';
        }
        if($childs){
            echo '<ul>';
            foreach($childs as $child ){
                $child = get_term_by( 'id' , $child ,'dalil_cat' );
                echo '<li><a class="listcat_item_child" href="'.get_term_link ($child).'" >'.$child->name.'</a></li>';
            }
            echo '</ul>';
        }
    }
    echo '</ul>';
    echo '<hr/>';
}


add_shortcode('list-cities-dalil', 'w_dalil_list_cities');
function w_dalil_list_cities(){
    ?>
<h2 class="all_cats" ><?php echo __('All Cities','w_dalil'); ?></h2>

<?php
    $custom_terms = get_terms('dalil_city');
    echo '<ul>';
    foreach($custom_terms as $custom_term) {
            echo '<li><a class="listcat_item" href="'.get_term_link ($custom_term->term_id ).'" >'.$custom_term->name.'</a></li>';
    }
    echo '</ul>';
    echo '<hr/>';
}








add_shortcode('FW-dalil', 'w_dalil_full');
function w_dalil_full(){
    do_shortcode('[list-cat-dalil]');
    do_shortcode('[search-dalil]');
    do_shortcode('[show-dalil]');
}









add_action( 'init', 'w_dalilhandle_addnew' );
function w_dalilhandle_addnew() {
    if(session_id() == '') {
        session_start();
    }
    if( isset($_FILES['dalil_logo']) &&  $_FILES['dalil_logo']['name'] != '' ){
        $uploadedfile = $_FILES['dalil_logo'];
        $upload_overrides = array( 'test_form' => false ,'unique_filename_callback' => 'dalil_logo_filename');
        add_filter('upload_mimes','dalil_mimes');
        add_filter( 'upload_dir', 'dalil_dir' );
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	    require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
        remove_filter( 'upload_dir', 'dalil_dir' );
        remove_filter('upload_mimes','dalil_mimes');
        if ( $movefile && !isset( $movefile['error'] ) ) {

            $get_ext = wg_get_ext($movefile['type']);
            $generated_for_small = 'sm_'. rand ( 100000 , 999999 ).$get_ext;
            $image1 = wp_get_image_editor( $movefile['file'] );
            $image1->resize( 270, NULL, true );
            $sm_dir = wp_upload_dir()['basedir'] . '/dalil_files/'.$generated_for_small;
            $sm_url = wp_upload_dir()['baseurl'] . '/dalil_files/'.$generated_for_small;
            $result = $image1->save( $sm_dir );

            $dalil_data['dalil-logo-orginal'] = $movefile['url'] ;
            $dalil_data['dalil-logo'] = $sm_url ;
        }else{
            $_SESSION['dalil_message'] = __('Logo File Not allowed','w_dalil');
            session_write_close();
            wp_redirect( $_SERVER["REQUEST_URI"] );
            exit;
        }
    }
    if(isset($_POST['w-dalil-addnew']) && wp_verify_nonce( $_POST['w-dalil-addnew'], 'w-dalil-nonce' ) && isset($_POST['dalil_newsubmit']) ){
            $daliltitle = mysql_real_escape_string( $_POST['dalil_newtitle'] );
            $dalil_data['dalil-address'] = mysql_real_escape_string( $_POST['dalil_newaddress'] );
            $dalil_data['dalil-phone'] = mysql_real_escape_string( $_POST['dalil_newphone'] );
            $dalil_data['dalil-activity'] = mysql_real_escape_string( $_POST['dalil_newactivity'] );
        if( isset($_POST['dalil_newemail']) ){
            $dalil_data['dalil-email'] = mysql_real_escape_string( $_POST['dalil_newemail'] );
        }
        if( isset($_POST['dalil_newwebsite']) ){
            $dalil_data['dalil-website'] = mysql_real_escape_string( $_POST['dalil_newwebsite'] );
        }
        $my_post = array(
          'post_title'    => $daliltitle,
          'post_type'  => 'dalil'
        );
        $post_id = wp_insert_post( $my_post, $wp_error );
        if(!$wp_error){
            update_post_meta( $post_id, 'dalil_information', $dalil_data );
            $_SESSION['dalil_message'] = __('Thanks For adding','w_dalil');
            session_write_close();
            wp_redirect( $_SERVER["REQUEST_URI"] ); exit;
        }
    }
}

add_shortcode('add-item-dalil', 'w_dalil_add_item');
function w_dalil_add_item(){
    ?>
    <script>
        $(document).ready(function(){
            $('#dalil_logo_input').change(function(){
                $( '.dalil_logo_label' ).html('<?php echo __('Chosen File :','w_dalil'); ?>'+$(this).val());
            });
        });
    </script>
    <h2 class="all_cats" ><?php echo __('Add Your Company','w_dalil'); ?></h2>
    <form class="insert_item_form" method='post' enctype="multipart/form-data" >
        <?php wp_nonce_field('w-dalil-nonce', 'w-dalil-addnew');  ?>
        <input name="dalil_newtitle" required type="text" placeholder="<?php echo __('Tilte*','w_dalil'); ?>" />
        <input name="dalil_newaddress" required type="text" placeholder="<?php echo __('Adress*','w_dalil'); ?>" />
        <input name="dalil_newphone" required type="text" placeholder="<?php echo __('Phone*','w_dalil'); ?>" />
        <input name="dalil_newemail" type="text" placeholder="<?php echo __('Email','w_dalil'); ?>" />
        <input name="dalil_newwebsite" type="text" placeholder="<?php echo __('Website','w_dalil'); ?>" />
        <input name="dalil_newactivity" required type="text" placeholder="<?php echo __('Kind of activity*','w_dalil'); ?>" />
        <input id="dalil_logo_input" name="dalil_logo" type="file" />
        <label class="dalil_logo_label" for="dalil_logo_input" ><?php echo __('(optional) Upload logo : ','w_dalil'); ?></label><br />
        <p class="dalil_logo_allow"><?php echo __('Only jpg file with 2 MB allowed','w_dalil'); ?></p>
        <input title = "<?php echo __('No File Selected','w_dalil'); ?>" name="dalil_newsubmit" type="submit" value="<?php echo __('Add Your Company ','w_dalil'); ?>" />
        <p class="dalil_message"><?php if(isset($_SESSION['dalil_message'])){echo $_SESSION['dalil_message'] ; unset($_SESSION['dalil_message']);}  ?></p>
    </form>

<?php
}

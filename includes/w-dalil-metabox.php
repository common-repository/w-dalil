<?php
add_action( 'admin_init', 'add_post_gallery_so_14445904' );
add_action( 'admin_head-post.php', 'print_scripts_so_14445904' );
add_action( 'admin_head-post-new.php', 'print_scripts_so_14445904' );
add_action( 'save_post', 'update_post_gallery_so_14445904', 10, 2 );

/**
 * Add custom Meta Box to Posts post type
 */
function add_post_gallery_so_14445904()
{
    add_meta_box(
        'w-dalil-address',
        __('Dalil item information','w_dalil'),
        'post_gallery_options_so_14445904',
        'dalil',
        'normal',//('normal', 'advanced', or 'side').
        'core'//$priority ('high', 'core', 'default' or 'low')
    );
}

/**
 * Print the Meta Box content
 */
function post_gallery_options_so_14445904()
{
    global $post;
    $dalil_information = get_post_meta( $post->ID, 'dalil_information', true );
    wp_nonce_field( plugin_basename( __FILE__ ), 'noncename_so_14445904' );
?>

<div id="dalil">

    <div id="dalil_inputs">
    <?php
    if ( isset( $dalil_information['dalil-address'] ) ){
        ?>
        <input class="dalil_input" name="dalil-address" type="text" placeholder="<?php echo __('Dalil item address','w-dalil'); ?>" value="<?php echo $dalil_information['dalil-address']; ?>"  />
    <?php
    }else{?>

        <input class="dalil_input" name="dalil-address" type="text" placeholder="<?php echo __('Dalil item address','w-dalil'); ?>" />

    <?php }
    ?>
        <br/>
    <?php
    if ( isset( $dalil_information['dalil-phone'] ) ){
        ?>
        <input class="dalil_input" name="dalil-phone" type="text" placeholder="<?php echo __('Dalil item Phone','w-dalil'); ?>" value="<?php echo $dalil_information['dalil-phone']; ?>"  />
    <?php
    }else{?>

        <input class="dalil_input" name="dalil-phone" type="text" placeholder="<?php echo __('Dalil item Phone','w-dalil'); ?>" />

  <?php }?>
        <br/>
    <?php
    if ( isset( $dalil_information['dalil-email'] ) ){
        ?>
        <input class="dalil_input" name="dalil-email" type="text" placeholder="<?php echo __('Dalil item Email','w-dalil'); ?>" value="<?php echo $dalil_information['dalil-email']; ?>"  />
    <?php
    }else{?>

        <input class="dalil_input" name="dalil-email" type="text" placeholder="<?php echo __('Dalil item Email','w-dalil'); ?>" />

  <?php }?>
        <br/>
    <?php
    if ( isset( $dalil_information['dalil-website'] ) ){
        ?>
        <input class="dalil_input" name="dalil-website" type="text" placeholder="<?php echo __('Dalil item Website','w-dalil'); ?>" value="<?php echo $dalil_information['dalil-website']; ?>"  />
    <?php
    }else{?>

        <input class="dalil_input" name="dalil-website" type="text" placeholder="<?php echo __('Dalil item Website','w-dalil'); ?>" />

  <?php }?>
        <br/><?php
    if ( isset( $dalil_information['dalil-activity'] ) ){
        ?>
        <input class="dalil_input" name="dalil-activity" type="text" placeholder="<?php echo __('Categorie (for suers)','w-dalil'); ?>" value="<?php echo $dalil_information['dalil-activity']; ?>"  />
    <?php
    }else{?>

        <input class="dalil_input" name="dalil-add" type="text" placeholder="<?php echo __('Categorie (for suers)','w-dalil'); ?>" />

  <?php }?>

        <?php
        if( isset( $dalil_information['dalil-logo'] )){?>
            <img class="dalil_logo_admin" src="<?php echo $dalil_information['dalil-logo']; ?>" /><br />
            <input class="button button-primary button-small" type="submit" name="remove_logo" value="remove" onclick="return confirm('<?php echo __('Are you sure to delete the logo ?','W_DALIL'); ?>');" />
            <?php if( !get_post_meta($post->ID,'dalil_item_hidden', true ) ){?>
                <input class="button button-primary button-small" type="submit" name="hide_logo" value="hide" />
        <?php }
        } ?>
        <?php
        if( isset( $dalil_information['dalil-logo'] ) && get_post_meta($post->ID,'dalil_item_hidden', true ) ){?>
            <input class="button button-primary button-small" type="submit" name="unhide_logo" value="unhide" />
        <?php } ?>
        <label for="dalil-logo" ><?php echo __('Logo for Dalil item(Optional)','w_dalil'); ?></label><br/>
        <input type="file" id="dalil-logo" name="dalil-logo" accept="image/*">



    </div><!-- end dalil inputs admin area -->
<?php }
/**
 * Print styles and scripts
 */
function print_scripts_so_14445904()
{
    ?>
    <style type="text/css">
        .dalil_input{
            width: 100%;
            padding: 8px 12px;
            font-size: 1em;
            line-height: 100%;
            width: 100%;
            outline: 0px none;
            margin: 0px 0px 3px;
            margin-bottom: 20px;
            background-color: #FFF;
        }
        .dalil_input:first-child{
            margin-top: 20px;
        }
        .dalil_logo_admin{
            max-width: 20%;
        }
    </style>

    <script type="text/javascript">
    </script>
    <?php
}

/**
 * Save post action, process fields
 */
function update_post_gallery_so_14445904(  $post_id, $post_object )
{
    // Doing revision, exit earlier **can be removed**
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    // Doing revision, exit earlier
    if ( 'revision' == $post_object->post_type )
        return;

//    // Verify authenticity
//    if ( !wp_verify_nonce( $_POST['noncename_so_14445904'], plugin_basename( __FILE__ ) ) )
//        return;

    // Correct post type
    if ( isset($_POST['post_type']) && 'dalil' != $_POST['post_type'] )
        return;


    add_filter( 'upload_dir', 'dalil_dir' );



    if( isset( $_POST['hide_logo'] ) &&  $_POST['hide_logo'] ='hide' ){
        update_post_meta( $post_id, 'dalil_item_hidden', true );
    }
    if( isset( $_POST['unhide_logo'] ) &&  $_POST['unhide_logo'] ='hide' ){
        update_post_meta( $post_id, 'dalil_item_hidden', false );
    }



    if( isset( $_POST['remove_logo'] ) &&  $_POST['remove_logo'] ='remove' ){
        $dalil_information = get_post_meta( $post_id, 'dalil_information', true );
        if ( $_POST['dalil-address'] ){
            $dalil_data['dalil-address'] = $_POST['dalil-address'];
        }
        if ( $_POST['dalil-phone'] ){
             $dalil_data['dalil-phone'] = $_POST['dalil-phone'];
        }
        if ( $_POST['dalil-email'] ){
             $dalil_data['dalil-email'] = $_POST['dalil-email'];
        }
        if ( $_POST['dalil-website'] ){
             $dalil_data['dalil-website'] = $_POST['dalil-website'];
        }


        /* delete old logo */
        if(isset($dalil_information['dalil-logo'])){
        $file_name = substr($dalil_information['dalil-logo'], strpos($dalil_information['dalil-logo'],'dalil_files') + 12  );
        rename (wp_upload_dir()['path'].'/'.$file_name, wp_upload_dir()['path'].'/removed_'.$file_name);
        }
        if(isset($dalil_information['dalil-logo-orginal'])){
            $file_name = substr($dalil_information['dalil-logo-orginal'], strpos($dalil_information['dalil-logo-orginal'],'dalil_files') + 12  );
            rename (wp_upload_dir()['path'].'/'.$file_name, wp_upload_dir()['path'].'/removed_'.$file_name);
        }
//        unlink( wp_upload_dir()['path'].'/'.$file_name ) ;


        update_post_meta( $post_id, 'dalil_information', $dalil_data );
        return;
    }

    if( isset($_FILES['dalil-logo']) &&  $_FILES['dalil-logo']['name'] != '' ){
        $dalil_information = get_post_meta( $post_id, 'dalil_information', true );
        $uploadedfile = $_FILES['dalil-logo'];
        $upload_overrides = array( 'test_form' => false ,'unique_filename_callback' => 'dalil_logo_filename');
        add_filter('upload_mimes','dalil_mimes');
        $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
        remove_filter('upload_mimes','dalil_mimes');
        if ( $movefile && !isset( $movefile['error'] ) ) {

            $get_ext = wg_get_ext($movefile['type']);
            $generated_for_small = 'sm_'. rand ( 100000 , 999999 ).$get_ext;
            $image1 = wp_get_image_editor( $movefile['file'] );
            $image1->resize( 270, NULL, true );
            $sm_dir = wp_upload_dir()['basedir'] . '/dalil_files/'.$generated_for_small;
            $sm_url = wp_upload_dir()['baseurl'] . '/dalil_files/'.$generated_for_small;
            $result = $image1->save( $sm_dir );

            $dalil_information = get_post_meta( $post_id, 'dalil_information', true );
            if( isset($dalil_information['dalil-logo']) ){
                /* delete old logo */
                $file_name = substr($dalil_information['dalil-logo-orginal'], strpos($dalil_information['dalil-logo-orginal'],'dalil_files') + 12  );
                rename (wp_upload_dir()['path'].'/'.$file_name, wp_upload_dir()['path'].'/removed_'.$file_name);
//              unlink( wp_upload_dir()['path'].'/'.$file_name ) ;
            }

            $dalil_data['dalil-logo-orginal'] = $movefile['url'] ;
            $dalil_data['dalil-logo'] = $sm_url ;

        }
    }else{
        $dalil_information = get_post_meta( $post_id, 'dalil_information', true );
         if(isset($dalil_information['dalil-logo']) && $dalil_information['dalil-logo'] != ''){
            $dalil_data['dalil-logo'] = $dalil_information['dalil-logo'];
         }
    }

    remove_filter( 'upload_dir', 'dalil_dir' );

    if ( isset($_POST['dalil-address']) && $_POST['dalil-address'] ){
         $dalil_data['dalil-address'] = $_POST['dalil-address'];
    }
    if ( isset($_POST['dalil-phone']) && $_POST['dalil-phone'] ){
         $dalil_data['dalil-phone'] = $_POST['dalil-phone'];
    }
    if ( isset($_POST['dalil-email']) && $_POST['dalil-email'] ){
         $dalil_data['dalil-email'] = $_POST['dalil-email'];
    }
    if (  isset($_POST['dalil-website']) && $_POST['dalil-website'] ){
         $dalil_data['dalil-website'] = $_POST['dalil-website'];
    }

    if (  isset($dalil_data) )
        update_post_meta( $post_id, 'dalil_information', $dalil_data );
    else
        delete_post_meta( $post_id, 'dalil_information' );
}
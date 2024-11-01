<?php
include_once(DALIL_INCLUDES . 'w-dalil-get-informatrion.php');


/* 1. define sub dir for logos */
function dalil_dir( $dirs ) {
    $dirs['subdir'] = '/dalil_files';
    $dirs['path'] = $dirs['basedir'] . '/dalil_files';
    $dirs['url'] = $dirs['baseurl'] . '/dalil_files';
    return $dirs;
}



/* 2. allowed extenstions for logos */ 
function dalil_mimes($mimes) {
  $mimes = array('jpg|jpeg' => 'image/jpeg');
  return $mimes;
}



/* 3. for metabox add abbility to upload files */
function update_edit_form() {
    echo ' enctype="multipart/form-data"';
}
add_action('post_edit_form_tag', 'update_edit_form');



/* 4. remove what make errors in the data for XML exported file */
function dalil_wrap_cdata( $string ){
    $string = preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', '&#47;', $string);
    $string = htmlspecialchars($string);
    return $string;
}


/* 5. seperate function to get extenstion for uploaded file */
function wg_get_ext($type){
    switch ($type){
    case "image/jpeg":
            return '.jpeg';
            break;
    case "image/png":
            return '.png';
            break;
    case "image/jpg":
            return '.jpg';
            break;
    default:
            return 'NULL';
    }
}


/* 6. enqueue style for dalil ( edit what you need in w-dalil/style/w-dalil.style.css ) */
function w_dalil_style_files() {
    wp_enqueue_style( 'w_dalil_style', DALILURL.'style/w-dalil-style.css' );
}
add_action( 'wp_enqueue_scripts', 'w_dalil_style_files' );


/* 7. get all dalil items ( needed in multiple locations ) */
function dalil_get_items($args = array()) {
    $args = array( 'post_type'        => 'dalil',
                   'numberposts'       => -1,);
    return $myposts = get_posts( $args );
}

/* 8. export file */
function fw_export( $args = array() ) {
    /* Query logs */
    $dalil_items = dalil_get_items( array() );
    /* If there are no logs - abort */
    if( !isset($dalil_items) ){
        /* empty dalil */
        return false;
    }

    /* Create a file name */
    $sitename = sanitize_key( get_bloginfo( 'name' ) );
    if ( ! empty($sitename) ) $sitename .= '.';
    $filename = $sitename . 'fwcodes-dalil-items.' . date( 'Y-m-d' ) . '.xml';

    /* Print header */
    header( 'Content-Description: File Transfer' );
    header( 'Content-Disposition: attachment; filename=' . $filename );
    header( 'Content-Type: application/xml;  charset=' . get_option( 'blog_charset' ), true );

    /* Print comments */
    echo "<!-- This is file for w-dalil plugin Written by Ghiath Alkhaled -->\n";
    echo "<!-- (For Backup) -->\n";

    /* Print the logs */
    echo '<dalil>';
    foreach ( $dalil_items as $dalil_item ) {
        $fw_dalil_meta = get_post_meta($dalil_item->ID, 'dalil_information', true );
        $dalil_item_city = get_the_terms( $dalil_item->ID , 'dalil_city' );
        $dalil_item_city =  $dalil_item_city[0]->name;
        $dalil_item_cat = get_the_terms( $dalil_item->ID , 'dalil_cat' );
        $dalil_item_cat =  $dalil_item_cat[0]->name;// we assume that every dalil item have one category listed udner it
?>
        <item>
            <fw_title><?php echo dalil_wrap_cdata($dalil_item->post_title) ; ?></fw_title>
            <fw_address><?php echo dalil_wrap_cdata($fw_dalil_meta['dalil-address']); ?></fw_address>
            <fw_phone><?php echo dalil_wrap_cdata($fw_dalil_meta['dalil-phone']); ?></fw_phone>
            <fw_email><?php echo dalil_wrap_cdata($fw_dalil_meta['dalil-email']); ?></fw_email>
            <fw_site><?php echo dalil_wrap_cdata($fw_dalil_meta['dalil-website']); ?></fw_site>
            <fw_categorie><?php echo $dalil_item_cat; ?></fw_categorie>
            <fw_city><?php echo $dalil_item_city; ?></fw_city>
        </item>
    <?php }
    echo '</dalil>';
    exit();
}


/* 9. desired email to send mails from user to dalil items's email (EDIT HERE) */
function yoursite_wp_mail_from($content_type) {
  return 'info@destination-ist.com';
}
function yoursite_wp_mail_from_name($name) {
  return 'Destination Istanbul Magazine';
}






/* 10. ajax function to send the email from front-end */
function send_email_pop() {
    $name = filter_input( INPUT_POST , 'name' );
    $subject = filter_input( INPUT_POST , 'subject' );
    $message = filter_input( INPUT_POST , 'message' );
    $headers = array('Content-Type: text/html; charset=UTF-8');
//    $to = filter_input( INPUT_POST , 'to' );
    $to = "ghiath@dimo-tr.com";
    $message = $message.'<br/><br/><hr/>This Email Sent by '.get_bloginfo('name').' - Dalil ';
    add_filter('wp_mail_from','yoursite_wp_mail_from');
    add_filter('wp_mail_from_name','yoursite_wp_mail_from_name');
    if( wp_mail( $to , $subject , $message , $headers  ) ){
        echo "<div class='alert alert-success pop_email_success' >";
        echo __('Your message was succefully sent . Thanks ','w_dalil');
        echo "</div>";
    }else{
        echo "<div class='alert alert-warning pop_email_fail' >";
        echo __('Error Sending, Contact US','w_dalil');
        echo "</div>";
    }
    remove_filter('wp_mail_from','yoursite_wp_mail_from');
    remove_filter('wp_mail_from','yoursite_wp_mail_from');
}
add_action('wp_ajax_send_email_pop', 'send_email_pop');
add_action('wp_ajax_nopriv_send_email_pop', 'send_email_pop');



/* 11. ajax function to pop the window in front-end */
function pop_ajax() {
    global $wpdb;
    $id = filter_input( INPUT_POST , 'item_id' );
    $dalil_information = get_post_meta($id,'dalil_information',true);
    $title = get_the_title( $id );
    $email = $dalil_information["dalil-email"];
?>
    <script>
    jQuery("#poped_mail").submit(function(event){
        jQuery('.mail_submit').fadeOut(400);
        var email = "<?php echo $email; ?>";
        var name = jQuery('[name^="mail_name"]').val();
        var subject = jQuery('[name^="mail_subject"]').val();
        var message = jQuery('[name^="mail_message"]').val();
        var options = {
            action : 'send_email_pop',
            subject : subject,
            message : message,
            name : name,
            to : email,
        };
        jQuery.post(ajax_url, options, function(data, textStatus,xhr) {
            jQuery('#poped_mail').append(jQuery(data));
            jQuery('.pop_email_fail').fadeIn(400);
            jQuery('.pop_email_success').fadeIn(400);
        });
        return false;
    })
    </script>
    <div class="w_dalil_pop_mail_container">
        <div class="w_dalil_pop_mail">
            <div class="fw_pop_head"><?php echo __('Send Email TO : ','w_dalil'); echo $title ;?></div>
            <form id="poped_mail" method="post">
                <input name="mail_name" required class="mail_input form-control" type="text" placeholder="<?php echo __('Your Name :','w_dalil'); ?>" />
                <input name="mail_subject" required class="mail_input form-control" type="text" placeholder="<?php echo __('About ? (subject)','w_dalil'); ?>" />
                <textarea name="mail_message" required class="mail_input mail_textarea form-control" type="text" placeholder="<?php echo __('Message....','w_dalil'); ?>" ></textarea>
                <input class="mail_submit btn btn-default" type="submit" value="<?php echo __('Send','w_dalil'); ?>" />
            </form>
        </div>
    </div>

<?php exit;
}
add_action('wp_ajax_pop_ajax', 'pop_ajax');
add_action('wp_ajax_nopriv_pop_ajax', 'pop_ajax');





/* 12. poped box scripts and style */
function pop_email_js() {
?>
    <style>
        .w_dalil_pop_mail_container{
            background-color: rgba(0,0,0,0.6);
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: 1000000;
            display: none;
        }
        .w_dalil_pop_mail{
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            margin: auto;
            height: 0;
            width: 0;min-width: 250px;
            display: block;
            background-color: #fff;
            border-radius: 4px;
            -webkit-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
            -moz-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
            box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
            padding: 20px;
        }
        .fw_pop_head{
            border-radius: 4px;
            background-color: rgb(240, 240, 240);
            padding: 10px;
            text-align: center;
            font-weight: 800;
            font-size: 14px;
        }
        .mail_input{
            margin-top: 10px;
            height: 30px;
            font-size: 14px;
            max-height: 150px;
            padding: 4px 12px !important;
        }
        .mail_textarea{
            height: 250px !important;
            resize: none;
        }
        .mail_submit{
            display: block;
            margin: auto;
            margin-top: 10px;
        }
        .pop_email_success,.pop_email_fail{
            padding: 6px !important;
            margin-top: 10px;
            text-align: center;
        }
    </style>

    <script>
    ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
    var clicked = false ;
    function pop_email(id){
        if(!clicked){
            clicked = true ;
            var options = {
                action : 'pop_ajax',
                item_id : id
            };
            jQuery.post(ajax_url, options, function(data, textStatus,xhr) {
                jQuery('body').append( jQuery(data) );
                jQuery('.w_dalil_pop_mail_container').fadeIn(200);
                jQuery('.w_dalil_pop_mail').animate({
                        height : '50%',
                        width : '30%'
                },500);
                jQuery('.w_dalil_pop_mail_container').click(function() {
                    jQuery('.w_dalil_pop_mail').animate({
                            height : '0',
                            width : '0'
                    },500);
                    jQuery('.w_dalil_pop_mail_container').fadeOut(200);
                    jQuery('.w_dalil_pop_mail_container').remove();
                });
                jQuery('.w_dalil_pop_mail').click(function(event){
                    event.stopPropagation();
                });
                clicked = false ;
            });
        }
    }
    </script><?php
}
add_action('wp_footer', 'pop_email_js');





/* 13. options page to export and imports XMLs */
class w_dalil_options_page {

    function __construct() {
        add_action( 'admin_menu', array( $this, 'dalil_admin_menu' ) );
        add_action( 'admin_init', array( $this ,'fw_handle_export'));
        add_action( 'admin_init', array( $this , 'fw_handle_import' ) );
        add_action( 'admin_notices',array( $this , 'admin_notices' ) );
    }

    function dalil_admin_menu() {
        add_options_page(
            __('Dalil Options','w_dalil'),
            __('FW Dalil','w_dalil'),
            'manage_options',
            'fw-dalil',
            array(
                $this,
                'dalil_settings'
            )
        );
    }
    
    function admin_notices() {
        // Was an import attempted and are we on the correct admin page?
        if ( ! isset( $_GET['imported'] ) )
            return;

        $imported = intval( $_GET['imported'] );

        if ( isset($_GET['passed']) ) {
            printf( '<div class="update-nag notice"><p>%s</p></div>', __(  $_GET['passed']. ' items skipped', 'w_dalil' ) );

        }
        if ( 1 == $imported ) {
            printf( '<div class="updated"><p>%s</p></div>', __( '1 Dalil item successfully imported', 'w_dalil' ) );

        }
        elseif ( intval( $_GET['imported'] ) ) {
            printf( '<div class="updated"><p>%s</p></div>', sprintf( __( '%d Dalil items successfully imported', 'w_dalil' ), $imported ) );
        }
        else {
            printf( '<div class="error"><p>%s</p></div>', __( ' No items were imported', 'w_dalil' ) );
        }
    }
    

    static function fw_handle_export() {
        
        if( empty( $_POST['fw_export_submit']) || 'export_dalil' !== $_POST['fw_export_submit'] )
            return;

        /* Check permissions and nonces */
        if( !current_user_can('manage_options') )
            wp_die('');

        check_admin_referer( '_fwnonce','fw_export_nonce');

        /* Trigger download */
        /* 8. */fw_export();
        
    }
    

    static function fw_handle_import() {
        /* Listen for form submission */
        if ( empty( $_POST['fw_import_submit'] ) || 'fw_import_submit' !== $_POST['fw_import_submit'] )
            return;

        /* Check permissions and nonces */
        if ( ! current_user_can( 'manage_options' ) )
            wp_die('');

        check_admin_referer( '_fwnonce', 'fw_import_nonce' );

        /* Perform checks on file: */
        // Sanity check
        if ( empty( $_FILES["fw_import_file"] ) )
            wp_die( 'No file found' );

        $file = $_FILES["fw_import_file"];

        // Is it of the expected type?
        if ( $file["type"] != "text/xml" )
            wp_die( sprintf( __( "There was an error importing the logs. File type detected: '%s'. 'text/xml' expected", 'w_dalil' ), $file['type'] ) );

        // Impose a limit on the size of the uploaded file. Max 2097152 bytes = 2MB
//        if ( $file["size"] > 2097152 ) {
//            $size = size_format( $file['size'], 2 );
//            wp_die( sprintf( __( 'File size too large (%s). Maximum 2MB', 'w_dalil' ), $size ) );
//        }

        if( $file["error"] > 0 )
            wp_die( sprintf( __( "Error encountered: %d", 'w_dalil' ), $file["error"] ) );

        /* If we've made it this far then we can import the data */
        $imported_skipped = self::import( $file['tmp_name'] );

        /* Everything is complete, now redirect back to the page */
        wp_redirect( admin_url('options-general.php?page=fw-dalil').'&imported='.$imported_skipped[0].'&passed='.$imported_skipped[1] );
        echo 'wrong redirect';
        exit();
    }

    static function import( $file ) {
        // Parse file
        $dalil_items = self::parse( $file );

        // Initialises a variable storing the number of logs successfully imported.
        $imported = 0;
        $skipped = 0;
        
        
        set_time_limit(60000);
        
        foreach ( $dalil_items as $dalil_item ) {
            $term = term_exists((string)$dalil_item['categorie'], 'dalil_cat');
            if( $term == 0 && $term == null && '' != (string)$dalil_item['categorie'] ){
                $catarr = array(
                    'cat_name' => (string)$dalil_item['categorie'],
                    'taxonomy' => 'dalil_cat' 
                );
                $inserted = wp_insert_category( $catarr );
                if( !$inserted ){
                    echo 'INSERTING-CATEGORIE-1'; /* developped by this numbers */
                    exit();
                }
            }
            $term = term_exists((string)$dalil_item['city'], 'dalil_city');
            if( $term == 0 && $term == null && null!=(string)$dalil_item['city'] ){
                $catarr = array(
                    'cat_name' => (string)$dalil_item['city'],
                    'taxonomy' => 'dalil_city' 
                );
                $inserted = wp_insert_category( $catarr );
                if( !$inserted  ){
                    echo 'INSERTING-CITIES-2'; /* developped by this numbers */
                    exit();
                }
            }
//            foreach($dalil_previous_items as $previous_item){
//                if($dalil_item['title'] == $previous_item->post_title){
//                    $item_exits = true;
//                    break;
//                }else{
//                    $item_exits = false;
//                    break;
//                }
//            }
            $item_exits = false;
            if(!$item_exits){
                $my_post = array(
                    'post_title'    => $dalil_item['title'],
                    'post_type'  => 'dalil',
                    'post_status'    => 'publish'
                );

                $post_id = wp_insert_post( $my_post );
                if( null !== ((string)$dalil_item['categorie']) ){
                    $item_set_taxonomy = wp_set_object_terms( $post_id, (string)$dalil_item['categorie'], 'dalil_cat' );
                }
                if( null !== ((string)$dalil_item['city']) ){
                    $item_set_taxonomy = wp_set_object_terms( $post_id, (string)$dalil_item['city'], 'dalil_city' );
                }
                if( $post_id !== 0 ){
                    if(isset($dalil_item['address']) )
                        $dalil_data['dalil-address'] = (string) $dalil_item['address'] ;
                    if(isset($dalil_item['phone']))
                        $dalil_data['dalil-phone'] = (string) $dalil_item['phone'] ;
                    if(isset($dalil_item['email']))
                        $dalil_data['dalil-email'] = (string) $dalil_item['email'] ;
                    if(isset($dalil_item['website']))
                        $dalil_data['dalil-website'] = (string) $dalil_item['website'] ;
                    update_post_meta( $post_id, 'dalil_information', $dalil_data );
                    $imported++;
                }
            }else{
                $skipped++;
                continue;
            }
            
            
        }
        return array($imported,$skipped);
    } /* end of import method */


    static function parse( $file ) {
        // Load the xml file
        $xml = simplexml_load_file( $file );
        $dalil_items_fromxml = json_decode(json_encode($xml), true);

        // halt if loading produces an error
        if ( ! $xml )
            return false;
        
        $count = 0;
        foreach ( $xml->item as $dalil_item ) {
            $dalil_items[$count] = array(
                'title' => $dalil_item->fw_title,
                'address' => $dalil_item->fw_address,
                'phone' => $dalil_item->fw_phone,
                'email' => $dalil_item->fw_email,
                'website' => $dalil_item->fw_site,
                'categorie' => $dalil_item->fw_categorie,
                'city' => $dalil_item->fw_city,
            );
            $count++;
        }

        return $dalil_items;
    }


    function  dalil_settings() {
        echo '<div class="wrap">';
        screen_icon();
        echo '<h2>' . __( 'Export Dalil items', 'w_dalil' ) . '</h2>';
        ?>
        <form id="w_dalil_export_form" method="post" action="">
            <p>
                <label><?php _e( 'Click to export Dalil items','w_dalil' ); ?></label>
                <input type="hidden" name="fw_export_submit" value="export_dalil" />
            </p>
            <?php wp_nonce_field('_fwnonce','fw_export_nonce') ;?>
            <?php submit_button( __('Export all dalil items','w_dalil'), 'button' ); ?>
        </form>
        <form id="w_dalil_import_form" method="post" action="" enctype="multipart/form-data">
            <p>
                <label for="fw_import_file"><?php _e( 'Import an .xml file.', 'w_dalil' ); ?></label>
                <input type="file" id="fw_import_dalil" name="fw_import_file" />
            </p>
            <input type="hidden" name="fw_import_submit" value="fw_import_submit" />
            <?php wp_nonce_field( '_fwnonce', 'fw_import_nonce' ); ?>
            <?php submit_button( __( 'Import Dalil', 'w_dalil' ), 'secondary' ); ?>
        </form>
    <?php
    }
}

new w_dalil_options_page;

function w_dalil_archive_template( $archive_template ) {
    if(is_archive() )
        if(get_post_type() == 'dalil')
            $archive_template = dirname( __FILE__ ) . '/w-dalil-archive.php';
    
     return $archive_template;
}

add_filter( 'archive_template', 'w_dalil_archive_template' ) ;


function dalil_taxonomy_template(){
    $archive_template = dirname( __FILE__ ) . '/w-dalil-archive.php';
    if( is_tax('dalil_cat') || is_tax('dalil_city') || (  is_search() && isset($_GET['post_type']) && $_GET['post_type'] == 'dalil' ) ){
        include $archive_template;
        die();
    }
}
add_action('template_redirect', 'dalil_taxonomy_template');

function dalil_custom_search($query) {
    if ($query->is_search && !isset($_GET['post_type']) && $_GET['post_type'] != 'dalil') {
        $query->set('post_type', array('post','page') );
    }
    return $query;
}
add_filter('pre_get_posts','dalil_custom_search');


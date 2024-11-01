<?php
function get_informatrion(){
?>
    <div class="col-md-3">
    <?php do_shortcode('[list-cat-dalil]'); 
    dynamic_sidebar('dalil-sidebar-1');    
    ?>
    </div>
    <div class="col-md-6 dalil_container">
        <?php  do_shortcode('[search-dalil]'); ?>
        <div class="dalil_filter">
        <?php 
            if(is_tax('dalil_cat')){
                $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
                echo __('Search results for : "','w_dalil').$term->name.'" ';
            }
            if(is_tax('dalil_city')){
                $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
                echo __('Search results for : "','w_dalil').$term->name.'" ';
            }
            if( is_search() ){
                echo __('Search results for : "','w_dalil').$_GET['s'].'" ';
            }
        ?>
        </div>
        <?php 
            if(is_tax('dalil_cat')){
            $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $args = array(
                    'post_type' => 'dalil',
                    'paged' => $paged,
                    'posts_per_page' => 15,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'dalil_cat',
                            'field' => 'name',
                            'terms'    => $term->name ,
                        )
                    )
                );
                $result_post = new WP_Query($args);
                global $wp_query;
                // Put default query object in a temp variable
                $tmp_query = $wp_query;
                // Now wipe it out completely
                $wp_query = null;
                // Re-populate the global with our custom query
                $wp_query = $result_post;
            }
            if(is_tax('dalil_city')){
            $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $args = array(
                    'post_type' => 'dalil',
                    'posts_per_page' => 15,
                    'paged' => $paged,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'dalil_city',
                            'field' => 'name',
                            'terms'    => $term->name ,
                        )
                    )
                );
                $result_post = new WP_Query($args);
                global $wp_query;
                // Put default query object in a temp variable
                $tmp_query = $wp_query;
                // Now wipe it out completely
                $wp_query = null;
                // Re-populate the global with our custom query
                $wp_query = $result_post;
            }
            $count = 0;
        while (have_posts()) {
            the_post();
            $dalil_information = get_post_meta( get_the_ID() , 'dalil_information', true );
            $dalil_item_cat = get_the_terms( get_the_ID()  , 'dalil_cat' );
            if($dalil_item_cat != false){
                $dalil_item_cat_id =  $dalil_item_cat[0]->term_id;
                $dalil_item_cat =  $dalil_item_cat[0]->name;// we assume that every dalil item have one category listed udner it
            }
            ?>
            <div class="dalil_item">
                <a style="<?php if(is_rtl()){echo "float:left;";}else{echo "float:right;";};  ?>" class="w_dalil_glyphs icon-print" onclick="printDiv<?php echo $count;?>('print-content')" ></a>
                <h2 class="dalil-title"><?php echo the_title();?></h2>
                <?php 
                if($dalil_item_cat != false){ ?>
                <h3 class="dalil-cat">
                    <a href="<?php echo get_term_link ($dalil_item_cat_id ); ?>" ><?php echo $dalil_item_cat; ?></a>
                </h3>
                <?php } ?>
                <?php
                if(isset($dalil_information['dalil-address'] )){?>
                    <div class="dalil-address">
                    <span class="w_dalil_glyphs icon-location"></span>
                    <p><?php echo $dalil_information['dalil-address'] ;?></p>
                    </div>
                <?php }
                ?>
                <?php
                if(isset($dalil_information['dalil-phone']) && $dalil_information['dalil-phone']!=''){?>
                    <div class="dalil-phone">
                    <span class="w_dalil_glyphs icon-phone"></span>
                    <a href="tel:<?php echo $dalil_information['dalil-phone'] ;?>" ><p><?php echo $dalil_information['dalil-phone'] ;?></p></a>
                    </div>
                <?php }
                ?>
                <?php
                if(isset($dalil_information['dalil-email'] ) && $dalil_information['dalil-email']!=''){?>
                    <div class="dalil-email">
                    <span class="w_dalil_glyphs icon-mail-alt"></span>
                    <a onclick='pop_email(<?php echo get_the_ID();?>);'><p><?php echo $dalil_information['dalil-email'] ;?></p></a>
                    </div>
                <?php }
                ?>
                <?php
                if(isset($dalil_information['dalil-website'] ) && $dalil_information['dalil-website']!=''){?>
                    <div class="dalil-website">
                    <span class="w_dalil_glyphs icon-globe"></span>
                    <a target="_blank" href="http://<?php echo $dalil_information['dalil-website'] ;?>"><p><?php echo $dalil_information['dalil-website'] ;?></p></a>
                    </div>
                <?php }
                ?>
                <?php
                if( isset( $dalil_information['dalil-logo'] ) && $dalil_information['dalil-logo'] != null  && !get_post_meta(get_the_ID(),'dalil_item_hidden', true )  ){ ?>
                  

                <?php  }else{ ?>
                <!--                        </div>   end dalil_inf -->
                <!--                </div>   end dalil_item_container -->
                <?php  } ?>


                <!-- edit -->
                <?php
                if( is_user_logged_in() ){?>
                    <a target="_blank" class="w_dalil_edit_item" href="<?php echo  get_site_url(); ?>/wp-admin/post.php?post=<?php echo get_the_ID(); ?>&action=edit"><?php echo __('edit','w_dalil'); ?></a>
                <?php }
                ?>

                <script type="text/javascript">
                    function printDiv<?php echo $count;?>(divName) {
                        var printContents = jQuery(jQuery('.dalil_item')[<?php echo $count; ?>]).html();
                        w=window.open("", "", "width=500, height=300");
                        w.document.write('<!DOCTYPE html><html><body><img class="logo_print" src="<?php echo plugins_url(); ?>/w-dalil/includes/file/print_logo.png" />');
                        w.document.write(printContents);
                        w.document.write('<style>.w_dalil_edit_item{display:none;}.dalil-title, .dalil-phone, .dalil-address, .dalil-cat, .dalil-email, .dalil-website {text-align:center;display:block !important;margin:auto !important;float:none !important;}.print_footer{text-align:center;background-color:#202020;color:#fff;display:block;cleat:both;}.dalil_inf,.dalil_logo{display:block !important;margin:auto !important;float:none !important;max-width:200px;}.logo_print{margin:auto;display:block;max-width:200px;}</style>');
                        w.document.write('<p class="print_footer"><?php echo __('This is generated By : ','w_dalil'); echo get_bloginfo('url'); ?></p></body></html>');
                        w.print();
                        w.close();
                    }
                </script>

            </div><!-- End dalil Item  -->
            <hr />
        <?php $count++; }
        ?>
        <div class="pagination_center">
            <?php
            if(is_rtl()){ $next = "<span class='glyphicon glyphicon-chevron-right'></span>";}else{ $next = "<span class='glyphicon glyphicon-chevron-left'></span>";}
            if(is_rtl()){ $back = "<span class='glyphicon glyphicon-chevron-left'></span>";}else{ $back = "<span class='glyphicon glyphicon-chevron-right'></span>";}
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => __( $next, 'textdomain' ),
                'next_text' => __( $back, 'textdomain' ),
            ) );
            ?>
        </div>
    </div> <!-- end of dalil_container -->
    <div class="col-md-3">
    <?php do_shortcode('[list-cities-dalil]'); 
    dynamic_sidebar('dalil-sidebar-2');    ?>
    </div>
<?php 
}
function get_informatrion_inpage(){
    global $post;

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'dalil',
        'posts_per_page' => 5,
        'paged' => $paged,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    $result_post = new WP_Query($args);
    global $wp_query;
    // Put default query object in a temp variable
    $tmp_query = $wp_query;
    // Now wipe it out completely
    $wp_query = null;
    // Re-populate the global with our custom query
    $wp_query = $result_post;

    if($result_post->have_posts()){?>
    <div class="dalil_container">
    <?php
     while ( $result_post->have_posts() ) : $result_post->the_post(); 
                $dalil_information = get_post_meta( get_the_ID() , 'dalil_information', true );
                $dalil_item_cat = get_the_terms( get_the_ID()  , 'dalil_cat' );
                $dalil_item_cat_id =  $dalil_item_cat[0]->term_id;
                $dalil_item_cat =  $dalil_item_cat[0]->name;// we assume that every dalil item have one category listed udner it?>
                <div class="dalil_item">
                    <h2 class="dalil-title"><?php echo the_title();?></h2>
                    <h3 class="dalil-cat">
                        <a href="<?php echo get_term_link ($dalil_item_cat_id ); ?>" ><?php echo $dalil_item_cat; ?></a>
                    </h3>
                    <?php
                    if(isset($dalil_information['dalil-address'] )){?>
                        <div class="dalil-address">
                        <span class="w_dalil_glyphs icon-location"></span>
                        <p><?php echo $dalil_information['dalil-address'] ;?></p>
                        </div>
                    <?php }
                    ?>
                    <?php
                    if(isset($dalil_information['dalil-phone']) && $dalil_information['dalil-phone']!=''){?>
                        <div class="dalil-phone">
                        <span class="w_dalil_glyphs icon-phone"></span>
                        <p><?php echo $dalil_information['dalil-phone'] ;?></p>
                        </div>
                    <?php }
                    ?>
                    <?php
                    if(isset($dalil_information['dalil-email'] ) && $dalil_information['dalil-email']!=''){?>
                        <div class="dalil-email">
                        <span class="w_dalil_glyphs icon-mail-alt"></span>
                        <a onclick='pop_email(<?php echo get_the_ID();?>);'><p><?php echo $dalil_information['dalil-email'] ;?></p></a>
                        </div>
                    <?php }
                    ?>
                    <?php
                    if(isset($dalil_information['dalil-website'] ) && $dalil_information['dalil-website']!=''){?>
                        <div class="dalil-website">
                        <span class="w_dalil_glyphs icon-globe"></span>
                        <a target="_blank" href="http://<?php echo $dalil_information['dalil-website'] ;?>"><p><?php echo $dalil_information['dalil-website'] ;?></p></a>
                        </div>
                    <?php }
                    ?>
                    <?php
                    if( isset( $dalil_information['dalil-logo'] ) && $dalil_information['dalil-logo'] != null  && !get_post_meta(get_the_ID(),'dalil_item_hidden', true )  ){ ?>


                    <?php  }?>
                    <!-- edit -->
                    <?php
                    if( is_user_logged_in() ){?>
                        <a target="_blank" class="w_dalil_edit_item" href="<?php echo  get_site_url(); ?>/wp-admin/post.php?post=<?php echo get_the_ID(); ?>&action=edit"><?php echo __('edit','w_dalil'); ?></a>
                    <?php }
                    ?>

                </div><!-- End dalil Item  -->
    <?php 
        endwhile; ?>
        <div class="pagination_center">
                <?php
                if(is_rtl()){ $next = "<span class='glyphicon glyphicon-chevron-right'></span>";}else{ $next = "<span class='glyphicon glyphicon-chevron-left'></span>";}
                if(is_rtl()){ $back = "<span class='glyphicon glyphicon-chevron-left'></span>";}else{ $back = "<span class='glyphicon glyphicon-chevron-right'></span>";}
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => __( $next, 'textdomain' ),
                    'next_text' => __( $back, 'textdomain' ),
                ) );
                ?>
            </div>
        </div>

    <?php 
    }
$wp_query = null;
$wp_query = $tmp_query;
}

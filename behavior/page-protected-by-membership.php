<?php

/* Template Name: Protected By Membership */

$required_membership = get_field('required_membership');

if (is_user_logged_in()) {
    $account_number = get_user_meta(get_current_user_id(), 'account_number', true);

    if ($account_number[0] !== $required_membership) {
        get_header();
        do_action( 'flatsome_before_page' ); ?>
        <?php if ($required_membership_notification = get_field('required_membership_notification', get_the_ID()))
        : ?>
                
            
            <h2 align='center'>
		        <?php echo $required_membership_notification; ?>
		    </h2>
        <?php endif; ?>
        <?php
        do_action( 'flatsome_after_page' );
        get_footer();
    } else {

        if(flatsome_option('pages_layout') != 'default') {

            // Get default template from theme options.
            echo get_template_part('page', flatsome_option('pages_layout'));
            return;

        } else {

            get_header();
            do_action( 'flatsome_before_page' ); ?>
            <div id="content" class="content-area page-wrapper" role="main">
                <div class="row row-main">
                    <div class="large-12 col">
                        <div class="col-inner">

                            <?php if(get_theme_mod('default_title', 0)){ ?>
                                <header class="entry-header">
                                    <h1 class="entry-title mb uppercase"><?php the_title(); ?></h1>
                                </header><!-- .entry-header -->
                            <?php } ?>

                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php do_action( 'flatsome_before_page_content' ); ?>

                                <?php the_content(); ?>

                                <?php if ( comments_open() || '0' != get_comments_number() ){
                                    comments_template(); } ?>

                                <?php do_action( 'flatsome_after_page_content' ); ?>
                            <?php endwhile; // end of the loop. ?>
                        </div><!-- .col-inner -->
                    </div><!-- .large-12 -->
                </div><!-- .row -->
            </div>

            <?php
            do_action( 'flatsome_after_page' );
            get_footer();

        }
    }
} else {
    /*wp_die("<h2 align='center'>
		    To view this page you must first 
		    <a href='". wp_login_url(get_permalink()) ."&check-membership={$required_membership}' title='Login'>log in</a>
		</h2>");*/
    wp_redirect( '/התחברות-2/' . '?redirect-to=' . get_permalink() . "&check-membership={$required_membership}" );
}


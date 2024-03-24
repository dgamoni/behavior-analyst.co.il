<?php
/**
 * The template for displaying all pages.
 *
 * @package flatsome
 */



$urlArr=parse_url($_SERVER['REQUEST_URI']);
parse_str($urlArr['query'], $output);

//print_r($output['ConfirmationCode']);
if ( isset($output['ConfirmationCode'])) {
	//print_r($output['ConfirmationCode']);
	global $woocommerce;
	$woocommerce->cart->empty_cart(); 


	wp_redirect( home_url( 'תודה-על-רכישתך') );

    // unset($_COOKIE['woocommerce_cart_hash']); 
    // unset($_COOKIE['cart']);
    // unset($_COOKIE['woocommerce_items_in_cart']);

	// $WC_Session_Handler = new WC_Session_Handler();
	// $var = $WC_Session_Handler->destroy_session();
}




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




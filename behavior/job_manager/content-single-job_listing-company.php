<?php
/**
 * Single view Company information box
 *
 * Hooked into single_job_listing_start priority 30
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-single-job_listing-company.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @since       1.14.0
 * @version     1.32.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! get_the_company_name() ) {
	return;
}
?>
<div class="company">
	<?php the_company_logo(); ?>

	<p class="name">
		<?php the_company_name( '<strong>', '</strong>' ); ?>
	</p>

    <p class="email">
        <?php the_company_email( ); ?>
    </p>

    <p class="phone">
        <?php the_company_phone( '<strong>', '</strong>' ); ?>
    </p>
</div>

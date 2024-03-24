<?php

/* Template Name: Protected By Membership */

$required_membership = get_field('required_membership');
//var_dump($required_membership);

if (is_user_logged_in()) {
    $account_number = get_user_meta(get_current_user_id(), 'account_number', true);
    //$account_number = get_user_meta(92, 'account_number', true); //test
    //var_dump($account_number[0]);

    $active_member = get_user_meta( get_current_user_id(), 'active_member', true );
    //$active_member = get_user_meta( 92, 'active_member', true );
    //var_dump($active_member);


    if ( (in_array($account_number[0], $required_membership) && $active_member == 'true') || current_user_can('administrator') ) {

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
    else {
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
    }

} else {
    /*wp_die("<h2 align='center'>
		    To view this page you must first 
		    <a href='". wp_login_url(get_permalink()) ."&check-membership={$required_membership}' title='Login'>log in</a>
		</h2>");*/
    wp_redirect( '/התחברות-2/' . '?redirect-to=' . get_permalink() . "&check-membership={$required_membership}" );
}



//$emails = mailchimp_get_all_subcriber();

//echo "<pre style='display:none;'>", var_dump($emails), "</pre>";

// update members
// if ($emails) :
// echo "<pre style='display:none;'>";

//     $members = get_users('meta_key=account_number');
//     foreach ($members as $key => $line) {
//             $json = str_replace('\\\'', '\\\\\'', get_user_meta($line->data->ID, 'membership_data', true));
//             $membership_data = json_decode($json, true);
//             //echo "<pre style='display:none'>", var_dump($membership_data), "</pre>";
//             $user_mail = $membership_data["user-email"];
//             $user_fname = $membership_data["first-name"]; 
//             $user_lname = $membership_data["last-name"]; 
//             $user_phone = $membership_data["phone-number"]; 
//             $tag = $membership_data["membership_type"];

//             if ( !in_array($user_mail, $emails) && $user_mail == 'adigolan0910@gmail.com' ) {
//                  var_dump($user_mail);
//                  $result = mailchimp_update_subcriber($user_mail, $user_fname, $user_lname, $user_phone, $tag);
//                  var_dump($result);
//             }


//             // update_user_meta($line->data->ID, 'membership_payment_date', $membership_data['payment_date']);
       
//     }

// echo "</pre>";
// endif;

    // var_dump($key);

// $members = get_users('meta_key=membership_data');
// $members_emails = array();
//         foreach ($members as $key => $member) {
//             array_push($members_emails, $member->data->user_email);
//         }

//         var_dump($members_emails);




// array(26) {
//   ["account_number"]=>
//   string(5) "C0253"
//   ["membership_type"]=>
//   string(12) "עמיתים"
//   ["payment_date"]=>
//   int(1555342825)
//   ["transaction_index"]=>
//   string(10) "B207914665"
//   ["form_type"]=>
//   string(10) "colleagues"
//   ["last-name"]=>
//   string(12) "ברשישט"
//   ["first-name"]=>
//   string(10) "פנינה"
//   ["birth-date"]=>
//   string(10) "1974-02-08"
//   ["address"]=>
//   string(39) "שבט לוי 19 דירה 21 אשדוד"
//   ["cell-phone"]=>
//   string(10) "0547821974"
//   ["phone-number"]=>
//   string(0) ""
//   ["user-email"]=>
//   string(21) "pnina.yossi@gmail.com"
//   ["id-number"]=>
//   string(9) "025749698"
//   ["accreditation"]=>
//   string(22) "אונ\' תל אביב"
//   ["ba-in-the-field"]=>
//   string(27) "כלכלה חשבונאות"
//   ["ba-academic-institution"]=>
//   string(13) "בר אילן"
//   ["masters-degree-in-the-field"]=>
//   string(0) ""
//   ["masters-academic-institution"]=>
//   string(0) ""
//   ["additional-degree"]=>
//   string(0) ""
//   ["additional-academic-institution"]=>
//   string(0) ""
//   ["finish-date"]=>
//   string(10) "2016-07-01"
//   ["speciality"]=>
//   string(12) "אוטיזם"
//   ["occupation"]=>
//   string(36) "מנתחת התנהגות באלוט"
//   ["coupon"]=>
//   string(0) ""
//   ["email"]=>
//   string(21) "pnina.yossi@gmail.com"
//   ["file_path"]=>
//   array(1) {
//     [0]=>
//     string(109) "/home/behavenew/public_html/wp-content/uploads/members/colleagues/student-certificate/pnina.yossi@gmail.com/."
//   }
// }
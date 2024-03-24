<?php



error_log(print_r('Payment data', true));
error_log(print_r($_GET, true));

get_header(); 
?>

<?php do_action( 'flatsome_before_page' ); ?>

<div class="row page-wrapper">
<div id="content" class="large-12 col" role="main">


<!--                     <header class="entry-header text-center">
                        <h1 class="entry-title">הצטרפות חברים חדשים</h1>
                        <div class="is-divider medium"></div>
                    </header> -->

                    <div class="entry-content">

                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php do_action( 'flatsome_before_page_content' ); ?>
                                
                                    <?php the_content(); ?>

                                <?php do_action( 'flatsome_after_page_content' ); ?>
                            <?php endwhile; // end of the loop. ?>

                            <div class="account-registration-wrapper">

<!--                                 <div class="logo">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/new-l-m.jpg" alt="Israel Certified Behavior Analyst">
                                </div> -->

                            <?php if (!empty($_GET['payment_status'])) {
                                $html = '';

                                switch ($_GET['payment_status']) {
                                    case 'success':
                                        require_once 'classes/Member.php';
                                        $member = new Member();
                                        $status = $member->registerNewPayMember($_GET);

                                        if ($status) {
                                            $html =' 
                                                <h1>שתהיה לנו שנה נפלאה!</h1>
                                                <p>תודה על הצטרפותך למנת״ה, קובץ מידע אודות שירותי הארגון נשלח אליך ישירות למייל ברגעים אלה ממש.</p>
                                            ';
                                    
                                        } else {
                                            $html = '
                                                <h1>Registration failed. Please, contact to our support team.</h1>
                                            ';
                                        }
                                        
                                        break;
                                    case 'fail':
                                        $html = '
                                            <h1>Payment failed!</h1>
                                            <p>Please, try again later.</p>
                                        ';
                                        break;
                                    case 'cancel':
                                        $html = '
                                            <h1>Payment was canceled!</h1>
                                            <p>Thank you for your time.</p>
                                        ';
                                        break;
                                }

                                echo $html;
                                ?>

                            <?php } else { ?>
                                <!-- <h1>הצטרפות חברים חדשים</h1> -->

                                <div class="member-types">
                                    <?php /* <div class="type" data-form-name="fellows">ידידים</div> */?>
                                    <div class="type" data-form-name="colleagues">עמיתים</div>
                                    <div class="type" data-form-name="students">סטודנטים</div>
                                    <div class="type active" data-form-name="friends">חברים</div>
                                </div>

                                <div class="form-ajax"></div>
                            <?php }?>

                            </div>
                    </div>
                    

        


</div><!-- #content -->
</div><!-- .row -->

<script>
    jQuery(document).ready(function($){
        //$('.elementor-heading-title').text('הצטרפות חברים חדשים');
    });
</script>

<?php get_footer(); ?>

<?php 

// $user = get_user_by('login', 'bigcatcode@gmail.com');
// var_dump($user->ID);
// $disabled = get_user_meta( $user->ID, 'active_member', true );
// var_dump($disabled);
// var_dump ( user_can( $user->ID, 'subscriber' ) );


// reactivate_member_message_custom('dgamoni@gmail.com');

?>
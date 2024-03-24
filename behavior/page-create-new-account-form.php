<?php
/**
 * Created by PhpStorm.
 * User: oleksii.khodakivskyi
 * Date: 25.12.18
 * Time: 13:10
 */

error_log(print_r('Payment data', true));
error_log(print_r($_GET, true));

//if (!empty($_GET['membership_type'])) {
//    require_once 'classes/Member.php';
//    $member_test = new Member();
//    $member_test->subscribeMember($_GET['user_mail'], $_GET['user_phone'], $_GET['membership_type']);
//}

get_header();?>

<div class="account-registration-wrapper">

    <div class="logo">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/new-l-m.jpg" alt="Israel Certified Behavior Analyst">
    </div>

<?php if (!empty($_GET['payment_status'])) {
    $html = '';

    switch ($_GET['payment_status']) {
        case 'success':
            require_once 'classes/Member.php';
            $member = new Member();
            $status = $member->registerNewPayMember($_GET);

            if ($status) {
                $html = <<<HTML
                <h1>שתהיה לנו שנה נפלאה!</h1>
                <p>תודה על הצטרפותך למנת״ה, קובץ מידע אודות שירותי הארגון נשלח אליך ישירות למייל ברגעים אלה ממש.</p>
HTML;
		
            } else {
                $html = <<<HTML
                <h1>Registration failed. Please, contact to our support team.</h1>
HTML;
            }
			
            break;
        case 'fail':
            $html = <<<HTML
                <h1>Payment failed!</h1>
                <p>Please, try again later.</p>
HTML;
            break;
        case 'cancel':
            $html = <<<HTML
                <h1>Payment was canceled!</h1>
                <p>Thank you for your time.</p>
HTML;
            break;
    }

    echo $html;
    ?>

<?php } else { ?>
    <h1>הצטרפות חברים חדשים</h1>

    <div class="member-types">
        <?php /* <div class="type" data-form-name="fellows">ידידים</div> */?>
        <div class="type" data-form-name="colleagues">עמיתים</div>
        <div class="type" data-form-name="students">סטודנטים</div>
        <div class="type active" data-form-name="friends">חברים</div>
    </div>

    <div class="form-ajax"></div>
<?php }?>

</div>
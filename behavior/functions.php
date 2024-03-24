<?php
if (is_admin()) {
    require_once 'classes/MemberAdmin.php';
    $member_admin = new MemberAdmin();
}

require_once 'classes/Member.php';
add_filter( 'woocommerce_order_button_text', 'woo_custom_order_button_text' );

function woo_custom_order_button_text() {
    return __( 'המשך לתשלום', 'woocommerce' );
}

/**
 * Lost password redirect.
 */
function behavior_reset_password_redirect() {
//    wc_add_notice( __( 'Your password has been reset successfully.', 'woocommerce' ) );
    wp_safe_redirect( get_bloginfo('url') );
    exit;
}
add_action( 'woocommerce_customer_reset_password', 'behavior_reset_password_redirect', 1, 2 );

//function iconic_reset_password_redirect( $user ) {
//    wc_add_notice( __( 'Your password has been reset successfully.', 'woocommerce' ) );
//    wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
//    exit;
//}
//add_action( 'woocommerce_customer_reset_password', 'iconic_reset_password_redirect', 10 );

// Add custom Theme Functions here
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    global $wp;
    /* START CSS */
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
    wp_enqueue_style( 'common-style', get_stylesheet_directory_uri().'/assets/css/common.css?' . time(), array('parent-style') );

    if (preg_match('#^%D7%97%D7%99%D7%A4%D7%95%D7%A9(/.+)?$#', $wp->request) || is_page_template( 'search-engine.php' )) {
        wp_enqueue_style( 'search-style', get_stylesheet_directory_uri() . '/assets/css/search.css?' . time(), array('parent-style'));
    }
    /* END CSS */

    /* START JS */
    wp_enqueue_script( 'validate-js', get_stylesheet_directory_uri().'/assets/js/libs/jquery.validate.min.js?' . time(), array('jquery') );
    wp_register_script( 'common-js', get_stylesheet_directory_uri().'/assets/js/common.js?' . time(), array('validate-js') );
    wp_localize_script( 'common-js', 'wpAjaxURL', admin_url( 'admin-ajax.php' ));
    wp_localize_script( 'common-js', 'wpHomeURL', home_url());
    wp_enqueue_script( 'common-js' );

    // SEARCH
    wp_enqueue_script( 'functions-js',  get_stylesheet_directory_uri() . '/assets/js/get_post_ajax_request.js?' . time() , array( 'jquery' ));
    /* END JS */
}

add_action('admin_enqueue_scripts', 'admin_enqueue');
function admin_enqueue($hook) {
    wp_enqueue_script('main-js', get_stylesheet_directory_uri().'/assets/js/admin/main.js?' . time());
}

add_filter( 'posts_where', 'title_like_posts_where', 10, 2 );
function title_like_posts_where( $where, &$wp_query ) {
    global $wpdb;
    if ( $post_title_like = $wp_query->get( 'post_title_like' ) ) {
        $pieces = explode(" ", $post_title_like);
        if (count($pieces) == 1) {
            $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' .  $wpdb->esc_like( $post_title_like ) . '%\') ';
        } else {
            $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . $post_title_like  . '%\' OR ' . $wpdb->posts . '.post_title LIKE \'%' . $pieces[1] .' '. $pieces[0] . '%\' ) ';
        }

    }
    return $where;
}

add_action( 'wp_ajax_nopriv_awpqsf_ajax','awpqsf_ajax' );
add_action( 'wp_ajax_awpqsf_ajax',  'awpqsf_ajax' );
function awpqsf_ajax()
{
    /* Tools
    ---------------------------------- */
    // global $post;
    parse_str($_POST['getdata'], $getdata);
    $nonce =  $getdata['s'];
    $pagenumber = isset($_POST['pagenum']) ? $_POST['pagenum'] : null;
    $search_name = $getdata['search_name'];
    $areas =  $getdata['areas'];
    $ctegories = $getdata['ctegories'];
    $populations = $getdata['populations'];
    $nonce_post = wp_create_nonce("view_post");



    /* Areas key value
    ---------------------------------- */
    if ($areas == 'areas' || $areas == '')
    {
        $key = "areas";
        $value = "";
    }  else {
        if ($areas != 'areas' || $areas != '')
        {
            $key = "";
            $value = $areas;

            if($areas == 'north_areas'){
                $key = "areas";
                $value = "north";
            }
            if($areas == 'sharon_areas'){
                $key = "areas";
                $value = "sharon";
            }
            if($areas == 'center_areas'){
                $key = "areas";
                $value = "center";
            }
            if($areas == 'shfela_areas'){
                $key = "areas";
                $value = "shfela";
            }
            if($areas == 'jerusalem_areas'){
                $key = "areas";
                $value = "jerusalem";
            }
            if($areas == 'south_areas'){
                $key = "areas";
                $value = "south";
            }
        }

    }




    /* Areas and Cities key value
   ---------------------------------- */
    /*'areas'           => 'field_54c3b63e60f1b',
    'north_areas'     => 'field_54c3b66560f1c',
    'sharon_areas'    => 'field_54c3b69d60f1d',
    'center_areas'    => 'field_54c3bb4360f1e',
    'shfela_areas'    => 'field_54c3bc4f60f1f',
    'jerusalem_areas' => 'field_54c3bd4760f20',
    'south_areas'     => 'field_54c3bd9960f21'*/


    $north_areas = get_field_object('field_54c3b66560f1c');
    if( $north_areas )
    {
        foreach( $north_areas['choices'] as $nkeys => $nvalues )
        {
            $north_areaCitykey[] = $nkeys;
        }
        if (in_array($value, $north_areaCitykey)) {
            $key = $north_areas['name'];
            // echo $key;
        }
    }

    $sharon_areas = get_field_object('field_54c3b69d60f1d');
    if( $sharon_areas )
    {
        foreach( $sharon_areas['choices'] as $skeys => $svalues )
        {
            $sharon_areaCitykey[] = $skeys;
        }
        if (in_array($value, $sharon_areaCitykey)) {
            $key = $sharon_areas['name'];
            // echo $key;
        }
    }

    $center_areas = get_field_object('field_54c3bb4360f1e');
    if( $center_areas )
    {
        foreach( $center_areas['choices'] as $ckeys => $cvalues )
        {
            $center_areaCitykey[] = $ckeys;
        }
        if (in_array($value, $center_areaCitykey)) {
            $key = $center_areas['name'];
            // echo $key;
        }
    }

    $shfela_areas = get_field_object('field_54c3bc4f60f1f');
    if( $shfela_areas )
    {
        foreach( $shfela_areas['choices'] as $shkeys => $shvalues )
        {
            $shfela_areaCitykey[] = $shkeys;
        }
        if (in_array($value, $shfela_areaCitykey)) {
            $key = $shfela_areas['name'];
            // echo $key;
        }
    }

    $jerusalem_areas  = get_field_object('field_54c3bd4760f20');
    if( $jerusalem_areas )
    {
        foreach( $jerusalem_areas['choices'] as $jkeys => $jvalues )
        {
            $jerusalem_areaCitykey[] = $jkeys;
        }
        if (in_array($value, $jerusalem_areaCitykey)) {
            $key = $jerusalem_areas['name'];
            // echo $key;
        }
    }

    $south_areas  = get_field_object('field_54c3bd9960f21');
    if( $south_areas )
    {
        foreach( $south_areas['choices'] as $sokeys => $sovalues )
        {
            $south_southareaCitykey[] = $sokeys;
        }
        if (in_array($value, $south_southareaCitykey)) {
            $key = $south_areas['name'];
            // echo $key;
        }
    }

    /* Categoriese
   ---------------------------------- */
    if($ctegories == ''){
        $ctegories = [-19, -1];
    }

    /* Populations key value
   ---------------------------------- */
    if ($populations == '')
    {
        $keypopulations  = '';
        $valuepopulations = '';
    }else{
        $keypopulations  = 'populations';
        $valuepopulations = $populations;
    }

    /* The Fucking machine ;)
   ---------------------------------- */
    if(wp_verify_nonce($nonce, 'ajaxwpsfsearch'))
    {

        if (!empty($search_name)){
            $the_query = new WP_Query
            (
                array(
                    'post_title_like' => $search_name,
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'paged'=> $pagenumber,
                    'posts_per_page' => 10,
                    'cat' => -19,
                    'orderby' => 'title',
                    'order'   => 'ASC',
                )
            );
        } else {

            if($populations != '' && $areas != 'areas' && $areas != '' ){
                /*
                    if (!empty($search_name)){
                        $the_query = new WP_Query
                        (
                            array(
                                'post_title_like' => $search_name,
                                'post_type' => 'post',
                                'post_status' => 'publish',
                                'meta_query' => array(
                                        'relation' => 'AND',
                                        array(
                                                'key' => $key ,
                                                'value' =>  $value,
                                                'compare' => 'LIKE'
                                        ),
                                        array(
                                                'key' => $keypopulations,
                                                'value' => $valuepopulations,
                                                'compare' => 'LIKE'
                                        ),
                                ),
                                'paged'=> $pagenumber,
                                'posts_per_page' => 10,
                                'cat' => $ctegories,
                                'orderby' => 'title',
                                'order'   => 'ASC'
                            )
                        );
                    } else {*/
                $the_query = new WP_Query
                (
                    array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => $key ,
                                'value' =>  $value,
                                'compare' => 'LIKE'
                            ),
                            array(
                                'key' => $keypopulations,
                                'value' => $valuepopulations,
                                'compare' => 'LIKE'
                            ),
                        ),
                        'paged'=> $pagenumber,
                        'posts_per_page' => 10,
                        'cat' => $ctegories,
                        'orderby' => 'title',
                        'order'   => 'ASC'
                    )
                );
                // }

            } else {

                if ($populations != '' && ($areas == 'areas'|| $areas == '') ) {
                    /*
                    if (!empty($search_name)){
                            $the_query = new WP_Query
                          (
                             array(
                                    'post_title_like' => $search_name,
                                 'post_type' => 'post',
                                 'post_status' => 'publish',
                                 'meta_key'=> $keypopulations ,
                                 'meta_value'=> $valuepopulations,
                                 'meta_compare'=> 'LIKE',
                                 'paged'=> $pagenumber,
                                 'posts_per_page' =>10,
                                 'cat' => $ctegories,
                                 'orderby' => 'title',
                                 'order'   => 'ASC'
                             )
                         );
                    } else { */
                    $the_query = new WP_Query
                    (
                        array(
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'meta_key'=> $keypopulations ,
                            'meta_value'=> $valuepopulations,
                            'meta_compare'=> 'LIKE',
                            'paged'=> $pagenumber,
                            'posts_per_page' =>10,
                            'cat' => $ctegories,
                            'orderby' => 'title',
                            'order'   => 'ASC'
                        )
                    );
                    // }
                } else {
                    /*
                        if (!empty($search_name)){
                            $the_query = new WP_Query
                            (
                               array(
                                      'post_title_like' => $search_name,
                                    'post_type' => 'post',
                                    'post_status' => 'publish',
                                    'meta_query' => array(
                                        'relation' => 'AND',
                                        array(
                                                'key' => $key ,
                                                'value' =>  $value,
                                                'compare' => 'LIKE'
                                        ),
                                    ),
                                    'paged'=> $pagenumber,
                                    'posts_per_page' =>10,
                                    'cat' => $ctegories,
                                    'orderby' => 'title',
                                   'order'   => 'ASC',
                               )
                           );
                        } else { */
                    $the_query = new WP_Query
                    (
                        array(
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'meta_query' => array(
                                'relation' => 'AND',
                                array(
                                    'key' => $key ,
                                    'value' =>  $value,
                                    'compare' => 'LIKE'
                                ),
                            ),
                            'paged'=> $pagenumber,
                            'posts_per_page' =>10,
                            'cat' => $ctegories,
                            'orderby' => 'title',
                            'order'   => 'ASC',
                        )
                    );
//                }

                }
            }
        }
//       echo "<pre>";print_r($the_query);echo "</pre>";exit;-

        /* The Loop
         * -----------------------*/

        $areasarr = array(
            'north_areas',
            'sharon_areas',
            'center_areas',
            'shfela_areas',
            'jerusalem_areas',
            'south_areas'
        );


        if ($the_query->have_posts())
        {
            while ( $the_query->have_posts())
            {
                $the_query->the_post();

                $post_id = get_the_ID();



                echo'<article class="post-row-'. $post_id .' article-row">';

                //echo'<a class="post-link button" rel="'. $post_id .'"href="'. get_permalink() .'" data-nonce="' . $nonce_post . '" onclick="return false;">'.  get_the_title() .'<i class="fa fa-arrow-left"></i></a>' ;
                //echo    get_the_title() . '</br>';
                echo    '<div class="full-name">שם: '.  get_the_title() .'</div>';
                echo    '<div class="top">';
                echo    '<ul class="top-lists">';
                echo    '<li>אזור מגורים: ' . get_field('living_area') . '</li>';
                echo    '<li>סוג הסמכה: ' . get_field('type_certification') . '</li>';
                echo    '<li>מספר הסמכה: ' . get_field('certification_number') . '</li>';
                echo    '</ul><ul class="top-lists">';
                echo    '<li>שנת הסמכה: ' . get_field('year_of_certification') . '</li>';
                echo    '<li>השכלה: ' . get_field('education') . '</li>';
                echo    '</ul><ul class="top-lists">';
                echo    '<li>טלפון: ' . get_field('phone') . '</li>';
                echo    '<li>דוא"ל: <a href="mailto:'.get_field('email').'">' . get_field('email') . '</a></li>';
                echo    '<li>אתר: <a href="http://' . get_field('website') . '" alt="' . get_field('website') . '" title="' . get_field('website') . '" target="_blank">' . get_field('website') . '</a></li>';
                echo    '</ul></div>';

                echo    '<div class="bottom"><div class="row orang">
                                <div class="label big">זמינות</div><div class="value"><div class="label">אזורי עבודה: </div>';
                foreach($areasarr as $maetak => $meta_key){
                    $allareaskey =    get_field($meta_key);
                    if($allareaskey){
                        if(is_array($allareaskey)){
                            $allareas_key_nameaa = get_field_object($meta_key);
                            $label_allreas_labelaa = str_replace("אזור", "",   $allareas_key_nameaa['label']);
                            echo '<span class="wrap-area"><strong>' . $label_allreas_labelaa.':</strong> ';
                            foreach($allareaskey as $allareaskey_val){
                                $allareas_key_name = get_field_object($meta_key);
                                $label_allreas_key = $allareas_key_name['choices'][$allareaskey_val];
                                if($label_allreas_key){
                                    $counter = count($label_allreas_key);

                                    echo  $label_allreas_key .'<span> , </span>';
                                }
                            }
                            echo '</span>';
                        }
                    }else{
                        if($maetak == 1 ){
                            $areaslocation =  get_field('areas');
                            if($areaslocation){
                                echo '<span class="wrap-area">';
                                foreach($areaslocation as $areaslocationval){
                                    $areaslocationfield_name = get_field_object('areas');
                                    $areaslocationlabel = $areaslocationfield_name['choices'][ $areaslocationval ];
                                    echo $areaslocationlabel . "<span> , </span>";
                                }
                                echo '</span>';
                            }
                        }
                    }

                }

                if($counter === ''){
                    $areaslocation =  get_field('areas');
                    if($areaslocation){
                        echo '<span class="wrap-area">';
                        foreach($areaslocation as $areaslocationval){
                            $areaslocationfield_name = get_field_object('areas');
                            $areaslocationlabel = $areaslocationfield_name['choices'][ $areaslocationval ];
                            echo $areaslocationlabel . "<span> , </span>";
                        }
                        echo '</span>';
                    }
                }

                echo    '  </div>
                            </div>
                            <div class="row orang">
                                <div class="label big no-bg"></div><div class="value"><div class="label">שעות עבודה: </div>'. get_field('can_work') .'</div>
                            </div>';

                echo '<div class="row gray"><div class="label big">תחומי התמחות</div><div class="value"><div class="label"></div><span class="wrap-area">';
                $practice_areas =    get_field('practice_areas');
                if($practice_areas){
                    foreach($practice_areas as $value_name){
                        $field_name = get_field_object('practice_areas');
                        $label = $field_name['choices'][ $value_name ];
                        echo $label . "<span> , </span>";
                    }
                }
                echo '</span></div></div>';

                echo '<div class="row orang"><div class="label big">אוכלוסיות</div><div class="value"><div class="label"></div><span class="wrap-area">';
                $populationsarray =    get_field('populations');
                if($populationsarray){
                    foreach($populationsarray as $populationsarrayvalue){
                        $field_name_populations = get_field_object('populations');
                        $label_populations = $field_name_populations['choices'][ $populationsarrayvalue ];
                        echo $label_populations . "<span> , </span>";
                    }
                }
                echo '</span></div></div>';

                echo '<div class="row gray"><div class="label big">התמחות</div><div class="value"><div class="label"></div><span class="wrap-area">';
                $post_categories = wp_get_post_categories( $post_id );
                $cats = array();

                foreach($post_categories as $c){
                    $cat = get_category( $c );
                    if($cat->cat_ID !== 1){
                        echo  $cat->name . "<span> , </span>";
                    }else{
                        echo  $cat->name . ': ' . strip_tags(get_field('ather')) . "<span> , </span>";
                    }
                }
                echo '</span></div></div>';
                echo '</div>';
                echo'</article>';
            }
            echo ajax_pagination($pagenumber,$the_query->max_num_pages, 10);
        } else{
            echo 'לא נמצאו תוצאות!';

        }
        wp_reset_postdata();

        /*

            Debugging
        * ------------- *

            echo 'areas: '. $areas . '</br>';
            echo 'populations: '.$populations . '</br>';
            echo 'ctegories: '.$ctegories . '</br>';
            echo 'key: ' . $key . '</br>';
            echo 'value: ' . $value . '</br>';
            echo 'keypopulations: ' . $keypopulations . '</br>';
            echo 'valuepopulations: ' . $valuepopulations . '</br>';

         */

    }else{ echo 'no naughty busisness here';}
    die;
}//end ajax

function ajax_pagination($pagenumber, $pages = '', $range = 4)
{
    $showitems = ($range * 2)+1;
    $paged = $pagenumber;
    if(empty($paged)) $paged = 1;

    if($pages == '')
    {
        global $wp_query;
        $pages = $query->max_num_pages;

        if(!$pages)
        {
            $pages = 1;
        }
    }

    if(1 != $pages)
    {
        echo  "<div class=\"ajaxsfpagi\">  ";
        echo '<input type="hidden" id="curform" value="#ajax_wpqsffrom_">';
        //<span>Page ".$paged." of ".$pages."</span>";
        if($paged > 2 && $paged > $range+1 && $showitems < $pages)
            echo '<a id="1" class="pagievent" href="#">&laquo; '.__("First","AjWPQSF").'</a>';
        $previous = $paged - 1;
        if($paged > 1 && $showitems < $pages) echo '<a id="'.$previous.'" class="pagievent" href="#">&lsaquo; '.__("Previous","AjWPQSF").'</a>';

        for ($i=1; $i <= $pages; $i++)
        {
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
            {
                echo ($paged == $i)? '<span class="pagicurrent">'.$i.'</span>': '<a id="'.$i.'" href="#" class="pagievent inactive">'.$i.'</a>';
            }
        }

        if ($paged < $pages && $showitems < $pages){
            $next = $paged + 1;
            echo '<a id="'.$next.'" class="pagievent"  href="#">'.__("Next","AjWPQSF").' &rsaquo;</a>';

        }
        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) {
            echo '<a id="'.$pages.'" class="pagievent"  href="#">'.__("Last","AjWPQSF").' &raquo;</a>';}
        echo "</div>\n";
        $max_num_pages = $pages;
        return apply_filters('ajwpqsf_pagination',$html,$max_num_pages,$pagenumber);
    }

}

add_action( 'wp_ajax_register-new-free-member', 'register_new_free_member' );
add_action( 'wp_ajax_nopriv_register-new-free-member', 'register_new_free_member' );
function register_new_free_member(){
    if (!empty($_POST)) {
        //error_log(print_r($_POST, true));

        $member = new Member();
        $status = $member->registerNewFreeMember($_POST);

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

        echo $html;
    }

    wp_die();
}

add_action('wp_ajax_get-ajax-form', 'get_ajax_form');
add_action('wp_ajax_nopriv_get-ajax-form', 'get_ajax_form');
function get_ajax_form() {
    if (!empty($_POST['form_name'])) {
        require_once get_stylesheet_directory() . '/template-parts/forms/form-' . $_POST['form_name'] . '.php';
    }

    wp_die();
}

add_action('wp_ajax_save-draft-member-data', 'save_draft_member_data');
add_action('wp_ajax_nopriv_save-draft-member-data', 'save_draft_member_data');
function save_draft_member_data() {
    //error_log(print_r($_POST, true));
    //error_log(print_r($_FILES, true));
    //die();
    if (!empty($_POST)) {
        if (!empty($_FILES)) {
            $_POST['files'] = $_FILES;
        }

        Member::saveDraftMemberData(filterPelepayFields($_POST));
    }

    wp_die();
}

function filterPelepayFields($data) {
    unset($data['cancel_return']);
    unset($data['fail_return']);
    unset($data['success_return']);
    unset($data['amount']);
    unset($data['business']);
    unset($data['description']);
    unset($data['max_payments']);
    unset($data['firstname']);
    unset($data['lastname']);
    unset($data['phone']);

    return $data;
}

add_action('wp_ajax_get-discount-price', 'get_discount_price');
add_action('wp_ajax_nopriv_get-discount-price', 'get_discount_price');
function get_discount_price() {
    //error_log(print_r($_POST, true));
    //die();

    $response = array(
        'status' => 0,
        'message' => 'הקופון לא הופעל'
    );

    if (!empty($_POST['coupon_name']) && !empty($_POST['price'])) {
        $price = Member::getMembershipPrice($_POST['coupon_name'], $_POST['price']);

        if (!empty($price)) {
            $response = array(
                'status' => 1,
                'message' => 'הקופון הופעל בהצלחה',
                'price' => $price
            );
        }
    }

    wp_send_json($response);
    wp_die();
}

add_action('wp_ajax_get-member-data', 'get_member_data');
add_action('wp_ajax_nopriv_get-member-data', 'get_member_data');
function get_member_data() {
    //error_log(print_r($_GET, true));
    //die();

    $response = array(
        'status' => 0,
        'message' => 'Data not found'
    );

    if (!empty($_GET['user_id'])) {
        $member_data = Member::getMembershipData($_GET['user_id']);
        //error_log(print_r($member_data, true));

        $member_data['payment_date'] = get_date_from_gmt( date( 'Y-m-d H:i:s', $member_data['payment_date'] ), 'F j, Y H:i:s');

        if (!empty($member_data['file_path'])) {
            if (is_array($member_data['file_path'])) {
                foreach ($member_data['file_path'] as $key => $path) {
                    $member_data['file_path'][$key] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);
                }
            } else {
                $member_data['file_path'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $member_data['file_path']);
            }
        }

        if (!empty($member_data)) {
            $response = array(
                'status' => 1,
                'message' => '',
                'member_data' => $member_data
            );
        }
    }

    wp_send_json($response);
    wp_die();
}

/**
 * Membership discount prices
 */
add_action( 'woocommerce_product_options_pricing', 'wc_membership_discount_price' );
add_action( 'woocommerce_variation_options_pricing', 'wc_membership_discount_variation_price', 10, 3 );
function wc_membership_discount_price() {
    woocommerce_wp_text_input(array(
            'id' => 'membership_price_A',
            'class' => 'wc_input_price short',
            'label' => 'חברים' . ' (' . get_woocommerce_currency_symbol() . ')',
        )
    );

    woocommerce_wp_text_input(array(
            'id' => 'membership_price_B',
            'class' => 'wc_input_price short',
            'label' => 'סטודנטים' . ' (' . get_woocommerce_currency_symbol() . ')'
        )
    );

    woocommerce_wp_text_input(array(
            'id' => 'membership_price_C',
            'class' => 'wc_input_price short',
            'label' => 'עמיתים' . ' (' . get_woocommerce_currency_symbol() . ')'
        )
    );
}

function wc_membership_discount_variation_price($loop, $variation_data, $variation) {
    woocommerce_wp_text_input(array(
            'id' => 'membership_variation_price_A' . $variation->ID,
            'name' => 'membership_variation_price_A[' . $variation->ID . ']',
            'class' => 'wc_input_price short',
            'label' => 'חברים' . ' (' . get_woocommerce_currency_symbol() . ')',
            'value' => get_post_meta( $variation->ID, 'membership_price_A', true )
        )
    );

    woocommerce_wp_text_input(array(
            'id' => 'membership_variation_price_B[' . $variation->ID . ']',
            'class' => 'wc_input_price short',
            'label' => 'סטודנטים' . ' (' . get_woocommerce_currency_symbol() . ')',
            'value' => get_post_meta( $variation->ID, 'membership_price_B', true )
        )
    );

    woocommerce_wp_text_input(array(
            'id' => 'membership_variation_price_C[' . $variation->ID . ']',
            'class' => 'wc_input_price short',
            'label' => 'עמיתים' . ' (' . get_woocommerce_currency_symbol() . ')',
            'value' => get_post_meta( $variation->ID, 'membership_price_C', true )
        )
    );
}

/**
 * Save membership rices
 */
add_action( 'save_post', 'wc_membership_discount_price_save' );
function wc_membership_discount_price_save( $product_id ) {
    // stop the quick edit interferring as this will stop it saving properly, when a user uses quick edit feature
    if (wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce'))
        return;

    // If this is a auto save do nothing, we only save when update button is clicked
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;


    if (!empty($_POST['membership_price_A']) && !is_array($_POST['membership_price_A'])) {
        if ( is_numeric( $_POST['membership_price_A'] ) )
            update_post_meta( $product_id, 'membership_price_A', $_POST['membership_price_A'] );
    } else {
        delete_post_meta( $product_id, 'membership_price_A' );
    }

    if (!empty($_POST['membership_price_B']) && !is_array($_POST['membership_price_B'])) {
        if ( is_numeric( $_POST['membership_price_B'] ) )
            update_post_meta( $product_id, 'membership_price_B', $_POST['membership_price_B'] );
    } else {
        delete_post_meta( $product_id, 'membership_price_B' );
    }

    if (!empty($_POST['membership_price_C']) && !is_array($_POST['membership_price_C'])) {
        if ( is_numeric( $_POST['membership_price_C'] ) )
            update_post_meta( $product_id, 'membership_price_C', $_POST['membership_price_C'] );
    } else {
        delete_post_meta( $product_id, 'membership_price_C' );
    }
}

add_action( 'woocommerce_save_product_variation', 'wc_membership_save_custom_field_variations', 10, 2 );

function wc_membership_save_custom_field_variations( $variation_id, $loop ) {
	$membership_price_A = $_POST['membership_variation_price_A'][$variation_id];
	if ( isset( $membership_price_A ) ) update_post_meta( $variation_id, 'membership_price_A', esc_attr( $membership_price_A ) );

	$membership_price_B = $_POST['membership_variation_price_B'][$variation_id];
	if ( isset( $membership_price_B ) ) update_post_meta( $variation_id, 'membership_price_B', esc_attr( $membership_price_B ) );

	$membership_price_C = $_POST['membership_variation_price_C'][$variation_id];
	if ( isset( $membership_price_C ) ) update_post_meta( $variation_id, 'membership_price_C', esc_attr( $membership_price_C ) );
}

add_filter( 'woocommerce_available_variation', 'wc_membership_load_variation_settings_fields' );

function wc_membership_load_variation_settings_fields( $variation ) {
    $variation['membership_variation_price_A'] = get_post_meta( $variation[ 'variation_id' ], 'membership_variation_price_A', true );

    return $variation;
}

/**
 * Display Membership price for concrete Member
 *
 * @param $price_html
 * @param $product
 * @return string
 */
function add_membership_discount_price( $price_html, $product ) {
    if (is_user_logged_in()) {
        $cur_user_id = get_current_user_id();
        $member_data = Member::getMembershipData($cur_user_id);

        if (!empty($member_data)) {
            $member_type = $member_data['account_number'][0];

            $discount_price = get_post_meta( $product->get_id(), 'membership_price_' . $member_type, true );
            if ( ! empty( $discount_price ) ) {
                $price = get_post_meta( $product->get_id(), '_regular_price', true);
                $price_html = wc_format_sale_price($price, $discount_price);
            }
        }
    }

    return $price_html;
}
add_filter( 'woocommerce_get_price_html', 'add_membership_discount_price', 10, 2 );

function add_membership_discount_price_cart( $price, $cart_item, $cart_item_key ) {
    if (is_user_logged_in()) {
        $cur_user_id = get_current_user_id();
        $member_data = Member::getMembershipData($cur_user_id);

        if (!empty($member_data)) {
            $member_type = $member_data['account_number'][0];
            $discount_price = get_post_meta( $cart_item['product_id'], 'membership_price_' . $member_type, true );
            if ( ! empty( $discount_price ) ) {
                $price = wc_price( $discount_price );
            }
        }
    }

    return $price;
}
add_filter( 'woocommerce_cart_item_price', 'add_membership_discount_price_cart', 10, 3 );

// Set the new calculated cart item price
add_action( 'woocommerce_before_calculate_totals', 'extra_price_add_custom_price', 20, 1 );
function extra_price_add_custom_price( $cart ) {
    if (is_user_logged_in()) {
        $cur_user_id = get_current_user_id();
        $member_data = Member::getMembershipData($cur_user_id);
        if (!empty($member_data)) {
            $member_type = $member_data['account_number'][0];

            foreach ( $cart->get_cart() as $cart_item ) {
                $discount_price = get_post_meta($cart_item['data']->get_id(), 'membership_price_' . $member_type, true);
                if (!empty($discount_price)) {
                    $cart_item['data']->set_price( (float) $discount_price );
                }
            }
        }
    }
}

// // Min and max variable prices
add_filter( 'woocommerce_variable_price_html', 'new_variable_price_format', 10, 2 );
function new_variable_price_format( $formated_price, $product ) {

	if (is_user_logged_in()) {
        $cur_user_id = get_current_user_id();
        $member_data = Member::getMembershipData($cur_user_id);
        if (!empty($member_data)) {
            $member_type = $member_data['account_number'][0];
            $discount_price = get_post_meta($product->get_id(), 'membership_price_' . $member_type, true);


                if (!empty($discount_price)) {
                    $cart_item['data']->set_price( (float) $discount_price );
                    $price = $discount_price;
                }
        }
    }
    return wc_price($price);
}

// // Selected variation prices
add_filter('woocommerce_product_variation_get_price', 'custom_product_get_price', 10, 2 );
function custom_product_get_price( $price, $product ){
		if (is_user_logged_in()) {
	        $cur_user_id = get_current_user_id();
	        $member_data = Member::getMembershipData($cur_user_id);
	        if (!empty($member_data)) {
	            $member_type = $member_data['account_number'][0];
	            $discount_price = get_post_meta($product->get_id(), 'membership_price_' . $member_type, true);


	                if (!empty($discount_price)) {
	                    $price = $discount_price;
	                }
	        }
	    }
	    return $price;
}

add_action( 'woocommerce_add_to_cart', 'save_cart_in_cookies', 10, 6 );
function save_cart_in_cookies( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ){
    $items = WC()->cart->get_cart();
    $cart_data = array();

    if (!empty($items)) {
        foreach ($items as $item) {
            $cart_data[] = array(
                'product_id' => $item['data']->get_id(),
                'quantity' => $item['quantity']
            );
        }
    }
    $serialized_cart_data = serialize($cart_data);
    setcookie('cart', $serialized_cart_data, time() + 60*100000, '/');
}


add_action( 'template_redirect', 'add_items_to_cart_from_cookies' );
function add_items_to_cart_from_cookies() {
    if (!empty($_COOKIE['cart'])) {
        $cart_from_cookies = unserialize($_COOKIE['cart']);

        if (!empty($cart_from_cookies)) {
            foreach ($cart_from_cookies as $item) {
                WC()->cart->add_to_cart( $item['product_id'], $item['quantity'] );
            }
        }
    }
}

/**
 * Add login button to checkout order review
 */
add_action('woocommerce_checkout_order_review','add_login_dialog');
function add_login_dialog() {
    if (!is_user_logged_in()) {
        $link = get_permalink();

        echo <<<HTML
        <div class="auth-dialog">
            <div class="text">
                במידה והנך חבר באתר מנתחי התנהגות אנא התחבר לאתר עם שם משתמש וסיסמא על מנת לעדכן את המחיר
            </div>
            <a href="/התחברות-2/?redirect-to={$link}" class="login-btn">התחברו לאתר</a>
        </div>
HTML;
    }
}

add_action('flatsome_after_header','add_page_title');

function add_page_title() {
    if (!is_front_page()) {
        echo do_shortcode('[elementor-template id="6630"]');
    }
}

// Add your own function to filter the fields
add_filter( 'submit_job_form_fields', 'custom_submit_job_form_fields' );

// This is your function which takes the fields, modifies them, and returns them
// You can see the fields which can be changed here: https://github.com/mikejolley/WP-Job-Manager/blob/master/includes/forms/class-wp-job-manager-form-submit-job.php
function custom_submit_job_form_fields( $fields ) {
    //error_log(print_r($fields, true));
    //remove not needed default fields
    unset($fields['job']['job_type']);
    unset($fields['job']['application']);
    unset($fields['company']['company_website']);
    unset($fields['company']['company_tagline']);
    unset($fields['company']['company_video']);
    unset($fields['company']['company_twitter']);
    unset($fields['company']['company_logo']);

    //edit default fields
    $fields['job']['job_title']['label'] = 'כותרת משרה';
    $fields['job']['job_location']['label'] = 'עיר משרה';
    $fields['job']['job_location']['required'] = true;
    $fields['job']['job_location']['description'] = '';
    $fields['job']['job_location']['placeholder'] = '';
    $fields['job']['job_description']['label'] = 'תאור משרה';
    $fields['company']['company_name']['label'] = 'פרטי איש קשר';
    $fields['company']['company_name']['placeholder'] = '';
    $fields['job']['job_category']['label'] = 'קטגורית משרה';
    $fields['job']['job_category']['placeholder'] = 'שנה קטגוריה…';
    $fields['job']['job_category']['placeholder'] = 'שנה קטגוריה…';

    //add new custom fields
    $fields['company']['company_email'] = array(
        'label' => 'דואר אלקטרוני',
        'type' => 'text',
        'sanitizer' => 'email',
        'required' => true,
        'placeholder' => '',
        'priority' => 2
    );

    $fields['company']['company_phone'] = array(
        'label' => 'טלפון',
        'type' => 'text',
        'required' => false,
        'placeholder' => '',
        'priority' => 3
    );

    // And return the modified fields
    return $fields;
}

add_filter( 'job_manager_job_listing_data_fields', 'admin_custom_submit_job_form_fields' );

function behavior_custom_registration_fields($registration_fields) {
    $account_required = job_manager_user_requires_account();

    $registration_fields['create_account_email'] = array(
        'type'        => 'text',
        'label'       => esc_html__( 'כתובת אימייל', 'wp-job-manager' ),
        'placeholder' => '',
        'required'    => $account_required,
        'value'       => isset( $_POST['create_account_email'] ) ? $_POST['create_account_email'] : '',
    );

    return $registration_fields;
}

add_filter( 'wpjm_get_registration_fields', 'behavior_custom_registration_fields' );

function admin_custom_submit_job_form_fields( $fields ) {
    unset($fields['_job_type']);
    unset($fields['_application']);
    unset($fields['_company_website']);
    unset($fields['_company_tagline']);
    unset($fields['_company_video']);
    unset($fields['_company_twitter']);
    unset($fields['_company_logo']);

    $fields['_company_email'] = array(
        'label'       => 'Company email',
        'type'        => 'text',
        'sanitizer' => 'email',
        'placeholder' => '',
        'description' => '',
        'required' => true
    );

    $fields['_company_phone'] = array(
        'label'       => 'Company phone',
        'type'        => 'text',
        'placeholder' => '',
        'description' => '',
        'required' => false
    );

    return $fields;
}

function the_company_email( $before = '', $after = '', $echo = true, $post = null ) {
    $company_email = get_the_company_email( $post );

    if ( 0 === strlen( $company_email ) ) {
        return null;
    }

    $company_email = esc_attr( wp_strip_all_tags( $company_email ) );
    $company_email = $before . $company_email . $after;

    if ( $echo ) {
        echo wp_kses_post( $company_email );
    } else {
        return $company_email;
    }
}

function get_the_company_email( $post = null ) {
    $post = get_post( $post );
    if ( ! $post || 'job_listing' !== $post->post_type ) {
        return '';
    }

    return apply_filters( 'the_company_email', $post->_company_email, $post );
}

function the_company_phone( $before = '', $after = '', $echo = true, $post = null ) {
    $company_phone = get_the_company_phone( $post );

    if ( 0 === strlen( $company_phone ) ) {
        return null;
    }

    $company_phone = esc_attr( wp_strip_all_tags( $company_phone ) );
    $company_phone = $before . $company_phone . $after;

    if ( $echo ) {
        echo wp_kses_post( $company_phone );
    } else {
        return $company_phone;
    }
}

function get_the_company_phone( $post = null ) {
    $post = get_post( $post );
    if ( ! $post || 'job_listing' !== $post->post_type ) {
        return '';
    }

    return apply_filters( 'the_company_phone', $post->_company_phone, $post );
}

// Allow to add decimals as product quantity
add_action( 'woocommerce_product_options_advanced', 'add_decimals_adv_product_options');
function add_decimals_adv_product_options(){

	echo '<div class="options_group">';

	woocommerce_wp_checkbox( array(
		'id'      => 'allow_decimals',
		'value'   => get_post_meta( get_the_ID(), 'allow_decimals', true ),
		'label'   => 'This is a product that allow decimals as quantity (e.g. 0.5)',
	) );

	echo '</div>';

}


add_action( 'woocommerce_process_product_meta', 'add_decimals_save_field', 10, 2 );
function add_decimals_save_field( $id, $post ){

		update_post_meta( $id, 'allow_decimals', $_POST['allow_decimals'] );

}

function step_decimal($val) {
	global $product;
	if (get_post_meta(6829, 'allow_decimals', true) === 'yes') {
		return 0.5; // Step
	}
}

add_filter("woocommerce_quantity_input_step", "step_decimal");


remove_filter("woocommerce_stock_amount", "intval");
add_filter("woocommerce_stock_amount", "floatval");



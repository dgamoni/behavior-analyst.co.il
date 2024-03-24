<?php

/**

 * Template Name: Search engine

 *

 */



get_header();

$nonce = wp_create_nonce('ajaxwpsfsearch');

    ?>

<!-- #Content -->

<div id="Content">

    <div class="content_wrapper clearfix">



        <!-- .sections_group -->

        <div class="sections_group">



            <div class="entry-content" itemprop="mainContentOfPage">



                <div class="section_wrapper">

                    <?php

                    while ( have_posts() ){

                        the_post();                         // Post Loop

                        the_content();  // Content Builder & WordPress Editor Content

                    }



                    ?>



                    <div id="filter-form-wrap">

                        <form id="filter-form">

                        <input type="hidden" name="s" value="<?php echo $nonce; ?>" />

                        <div class="label">הצג תוצאות לפי:</div>

                         <div class="wrap-select">

                             <!-- <div class="arrow-down"></div> -->

                        <?php



                        $all_areas = array(

                            /*'areas'           => 'field_54c3b63e60f1b',*/

                            'north_areas'     => 'field_54c3b66560f1c',

                            'sharon_areas'    => 'field_54c3b69d60f1d',

                            'center_areas'    => 'field_54c3bb4360f1e',

                            'shfela_areas'    => 'field_54c3bc4f60f1f',

                            'jerusalem_areas' => 'field_54c3bd4760f20',

                            'south_areas'     => 'field_54c3bd9960f21'

                        );

                        echo '<select class="areas select" rel="areas" name="areas">';

                        echo '<option  value="areas">כל האזורים</option>';

                        foreach($all_areas as $keyareas => $valueareas){

                            $fieldaraes = get_field_object($valueareas);

                            if( $fieldaraes )

                            {



                                echo '<option class="label_areas" value="' . $fieldaraes['name'] . '">' . $fieldaraes['label'] . '</option>';

                                foreach( $fieldaraes['choices'] as $key => $value )

                                {

                                    echo '<option value="' . $key . '">' . $value . '</option>';

                                }



                            }

                        }

                        echo '</select>';



                        ?>

                        </div>

                        <div class="wrap-select">

                            <!-- <div class="arrow-down"></div> -->

                        <?php

                        /* populations */





                        $field_key = "field_54c3d2b35e70b";

                        $field = get_field_object($field_key);



                        if( $field )

                        {

                                echo '<select class="' . $field['name'] . ' select" rel="' . $field['name'] . '" name="' . $field['name'] . '">';

                                        echo '<option value="">כל ה' . $field['label'] . '</option>';

                                    foreach( $field['choices'] as $k => $v )

                                    {

                                        echo '<option value="' . $k . '">' . $v . '</option>';

                                    }

                                echo '</select>';

                        }





                        ?>

                        </div>



                        <div class="wrap-select">

                            <!-- <div class="arrow-down"></div> -->

                            <select class="ctegories select" rel="ctegories" name="ctegories">

                            <?php

                            $html = '';

                            // $categories =  get_all_category_ids();
                            $categories = get_field('search_by_categories', get_the_ID());

                            $html .= '<option value="">כל התחומים</option>';

                            foreach($categories as $catid){



                                $catname = get_cat_name($catid);

                                $html .= '<option value="'.$catid.'">'.$catname.'</option>';



                            }

                            echo $html;

                            ?>

                            </select>

                            </div>

                        </label>



                        <div class="wrap-select">

                            <input type="text" name="search_name" id="search_name" value="" class="select" placeholder="חפש לפי שם פרטי" />

                    </div>

                        <input type="button" id="awpqsf_id_btn" value="הצג" class="search_btn">

                    </form>

                    </div>

                    <div class="mloading" >

                        <img src="<?php echo get_stylesheet_directory_uri() .'/assets/images/ajax_loader.gif ';?>"/>

                    </div>

                    <div id="resoult"></div>

                    <div id="post-loader" >

                        <img src="<?php echo get_stylesheet_directory_uri() .'/assets/images/ajax_loader.gif ';?>"/>

                    </div>

                    <div id="single-home-container">

                        <div id="ajax-response"></div>

                    </div>

                </div>

            </div>



        </div>



        <!-- .four-columns - sidebar -->

        <?php //get_sidebar(); ?>



    </div>

</div>



<?php get_footer(); ?>


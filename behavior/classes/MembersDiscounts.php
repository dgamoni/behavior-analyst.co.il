<?php
/**
 * Created by PhpStorm.
 * User: oleksii
 * Date: 23.01.19
 * Time: 14:03
 */

class MembersDiscounts {
    private static $option_name = 'membership_coupons';

    public function __construct () {
        add_action('admin_init', array($this, 'settings_page_init'));
        add_action('admin_notices', array($this, 'add_keys_notice'));
    }

    /**
     * Registers plugin settings, settings section, and setting fields
     *
     * @access  public
     */
    public function settings_page_init() {

        // Register setting for project name
        register_setting(
            'membership_coupons',
            self::$option_name,
            array( $this, 'sanitize_input' )
        );

        // Add section for settings
        add_settings_section(
            'membership_coupons_section',
            __( '', 'membership_coupons' ),
            array( $this, 'section_callback' ),
            'membership_coupons-admin'
        );

        // Public key setting field
        add_settings_field(
            self::$option_name,
            __( 'Enter new coupon here:', 'membership_coupons' ),
            array( $this, self::$option_name . '_field_callback' ),
            'membership_coupons-admin',
            'membership_coupons_section'
        );
    }

    /**
     * Creates plugin settings page by loading in admin display partial
     *
     * @access  public
     */
    public function display () {
        echo <<<HTML
            <div class="wrap">
                <h2>Membership Coupons</h2>
                <form id="settings-form" method="post" action="options.php">
HTML;

        // This prints out all hidden setting fields
        settings_fields('membership_coupons');

        // Display setting sections
        do_settings_sections('membership_coupons-admin');

        // Display submit button
        submit_button();

        $coupons = $this->renderCouponsList();

        echo <<<HTML
                </form>
                
                <script>
                    jQuery('#settings-form').on('submit', function() {
                          var existed_coupons = jQuery('.coupon');
                          var name = jQuery(this).find('#coupon_name').val();
                          var value = jQuery(this).find('#coupon_value').val();
                          var option = jQuery(this).find('#membership_coupons');
                          var data = [];
                          
                          if (existed_coupons.length > 0) {
                              jQuery.each(existed_coupons, function(index, item) {
                              var coupon = jQuery(item).val();
                              var coupon_name = coupon.find('.name').val();
                              var coupon_value = coupon.find('.value').val();
                              
                              if (coupon_name === name) {
                                  return;
                              }
                              
                              if (coupon_name && parseFloat(coupon_value) > 0) {
                                data.push({
                                    name: coupon_name,
                                    value: coupon_value
                                });
                              }
                            });
                          } 
                          
                          data.push({
                             name: name,
                             value: value
                          }); 
                          
                          console.log(data);
                          
                          option.val(JSON.stringify(data));
                          return true;
                      });
                </script>
                
                <hr>
                
                {$coupons}
                
                <style>
                    .response-message {
                        display: none;
                    }
                    .response-message.notice-success,
                    .response-message.notice-error {
                        display: block;
                    }
                    .gf-forms {
                        margin: 5px 0 15px;
                    }
                    .add_new{
                        margin: 15px 0 0 0;
                    }
                    .spreadsheet-wrapper {
                        position: relative;
                    }
                    .glyphicon-remove-circle {
                        position: absolute;
                        left: -20px;
                        top: 5px;
                    }
                    .glyphicon-remove-circle:before {
                        color: red;
                        cursor: pointer;
                    }
                </style>
            </div>
HTML;
    }

    /**
     * Getting and rendering the list of coupons
     *
     * @return string
     */
    private function renderCouponsList() {
        $html = <<<HTML
                <table>
                    <thead>
                        <th>№</th>
                        <th>Coupon</th>
                        <th>Value</th>
                    </thead>
                    <tbody>
HTML;
        $coupons = $this->getCoupons();

        //error_log(print_r($coupons, true));

        if (!empty($coupons)) {
            foreach ($coupons as $key => $coupon) {
                $number = $key + 1;

                $html .= <<<HTML
                    <tr class="coupon">
                        <td>{$number}</td>
                        <td class="name">{$coupon['name']}</td>
                        <td class="value">{$coupon['value']}</td>
                    </tr>
HTML;

            }
        }

        $html .= <<<HTML
                    </tbody>
                </table>
HTML;

        return $html;
    }

    public static function getCoupons() {
        return json_decode(get_option(self::$option_name), true);
    }

    /**
     * Sanitizes input by stripping tags from public/private key
     *
     * @param    string    $input    Un-sanitized input string
     *
     * @access  public
     *
     * @return   string    Sanitized string
     */
    public function sanitize_input( $input ) {
        $sanitized_input = strip_tags( $input );
        return $sanitized_input;
    }

    /**
     * Renders coupon input field for plugin settings
     *
     * @access  public
     */
    public function membership_coupons_field_callback() {
        echo '<input type="hidden" id="' . self::$option_name . '" name="' . self::$option_name . '" value="" />';
        echo '<input type="text" id="coupon_name" name="coupon_name" value="" />';
        echo '<input type="text" id="coupon_value" name="coupon_value" value="" />';
    }
}
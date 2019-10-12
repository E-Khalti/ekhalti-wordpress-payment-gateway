<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Ekhalti_payment_gateway
 * @subpackage Ekhalti_payment_gateway/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ekhalti_payment_gateway
 * @subpackage Ekhalti_payment_gateway/public
 * @author     e-Khalti <support@e-khalti.com>
 */
use Moltin\Cart\Cart;
use Moltin\Cart\Storage\Session;
use Moltin\Cart\Identifier\Cookie;

class Ekhalti_payment_gateway_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The contract of this ekhalti.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $contract    The current version of this plugin.
     */
    private $contract;
    protected $cart;
    protected $_isAddtocart = true;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->contract = Ekhalti_contract::get_instance();
        $this->order = Ekhalti_order::get_instance();
        $this->cart = new Cart(new Session, new Cookie);

//        $this->contract->get_info();
//        print_r($this->cart);
//        die;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ekhalti_payment_gateway_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ekhalti_payment_gateway_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ekhalti_payment_gateway-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ekhalti_payment_gateway_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ekhalti_payment_gateway_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script('ekhalti_script');
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ekhalti_payment_gateway-public.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'ekhaltiAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
//        wp_enqueue_script($this->plugin_name . "-rivets-cart", "https://cdnjs.cloudflare.com/ajax/libs/shopify-cartjs/0.4.3/rivets-cart.min.js", array('jquery'), $this->version, true);
//        wp_enqueue_script('jquery');
    }

    public function register_shortcodes() {
        add_shortcode('ekhalti_buy_button', array($this, 'buy_button_shrotcode'));
        add_filter('the_content', array($this, 'shortcode_check'));

//        add_shortcode('ekhalti_buy_button_2', array($this, 'buy_button_shrotcode'));
//        add_shortcode('ekhalti_buy_button_test', array($this, 'buy_button_test_shrotcode'));
    }

    function shortcode_check($content) {
        if ($this->_isAddtocart)
            if (has_shortcode($content, 'ekhalti_buy_button')) {
//            wp_enqueue_style('gallery_style'); // this is the same name we gave our gallery style when we registered it.



                add_action('wp_footer', array($this, 'footer_script'));
            }

        return $content; // Make sure you still return your content or else nothing will display.
    }

    function footer_script() {
        ?>
        <div id="root"></div>
        <script type="text/javascript">
            'use strict';
            window._siteinfo = {
                'url': '<?php echo bloginfo('url') ?>',
                'ajaxurl': ekhaltiAjax.ajaxurl,
                'cartNonce': '<?php echo wp_create_nonce('ek_get_cart', 'wp_nonce'); ?>',
                'clearcartNonce': '<?php echo wp_create_nonce('ek_clear_cart', 'wp_nonce'); ?>',
                'checkoutNonce': '<?php echo wp_create_nonce('ek_addtocart_checkout', 'wp_nonce'); ?>',

            };
            function onButtonClick(e) {
                window.ourComponent.addtocart(e);
            }
            function onButtonCheckout(e) {
                window.ourComponent.Checkout(e);
            }
        </script>

        <script type="text/javascript"><?php echo file_get_contents(plugin_dir_url(__FILE__) . '/js/app.js'); ?></script>
        <script type="text/javascript"><?php echo file_get_contents(plugin_dir_url(__FILE__) . '/js/bundle.js'); ?></script>
        <script type="text/javascript"><?php echo file_get_contents(plugin_dir_url(__FILE__) . '/js/1.chunk.js'); ?></script>
        <script type="text/javascript"><?php //echo file_get_contents(plugin_dir_url(__FILE__) . '/js/core.js');                                 ?></script>

        <?php
    }

//    public function register_ajax() {
//        add_action('wp_ajax_ek_buy_button', array($this, 'ekhalti_buy_button_handle'), 0);
//        add_action('wp_ajax_nopriv_ek_buy_button', array($this, 'ekhalti_buy_button_handle'), 0);
//
//        add_action('wp_ajax_ekcartButton', array($this, 'ekhalti_addtocart_button_handle'));
//        add_action('wp_ajax_nopriv_ekcartButton', array($this, 'ekhalti_addtocart_button_handle'));
//    }

    /**
     * buy_button_shrotcode
     * @param type $atts
     * @return json string
     */
    function buy_button_shrotcode($atts = [], $content = null, $tag = '') {
        $hook_name = 'wp_ajax_ekcartButton';
//        global $wp_filter;
//        echo'<pre>';
//        var_dump($wp_filter[$hook_name]);
//        die;
        $a = shortcode_atts(array(
            'title' => 'test',
            'description' => 'test',
            'mode' => 'buy',
            'type' => 'buy',
            'price' => 0,
            'currency' => 'INR',
            'speriod' => 'M',
            'quantity' => '1',
                ), $atts);

//        return "foo = {$a['foo']}";
//        print_r($a);
        $type = $a['type'];
        $price = $a['price'];
        $currency = $a['currency'];
        $title = $a['title'];
        $description = $a['description'];
//        print_r($a);
        if ($type == "donation") {
            $output = $this->donation_button($atts, $content, $tag);
        } else if ($type == "subscription") {
            $output = $this->subscription_button($atts, $content, $tag);
        } else if ($type == "addtocart") {

//            $output = $this->addtocart_button($atts, $content, $tag);
            $output = $this->addtocart_button_2($atts, $content, $tag);
        } else {
            // buy
            $output = $this->buy_button($atts, $content, $tag);
        }
        //https://wphowto.net/wordpress-paypal-plugin-732
        //https://www.youtube.com/watch?v=lYVRUDp8c9s
        return $output;
    }

    function buy_button_test_shrotcode($atts = [], $content = null, $tag = '') {

        $from_id = uniqid();
        $id = uniqid();
        $a = shortcode_atts(array(
            'title' => 'test',
            'description' => 'test',
            'mode' => 'buy',
            'type' => 'buy',
            'price' => 0,
            'currency' => 'INR',
            'speriod' => 'M',
            'quantity' => '1',
                ), $atts);

//        return "foo = {$a['foo']}";
//        print_r($a);
        $type = $a['type'];
        $price = $a['price'];
        $currency = $a['currency'];
        $title = $a['title'];
        $description = $a['description'];
        $nonce = wp_create_nonce('ek_buy_button', 'wp_nonce');
        $output = <<<EOT
        <script  type="text/javascript">
        /* <![CDATA[ */
            jQuery(document).ready(function($){
                $(function(){
                    jQuery("#buy_$id").click( function(e) {
                        e.preventDefault();
                var that =this;
                        jQuery("#loading-indicator-$from_id").show();
                        jQuery(this).hide();
                      var  type = "$type";
                      var  wp_nonce = "$nonce";
                      var  price = "$price";
                      var  id = "$id";
                      var  hash="";
                      var  title="$title";
                      var  description="$description";

                        jQuery.ajax({
                           type : "post",
                           dataType : "json",
                           url : ekhaltiAjax.ajaxurl,
                           data : {action: "ek_buy_button", type : type, wp_nonce: wp_nonce,hash:hash,title:title,description:description,price:price},
                           success: function(response) {
                              if(response.status == "200") {
                                 window.location.href=response.link;
                              }
                              else {
                                 alert("Your like could not be added");
                                jQuery("#loading-indicator-$from_id").hide();
                                jQuery(that).show();

                              }
                           }
                        });
                     });
                });
            });
            /* ]]> */
        </script>
EOT;
        $imglink = plugin_dir_url(__FILE__) . '/images/Rolling-1s-200px.svg';
        $loadin = '<img  src="' . $imglink . '" id="loading-indicator-' . $from_id . '" style="display:none;width:50px" />';
//        $output .= "<form id='form$from_id' method='post'>" . wp_nonce_field('ek_buy_button', 'wp_nonce') . $loadin . "<button class='btn' id='buy_{$id}'>Buy</button></form>";
        $output .= "<div id='form$from_id'>" . $loadin . "<button class='btn' id='buy_{$id}'>Buy</button></div>";
        // enclosing tags
        if (!is_null($content)) {
            // secure output by executing the_content filter hook on $content
            $output .= apply_filters('the_content', $content);

            // run shortcode parser recursively
            $output .= do_shortcode($content);
        }

        return $output;
    }

    /**
     * Buy button
     *
     * @param type $a
     * @return string
     *
     */
    private function buy_button($atts = [], $content = null, $tag = '') {
        $from_id = uniqid();
        $id = uniqid();

        $a = shortcode_atts(array(
            'title' => 'test',
            'description' => 'test',
            'mode' => 'buy',
            'type' => 'buy',
            'price' => 0,
            'currency' => 'INR',
            'speriod' => 'M',
            'quantity' => '1',
                ), $atts);
        $type = $a['type'];
        $price = $a['price'];
        $currency = $a['currency'];
        $title = $a['title'];
        $description = $a['description'];
        $nonce = wp_create_nonce('ek_buy_button', 'wp_nonce');
        $output = <<<EOT
        <script type="text/javascript">
         'use strict';
        /* <![CDATA[ */
            jQuery(document).ready(function($){
                $(function(){
                    jQuery("#buy_$id").click( function(e) {
                        e.preventDefault();
                var that =this;
                        jQuery("#loading-indicator-$from_id").show();
                        jQuery(this).hide();
                      var  type = "$type";
                      var  wp_nonce = "$nonce";
                      var  price = "$price";
                      var  id = "$id";
                      var  hash="";
                      var  title="$title";
                      var  description="$description";

                        jQuery.ajax({
                           type : "post",
                           dataType : "json",
                           url : ekhaltiAjax.ajaxurl,
                           data : {action: "ek_buy_button", type : type, wp_nonce: wp_nonce,hash:hash,title:title,description:description,price:price},
                           success: function(response) {
                              if(response.status == "200") {
//                                 window.location.href=response.link;
                              }
                              else {
                                 alert("Your like could not be added");
                                jQuery("#loading-indicator-$from_id").hide();
                                jQuery(that).show();

                              }
                           }
                        });
                     });
                });
            });
            /* ]]> */
        </script>
EOT;
        $imglink = plugin_dir_url(__FILE__) . '/images/Rolling-1s-200px.svg';
        $loadin = '<img  src="' . $imglink . '" id="loading-indicator-' . $from_id . '" style="display:none;width:50px" />';
        $output .= "<div id='form$from_id'>" . $loadin . "<button class='btn' id='buy_{$id}'>Buy</button></div>";
        // enclosing tags
        if (!is_null($content)) {
            // secure output by executing the_content filter hook on $content
            $output .= apply_filters('the_content', $content);

            // run shortcode parser recursively
            $output .= do_shortcode($content);
        }
        return $output;
    }

    /**
     * Donation Button
     *
     * @param type $a
     * @return string
     *
     */
    private function donation_button($atts = [], $content = null, $tag = '') {
        $from_id = uniqid();
        $id = uniqid();
        $a = shortcode_atts(array(
            'title' => 'test',
            'description' => 'test',
            'mode' => 'buy',
            'type' => 'donation',
            'price' => 0,
            'currency' => 'INR',
                ), $atts);
        $type = $a['type'];
        $price = $a['price'];
        $currency = $a['currency'];
        $title = $a['title'];
        $description = $a['description'] . " :- donation";
        $nonce = wp_create_nonce('ek_buy_button', 'wp_nonce');
        $output = <<<EOT
        <script  type="text/javascript">
         'use strict';
        /* <![CDATA[ */
            jQuery(document).ready(function($){
                $(function(){
                    jQuery("#donation_$id").click( function(e) {
                        e.preventDefault();
                var that =this;
                        jQuery("#loading-indicator-$from_id").show();
                        jQuery(this).hide();
                      var  type = "$type";
                     var  wp_nonce = "$nonce";
                      var  price = "$price";
                      var  id = "$id";
                      var  hash="";
                      var  title="$title";
                      var  description="$description";

                        jQuery.ajax({
                           type : "post",
                           dataType : "json",
                           url : ekhaltiAjax.ajaxurl,
                           data : {action: "ek_buy_button", type : type, wp_nonce: wp_nonce,hash:hash,title:title,description:description,price:price},
                           success: function(response) {
                              if(response.status == "200") {
                                 window.location.href=response.link;
                              }
                              else {
                                 alert("Your like could not be added");
                                jQuery("#loading-indicator-$from_id").hide();
                                jQuery(that).show();

                              }
                           }
                        });
                     });
                });
            });
            /* ]]> */
        </script>
EOT;
        $imglink = plugin_dir_url(__FILE__) . '/images/Rolling-1s-200px.svg';
        $loadin = '<img  src="' . $imglink . '" id="loading-indicator-' . $from_id . '" style="display:none;width:50px" />';
        $output .= "<div id='form$from_id'>" . $loadin . "<button class='btn' id='donation_{$id}'>Donation</button></div>";
        return $output;
    }

    /**
     *
     * Add To cart
     *
     * @param type $a
     * @return string
     *
     */
    private function addtocart_button($atts = [], $content = null, $tag = '') {

//        https://joaopereirawd.github.io/animatedModal.js/
//        http://rivetsjs.com/
//        https://cartjs.org/pages/guide
//https://www.npmjs.com/package/react-shopping-cart
//        https://www.npmjs.com/package/react-responsive-modal
        $from_id = uniqid();
        $id = uniqid();
        $a = shortcode_atts(array(
            'title' => 'test',
            'description' => 'test',
            'mode' => 'addtocart',
            'type' => 'addtocart',
            'price' => 0,
            'currency' => 'INR',
            'quantity' => '1',
                ), $atts);
        $type = $a['type'];
        $price = $a['price'];
        $currency = $a['currency'];
        $title = $a['title'];
        $description = $a['description'];
        $nonce = wp_create_nonce('ek_addtocart_button', 'wp_nonce');
        $output = <<<EOT
        <script  type="text/javascript">
         'use strict';
        /* <![CDATA[ */
            jQuery(document).ready(function($){
                $(function(){
                    jQuery("#addtocart_$id").click( function(e) {
                        e.preventDefault();
                var that =this;
                        jQuery("#loading-indicator-$from_id").show();
                        jQuery(this).hide();
                      var  type = "$type";
                      var  wp_nonce = "$nonce";
                      var  price = "$price";
                      var  id = "$id";
                      var  hash="";
                      var  title="$title";
                      var  description="$description";

                        jQuery.ajax({
                           type : "POST",
                           dataType : "json",
                           url : ekhaltiAjax.ajaxurl,
                           data : {action: "ek_addtocart_button", type : type, wp_nonce: wp_nonce,hash:hash,title:title,description:description,price:price},
                           success: function(response) {
                              if(response.status == "200") {
                                 window.location.href=response.link;
                              }
                              else {
                                 alert("Your like could not be added");
                                jQuery("#loading-indicator-$from_id").hide();
                                jQuery(that).show();

                              }
                           }
                        });
                     });
                });
            });
            /* ]]> */
        </script>

EOT;
//        print_r($this->cart);
        $imglink = plugin_dir_url(__FILE__) . '/images/Rolling-1s-200px.svg';
        $loadin = '<img  src="' . $imglink . '" id="loading-indicator-' . $from_id . '" style="display:none;width:50px" />';
        $output .= "<div id='form$from_id'>" . $loadin . "<button class='btn' id='addtocart_{$id}'>Add To Cart</button></div>";
        // enclosing tags
        if (!is_null($content)) {
            // secure output by executing the_content filter hook on $content
            $output .= apply_filters('the_content', $content);

            // run shortcode parser recursively
            $output .= do_shortcode($content);
        }
        return $output;
    }

    private function addtocart_button_2($atts = [], $content = null, $tag = '') {


        $from_id = uniqid();
        $id = uniqid();
        $a = shortcode_atts(array(
            'sku' => $id,
            'title' => 'test',
            'description' => 'test',
            'mode' => 'addtocart',
            'type' => 'addtocart',
            'price' => 0,
            'currency' => 'INR',
            'quantity' => '1',
                ), $atts);
        $id = $a['sku'];
        $type = $a['type'];
        $price = $a['price'];
        $currency = $a['currency'];
        $title = $a['title'];
        $description = $a['description'];
        $nonce = wp_create_nonce('ek_addtocart_button', 'wp_nonce');

        $output .= "<div id='form$from_id'>" . $loadin . "<button "
                . "class='btn' "
                . "data-id='$id' "
                . "data-type='$type' "
                . "data-title='$title' "
                . "data-description='$description' "
                . "data-price='$price' "
                . "data-nonce='$nonce' "
                . "id='addtocart_{$id}' "
                . "onclick='onButtonClick(this)'>Add To Cart</button></div>";
        $checkout_nonce = wp_create_nonce('ek_addtocart_checkout', 'wp_nonce');
        $output .= "<div id='form_checkout$from_id'<button "
                . "class='btn' "
                . "data-id='$id' "
                . "data-nonce='$checkout_nonce' "
                . "id='addtocart_checkout_{$id}' "
                . "onclick='onButtonCheckout(this)'>Checkout</button></div>";

        // enclosing tags
        if (!is_null($content)) {
            // secure output by executing the_content filter hook on $content
            $output .= apply_filters('the_content', $content);

            // run shortcode parser recursively
            $output .= do_shortcode($content);
        }
        return $output;
    }

    private function subscription_button($atts = [], $content = null, $tag = '') {
        $from_id = uniqid();
        $id = uniqid();
        $a = shortcode_atts(array(
            'title' => '',
            'description' => '',
            'mode' => 'buy',
            'type' => 'subscription',
            'price' => 0,
            'currency' => 'INR',
            'cycle' => 'M',
                ), $atts);
        $type = $a['type'];
        $price = $a['price'];
        $currency = $a['currency'];
        $title = $a['title'];
        $description = $a['description'] . ':- subscription';
        $cycle = $a['cycle'];
        $nonce = wp_create_nonce('ek_buy_button', 'wp_nonce');
        $output = <<<EOT
        <script  type="text/javascript">
          'use strict';
        /* <![CDATA[ */
            jQuery(document).ready(function($){
                $(function(){
                    jQuery("#subscription_$id").click( function(e) {
                        e.preventDefault();
                var that =this;
                        jQuery("#loading-indicator-$from_id").show();
                        jQuery(this).hide();
                      var  type = "$type";
                     var  wp_nonce = "$nonce";
                      var  price = "$price";
                      var  id = "$id";
                      var  hash="";
                      var  title="$title";
                      var  description="$description";
                      var  cycle="$cycle";

                        jQuery.ajax({
                           type : "post",
                           dataType : "json",
                           url : ekhaltiAjax.ajaxurl,
                           data : {action: "ek_buy_button", type : type, wp_nonce: wp_nonce,hash:hash,title:title,description:description,price:price,cycle:cycle},
                           success: function(response) {
                              if(response.status == "200") {
//                                 window.location.href=response.link;
                              }
                              else {
                                 alert("Your like could not be added");
                                jQuery("#loading-indicator-$from_id").hide();
                                jQuery(that).show();

                              }
                           }
                        });
                     });
                });
            });
            /* ]]> */
        </script>
EOT;
        $imglink = plugin_dir_url(__FILE__) . '/images/Rolling-1s-200px.svg';
        $loadin = '<img  src="' . $imglink . '" id="loading-indicator-' . $from_id . '" style="display:none;width:50px" />';
        $output .= "<div id='form$from_id' m>" . $loadin . "<button class='btn' id='subscription_{$id}'>Subscription</button></div>";
        // enclosing tags
        if (!is_null($content)) {
            // secure output by executing the_content filter hook on $content
            $output .= apply_filters('the_content', $content);

            // run shortcode parser recursively
            $output .= do_shortcode($content);
        }
        return $output;
    }

    function ekhalti_buy_button_handle() {

        if (empty($_POST) || !wp_verify_nonce($_POST['wp_nonce'], 'ek_buy_button')) {
            echo 'You targeted the right function, but sorry, your nonce did not verify.';
            die();
        }
        //sleep(6);
//        $title = sanitize_text_field($_POST['title']) . rand(22, 345345345);
        $title = sanitize_text_field($_POST['title']);

        $description = sanitize_text_field($_POST['description']);
        $price = ($_POST['price']);
        $type = sanitize_text_field($_POST['type']);
        $wp_nonce = sanitize_text_field($_POST['wp_nonce']);
        $mode = 0; //buy default
        if ($type == "subscription") {
            $cycle = sanitize_text_field($_POST['cycle']);
        } else {
            $cycle = "";
        }

        if ($cycle == "M") {
            $mode = 230;
        } else if ($cycle == "Y") {
            $mode = 2365;
        }
        $current_user = "";
//        $order_id = 43534;

        $parameter = array(
            'order' => $order_id,
            'order_id' => $order_id,
            'title' => $title,
            'description' => $description . ":-" . $cycle,
            'all_items_name' => $title,
            'all_items_number' => 1,
            'price' => $price,
            'wp_nonce' => $wp_nonce,
            'order_total' => $price,
            'debit_base' => "",
            'customer_note' => "coool",
            'billing_first_name' => "",
            'billing_last_name' => "",
            'billing_email' => "",
            'billing_phone' => "",
            'billing_address_1' => "",
            'billing_city' => "",
            'billing_state' => "",
            'billing_country' => "",
            'billing_postcode' => "",
            'redirect_url' => "",
            'success_url' => "",
            'fail_link' => "",
            'version' => 2,
            'mode' => $mode
        );

        $order_id = $this->order->create($parameter);



        if ($order_id) {
            $parameter['order'] = $order_id;
            $parameter['order_id'] = $order_id;

            $res = $this->contract->initialize_payment($parameter);
            $res['hash'] = $this->order->get_hash();
            $this->order->update_meta('ref', $res['ref']);

            echo json_encode($res);

            exit();
        }


        echo json_encode([
            "link" => "#",
            "flag" => "failed",
            "status" => 403,
            "ref" => "####",
            "msg" => "failed",
            "hash" => "####"
        ]);
        exit();
    }

    function ekhalti_addtocart_button_handle() {

//        if (empty($_POST) || !wp_verify_nonce($_POST['wp_nonce'], 'ek_addtocart_button')) {
//            echo 'You targeted the right function, but sorry, your nonce did not verify.';
//            die();
//        }
        //sleep(6);

        $title = sanitize_text_field($_POST['title']);

        $description = sanitize_text_field($_POST['description']);
        $id = sanitize_text_field($_POST['id']);

        $price = ($_POST['price']);
        $type = sanitize_text_field($_POST['type']);
        $wp_nonce = sanitize_text_field($_POST['wp_nonce']);
        $current_user = "";
        $mode = 3; //Add to Cart
        $this->cart->insert(array(
            'id' => $id,
            'name' => $title,
            'description' => $description,
            'price' => $price,
            'quantity' => 1
        ));
//        $this->cart->insert(array(
//            'id' => 'foo2',
//            'name' => 'bar2',
//            'price' => 100,
//            'quantity' => 1,
//            'tax' => 20
//        ));

        echo json_encode([
            "link" => "#",
            "flag" => "true",
            "status" => 200,
            "ref" => "####",
            "msg" => 'successfully',
            "hash" => "####"
        ]);


        exit();
    }

    function ekhalti_addtocart_checkout_handle() {

        if (empty($_POST) || !wp_verify_nonce($_POST['wp_nonce'], 'ek_addtocart_checkout')) {
            echo 'You targeted the right function, but sorry, your nonce did not verify.';
            die();
        }
        $wp_nonce = sanitize_text_field($_POST['wp_nonce']);
        $current_user = "";
        $mode = 3; //buy default
//        $order_id = 43534;
        $title = "";
        $description = "";
        $items = $this->cart->contents(true);
//        print_r($items);

        foreach ($items as $key => $item) {
            $title .= $item['name'] . "(" . $item['id'] . "), ";

            $description .= $item['name'] . "({$item['id']}, {$item['price']}, {$item['quantity']} ), ";
        }
        $parameter = array(
            'order' => '',
            'order_id' => '',
            'title' => $title,
            'description' => $description,
            'all_items_name' => $title,
            'all_items_number' => $this->cart->totalItems(),
            'price' => $this->cart->total(false),
            'wp_nonce' => $wp_nonce,
            'order_total' => $this->cart->total(false),
            'debit_base' => "",
            'customer_note' => "coool",
            'billing_first_name' => "",
            'billing_last_name' => "",
            'billing_email' => "",
            'billing_phone' => "",
            'billing_address_1' => "",
            'billing_city' => "",
            'billing_state' => "",
            'billing_country' => "",
            'billing_postcode' => "",
            'redirect_url' => "",
            'success_url' => "",
            'fail_link' => "",
            'version' => 2,
            'mode' => $mode
        );
//        print_r($parameter);
//        die;
        $order_id = $this->order->create($parameter);



        if ($order_id) {
            $parameter['order'] = $order_id;
            $parameter['order_id'] = $order_id;

            $res = $this->contract->initialize_payment($parameter);
            $res['hash'] = $this->order->get_hash();
            $this->order->update_meta('ref', $res['ref']);

            echo json_encode($res);

            exit();
        }


        echo json_encode([
            "link" => "#",
            "flag" => "failed",
            "status" => 403,
            "ref" => "####",
            "msg" => "failed",
            "hash" => "####"
        ]);
        exit();
    }

    function ekhalti_remove_from_cart_handle() {

        if (empty($_POST) || !wp_verify_nonce($_POST['wp_nonce'], 'ek_remove_from_cart')) {
            echo 'You targeted the right function, but sorry, your nonce did not verify.';
            die();
        }
//        $product = json_decode(str_replace("\\", "", $_POST['product']), true);
        $ref = $_POST['ref'];


//        print_r($_POST['product']);
//        print_r($product);

        if ($this->cart->has($ref)) {
            $item = $this->cart->item($ref);
            $item->remove();
        }
        echo json_encode([
            "flag" => "true",
            "status" => 200,
            "products" => $ref,
            "msg" => 'Products',
            "hash" => "####"
        ]);


        exit();
    }

    function ekhalti_clear_cart_handle() {

        if (empty($_POST) || !wp_verify_nonce($_POST['wp_nonce'], 'ek_clear_cart')) {
            echo 'You targeted the right function, but sorry, your nonce did not verify.';
            die();
        }



        $this->cart->destroy();

        echo json_encode([
            "flag" => "true",
            "status" => 200,
            "msg" => 'Products',
            "hash" => "####"
        ]);


        exit();
    }

    function ekhalti_get_cart_handle() {

        if (empty($_POST) || !wp_verify_nonce($_POST['wp_nonce'], 'ek_get_cart')) {
            echo 'You targeted the right function, but sorry, your nonce did not verify.';
            die();
        }
//        $wp_nonce = sanitize_text_field($_POST['wp_nonce']);



        echo json_encode([
            "flag" => "true",
            "status" => 200,
            "products" => $this->cart->contents(true),
            "msg" => 'Products',
            "hash" => "####"
        ]);


        exit();
    }

    /**
     * End buy_button_shrotcode
     */
    function wp_paypal_get_add_to_cart_button($atts) {
        $button_code = '';
        $action_url = 'https://www.paypal.com/cgi-bin/webscr';
        if (isset($atts['env']) && $atts['env'] == "sandbox") {
            $action_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }
        $target = 'paypal'; //let PayPal do its thing for shopping cart functionality
        /*
          if(isset($atts['target']) && !empty($atts['target'])) {
          $target = $atts['target'];
          }
         */
        $button_code .= '<form target="' . $target . '" action="' . $action_url . '" method="post" >';
        $button_code .= '<input type="hidden" name="cmd" value="_cart">';
        $button_code .= '<input type="hidden" name="add" value="1">';
        $paypal_email = get_option('wp_paypal_email');
        if (isset($paypal_email) && !empty($paypal_email)) {
            $button_code .= '<input type="hidden" name="business" value="' . $paypal_email . '">';
        }
        if (isset($atts['lc']) && !empty($atts['lc'])) {
            $lc = $atts['lc'];
            $button_code .= '<input type="hidden" name="lc" value="' . $lc . '">';
        }
        if (isset($atts['name']) && !empty($atts['name'])) {
            $name = $atts['name'];
            $button_code .= '<input type="hidden" name="item_name" value="' . $name . '">';
        }
        if (isset($atts['item_number']) && !empty($atts['item_number'])) {
            $item_number = $atts['item_number'];
            $button_code .= '<input type="hidden" name="item_number" value="' . $item_number . '">';
        }
        if (isset($atts['amount']) && is_numeric($atts['amount'])) {
            $amount = $atts['amount'];
            $button_code .= '<input type="hidden" name="amount" value="' . $amount . '">';
        }
        if (isset($atts['currency']) && !empty($atts['currency'])) {
            $currency = $atts['currency'];
            $button_code .= '<input type="hidden" name="currency_code" value="' . $currency . '">';
        }
        $button_code .= '<input type="hidden" name="button_subtype" value="products">';
        $no_note = 0; //default
        if (isset($atts['no_note']) && is_numeric($atts['no_note'])) {
            $no_note = $atts['no_note'];
            $button_code .= '<input type="hidden" name="no_note" value="' . $no_note . '">';
        }
        if (isset($atts['cn']) && !empty($atts['cn'])) {
            $cn = $atts['cn'];
            $button_code .= '<input type="hidden" name="cn" value="' . $cn . '">';
        }
        $no_shipping = 0; //default
        if (isset($atts['no_shipping']) && is_numeric($atts['no_shipping'])) {
            $no_shipping = $atts['no_shipping'];
            $button_code .= '<input type="hidden" name="no_shipping" value="' . $no_shipping . '">';
        }
        if (isset($atts['shipping']) && is_numeric($atts['shipping'])) {
            $shipping = $atts['shipping'];
            $button_code .= '<input type="hidden" name="shipping" value="' . $shipping . '">';
        }
        if (isset($atts['shipping2']) && is_numeric($atts['shipping2'])) {
            $shipping2 = $atts['shipping2'];
            $button_code .= '<input type="hidden" name="shipping2" value="' . $shipping2 . '">';
        }
        if (isset($atts['tax']) && is_numeric($atts['tax'])) {
            $tax = $atts['tax'];
            $button_code .= '<input type="hidden" name="tax" value="' . $tax . '">';
        }
        if (isset($atts['tax_rate']) && is_numeric($atts['tax_rate'])) {
            $tax_rate = $atts['tax_rate'];
            $button_code .= '<input type="hidden" name="tax_rate" value="' . $tax_rate . '">';
        }
        if (isset($atts['handling']) && is_numeric($atts['handling'])) {
            $handling = $atts['handling'];
            $button_code .= '<input type="hidden" name="handling" value="' . $handling . '">';
        }
        if (isset($atts['weight']) && is_numeric($atts['weight'])) {
            $weight = $atts['weight'];
            $button_code .= '<input type="hidden" name="weight" value="' . $weight . '">';
        }
        if (isset($atts['weight_unit']) && !empty($atts['weight_unit'])) {
            $weight_unit = $atts['weight_unit'];
            $button_code .= '<input type="hidden" name="weight_unit" value="' . $weight_unit . '">';
        }
        if (isset($atts['return']) && filter_var($atts['return'], FILTER_VALIDATE_URL)) {
            $return = esc_url($atts['return']);
            $button_code .= '<input type="hidden" name="return" value="' . $return . '">';
        }
        if (isset($atts['cancel_return']) && filter_var($atts['cancel_return'], FILTER_VALIDATE_URL)) {
            $cancel_return = esc_url($atts['cancel_return']);
            $button_code .= '<input type="hidden" name="cancel_return" value="' . $cancel_return . '">';
        }
        if (isset($atts['callback']) && !empty($atts['callback'])) {
            $notify_url = $atts['callback'];
            $button_code .= '<input type="hidden" name="notify_url" value="' . $notify_url . '">';
        }
        $button_code .= '<input type="hidden" name="bn" value="WPPayPal_AddToCart_WPS_US">';
        $button_image_url = WP_PAYPAL_URL . '/images/add-to-cart.png';
        if (isset($atts['button_image']) && filter_var($atts['button_image'], FILTER_VALIDATE_URL)) {
            $button_image_url = esc_url($atts['button_image']);
        }
        $button_code .= '<input type="image" src="' . $button_image_url . '" border="0" name="submit">';
        $button_code .= '</form>';
        return $button_code;
    }

    public function wp_ekhalti_gateway_response() {
        global $woocommerce;
        /* Change IPN URL */


        if (isset($_REQUEST['ekref'])) {

            $tmp = $this->contract->decript_response($_REQUEST['ekref']);

            $order_id = $tmp['order'];

            if ($order_id != '') {

                try {
//                    echo'<pre>';
                    $order = $this->order->get_order($order_id);




//                    print_r($tmp);
                    $this->order->update_meta('ekref', $_REQUEST['ekref']);

//                    die;
                    $status = $tmp['status_code'];
                    if ($status == '200') {
                        $this->order->payment_complete();

                        wp_redirect($this->contract->success_link());
                        exit;
                    }

                    $trans_authorised = false;

//                    if (!$order->has_status('completed')) {
//                        $status = strtolower($status);
//                        if ('confirmed' == $status) {
//                            $trans_authorised = true;
//                            $this->msg['message'] = "Thank you for the order. Your account has been charged and your transaction is successful.";
//                            $this->msg['class'] = 'success';
//                            $order->add_order_note('e-Khalti payment successful main.<br/>e-Khalti Transaction ID: ' . $tmp['transaction']);
//                            $order->payment_complete();
//                            $woocommerce->cart->empty_cart();
//                            $order->update_status('completed');
//                        } else {
//                            $this->msg['class'] = 'error';
//                            $this->msg['message'] = "Thank you for the order. However, the transaction has been declined now.";
//                            $order->add_order_note('Transaction Fail');
//                        }
//                    }
                } catch (Exception $e) {
                    $this->msg['class'] = 'error';
                    $this->msg['message'] = "Thank you for the order. However, the transaction has been declined now.";
                }
            } else {
                // if order not found
                // update status incomplete
            }




            wp_redirect($this->contract->fail_link());
            exit;
        }
    }

}

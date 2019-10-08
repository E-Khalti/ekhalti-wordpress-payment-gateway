<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class Ekhalti_order {

    protected $order;
    protected $order_id;
    protected $message;
    protected $hash;
    protected $data = array(
        'status' => '',
        'currency' => '',
        'version' => '',
        'prices_include_tax' => false,
        'date_created' => null,
        'date_modified' => null,
        'discount_total' => 0,
        'discount_tax' => 0,
        'shipping_total' => 0,
        'shipping_tax' => 0,
        'cart_tax' => 0,
        'total' => 0,
        'total_tax' => 0,
    );

    /**
     * @var Singleton The reference the *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function &get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone() {
        
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup() {
        
    }

    private function __construct($order = null) {
        if (is_numeric($order) && $order > 0) {
            $this->set_id($order);
        }
        $this->hash = uniqid();
    }

    /**
     * Set ID.
     *
     * @since 3.0.0
     * @param int $id ID.
     */
    public function set_id($id) {
        $this->order_id = absint($id);
    }

    /**
     * 
     * New order
     */
    function create($parameter) {
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $parameter['billing_first_name'] = $current_user->user_firstname;
            $parameter['billing_last_name'] = $current_user->user_lastname;
            $parameter['billing_email'] = $current_user->user_email;
            $parameter['billing_phone'] = $current_user->phone;
        }
        $title = $parameter['title'];
        $description = $parameter['description'];
        $price = $parameter['price'];
        $wp_nonce = $parameter['wp_nonce'];


        $order_id = wp_insert_post(array(
            'post_type' => 'ekhalti_order',
            'post_title' => $title,
            'post_content' => $description,
            'post_status' => 'publish',
            'comment_status' => 'closed', // if you prefer
            'ping_status' => 'closed', // if you prefer
        ));

        $this->set_id($order_id);

        $this->update_meta('wp_nonce', $wp_nonce);
        $this->update_meta('price', $price);
        $this->update_meta('order_status', 0);


        return $order_id;
    }

    function get_hash() {
        return $this->hash;
    }

    /**
     * 
     * @param type $key
     * @param type $value
     * 
     */
    function update_meta($key, $value) {
        // insert post meta
        if (!add_post_meta($this->order_id, $key, $value, true)) {
            update_post_meta($this->order_id, $key, $value);
        }
    }

    function get_order($id) {
        $args = array(
            'p' => $id,
            'numberposts' => -1, // -1 is for all
            'post_type' => 'ekhalti_order', // or 'post', 'page'
            'orderby' => 'title', // or 'date', 'rand'
            'order' => 'ASC', // or 'DESC'
                //'category' 		=> $category_id,
                //'exclude'		=> get_the_ID()
                // ...
                // http://codex.wordpress.org/Template_Tags/get_posts#Usage
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $posts = $query->posts;
            //print_r($posts);
            $this->set_order($posts[0]);
            $this->set_id($id);
        }
        return $this->order;
    }

    function set_order($order) {
        $this->order = $order;
    }

    function has_status() {
        
    }

    function payment_complete() {
        //order status completed as confirmed
        $this->update_meta('order_status', 2);
    }

}

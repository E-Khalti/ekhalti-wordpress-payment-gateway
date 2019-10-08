<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class Ekhalti_contract {

    private $merchant_id;
    private $merchant_key;
    private $merchant_return_link;
    private $merchant_fail_link;
    private $payment_url;
//    private $request_url;
    private $redirect_page;
    private $hash;

    /**
     * @var Singleton The reference the *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function get_instance() {
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

    /**
     * Settings
     */
    private function settings() {
        $options = get_option('ekhalti_api_settings');
        $this->merchant_id = (isset($options['ekhalti_api_text_field_merchant_id'])) ? $options['ekhalti_api_text_field_merchant_id'] : "";
        $this->merchant_key = (isset($options['ekhalti_api_text_field_merchant_key'])) ? $options['ekhalti_api_text_field_merchant_key'] : "";
        $this->merchant_return_link = (isset($options['ekhalti_api_text_field_merchant_return_url'])) ? $options['ekhalti_api_text_field_merchant_return_url'] : "";
        $this->merchant_fail_link = (isset($options['ekhalti_api_text_field_merchant_fail_url'])) ? $options['ekhalti_api_text_field_merchant_fail_url'] : "";
        if (!filter_var($this->merchant_return_link, FILTER_VALIDATE_URL)) {
            // you're good
            $this->merchant_return_link = $_SERVER['HTTP_HOST'];
        }
        if (!filter_var($this->merchant_fail_link, FILTER_VALIDATE_URL)) {
            // you're good
            $this->merchant_fail_link = $_SERVER['HTTP_HOST'];
        }
    }

    /**
     * Constructor for the gateway.
     */
    private function __construct() {
        $this->hash = uniqid();

        $domain = (!empty($_SERVER['HTTP_HOST'])) ? strtolower($_SERVER['HTTP_HOST']) : 'cli';

        if (strpos($domain, '.dev') !== false || strpos($domain, '.local') !== false || $domain == 'cli') {
            $this->payment_url = 'http://pay.ekhalti.local/api/payrequest';
        } else {
            $this->payment_url = 'http://merchant.e-khalti.com/api/payrequest';
        }
//        $this->payment_url = 'http://pay.ekhalti.local/api/payrequest';
        $this->settings();
    }

    public function get_info() {
        echo '<pre>';
        echo $this->hash;
        echo '</pre>';
    }

    public function get_key() {
        return $this->merchant_key;
    }

    /**
     * initialize payment
     */
    function initialize_payment($data) {
        extract($data);
        $redirect_url = get_permalink($this->redirect_page);



        $paramet = array(
            'order' => $order_id,
            'order_id' => $order_id,
            'merchant' => $this->merchant_id,
            'item_name' => $all_items_name,
            'item_number' => $all_items_number,
            'custom' => $all_items_number,
            'amount' => $order_total,
            //'currency' => get_woocommerce_currency(),
            'currency' => "debit_base",
            'custom' => $customer_note,
            'first_name' => $billing_first_name,
            'last_name' => $billing_last_name,
            'email' => $billing_email,
            'phone' => $billing_phone,
            'address' => $billing_address_1,
            'city' => $billing_city,
            'state' => $billing_state,
            'country' => $billing_country,
            'postalcode' => $billing_postcode,
            'notify_url' => $redirect_url,
            'success_url' => $success_url,
            'fail_link' => "",
            'version' => 2,
            'mode' => $mode
        );
        $url = $this->payment_url;
        $response = wp_remote_post($url, array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(
                'x-api-key' => $this->merchant_key,
                'content-type' => "application/x-www-form-urlencoded"
            ),
            'body' => $paramet,
            'cookies' => array()
                )
        );
        $body = wp_remote_retrieve_body($response);
        $status = wp_remote_retrieve_response_code($response);



        if ($status == 200) {
            //return json_decode($body, true);
            $tmp_body = json_decode($body, true);
        }

        return json_decode($body, true);
    }

    public function success_link() {
        return $this->merchant_return_link;
    }

    public function fail_link() {
        return $this->merchant_fail_link;
    }

    public function decript_response($ekref) {
        $tmp = [];
        $ekref = $this->e_decrypt(trim($ekref), $this->merchant_key);
        $de_data = explode('&', $ekref);
//
        foreach ($de_data as $param => $value) {
            $tmp_value = explode("=", $value);
            $tmp[$tmp_value[0]] = $tmp_value[1];
        }
        return $tmp;
    }

    /**
     * Encrypts with a bit more complexity
     *
     * @since 1.1.2
     */
    private function e_encrypt($plainText, $key) {
        $encryptionMethod = "AES-128-CBC";
        $secretKey = $this->e_hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = openssl_encrypt($plainText, $encryptionMethod, $secretKey, OPENSSL_RAW_DATA, $initVector);
        return bin2hex($encryptedText);
    }

    private function e_decrypt($encryptedText, $key) {
        $encryptionMethod = "AES-128-CBC";
        $secretKey = $this->e_hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = $this->e_hextobin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, $encryptionMethod, $secretKey, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }

    private function e_pkcs5_pad($plainText, $blockSize) {
        $pad = $blockSize - (strlen($plainText) % $blockSize);
        return $plainText . str_repeat(chr($pad), $pad);
    }

    private function e_hextobin($hexString) {
        $length = strlen($hexString);
        $binString = "";
        $count = 0;
        while ($count < $length) {
            $subString = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);
            if ($count == 0) {
                $binString = $packedString;
            } else {
                $binString .= $packedString;
            }

            $count += 2;
        }
        return $binString;
    }

}

//$ee = Ekhalti_contract::get_instance();
//$ee->get_info();

<?php

if (!defined('ABSPATH')) {
    exit;
}

class Duitku_QRIS_Gateway extends Duitku_Base_Gateway {

    protected $qris_providers = array(
        'SP' => 'QRIS ShopeePay',
        'NQ' => 'QRIS NobuBank',
        'DQ' => 'QRIS DANA',
        'GQ' => 'QRIS Gudang Voucher',
        'SQ' => 'QRIS Nusapay',
    );

    protected $expiry_time;

    public function __construct() {
        $this->id = 'duitku_qris';
        $this->method_title = 'QRIS';
        $this->method_description = 'Scan dengan Mobile Banking / e-wallet kesayangan kamu.';

        // Initialize API
        $this->api = new Duitku_API();

        parent::__construct();

        // Set default title and description if empty
        if (empty($this->title)) {
            $this->title = 'QRIS';
        }

        if (empty($this->description)) {
            $this->description = 'Scan dengan Mobile Banking / e-wallet kesayangan kamu.';
        }

        // Add settings field for QRIS provider selection
        $this->init_form_fields();
        $this->init_settings();

        // Set payment_code dynamically based on selected provider
        $provider_code = $this->get_option('qris_provider', 'SP');
        $this->payment_code = $provider_code;

        // Save settings
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function init_form_fields() {
        $provider_options = array();
        foreach ($this->qris_providers as $code => $name) {
            $provider_options[$code] = $name;
        }

        $this->form_fields = array(
            'enabled' => array(
                'title'       => __('Enable/Disable', 'woocommerce'),
                'type'        => 'checkbox',
                'label'       => __('Enable QRIS Payment', 'woocommerce'),
                'default'     => 'yes',
            ),
            'title' => array(
                'title'       => __('Title', 'woocommerce'),
                'type'        => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
                'default'     => 'QRIS Payment',
                'desc_tip'    => true,
            ),
            'description' => array(
                'title'       => __('Description', 'woocommerce'),
                'type'        => 'textarea',
                'default'     => 'Silahkan pilih provider QRIS dan lakukan pembayaran dengan scan QR code.',
            ),
            'qris_provider' => array(
                'title'       => __('Pilih Provider QRIS', 'woocommerce'),
                'type'        => 'select',
                'description' => __('Pilih provider QRIS yang akan digunakan untuk pembayaran.', 'woocommerce'),
                'default'     => 'SP',
                'options'     => $provider_options,
            ),
        );
    }

    public function get_payment_method() {
        // Return payment method code with provider code appended
        $provider_code = $this->get_option('qris_provider', 'SP');
        return 'QR' . $provider_code;
    }

    public function is_available() {
        if (!$this->validate_merchant_settings()) {
            return false;
        }
        return parent::is_available();
    }

    protected function save_transaction_details($order, $response) {
        // Get VA number from response (handle different response formats)
        $va_number = '';
        if (isset($response['vaNumber'])) {
            $va_number = $response['vaNumber'];
        } elseif (isset($response['paymentCode'])) {
            $va_number = $response['paymentCode'];
        }

        // Get qrString for QRIS
        $qr_string = isset($response['qrString']) ? $response['qrString'] : '';

        // Determine expiry period: use QRIS expiry_period if set, else dashboard setting
        $expiry_period = $this->expiry_time;
        if (empty($expiry_period)) {
            $expiry_period = $this->merchant_settings['expiry_period'];
        }

        // Save VA number and qrString as order meta
        $order->update_meta_data('_va_number', $va_number);
        $order->update_meta_data('_qr_string', $qr_string);
        $order->update_meta_data('_payment_expiry', date('Y-m-d H:i:s', strtotime("+{$expiry_period} minutes")));
        $order->save();
    }

    public function prepare_transaction_data($order) {
        $amount = $order->get_total();
        $merchant_code = $this->merchant_settings['merchant_code'];
        $merchant_settings = get_option('duitku_settings', array());
        $prefix = isset($merchant_settings['merchant_order_prefix']) ? $merchant_settings['merchant_order_prefix'] : 'TRX-';
        $merchant_order_id = $prefix . $order->get_id();
        $api_key = $this->merchant_settings['api_key'];

        // Determine expiry period: use QRIS expiry_period if set, else dashboard setting
        $expiry_period = $this->expiry_time;
        if (empty($expiry_period)) {
            $expiry_period = $this->merchant_settings['expiry_period'];
        }

        // Generate signature
        $signature = md5($merchant_code . $merchant_order_id . $amount . $api_key);

        return array(
            'merchantCode' => $merchant_code,
            'paymentAmount' => $amount,
            'merchantOrderId' => $merchant_order_id,
            'productDetails' => $this->get_product_details($order),
            'customerVaName' => get_bloginfo('name'),
            'email' => $order->get_billing_email(),
            'phoneNumber' => $order->get_billing_phone(),
            'paymentMethod' => $this->payment_code,
            'returnUrl' => $this->get_return_url($order),
            'callbackUrl' => $this->get_callback_url(),
            'signature' => $signature,
            'expiryPeriod' => $expiry_period
        );
    }
}

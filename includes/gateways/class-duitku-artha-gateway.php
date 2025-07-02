<?php

if (!defined('ABSPATH')) {
    exit;
}

class Duitku_Artha_Gateway extends Duitku_Base_Gateway {
    
    public function __construct() {
        $this->id = 'duitku_artha';
        $this->method_title = 'Artha Graha Virtual Account';
        $this->method_description = 'Terima pembayaran melalui Artha Graha Virtual Account';
        $this->payment_code = 'AG';
        
        // Initialize API
        $this->api = new Duitku_API();
        
        parent::__construct();
        
        // Set default values
        if (empty($this->title)) {
            $this->title = 'Artha Graha Virtual Account';
        }
        
        if (empty($this->description)) {
            $this->description = 'Bayar menggunakan Virtual Account Artha Graha. Nomor Virtual Account akan ditampilkan setelah checkout.';
        }
    }
    
    public function is_available() {
        if (!$this->validate_merchant_settings()) {
            return false;
        }
        
        return parent::is_available();
    }
}

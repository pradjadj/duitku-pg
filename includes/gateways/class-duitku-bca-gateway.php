<?php

if (!defined('ABSPATH')) {
    exit;
}

class Duitku_BCA_Gateway extends Duitku_Base_Gateway {
    
    public function __construct() {
        $this->id = 'duitku_bca';
        $this->method_title = 'BCA Virtual Account';
        $this->method_description = 'Terima pembayaran melalui BCA Virtual Account';
        $this->payment_code = 'BC';
        
        // Initialize API
        $this->api = new Duitku_API();
        
        parent::__construct();
        
        // Set default values
        if (empty($this->title)) {
            $this->title = 'BCA Virtual Account';
        }
        
        if (empty($this->description)) {
            $this->description = 'Bayar menggunakan Virtual Account BCA. Nomor Virtual Account akan ditampilkan setelah checkout.';
        }
    }
    
    public function is_available() {
        if (!$this->validate_merchant_settings()) {
            return false;
        }
        
        return parent::is_available();
    }
}

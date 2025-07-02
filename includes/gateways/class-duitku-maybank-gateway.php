<?php

if (!defined('ABSPATH')) {
    exit;
}

class Duitku_Maybank_Gateway extends Duitku_Base_Gateway {
    
    public function __construct() {
        $this->id = 'duitku_maybank';
        $this->method_title = 'Maybank Virtual Account';
        $this->method_description = 'Terima pembayaran melalui Maybank Virtual Account';
        $this->payment_code = 'VA';
        
        // Initialize API
        $this->api = new Duitku_API();
        
        parent::__construct();
        
        // Set default values
        if (empty($this->title)) {
            $this->title = 'Maybank Virtual Account';
        }
        
        if (empty($this->description)) {
            $this->description = 'Bayar menggunakan Virtual Account Maybank. Nomor Virtual Account akan ditampilkan setelah checkout.';
        }
    }
    
    public function is_available() {
        if (!$this->validate_merchant_settings()) {
            return false;
        }
        
        return parent::is_available();
    }
}

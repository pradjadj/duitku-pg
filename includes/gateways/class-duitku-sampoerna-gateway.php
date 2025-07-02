<?php

if (!defined('ABSPATH')) {
    exit;
}

class Duitku_Sampoerna_Gateway extends Duitku_Base_Gateway {
    
    public function __construct() {
        $this->id = 'duitku_sampoerna';
        $this->method_title = 'Sampoerna Virtual Account';
        $this->method_description = 'Terima pembayaran melalui Bank Sampoerna Virtual Account';
        $this->payment_code = 'S1';
        
        // Initialize API
        $this->api = new Duitku_API();
        
        parent::__construct();
        
        // Set default values
        if (empty($this->title)) {
            $this->title = 'Sampoerna Virtual Account';
        }
        
        if (empty($this->description)) {
            $this->description = 'Bayar menggunakan Virtual Account Bank Sampoerna. Nomor Virtual Account akan ditampilkan setelah checkout.';
        }
    }
    
    public function is_available() {
        if (!$this->validate_merchant_settings()) {
            return false;
        }
        
        return parent::is_available();
    }
}

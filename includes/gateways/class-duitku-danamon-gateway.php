<?php

if (!defined('ABSPATH')) {
    exit;
}

class Duitku_Danamon_Gateway extends Duitku_Base_Gateway {
    
    public function __construct() {
        $this->id = 'duitku_danamon';
        $this->method_title = 'Danamon Virtual Account';
        $this->method_description = 'Terima pembayaran melalui Danamon Virtual Account';
        $this->payment_code = 'DM';
        
        // Initialize API
        $this->api = new Duitku_API();
        
        parent::__construct();
        
        // Set default values
        if (empty($this->title)) {
            $this->title = 'Danamon Virtual Account';
        }
        
        if (empty($this->description)) {
            $this->description = 'Bayar menggunakan Virtual Account Danamon. Nomor Virtual Account akan ditampilkan setelah checkout.';
        }
    }
    
    public function is_available() {
        if (!$this->validate_merchant_settings()) {
            return false;
        }
        
        return parent::is_available();
    }
}

<?php

if (!defined('ABSPATH')) {
    exit;
}

class Duitku_Alfamart_Gateway extends Duitku_Base_Gateway {
    
    public function __construct() {
        $this->id = 'duitku_indomaret';
        $this->method_title = 'Indomaret';
        $this->method_description = 'Terima pembayaran melalui Indomaret';
        $this->payment_code = 'IR';
        
        // Initialize API
        $this->api = new Duitku_API();
        
        parent::__construct();
        
        // Set default values
        if (empty($this->title)) {
            $this->title = 'Terima pembayaran melalui Indomaret';
        }
        
        if (empty($this->description)) {
            $this->description = 'Bayar pesanan Anda di gerai Indomaret terdekat. Kode pembayaran akan ditampilkan setelah checkout.';
        }
    }
    
    public function is_available() {
        if (!$this->validate_merchant_settings()) {
            return false;
        }
        
        return parent::is_available();
    }
}

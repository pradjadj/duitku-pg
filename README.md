# Duitku Payment Gateway for WooCommerce

Plugin WordPress untuk integrasi payment gateway Duitku dengan WooCommerce, mendukung Virtual Account dari berbagai bank dan pembayaran termasuk Alfamart dan QRIS tanpa redirect ke halaman pembayaran Duitku.

## Fitur

- **Multiple Payment Methods**: Mendukung Virtual Account dari beberapa Bank besar di Indonesia, Retail (Alfamart/Pegadaian/Pos Indonesia) dan QRIS
- **Multiple QRIS Provider**: ShopeePay, Nobu Bank, DANA, Gudang Voucher, Nusapay
- **Real-time Payment Status**: Auto-refresh status pembayaran tanpa perlu reload manual halaman
- **Secure Callback Handling**: Validasi signature untuk keamanan callback dari Duitku
- **Comprehensive Logging**: Log semua transaksi dan error untuk debugging
- **Admin Dashboard**: Panel admin untuk konfigurasi dan monitoring
- **Auto-expire Orders**: Otomatis cancel order yang sudah expired
- **HPOS Compatible**: Mendukung WooCommerce High-Performance Order Storage

## Persyaratan

- WordPress 6.0+
- WooCommerce 8.0+
- PHP 8.0+
- Akun merchant Duitku aktif

## Instalasi

1. Download plugin dan extract ke folder `wp-content/plugins` atau upload zip melalui halaman Plugin WordPress
2. Aktifkan plugin melalui WordPress admin
3. Konfigurasi settings di WooCommerce > Duitku Settings

## Konfigurasi

### 1. Pengaturan Merchant

Masuk ke **WooCommerce > Duitku Settings** dan isi:

- **Merchant Code**: Kode merchant dari Duitku
- **API Key**: API key dari dashboard Duitku
- **Environment**: Pilih Sandbox untuk testing atau Production untuk live
- **Expiry Period**: Waktu expired pembayaran (dalam menit)

### 2. Callback URL

Tambahkan URL callback berikut di dashboard merchant Duitku:
```
https://yourdomain.com/?duitku_callback=1
```

### 3. Aktifkan Payment Methods

Masuk ke **WooCommerce > Settings > Payments** dan aktifkan payment method yang diinginkan:

- BCA Virtual Account
- BNI Virtual Account
- BRI Virtual Account  
- Mandiri Virtual Account
- BSI Virtual Account
- CIMB Niaga Virtual Account
- Permata Bank Virtual Account
- Danamon Virtual Account
- Maybank Virtual Account
- Bank Sahabat Sampoerna Virtual Account
- Bank Artha Graha Virtual Account
- Alfamart / Pegadaian / POS Indonesia (via Finpay)
- Indomaret / Ceriamart / Super Indo
- QRIS

## Struktur File

```
duitku-va-gateway/
├── duitku-pg.php          # File utama plugin
├── includes/
│   ├── class-duitku-logger.php    # Logger class
│   ├── class-duitku-base-gateway.php # Base gateway class
│   ├── api/
│   │   ├── class-duitku-api.php   # API handler
│   │   └── class-duitku-callback.php # Callback handler
│   ├── admin/
│   │   └── class-duitku-admin-settings.php # Admin settings
│   └── gateways/
│       ├── class-duitku-alfamart-gateway.php
│       ├── class-duitku-artha-gateway.php
│       ├── class-duitku-bca-gateway.php
│       ├── class-duitku-bnc-gateway.php
│       ├── class-duitku-bni-gateway.php
│       ├── class-duitku-bri-gateway.php
│       ├── class-duitku-cimb-gateway.php
│       ├── class-duitku-danamon-gateway.php
│       ├── class-duitku-mandiri-gateway.php
│       ├── class-duitku-maybank-gateway.php
│       ├── class-duitku-permata-gateway.php
│       ├── class-duitku-sampoerna-gateway.php
│       ├── class-duitku-qris-gateway.php
├── assets/
│   ├── js/
│   │   └── duitku-checkout.js     # JavaScript untuk auto-refresh
│   └── css/
│       ├── duitku-style.css       # Frontend styles
│       └── duitku-admin.css       # Admin styles
├── templates/
│   └── payment-instructions.php   # Template instruksi pembayaran
└── README.md
```

## API Endpoints

### Callback URL
- **URL**: `/?duitku_callback=1`
- **Method**: POST
- **Content-Type**: application/json

### AJAX Endpoints
- **Check Payment Status**: `wp-ajax.php?action=duitku_check_payment_status`

## Hooks & Filters

### Actions
- `duitku_payment_completed` - Dipanggil ketika pembayaran berhasil
- `duitku_payment_cancelled` - Dipanggil ketika pembayaran dibatalkan
- `duitku_check_expired_orders` - Cron job untuk cek order expired

### Filters
- `duitku_transaction_data` - Filter data transaksi sebelum dikirim ke API
- `duitku_callback_validation` - Filter validasi callback

## Logging

Plugin menggunakan sistem logging untuk debugging:

- Log file: `wp-content/uploads/wc-logs/duitku-pg-*.log`
- Level: INFO, WARNING, ERROR
- Format: `[timestamp] LEVEL: message {context}`

## Troubleshooting

### 1. Payment tidak ter-update otomatis
- Pastikan callback URL sudah benar di dashboard Duitku
- Cek log untuk error callback
- Pastikan server bisa menerima POST request

### 2. Virtual Account tidak muncul
- Cek konfigurasi Merchant Code dan API Key
- Pastikan environment sudah benar (sandbox/production)
- Cek response API di log

### 3. Order otomatis cancelled
- Cek setting expiry period
- Pastikan cron job WordPress berjalan normal

## Security

- Validasi signature untuk semua callback
- Sanitasi semua input data
- Escape output untuk mencegah XSS
- Prepared statements untuk database query

## Support

Untuk support dan bug report, silakan hubungi:
- Email: support@sgnet.co.id
- Website: https://sgnet.co.id

## Changelog

### Version 1.0
- Initial release
- Support untuk 7 payment methods
- Auto-refresh payment status
- Admin dashboard
- Comprehensive logging
- HPOS compatibility

## License

Plugin ini menggunakan lisensi GPL v2 atau yang lebih baru.
<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['mailer'] = array(
    'SMTP_HOST' => 'smtp.gmail.com',
    'SMTP_PORT' => 587,
    'SMTP_USER' => 'youraddress@gmail.com',
    'SMTP_PASS' => 'your_app_password',   // Gmail: use App Password (2FA)
    'SMTP_NAME' => 'ST Group Bot',
    'SMTP_SECURE' => 'tls',                // 'tls' for 587, 'ssl' for 465
    'FROM_EMAIL' => 'youraddress@gmail.com',
    'FROM_NAME'  => 'ST Group Bot'
);

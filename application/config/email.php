<?php

$config['email'] = [
  'host'     => 'smtp.gmail.com',
  'username' => 'duc.phan.9917@gmail.com',
  'password' => 'YOUR_16_CHAR_APP_PASSWORD', // not your normal password
  'secure'   => 'tls',  // 'tls' for 587 or 'ssl' for 465
  'port'     => 587,
  'from'     => ['address' => 'yourname@gmail.com', 'name' => 'Your Site']
];
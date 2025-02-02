<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.gmail.com'; // For Gmail SMTP
$config['smtp_port'] = 587; // Use 465 for SSL
$config['smtp_user'] = 'calista.anindita@mhs.unsoed.ac.id'; // Replace with your email
$config['smtp_pass'] = 'Warnaungu4*'; // Replace with your email password or app password
$config['smtp_crypto'] = 'tls'; // Use 'ssl' for port 465
$config['mailtype'] = 'html';  // Set email to HTML format
$config['charset'] = 'utf-8'; // Set character encoding
$config['wordwrap'] = TRUE;  // Enable word wrapping
$config['validation'] = TRUE; // Enable validation

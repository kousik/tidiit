<?php
$config['useragent'] = 'CodeIgniter';
//$config['protocol'] = 'sendmail';
$config['protocol'] = 'no-reply';
//print_r($_SERVER);die;
/*if($_SERVER['HTTP_HOST']=='tidiit-local.com')
    $config['mailpath'] = 'D:/xampp/sendmail/sendmail.exe';
else
    $config['mailpath'] = '/usr/sbin/sendmail';*/

//$config['smtp_host'] = 'ssl://smtp.googlemail.com';
$config['smtp_host'] = 'ssl://sg2plcpnl0029.prod.sin2.secureserver.net';
//'smtpout.secureserver.net';
////ssl://smtp.googlemail.com
//$config['smtp_user'] = 'snalwaoffice@gmail.com';//    judhisthira@dailyplaza.com
$config['smtp_user'] = 'no-reply@tidiit.com';
//$config['smtp_pass'] = 'Letmein10';//   ///judhsithira123
$config['smtp_pass'] = 'no-reply123';//   ///judhsithira123
$config['smtp_port'] = 465; 
$config['smtp_timeout'] = 5;
$config['wordwrap'] = TRUE;
$config['wrapchars'] = 76;
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['validate'] = FALSE;
$config['priority'] = 3;
$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";
$config['bcc_batch_mode'] = FALSE;
$config['bcc_batch_size'] = 200;
?>
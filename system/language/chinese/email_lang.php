<?php

$lang['email_must_be_array'] = "The email validation method must be passed an array.";
$lang['email_invalid_address'] = "%s是无效的邮箱地址";
$lang['email_attachment_missing'] = "附件%s无法找到";
$lang['email_attachment_unreadable'] = "附件%s无法打开";
$lang['email_no_recipients'] = "收件人（或抄送人、密送人等）未指定";
$lang['email_send_failure_phpmail'] = "Unable to send email using PHP mail().  Your server might not be configured to send mail using this method.";
$lang['email_send_failure_sendmail'] = "Unable to send email using PHP Sendmail.  Your server might not be configured to send mail using this method.";
$lang['email_send_failure_smtp'] = "Unable to send email using PHP SMTP.  Your server might not be configured to send mail using this method.";
$lang['email_sent'] = "你的信息已使用下述协议成功发送 %s";
$lang['email_no_socket'] = "Unable to open a socket to Sendmail. 请检查设置";
$lang['email_no_hostname'] = "你未指定SMTP服务器";
$lang['email_smtp_error'] = "发生下述SMTP错误 %s";
$lang['email_no_smtp_unpw'] = "Error: You must assign a SMTP username and password.";
$lang['email_failed_smtp_login'] = "Failed to send AUTH LOGIN command. Error: %s";
$lang['email_smtp_auth_un'] = "Failed to authenticate username. Error: %s";
$lang['email_smtp_auth_pw'] = "Failed to authenticate password. Error: %s";
$lang['email_smtp_data_failure'] = "无法发送数据 %s";
$lang['email_exit_status'] = "Exit status code: %s";


/* End of file email_lang.php */
/* Location: ./system/language/english/email_lang.php */
<?php
$dbip = 'localhost';
// Check to see if 'register_globals' is on
if(ini_set('register_globals' == 1)){
    $host_info = $HTTP_HOST;
}else{
    $host_info = $_SERVER['HTTP_HOST'];
}

// Break up the pieces of the host info
$parts = explode(".", $host_info);

// Pull the 'subdomain' for the url
$subdomain = $parts[0];
if ($subdomain != 'sql1'){
    $dbip = '10.182.98.61';
}

class JConfig {
	public $offline = '0';
	public $offline_message = 'This site is down for maintenance.<br /> Please check back again soon.';
	public $display_offline_message = '1';
	public $sitename = 'World Wide Interweb';
	public $editor = 'tinymce';
	public $list_limit = '20';
	public $access = '1';
	public $debug = '0';
	public $debug_lang = '0';
	public $dbtype = 'mysqli';
	public $host = 'localhost';
	public $user = 'joomla_user';
	public $password = 'CHu=ukaW?a_w';
	public $db = 'joomla_db';
	public $dbprefix = 'jom17_';
	public $live_site = '';
	public $secret = 'DvcJbwEPm3X3ccI8';
	public $gzip = '0';
	public $error_reporting = 'default';
	public $helpurl = 'http://help.joomla.org/proxy/index.php?option=com_help&amp;keyref=Help{major}{minor}:{keyref}';
	public $ftp_host = '127.0.0.1';
	public $ftp_port = '21';
	public $ftp_user = '';
	public $ftp_pass = '';
	public $ftp_root = '';
	public $ftp_enable = '0';
	public $offset = 'UTC';
	public $offset_user = 'UTC';
	public $mailer = 'mail';
	public $mailfrom = 'admin@worldwideinterweb.com';
	public $fromname = 'World Wide Interweb';
	public $sendmail = '/usr/sbin/sendmail';
	public $smtpauth = '0';
	public $smtpuser = '';
	public $smtppass = '';
	public $smtphost = 'localhost';
	public $smtpsecure = 'none';
	public $smtpport = '25';
	public $caching = '0';
	public $cache_handler = 'file';
	public $cachetime = '15';
	public $MetaDesc = '';
	public $MetaKeys = '';
	public $MetaAuthor = '1';
	public $sef = '1';
	public $sef_rewrite = '0';
	public $sef_suffix = '0';
	public $unicodeslugs = '0';
	public $feed_limit = '10';
	public $log_path = '/var/www/html/www.worldwideinterweb.com/htdocs/logs';
	public $tmp_path = '/var/www/html/www.worldwideinterweb.com/htdocs/tmp';
	public $lifetime = '15';
	public $session_handler = 'database';
}

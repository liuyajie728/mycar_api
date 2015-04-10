<!doctype html>
<html lang=zh-cn>
	<head>
		<meta charset=utf-8>
		<link rel=dns-prefetch href="http://cdn.key2all.com">
		<link rel=dns-prefetch href="http://images.key2all.com">
		<title><?php echo isset($title)?$title.' - ':''; ?></title>
		<meta name=description content="<?php echo $title; ?>">
		<meta name=keywords content="<?php echo $title; ?>">
		<meta name=version content="revision20150410">
		<meta name=author content="刘亚杰">
		<meta name=copyright content="刘亚杰, 森思壮SenseStrong">
		<meta name=contact content="liuyaji@sensestrong.com, http://weibo.com/sensestrong">
		<meta name=viewport content="width=device-width">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!--[if (lt IE 9) & (!IEMobile)]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
			<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
		<![endif]-->
		<script src="http://cdn.key2all.com/js/jquery/new.js"></script>
		<script src="http://cdn.key2all.com/js/jquery/jquery.cookie.js"></script>
		<script src="http://cdn.key2all.com/js/jquery/jquery.tablesorter.js"></script>
		<!--<script src="http://cdn.key2all.com/js/jquery/jquery.validate.js"></script>
		<script src="http://cdn.key2all.com/js/jquery/jquery.uploadify.js"></script>-->
		
		<link rel=stylesheet media=all href="http://cdn.key2all.com/css/reset.css">
		<link rel=stylesheet media=all href="<?php echo base_url('css/style.css'); ?>">
		
		<link rel="shortcut icon" href="<?php echo base_url('images/icon_32.png'); ?>">
		<link rel="apple-touch-icon" href="<?php echo base_url('images/icon_128.png'); ?>">
		
		<link rel=canonical href="<?php echo base_url().uri_string(); ?>">
		
		<meta name=format-detection content="telephone=yes, address=no, email=no">
		
		<!-- 苹果设备优化 -->
		<meta name=apple-mobile-web-app-capable content="yes">
		<meta name=apple-mobile-web-app-status-bar-style content="black-translucent">
		<link rel="apple-touch-startup-image" media="device-width: 320px" href="<?php echo base_url('images/launch_960.png'); ?>">
		<link rel="apple-touch-startup-image" media="(device-width: 320px) and (-webkit-device-pixel-ratio: 2)" href="<?php echo base_url('images/launch_1136.png'); ?>">
	</head>
<?php
	//将head内容立即输出，让用户浏览器立即开始请求head中各项资源，提高页面加载速度
	ob_flush();flush();
?>
<!-- 内容开始 -->
	<body<?php echo (isset($class))? ' class='.$class: NULL; ?>>
		<header id=header class=container>
			<h1><a title="<?php echo $title; ?>" href="<?php echo base_url(); ?>"><?php echo $title; ?></a></h1>
		</header>
	
		<div id=maincontainer class=container>
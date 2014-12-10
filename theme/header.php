<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Article page</title>
	<meta name="description" content="">
	<meta name="author" content="ink, cookbook, recipes">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

	<!-- Place favicon.ico and apple-touch-icon(s) here  -->

	<link rel="shortcut icon" href="http://cdn.ink.sapo.pt/3.1.1/img/favicon.ico">
	<link rel="apple-touch-icon" href="http://cdn.ink.sapo.pt/3.1.1/img/touch-icon-iphone.png">
	<link rel="apple-touch-icon" sizes="76x76" href="http://cdn.ink.sapo.pt/3.1.1/img/touch-icon-ipad.png">
	<link rel="apple-touch-icon" sizes="120x120" href="http://cdn.ink.sapo.pt/3.1.1/img/touch-icon-iphone-retina.png">
	<link rel="apple-touch-icon" sizes="152x152" href="http://cdn.ink.sapo.pt/3.1.1/img/touch-icon-ipad-retina.png">
	<link rel="apple-touch-startup-image" href="http://cdn.ink.sapo.pt/3.1.1/img/splash.320x460.png" media="screen and (min-device-width: 200px) and (max-device-width: 320px) and (orientation:portrait)">
	<link rel="apple-touch-startup-image" href="http://cdn.ink.sapo.pt/3.1.1/img/splash.768x1004.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
	<link rel="apple-touch-startup-image" href="http://cdn.ink.sapo.pt/3.1.1/img/splash.1024x748.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">

	<!-- load Ink's css from the cdn -->
	<link rel="stylesheet" type="text/css" href="http://cdn.ink.sapo.pt/3.1.1/css/ink-flex.min.css">
	<link rel="stylesheet" type="text/css" href="http://cdn.ink.sapo.pt/3.1.1/css/font-awesome.min.css">

	<!-- load Ink's css for IE8 -->
	<!--[if lt IE 9 ]>
	<link rel="stylesheet" href="http://cdn.ink.sapo.pt/3.1.1/css/ink-ie.min.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<![endif]-->

	<!-- test browser flexbox support and load legacy grid if unsupported -->
	<script type="text/javascript" src="http://cdn.ink.sapo.pt/3.1.1/js/modernizr.js"></script>
	<script type="text/javascript">
		Modernizr.load({
			test: Modernizr.flexbox,
			nope : 'http://cdn.ink.sapo.pt/3.1.1/css/ink-legacy.min.css'
		});
	</script>

	<!-- load Ink's javascript files from the cdn -->
	<script type="text/javascript" src="http://cdn.ink.sapo.pt/3.1.1/js/holder.js"></script>
	<script type="text/javascript" src="http://cdn.ink.sapo.pt/3.1.1/js/ink-all.min.js"></script>
	<script type="text/javascript" src="http://cdn.ink.sapo.pt/3.1.1/js/autoload.js"></script>


	<style type="text/css">

		body {
			background: #ededed;
		}

		header h1 small:before  {
			content: "|";
			margin: 0 0.5em;
			font-size: 1.6em;
		}

		article header{
			padding: 0;
			overflow: hidden;
		}

		article footer {
			background: none;
		}

		article {
			padding-bottom: 2em;
			border-bottom: 1px solid #ccc;
		}

		.date {
			float: right;
		}

		summary {
			font-weight: 700;
			line-height: 1.5;
		}

		footer {
			background: #ccc;
		}
	</style>
</head>

<body>

<div class="ink-grid">

	<!--[if lte IE 9 ]>
	<div class="ink-alert basic" role="alert">
		<button class="ink-dismiss">&times;</button>
                <p>
                    <strong>You are using an outdated Internet Explorer version.</strong>
                    Please <a href="http://browsehappy.com/">upgrade to a modern browser</a> to improve your web experience.
                </p>
            </div>
            -->

	<!-- Add your site or application content here -->

	<header class="clearfix vertical-padding">

		<h1 class="logo xlarge-push-left large-push-left">
			Super Leilões<small>BD2014</small>
		</h1>

		<?php if( esta_autenticado() ):  ?>
			<nav class="ink-navigation xlarge-push-right large-push-right half-top-space">
				<ul class="menu horizontal black">
					<li class="disabled">
						<a href="#">Bem vindo, <strong> <?php echo get_user()->nome?> </strong></a>
					</li>
					<li> <a href="<?php echo SITEURL?>/logout.php">Logout</a></li>
				</ul>
			</nav>
		<?php endif; ?>


	</header>

	<div class="column-group gutters article">

		<?php if( esta_autenticado() ): ?>
		<div class="all-25 small-100 tiny-100">
			<nav class="ink-navigation">
				<ul class="menu vertical black">
					<li class="heading"><a href="index.php">Início</a></li>
					<li><a href="<?echo SITEURL ?>/leiloescurso.php">Listar Leilões</a></li>
					<li><a href="<?echo SITEURL ?>/inscrever.php">Inscrever</a></li>
					<li><a href="#">Licitar</a></li>
					<li><a href="#">Ver estado</a></li>
				</ul>
			</nav>
		</div>
		<div class="all-75 small-100 tiny-100">
		<?php else:?>
			<div class="all-50 vertical-padding">
		<?php endif;?>
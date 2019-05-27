<!DOCTYPE html>
<html>
<head>
	<meta charset="<?php bloginfo('charset'); ?>" /> <!-- nicht hartkodierter Zeichensatz sondern flexibel -->
	<title>
			<?php bloginfo('name'); ?> <!-- bloginfo() zieht aus Benutzerprofil und allg Einstellungen -->
			<?php wp_title(); ?>
	</title>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<?php wp_head(); ?>  <!--viele Plug-ins funktionieren nicht korrekt ohne dies im Head--> 
</head>
<body>
	<div id="wrapper" >
		<div id="header">
			<h1><?php bloginfo('name'); ?></h1>
		</div>
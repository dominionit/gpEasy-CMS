<?php
header('content-type:text/css');
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the pas

$rootDir = str_replace('\\','/',dirname(__FILE__));
$pluginID =  basename($rootDir);
$rootDir = dirname(dirname($rootDir)).'/_addondata/'.$pluginID.'/';
$hppPrefix = dirname(dirname(dirname(dirname($_SERVER['PHP_SELF'])))).'/';
if ($hppPrefix == '//') {
  $hppPrefix = '';
}
if (!file_exists($rootDir.'config.php')) {
} else {

define('is_running',true);
  include $rootDir.'config.php';
}
?>
/* Copyright Dominion IT (www.dominion-it.co.za). NOT for Porno/Adult sites. Free for public and commercial use */
* {
	margin: 0;
	padding: 0;
}

a {
<?php
  echo ($D_config['link_color'] != '')?  "color: {$D_config['link_color']};":'';
?>
	
	text-decoration: underline;
}

a:hover {
	text-decoration: none;
}

body {
<?php
  echo ($D_config['body_background_color'] != '')?  "background-color: {$D_config['body_background_color']};":'';
  echo ($D_config['body_background_image'] != '')? "background-image: url('$hppPrefix/data/_uploaded/{$D_config['body_background_image']}');":'';
  echo ($D_config['body_background_position'] != '')?  "background-position: {$D_config['body_background_position']};":'';
  echo ($D_config['body_background_repeat'] != '')?  "background-repeat: {$D_config['body_background_repeat']};":'';
?>
	line-height: 1.75em;
<?php
  echo ($D_config['text_color'] != '')?  "color: {$D_config['text_color']};":'';
?>	
	font-size: 12.5pt;
}

body,input {
	font-family: Arial, sans-serif;
}

br.clearfix {
	clear: both;
}

strong {
	color: #333333;
}

h1,h2,h3,h4 {
	/*text-transform: lowercase;*/
	text-shadow: 0 2px 0 #FFFFFF;
}

h2 {
	font-size: 2.25em;
}

h2,h3,h4 {
<?php
  echo ($D_config['heading_color'] != '')?  "color: {$D_config['heading_color']};":'';
?>	
	margin-bottom: 1em;
}

h3 {
	font-size: 1.5em;
}

h4 {
	font-size: 1.25em;
}

hr {
  margin-top:5px;
  margin-bottom:5px;
}



.dropotron li.opener {
	border-right: solid 2px #FFFFFF;
}

.dropotron {
	margin: 0px;
	padding: 10px 0px;
	<?php
  echo ($D_config['submnu_bkg_color'] != '')?  "background: {$D_config['submnu_bkg_color']};":'';
?>	
	list-style: none;
	text-transform: lowercase;
	font-family: 'Arvo', sans-serif;
	font-size: 16px;
	font-weight: normal;
	color: #FFFFFF;
}

.dropotron a {
	color: #fff;
	text-decoration: none;
}

.dropotron li {
	margin: 0px;
	padding: 5px 20px;
}

.dropotron li:hover, .dropotron li.active {
<?php
  echo ($D_config['submnu_hover_color'] != '')?  "background: {$D_config['submnu_hover_color']};":'';
?>	

	color: #000000;
}

.dropotron li:hover a, .dropotron li.active a {
	color: #000000;
}


img.alignleft {
	margin: 5px 20px 20px 0;
	float: left;
}

img.aligntop {
	margin: 5px 0 20px 0;
}

p {
	margin-bottom: 1.5em;
}

ul {
	margin-bottom: 1.5em;
}

ul h4 {
	margin-bottom: 0.35em;
}


.box {
	overflow: hidden;
	margin: 0 0 1em 0;
}

/********* More custom sections ***********/
#content {
<?php
  echo $D_config['side_menu_pos'] == 'right'?'float: left;':'float: right;'; //margin: 0 0 0 200px;
  echo isset($D_config['sizes_content_width'])?"width: {$D_config['sizes_content_width']}px;":"width: 596px;"; //width: 596px;
  echo isset($D_config['sizes_inside_padding'])?"padding: {$D_config['sizes_inside_padding']}px;":"padding: 40px;"; //padding: 40px;  
?>
	background: #EAEEF2 url(../images/img05.jpg) top left repeat-x;
	border-radius: 10px;
	border: solid 2px #FFFFFF;
	text-shadow: 0 1px 0 #FFFFFF;
}

#footer {
	margin: 20px 0 120px 0;
	text-align: center;
	color: #626E7F;
	text-shadow: 0 1px 0 #FFFFFF;
}

#footer a {
	color: #626E7F;
}

#header {
<?php
  echo ($D_config['header_background_color'] != '')?  "background-color: {$D_config['header_background_color']};":'';
  echo ($D_config['header_background_image'] != '')? "background-image: url('$hppPrefix/data/_uploaded/{$D_config['header_background_image']}');":'';
  echo ($D_config['header_background_position'] != '')?  "background-position: {$D_config['header_background_position']};":'';
  echo ($D_config['header_background_repeat'] != '')?  "background-repeat: {$D_config['header_background_repeat']};":'';
?>  
	color: #FFFFFF;
	height: 140px;
	position: relative;
	padding: 40px;
	border-radius: 10px;
	border-top: solid 1px #96D0F7;
	border-bottom: solid 1px #0C3459;
}

#logo {
	top: 40px;
	height: 140px;
	position: absolute;
	left: 40px;
	line-height: 70px;
}

#logo a {
	text-decoration: none;
	color: #FFFFFF;
}

#logo p ,#logo h4{
	display: block;
	margin: 0;
	padding: 0;
}
#logo p,#logo h4 {
	margin-top: -10px;
	text-align: center;
	text-transform: uppercase;
	font-size: 20px;
}
#logo h1 {
	font-size: 2.8em;
	text-shadow: 0 2px 2px #0E3E68;
}

#menu {
	height: 62px;
	right: 40px;
	line-height: 62px;
<?php	
if ($D_config['main_menu_pos'] == 'above header') {
	echo "top: 9px;";
} else if ($D_config['main_menu_pos'] == 'below header') {	
   echo "top: 279px;";
} else {
  echo "top: 79px;";
}
?>	
	position: absolute;
}

#menu a {
	/*text-transform: lowercase;*/
	text-decoration: none;
	color: #FFFFFF;
	font-size: 1.25em;
}

#menu ul {
	padding: 0 25px 0 25px;
	list-style: none;
}

#menu ul li {
	padding: 10px 15px 10px 15px;
	display: inline;
	text-shadow: 0 1px 1px #0E3E68;
}

#menu ul li.selected {
	background: #56AFD8 url(../images/img04.jpg) top left no-repeat;
	border-top: solid 1px #82D9ED;
	border-bottom: solid 1px #24627C;
	border-radius: 5px;
}

#menu ul li.last {
	padding-right: 0;
}

#page {
	position: relative;
<?php
	if ($D_config['main_menu_pos'] == 'below header') {	
		echo "margin: 40px 0 20px 0;";
	} else {	
		echo "margin: 20px 0 20px 0;";
	}	
	echo isset($D_config['sizes_page_width'])?"width: {$D_config['sizes_page_width']}px;":"width: 980px;";
	
?>	
	padding: 0;
	
	
}

#page .section-list {
	padding-left: 0;
	list-style: none;
}

#page .section-list li {
	clear: both;
	padding: 25px 0 25px 0;
}

#page ul {
	list-style: none;
}

#page ul li {
	padding: 15px 0 15px 0;
	border-top: dotted 1px #C5CDD8;
}

#page ul li.first {
	padding-top: 0;
	border-top: 0;
}

#search input.form-submit {
	background: #B87A23;
	padding: 5px;
	color: #FFFFFF;
	border: 0;
	margin-left: 1em;
}

#search input.form-text {
	padding: 10px;
	border: dotted 1px #569DBF;
}

#sidebar {
<?php
  echo $D_config['side_menu_pos'] == 'right'?'float: right;':'float: left;'; //margin: 0 0 0 700px;
  echo isset($D_config['sizes_sidebar_width'])?"width: {$D_config['sizes_sidebar_width']}px;":"width: 196px;"; //width: 196px;
  echo isset($D_config['sizes_inside_padding'])?"padding: {$D_config['sizes_inside_padding']}px;":"padding: 40px;"; //padding: 40px;  
?>
	background: #EAEEF2 url(../images/img05.jpg) top left repeat-x;
	border-radius: 10px;
	border: solid 2px #FFFFFF;
	text-shadow: 0 1px 0 #FFFFFF;
}

#sidebar ul {
	list-style: none;
}

#sidebar ul li {
	padding: 15px 0 15px 0;
	border-top: dotted 1px #C5CDD8;
}

#sidebar ul li.first {
	border-top: 0;
	padding-top: 0;
}

#wrapper {
	position: relative;
	margin: 0 auto 0 auto;
	padding: 75px 0 0 0;
<?php
echo isset($D_config['sizes_page_width'])?"width: {$D_config['sizes_page_width']}px;":"width: 980px;";
?>
}
/* Three Column Footer Content */
#footer-content-wrapper {
	/*background-color: #F4F3EE url(../images/img05.jpg) repeat;*/
}

#footer-content {
	overflow: hidden;
	width: 1000px;
	margin: 0px auto;
	padding: 10px 0px;
	color: #D6E2F0;
}

#footer-content a {
}

#footer-bg {
	overflow: hidden;
	padding: 30px 0px;
	background: #E8E8E8;
}

#footer-content h2 {
	margin: 0px;
	padding: 0px 0px 20px 0px;
	letter-spacing: -1px;
	font-size: 26px;
	color: #262626;
}

#footer-content #fbox1 {
	float: left;
	width: 300px;
	margin-right: 50px;
}

#footer-content #fbox2 {
	float: left;
	width: 300px;
}

#footer-content #fbox3 {
	float: right;
	width: 300px;
}

#footer-content a {
}
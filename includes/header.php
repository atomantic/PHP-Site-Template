<?php
/**
 * The site wide header
 * This file should draw out the Doctype, meta tags, etc
 * and dynamically load js/css and set title, etc via configs from the laoding file
 * Example use:
 * 
 	index.php:
    <?php
	// setup php configs
	$js = array('js_file_name_1','js_file_name_2');
	$jsExteral = array('path/tojs','anotherLib/jsfile');
	$jsLib = array(PROTOCOL.'www.google.com/api/blah.js');
	$css = array('css_file_name_1','css_file_name_2');
	$pageName = 'Profile'; // used in language pack to get title, etc
	$pageKeywords = 'one,two'; // optional
	$pageDescription 'some description';// optional
    $pageTitle = $Text['page_title_'.$pageName];
	include('header.php'); // load it all
    ?>
	// the rest of the page
	...
 * 
 * @package Core
 */

// handle any universal redirects, etc

// setup generic data:
if(!$pageTitle) 		$pageTitle = 'Home'; // TODO: localize
if(!$pageKeywords) 		$pageKeywords = siteKeywords; // TODO: localize
if(!$js)				$js = array();
if(!$jsExternal)		$jsExteral = array();
if(!$css)				$css = array();

// Generic site includes
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
    <meta name="keywords" content="<?=$pageKeywords?>" />
    <?=isset($pageDescription)?'<meta name="description" content="'.$pageDescription.'" />':''?>
	<title><?=$pageTitle?>: <?=$siteTitle?></title>
	<link rel=" shortcut icon" type="image/ico" href="/favicon.ico?v=1" />
    <!-- global css -->
	<link rel="stylesheet" type="text/css" href="/css/main.css?v=1" />
	<script type="text/javascript" src="<?=PROTOCOL?>ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <?php
	// js that is hotlinked from a full server path
	// TODO: we should maintain these in one place and just specify $jsLib('swfObject') so each page doesn't have to track version number
	for($i=0;$i<count($jsExternal);$i++){
		echo '<script type="text/javascript" src="'.PROTOCOL.$jsLib[$i].'"></script>';
	}
	// load page specific dynamic js
	for($i=0;$i<count($js);$i++){
		echo '<script type="text/javascript" src="/js/'.$js[$i].'.js?v='.CACHEBUST.'"></script>';
	}
	// css
	for($i=0;$i<count($css);$i++){
		echo '<link rel="stylesheet" type="text/css" href="/css/'.$css[$i].'.css?v='.CACHEBUST.'" />';
	}
?>
</head>
<body>
    <div id="header">
    	<a accesskey="h" href="/" title="Home"><img src="" alt="Logo" /></a>
    </div>
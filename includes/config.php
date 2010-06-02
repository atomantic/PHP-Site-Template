<?php
/**
 * @package config
 */

/* 
protocol allows us to detect if the page is being accessed via SSL or not. We need to do this to prevent IE from displaying a warning to the user that some content is being delivered insecure on an otherwise secure site (such as 3rd party libraries). The warning message that IE presents is horribly written and generally leads an unwitting user to prevent loading non-SSL content, thereby harming the effective functionality of your site.
*/
$Protocol = $_SERVER['USING_HTTPS'] ? 'https://' : 'http://';
define('PROTOCOL',$Protocol);

// CASHEBUST is used to version your css and js files on the web
define('CACHEBUST','1');

$siteTitle = '';

$siteKeywords = '';

$siteDescription = '';
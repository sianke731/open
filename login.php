<?php
	include 'config.php';

	$ref = $_REQUEST['ref'];
	if($_REQUEST['ac']=='logout'){
		$_SESSION['topsession'] = '';
		header("location:".$ref);		
	}
	$top_appkey = $_GET['top_appkey']; 
	$top_parameters = $_GET['top_parameters']; 
	$top_session = $_GET['top_session']; 
	$top_sign = $_GET['top_sign']; 

	$md5 = md5( $top_appkey . $top_parameters . $top_session . $appSecret, true ); 
	$sign = base64_encode( $md5 ); 

	if ( $sign != $top_sign ) { 
		echo "signature invalid."; 
		exit(); 
	} 
	
	$parameters = array(); 
	parse_str( base64_decode( $top_parameters ), $parameters ); 
	$now = time(); 
	$ts = $parameters['ts'] / 1000; 
	if ( $ts > ( $now + 60 * 10 ) || $now > ( $ts + 60 * 30 ) ) { 
		echo "request out of date."; 
		exit(); 
	} 
	$_SESSION['topsession'] = $_REQUEST['top_session'];
	$_SESSION['nick'] = $parameters['visitor_nick'];
	header("location:".$ref);
?> 

<?php
session_start();
$_SESSION["logged_in"] = !$_SESSION["logged_in"]; // So much wrong in so much awesome
if($ajax_request = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$metadata = array("reload" => true);
	header("K16-META: ".json_encode($metadata));
} else {
	header('Location: '.$_SERVER["HTTP_REFERER"] ?: "/"); // Back or home
}
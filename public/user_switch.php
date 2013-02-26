<?php
session_start();
$_SESSION["logged_in"] = !$_SESSION["logged_in"]; // So much wrong in so much awesome
header('Location: '.$_SERVER["HTTP_REFERER"] ?: "/"); // Back or home
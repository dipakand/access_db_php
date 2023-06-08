<?php
ini_set('memory_limit', '700M');
session_start();
error_reporting(0);
/* // Report runtime errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);
// Report all errors
error_reporting(E_ALL);
// Same as error_reporting(E_ALL);
ini_set("error_reporting", E_ALL);
// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// phpinfo(); */

define('hOST', '');
define('dBuSER', '');
define('dBpWD', '');
// define('dBnAME', 'E:\testngdb.accdb');
// define('dBnAME', 'E:\testngdb.mdb');
define('dBnAME', 'D:\xampp-7.2\htdocs\access_db\testngdb1.mdb');
define('mdbFilename', '');

$current_page = basename($_SERVER['PHP_SELF']);
define('curPage', $current_page);

// Date Function
function data_formate($date = '', $formate = ''){
    if($date != ''){
        if($formate != ''){
            return date($date." ".$formate);
        }else{
            return date($date);
        }
    }else{
        if($formate != ''){
            return date("d-m-Y ".$formate);
        }else{
            return date("d-m-Y");
        }
    }
}
// echo data_formate("d-m-Y","H:i:s");
// exit;

include(dirname(__FILE__) . '/Database.php');
ini_set('display_errors', 0);
error_reporting(1);
$db = new Database;
// $db->executeQuery("SET NAMES utf8");

$timezone = "Asia/Calcutta";
if (function_exists('date_default_timezone_set'))
	date_default_timezone_set($timezone);
$lastRefreshedDataDateTimeIST = date("d M Y H:i A") . " " . date('T') . " (" . date('e') . ")";

$ajjDate = date("Y-m-d");
$tomDate = date('Y-m-d', strtotime("+1 day", strtotime($ajjDate)));

// include(dirname(dirname(__FILE__)) . '/WebClass.php');
include(dirname(__FILE__) . '/WebClass.php');
$obj = new WebClass($db);

if(!isset($_SESSION['auth'])){
    // header('location: '.dirname(__FILE__) .'index.php');
    // exit();
}

?>
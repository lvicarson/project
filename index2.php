<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
//include('connect.php');
/**
 * Created by PhpStorm.
 * User: lameckonyango
 * Date: 4/16/15
 * Time: 4:11 PM
*/
$text       = $_REQUEST["text"];
/* Split text input based on asteriks(*)
 * Africa's talking appends asteriks for after every menu level or input
 * One needs to split the response from Africa's Talking in order to determine
* the menu level and input for each level
* */
if(!empty($text)){
    $exploded_text = explode("*", $text);
    // Get menu level from ussd_string reply
    $level = count($exploded_text);
}else{
    $level = 0;
}
switch ($level) {
    case 0:
        $response = getHomeMenu();
        break;
    case 1:
        $response = getCaseNumberLevelone(end($exploded_text));
        break;
    case 2:
           $response = getCaseNumberlevelTwo($exploded_text);
           break;

    default:
        $response = "This option Does not Exist. Please try again".getHomeMenu();
        break;
}
//Let's set the HTTP content-type of our response
header('Content-type: text/plain');
echo "CON ".$response;
exit;

//First menu function

function getHomeMenu(){
    $response = "Welcome to Case Law Service \n 1.Case Search \n 2.Court List \n 3.Exit";
    return $response;
}
//second menu function
//Enter the case number here
function getCaseNumberLevelone($choice){
    if ($choice == 1) {
        $response = "1. Enter Case Number (eg 21 of 2014)";
    }
    else if ($choice == 2){
        $response = "2. Enter Cause List Number (eg 22 of 2014)";
    }
    else if ($choice == 3){
        $response ="Thank you for using this service";
    }
    return $response;
}
//check the record in the db and display results
function getCaseNumberlevelTwo($casenumber)
{
    //Db Connection
    $host = "localhost";
    $username = "";
    $password = "";
    $db_name = "";
    mysql_connect($host, $username, $password) or die("cannot connect");
    mysql_select_db($db_name) or die("cannot select DB");

if ($casenumber[0] ==1 ) {
    //searching for your query
    $query = mysql_query("SELECT `Id` , `number` , `parties` , `judge` , `action` , `date` FROM `case`  WHERE number LIKE '%$casenumber[1]%'");

    if (mysql_num_rows($query) > 0) {
        $row = mysql_fetch_row($query);
    //response from system
        $response = "Case number $row[1] Parties  $row[2] Judge $row[3]  Action $row[4]";
   //$response = "Case number $row[1]";
    }
    else {
        $response = "The Case number does not exist";
    }
    // $response = "End Case Number and parties =2323";
}else if($casenumber[0] ==2){

    $query = mysql_query("SELECT `id` , `number` , `parties` , `date` FROM `calender`  WHERE number LIKE '%$casenumber[1]%'");

    if (mysql_num_rows($query) > 0) {
        $row = mysql_fetch_row($query);

        $response = "Case number $row[1] Parties  $row[2] Date $row[3] ";
    }
    else {
        $response = "The Case number does not exist";
    }
}

else {
        $response = "The Case number does not exist";
    }
    return $response;
}

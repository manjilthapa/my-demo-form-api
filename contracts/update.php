<?php
// required headers
header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
 // include database and object files
include_once '../config/database.php';
include_once '../objects/contract.php';

require('../vendor/autoload.php');

 
// get database connection
$database = new Database();
$db = $database->getConnection();
// prepare user object
$contract = new Contract($db);

//$player->name = isset($_POST['name']) ? $_POST['name']: die();
//$player->group_id = isset($_POST['group_id']) ? $_POST['group_id']: die();
$item = json_decode(file_get_contents("php://input"));


$contract->id = $item->id;
$contract->address = $item->address;
$contract->institution =  $item->institution;
$contract->contact_person = $item->contact_person;
$contract->supplier = $item->supplier;
$contract->installation = $item->installation;
$contract->authority_require =  $item->authority_require;
$contract->annual_contract_amount = $item->annual_contract_amount;
$contract->who_pay = $item->who_pay;
$contract->start_date = $item->start_date;
$contract->end_date =  $item->end_date;
$contract->comment = $item->comment;


if($contract->update()){
    echo json_encode(array("OK" => "YES"));
}
else{
    echo json_encode(array("OK" => "ERROR"));
}

?>
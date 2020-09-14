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
 
// get database connection
$database = new Database();
$db = $database->getConnection();
// prepare user object
$contract = new Contract($db);

//$player->name = isset($_POST['name']) ? $_POST['name']: die();
//$player->group_id = isset($_POST['group_id']) ? $_POST['group_id']: die();
$json = file_get_contents('php://input');
$item = json_decode($json, true);

$contract->address = isset($item['address']) ? $item['address']: die();
$contract->institution = isset($item['institution']) ? $item['institution']: die();
$contract->supplier = isset($item['supplier']) ? $item['supplier']: die();
$contract->installation = isset($item['installation']) ? $item['installation']: die();
$contract->authority_require = isset($item['authority_require']) ? $item['authority_require']: "";
$contract->annual_contract_amount = isset($item['annual_contract_amount']) ? $item['annual_contract_amount']: die();
$contract->who_pay = isset($item['who_pay']) ? $item['who_pay']: "";
$contract->start_date = isset($item['start_date']) ? $item['start_date']: "";
$contract->end_date = isset($item['end_date']) ? $item['end_date']: "";
$contract->comment = isset($item['comment']) ? $item['comment']: "";
$contract->file = isset($item['file']) ? $item['file']: "";


if($contract->create()){

    $contract_arr=array(
        "OK" => "YES",
        "contract" => array(
            "id" => $contract->id,
            "file_path" => $contract->file_path
        ),
    );

    // tell the user
    echo json_encode($contract_arr);
}

// if unable to create the contract, tell the user
else{

    // set response code - 503 service unavailable
    //  http_response_code(503);

    // tell the user
    echo json_encode(array("OK" => "ERROR"));
}

?>
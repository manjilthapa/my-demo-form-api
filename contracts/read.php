<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
 // include database and object files
include_once '../config/database.php';
include_once '../objects/contract.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
// prepare user object
$contract = new Contract($db);

// query products
$stmt = $contract->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $response=array();
    $contracts_arr["OK"] = "YES";
    $contracts_arr["contracts"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $contract=array(
            "id" => $id,
            "address" => $address,
            "institution" => $institution,
            "supplier" => $supplier,
            "installation" => $installation,
            "authority_require" => $authority_require,
            "annual_conract_amount" => $annual_contract_amount,
            "who_pay" => $who_pay,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "comment" => $comment,
            "file_path" => $file_path
        );
 
        array_push($contracts_arr["contracts"], $contract);
    }
 
    // set response code - 200 OK
  //  http_response_code(200);
 
    // show products data in json format
    echo json_encode($response);
}
else{
 
    // set response code - 404 Not found
   // http_response_code(404);
 
    // tell the user no products found
    echo json_encode(
        array("OK" => "ERROR")
    );
}

?>
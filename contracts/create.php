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
$json = file_get_contents('php://input');
$item = json_decode($json, true);

$contract->address = isset($item['address']) ? $item['address']: "";
$contract->institution = isset($item['institution']) ? $item['institution']: "";
$contract->contact_person = isset($item['contact_person']) ? $item['contact_person']: "";
$contract->supplier = isset($item['supplier']) ? $item['supplier']: "";
$contract->installation = isset($item['installation']) ? $item['installation']: "";
$contract->authority_require = isset($item['authority_require']) ? $item['authority_require']: "";
$contract->annual_contract_amount = isset($item['annual_contract_amount']) ? $item['annual_contract_amount']: "";
$contract->who_pay = isset($item['who_pay']) ? $item['who_pay']: "";
$contract->start_date = isset($item['start_date']) ? $item['start_date']: "";
$contract->end_date = isset($item['end_date']) ? $item['end_date']: "";
$contract->comment = isset($item['comment']) ? $item['comment']: "";
$contract->file_path =  "";

//Save file to aws s3
if(isset($_FILES['file']) 
    && $_FILES['file']['error'] == UPLOAD_ERR_OK 
    && is_uploaded_file($_FILES['file']['tmp_name'])) {
    
    // this will simply read AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY from env vars
    $s3 = new Aws\S3\S3Client([
        'version'  => '2006-03-01',
        'region'   => 'eu-north-1',
    ]);
    $bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
    // FIXME: you should add more of your own validation here, e.g. using ext/fileinfo
    $file_Path = $_FILES['file']['tmp_name'];
    $key = basename($_FILES['file']['name']);
    try {
        // FIXME: you should not use 'name' for the upload, since that's the original filename from the user's computer - generate a random filename that you then store in your database, or similar
        //$upload = $s3->upload($bucket, $_FILES['userfile']['name'], $_FILES['userfile']['tmp_name'], 'public-read');
        $result = $s3->putObject([
            'Bucket' => $bucket,
            'Key' => $key,
            'SourceFile' => $file_Path,
            'ACL'    => 'public-read',
            'ContentType'  => $_FILES['file']['type']
        ]);
        $contract->file_path =  $result['ObjectURL'];
    } catch(Exception $e) { 
        echo json_encode(array("upload" => "Error"));
    } 
}

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
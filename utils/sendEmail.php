<?php
// Load Composer's autoloader
require __DIR__ . "/../vendor/autoload.php";
// include database and object files
include_once __DIR__ . "/../config/database.php";
include_once __DIR__ . "/../objects/contract.php";
 
// get database connection
$database = new Database();
$db = $database->getConnection();
// prepare user object
$contract = new Contract($db);

// query products
$stmt = $contract->getEndDateIsFromTomorrow();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("manjil@lifebonder.com", "Manjil Thapa");
        $email->setSubject("Sending email for reminder of contract expire");
        $email->addTo("manjilthapa@gmail.com", "Example User");
        $email->addContent(
            "text/html", "<strong>$institution contract with $supplier is expiring, on $end_date</strong>"
        );
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
        try {
            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
    }
}
else{
 

}



?>
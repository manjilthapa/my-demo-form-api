<?php
class Contract{
 
    // database connection and table name
    private $conn;
    private $table_name = "demo_form";
 
    // object properties
    public $id;
    public $address;
    public $institution;
    public $contact_person;
    public $supplier;
    public $installation;
    public $authority_require;
    public $annual_contract_amount;
    public $who_pay;
    public $start_date;
    public $end_date;
    public $comment;
    public $file_path;
    public $file_name;
    public $status;
    public $created;

 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    // read contract
    function read(){
         // select all query
        $query = "SELECT
               *
            FROM
                " . $this->table_name . "
            
            WHERE status = 'active'
            " ;
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }

    function create(){
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    address=:address,institution=:institution,contact_person=:contact_person,
                    supplier=:supplier,installation=:installation,
                    authority_require=:authority_require,annual_contract_amount=:annual_contract_amount,
                    who_pay=:who_pay,start_date=:start_date, end_date=:end_date, comment=:comment,
                    file_path=:file_path, file_name=:file_name, created=:created";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->address=htmlspecialchars(strip_tags($this->address));
        $this->institution=htmlspecialchars(strip_tags($this->institution));
        $this->contact_person=htmlspecialchars(strip_tags($this->contact_person));
        $this->supplier=htmlspecialchars(strip_tags($this->supplier));
        $this->installation=htmlspecialchars(strip_tags($this->installation));
        $this->authority_require=htmlspecialchars(strip_tags($this->authority_require));
        $this->annual_contract_amount=htmlspecialchars(strip_tags($this->annual_contract_amount));
        $this->who_pay=htmlspecialchars(strip_tags($this->who_pay));
        $this->start_date=htmlspecialchars(strip_tags($this->start_date));
        $this->end_date=htmlspecialchars(strip_tags($this->end_date));
        $this->comment=htmlspecialchars(strip_tags($this->comment));
        $this->file_path=htmlspecialchars(strip_tags($this->file_path));
        $this->file_name=htmlspecialchars(strip_tags($this->file_name));
        $this->created=htmlspecialchars(strip_tags($this->created));
    
        // bind values
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":institution", $this->institution);
        $stmt->bindParam(":contact_person", $this->contact_person);
        $stmt->bindParam(":supplier",$this->supplier);
        $stmt->bindParam(":installation", $this->installation);
        $stmt->bindParam(":authority_require", $this->authority_require);
        $stmt->bindParam(":annual_contract_amount", $this->annual_contract_amount);
        $stmt->bindParam(":who_pay",$this->who_pay);
        $stmt->bindParam(":start_date", $this->start_date);
        $stmt->bindParam(":end_date", $this->end_date);
        $stmt->bindParam(":comment", $this->comment);
        $stmt->bindParam(":file_path",$this->file_path);
        $stmt->bindParam(":file_name",$this->file_name);
        $stmt->bindParam(":created", $this->created);

    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
        
    }

    function update(){
 
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                address=:address,institution=:institution,contact_person=:contact_person,
                supplier=:supplier,installation=:installation,
                authority_require=:authority_require,annual_contract_amount=:annual_contract_amount,
                who_pay=:who_pay,start_date=:start_date, end_date=:end_date, comment=:comment
                WHERE
                    id = :id";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
        $this->address=htmlspecialchars(strip_tags($this->address));
        $this->institution=htmlspecialchars(strip_tags($this->institution));
        $this->contact_person=htmlspecialchars(strip_tags($this->contact_person));
        $this->supplier=htmlspecialchars(strip_tags($this->supplier));
        $this->installation=htmlspecialchars(strip_tags($this->installation));
        $this->authority_require=htmlspecialchars(strip_tags($this->authority_require));
        $this->annual_contract_amount=htmlspecialchars(strip_tags($this->annual_contract_amount));
        $this->who_pay=htmlspecialchars(strip_tags($this->who_pay));
        $this->start_date=htmlspecialchars(strip_tags($this->start_date));
        $this->end_date=htmlspecialchars(strip_tags($this->end_date));
        $this->comment=htmlspecialchars(strip_tags($this->comment));
    
        // bind values
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":institution", $this->institution);
        $stmt->bindParam(":contact_person", $this->contact_person);
        $stmt->bindParam(":supplier",$this->supplier);
        $stmt->bindParam(":installation", $this->installation);
        $stmt->bindParam(":authority_require", $this->authority_require);
        $stmt->bindParam(":annual_contract_amount", $this->annual_contract_amount);
        $stmt->bindParam(":who_pay",$this->who_pay);
        $stmt->bindParam(":start_date", $this->start_date);
        $stmt->bindParam(":end_date", $this->end_date);
        $stmt->bindParam(":comment", $this->comment);
        
     
        // execute the query
        if($stmt->execute()){
            
            return true;
        }
     
        return false;
    }

    // delete the contract -> update status to deleted
    function delete(){
 
        $query = "UPDATE
                " . $this->table_name . "
            SET
                status = 'deleted'
            WHERE
                id = :id
            ";
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind new values
        $stmt->bindParam(':id', $this->id);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }

    //get data that's end date is tomorrow
    function getEndDateIsFromTomorrow(){
        // select all query
       $query = "SELECT
              *
           FROM
               " . $this->table_name . "
           
           WHERE end_date = CURDATE() + INTERVAL 1 DAY AND status = 'active' 
           " ;
       // prepare query statement
       $stmt = $this->conn->prepare($query);
       // execute query
       $stmt->execute();
       return $stmt;
   }
}
?>
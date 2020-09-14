<?php
class Contract{
 
    // database connection and table name
    private $conn;
    private $table_name = "demo_form";
 
    // object properties
    public $id;
    public $address;
    public $institution;
    public $supplier;
    public $installation;
    public $authority_require;
    public $annual_contract_amount;
    public $who_pay;
    public $start_date;
    public $end_date;
    public $comment;
    public $file_path;
 
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
                " . $this->table_name ;
 
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
                    address=:address,institution=:institution,
                    supplier=:supplier,installation=:installation,
                    authority_require=:authority_require,annual_contract_amount=:annual_contract_amount,
                    who_pay=:who_pay,start_date=:start_date, end_date=:end_date, comment=:comment,
                    file_path=:file_path";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->address=htmlspecialchars(strip_tags($this->address));
        $this->institution=htmlspecialchars(strip_tags($this->institution));
        $this->supplier=htmlspecialchars(strip_tags($this->supplier));
        $this->installation=htmlspecialchars(strip_tags($this->installation));
        $this->authority_require=htmlspecialchars(strip_tags($this->authority_require));
        $this->annual_contract_amount=htmlspecialchars(strip_tags($this->annual_contract_amount));
        $this->who_pay=htmlspecialchars(strip_tags($this->who_pay));
        $this->start_date=htmlspecialchars(strip_tags($this->start_date));
        $this->end_date=htmlspecialchars(strip_tags($this->end_date));
        $this->comment=htmlspecialchars(strip_tags($this->comment));
        $this->file_path=htmlspecialchars(strip_tags($this->file_path));
    
        // bind values
        $stmt->bindParam(":name", $this->address);
        $stmt->bindParam(":user_id", $this->institution);
        $stmt->bindParam(":pin",$this->supplier);
        $stmt->bindParam(":created", $this->installation);
        $stmt->bindParam(":name", $this->authority_require);
        $stmt->bindParam(":user_id", $this->annual_contract_amount);
        $stmt->bindParam(":pin",$this->who_pay);
        $stmt->bindParam(":created", $this->start_date);
        $stmt->bindParam(":name", $this->end_date);
        $stmt->bindParam(":user_id", $this->comment);
        $stmt->bindParam(":pin",$this->file_path);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }
    
        return false;
        
    }
}
?>
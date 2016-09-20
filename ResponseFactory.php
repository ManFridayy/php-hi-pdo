<?php
///Reponse Factory

abstract class Response 
{
    protected $stmt = null;

    abstract public function WriteHeader();
    abstract public function WriteBody();

    public function __construct($stmt)
    {
        $this->stmt = $stmt;
    }    

    public function Write()
    {
        $this->WriteHeader();
        $this->WriteBody();
    }
}

class ResponseSeperate extends Response
{

    public function __construct($stmt) {
        parent::__construct($stmt);
    }

    public function WriteHeader()
    {
        //Get column name 
        //$columns = $stmt->getColumnMeta();

        $columns = array_keys($this->stmt->fetch(PDO::FETCH_ASSOC));
        echo rtrim(implode('|', $columns));
    }

    public function WriteBody()
    {
        while ($row = $this->stmt->fetch(PDO::FETCH_ASSOC)) {
            echo rtrim(implode('|', array_values($row)));
        }        
    }
}

class ResponseSeperateEach extends Response
{

    public function __construct($stmt) {
        parent::__construct($stmt);
    }

    public function WriteHeader()
    {
        $columns = array_keys($this->stmt->fetch(PDO::FETCH_ASSOC));
        echo rtrim(implode('|', $columns));
    }

    public function WriteBody()
    {
        $data = $this->stmt->fetchAll();
        foreach ($data as $row) {
            echo rtrim(implode('|', array_values($row)));
        }      
    }
}

class ResponseJson extends Response 
{

    public function __construct($stmt) {
        parent::__construct($stmt);
    }

    public function WriteHeader()
    {
        echo '';
    }

    public function WriteBody()
    {
        $data = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    }
}


class ResponseFactory {
    public static function Create($res, $stmt)
    {
        $response = 'Response' . $res;

        if (class_exists($response))
        {
            return new $response($stmt);
        }
    }
}

?>
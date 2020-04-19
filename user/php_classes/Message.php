<?php
class Message{
    private $MID;
    private $MID_Hashed;
    private $Sender;
    private $Sender_Name;
    private $conn;
    private $Receiver;
    private $Receiver_Name;
    private $Title;
    private $Message;
    private $datee;

    public function __construct($row,$conn){
        $this->conn=$conn;
        //$this->MID_Hashed = openssl_random_pseudo_bytes(6, $strong);
        $this->MID = $row['MID'];
        $this->Sender = $row['Sender'];
        $this->Receiver = $row['Receiver'];
        $this->Title = $row['Title'];
        $this->Message = $row['Message'];
                $this->MID_Hashed = hash('crc32',$this->Title,false);

        $this->datee = $row['datee'];

    }
    public function __set($property,$value) 
    {
        if ((property_exists($this,$property)))
        {
            $this->$property = $value;
        }
    }

    public function __get($property) 
    {
        if ((property_exists($this,$property)))
            return  $this->$property;  
    }
    public function set_sender_Name()
    {
        $stat = $this->conn->prepare("select Name from users where UID =?");
        $stat->bind_Param("s",$this->Sender);
        $stat->execute();
        $result = $stat->get_result();
        $row = $result->fetch_assoc();
        $this->Sender_Name=$row['Name'];
        
    }
    public function set_receiver_Name()
    {
        $stat = $this->conn->prepare("select Name from users where UID =?");
        $stat->bind_Param('s',$this->Receiver);
        $stat->execute();
        $result = $stat->get_result();
        $row = $result->fetch_assoc();
        $this->Receiver_Name=$row['Name'];
    }
    
    
        
}

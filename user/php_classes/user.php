<?php  ///to to Succuceful message
include 'Message.php';
class User{
    private $UID;
    private $conn;
    private $Username;
    private $Email;
    private $Name;
    private $Message_Inbox = array();
    private $Message_Outbox = array();

    public function __construct($row,$conn){
        $this->conn=$conn;
        $this->UID = $row['UID'];
        $this->Username = $row['UserName'];
        $this->Email = $row['Email'];
        $this->Name = $row['Name'];
    }
    public function Send_Message($Message,$Title,$Receive)
    {
        $iv_1 =$iv_2 =0;
        $conn = $this->conn;
        $seen =0;
        $this->Encrypt_Message($Message,$Title,$iv_1,$iv_2);
        $stmt = $conn->prepare("INSERT INTO messages(Sender,Receiver,Title,Message,IV_1,IV_2,seen,datee) VALUES          (?,?,?,?,?,?,?,now())");
        if ($stmt === FALSE) {

            die($mysqli->error);
        }
        $stmt->bind_param("ssssssi", $this->UID,$Receive,$Title,$Message,$iv_1,$iv_2,$seen);
        if ($stmt->execute()) {
            /*$new_message = new Message($row,$this->conn);
                $new_message->set_receiver_Name();
                $new_message->__set('Sender_Name',"you");
            array_push($this->Message_Inbox,$new_message);*/
            echo '<script>alert("Done Send Message")</script>';
        } else {
            echo '<script>alert("No")</script>';

        }
    }
    public function get_Message_Inbox()
    {
        $stmt = $this->conn->prepare("SELECT * FROM messages WHERE Receiver=?");
        $stmt->bind_param("s", $this->UID);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows==0)
        {
            unset($this->Message_Inbox);
            $this->Message_Inbox = array();
        }
        else if($result->num_rows==count($this->Message_Inbox))
        {
            return $this->Message_Inbox;
        }
        else
        {
            unset($this->Message_Outbox);
            $this->Message_Outbox = array();
            while($row =$result->fetch_assoc())
            {
                $this->Decrypt_Message($row);
                $new_message = new Message($row,$this->conn);
                $new_message->set_sender_Name();
                $new_message->__set('Receiver_Name',"you");
                array_push($this->Message_Inbox,$new_message);
            }
            return $this->Message_Inbox;

        }
    }
    public function get_Message_Outbox()
    {
        $stmt = $this->conn->prepare("SELECT * FROM messages WHERE Sender=?");
        $stmt->bind_param("s", $this->UID);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows==0)
        {
            unset($this->Message_Outbox);
            $this->Message_Outbox = array();
        }
        else if($result->num_rows==count($this->Message_Outbox))
        {
            return $this->Message_Outbox;
        }
        else
        {
            unset($this->Message_Outbox);
            $this->Message_Outbox = array();
            while($row=$result->fetch_assoc())
            {
                $this->Decrypt_Message($row);
                $new_message = new Message($row,$this->conn);
                $new_message->set_receiver_Name();
                $new_message->__set('Sender_Name',"you");
                array_push($this->Message_Outbox,$new_message);
            }
                return $this->Message_Outbox;

        }
    }
    private function Encrypt_Message(&$Message,&$Title,&$iv_1,&$iv_2)
    {
        $key_size = 32;
        $encryption_key = 'AdekFy7amamaaaaaaaaaaaaaaaaaaaaa';
        $iv_size = 16; 
        $iv_1 = openssl_random_pseudo_bytes($iv_size, $strong);
        $Message= openssl_encrypt(
            $this->message_pad($Message, 16), 
            'AES-256-CBC', 
            $encryption_key,
            0,
            $iv_1
        );
        $iv_2 = openssl_random_pseudo_bytes($iv_size, $strong);
        $Title= openssl_encrypt(
            $this->message_pad($Title, 16), 
            'AES-256-CBC', 
            $encryption_key,
            0,
            $iv_2
        );
    }
    private function Decrypt_Message(&$row)
    {
        $row['Title'] = $this->message_unpad(openssl_decrypt(
            $row['Title'],
            'AES-256-CBC',
            "AdekFy7amamaaaaaaaaaaaaaaaaaaaaa",
            0,
            $row['IV_2']
        ));
        $row['Message'] = $this->message_unpad(openssl_decrypt(
            $row['Message'],
            'AES-256-CBC',
            "AdekFy7amamaaaaaaaaaaaaaaaaaaaaa",
            0,
            $row['IV_1']
        ));

    }
    function message_pad($data, $size)
    {
        $length = $size - strlen($data) % $size;
        return $data . str_repeat(chr($length), $length);
    }
    function message_unpad($data)
    {
        return substr($data, 0, -ord($data[strlen($data) - 1]));
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
    public function getmessage($id,$type)
    {
        //echo 'hiiii'.count($this->Message_Inbox);
        if($type==0)
            foreach($this->Message_Inbox as $Message)
            {
                if($Message->__get("MID_Hashed")==$id)
                    /*  echo $Message->__get("MID_Hashed").'<br>';
            echo $id;*/
                    return $Message;
            }
        else
        {
            foreach($this->Message_Outbox as $Message)
            {
                if($Message->__get("MID_Hashed")==$id)
                    /*  echo $Message->__get("MID_Hashed").'<br>';
            echo $id;*/
                    return $Message;
            }
        }
        return null;
    }
}
?>
<?php SESSION_start();
if(!isset($_SESSION['user']))
    header("Location: http://localhost:80/Project");
else
{
    if (!isset($_SESSION['canary'])) {
    session_regenerate_id(true);
    $_SESSION['canary'] = time();
}
// Regenerate session ID every five minutes:
if ($_SESSION['canary'] < time() - 300) {
    session_regenerate_id(true);
    $_SESSION['canary'] = time();
}
    require ('php_classes/user.php');
    $user =  unserialize($_SESSION['user']);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Chat System - Send Message</title>
        <link rel="stylesheet" href="../css/home.css">
        <link rel="stylesheet" href="../css/fixit.css">
        <style>
            textarea {
                width: 100%;
                height: 150px;
                padding: 12px 20px;
                box-sizing: border-box;
                border: 2px solid #ccc;
                border-radius: 4px;
                background-color: #f8f8f8;
                font-size: 16px;
                resize: none;
            }
            input[type=text] {
                width: 50%;
                padding: 12px 20px;
                background-color: #f8f8f8;
                margin: 8px 0;
                box-sizing: border-box;
            }
            input[type=submit]{
                background-color: #4CAF50;
                border: none;
                color: white;
                width: 25%;
                padding: 10px 10px;
                text-decoration: none;
                margin: 4px 2px;
                font-size: 20px;
                cursor: pointer;
            }
        </style>
    </head>

    <body>
        <div class="parallelogram" id="one"></div>
        <div class="parallelogram" id="two"></div>
        <div class="parallelogram" id="three"></div>
        <div class="parallelogram" id="four"></div>
        <div class="parallelogram" id="five"></div>
        <div class="parallelogram" id="six"></div>
        <div id="head">
            <div id="header-inner"> 
                <div id="logo">
                    <p>Chat System</p>
                </div>
                <div id="top-nav">
                    <ul>
                        <li><a href="home.php">Home</a></li>
                        <li><a href="inbox.php">Messages</a></li>
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
                <div class="clr"></div>
            </div>
        </div>
        <div id="container">
            <div class="clearfix">

                <div class="column menu">
                    <ul>
                        <li style="font-weight: bold;color:orange;"><a href="SendMessage.php">Send Message</a></li>
                        <li ><a href="inbox.php">Inbox</a></li>
                        <li ><a href="outbox.php">Outbox</a></li>
                    </ul>
                </div>
                <div class="column content">
                    <form name="Send Message" action="" method="post">
                        <input type="text" placeholder="UserName" name="UserName"/>
                        <input type="text" placeholder="Message title" name ="title"/>
                        <textarea placeholder="Enter the message." name="message"></textarea>
                        <input type="submit" value="Send" name="send">
                    </form>
                </div>
            </div>
        </div>
    </body>
    <?php
    require '../htmlpurifier-4.9.3/library/HTMLPurifier.auto.php';
    require '../database.php';
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);

    if(isset($_POST['send'])){
        if(empty($_POST["UserName"])||empty($_POST["message"])||empty($_POST["title"])) 
        {
            echo '<script>alert("all field are required")</script>';
            exit;
        }
        $rec = $purifier->purify(strip_tags($_POST['UserName']));

        $stmt = $conn->prepare("SELECT * FROM users WHERE UserName=?");
        $stmt->bind_param("s", $rec);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows==0)
        {
            echo '<script>alert("Username not exsists")</script>';
            exit;
        }
        $row =$result->fetch_assoc();
        $message = $purifier->purify(strip_tags($_POST['message']));
        $title = $purifier->purify(strip_tags($_POST['title']));
        $user->__set('conn',$conn);
        $user->Send_Message($message,$title,$row['UID']);

    }
    ?>
</html>

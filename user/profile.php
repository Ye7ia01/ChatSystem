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
        <title>Chat System - profile</title>
        <link rel="stylesheet" href="../css/home.css">
        <link rel="stylesheet" href="../css/fixit.css">
        <style>
            #changepass {
                background-color: rgba(#7D1935,0.8);
                margin:auto;
                padding: 5px;
                width: 45%;
                position: relative;
                box-sizing: border-box;
            }
            input[type=password] {
                width: 100%;
                padding: 5px 5px;
                margin: 10px;
                box-sizing: border-box;
            }
            h4 {
                text-align: center;
                color:white;
                font-weight: bold;
                padding: 5px;
            }
            input[type=submit]{
                background-color: #4CAF50;
                border: none;
                color: white;
                width: 50%;
                padding: 10px 10px;
                text-align: center;
                text-decoration: none;
                margin:auto;
                display:block;
                cursor: pointer;
                margin-bottom: 20px;
                margin-top: 20px;
            }
            p {
                color:white;
                font-weight: bold;
                font-style: italic;
                padding: 5px 20px;
            }
            input[type=submit]:hover {
                background-color: aqua;
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
                    <h2 style="color:white">Chat System</h2>
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
            <h4 style="font-size: 25px">Change Password</h4>
            <div id="changepass">
                <form action="" method="post">
                    <p><input type="password" placeholder="Old password" name="oldpass"><br>
                        <input type="password" placeholder="New password" name ="newpass"><br>
                        <input type="password" placeholder="Repeat new password" name="newpass2"></p>
                    <input type="submit" value="Change Password" name ="change">
                </form>
            </div>


        </div>
    </body>
    <?php
    if(isset($_POST['change']))
    {
        require '../htmlpurifier-4.9.3/library/HTMLPurifier.auto.php';
        require '../database.php';
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        if(empty($_POST["oldpass"])||empty($_POST["newpass"])||empty($_POST["newpass2"])) 
        {
            echo '<script>alert("all field are required")</script>';
            exit;
        }
        if($_POST['newpass']!=$_POST['newpass2'])
        {
            echo '<script>alert("New pass fields not similar")</script>';
            exit; 
        }
        $oldpass = $purifier->purify(strip_tags($_POST['oldpass']));
        $newpass =  $purifier->purify(strip_tags($_POST['newpass']));
        $stmt = $conn->prepare("SELECT * FROM users WHERE UserName=?");;
        $id = $user->__get('Username');
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if(password_verify($oldpass, $row['Pass']))
        {
            if(strlen($newpass)<6)
            {
                echo '<script>alert("Password is too short")</script>';
                exit;
            }
            $newpass = password_hash($newpass, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("update users set Pass=? where UserName =?");
            $stmt->bind_param("ss", $newpass,$id);
            $stmt->execute();
            echo '<script>alert("Done updated pass")</script>';
        }
        else{
            echo '<script>alert("Old pass is wrong !!")</script>';
        }
    }
    ?>
</html>

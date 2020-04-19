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
    include_once  '../database.php';
    include_once  ('php_classes/user.php');
    include_once  ('php_classes/Message.php');
    $user =  unserialize($_SESSION['user']);
    //echo $user->__get('Name');
    $user->__set('conn',$conn);
    if (isset($_GET['id']))
    {
        require '../htmlpurifier-4.9.3/library/HTMLPurifier.auto.php';
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $id = $purifier->purify(strip_tags($_GET['id']));
        $type = $purifier->purify(strip_tags($_GET['type']));
        if($type=="in")
        {
            $message= $user->getmessage($id,0);
        }
        else if($type=="out")
        {
            $message= $user->getmessage($id,1);
        }
        else
            //header("location: ../inbox.php");
        /*$x = explode('/', $_SERVER['REQUEST_URI']);
        $uri ='http://'.getHostByName(getHostName()).'/'.$x[1].'/'.$x[2].'/';*/
        if($message ==null)
            header("location: ../inbox.php");

    }
}
?>
<!DOCTYPE html>
<html>
    <head>

        <meta charset="UTF-8">
        <title>CyperPunks</title>
        <link rel="stylesheet" href="../../css/home.css">
        <link rel="stylesheet" href="../../css/fixit.css">
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
                        <li><a href="../home.php">Home</a></li>
                        <li><a href="../inbox.php">Messages</a></li>
                        <li><a href="../profile.php">Profile</a></li>
                        <li><a href="#">Logout</a></li>
                    </ul>
                </div>
                <div class="clr"></div>
            </div>
        </div>
        <div id="container">
            <div class="clearfix">

                <div class="column menu">
                    <ul>
                        <li><a href="../SendMessage.php">Send Message</a></li>
                        <li><a href="../inbox.php">Inbox</a></li>
                        <li ><a href="../outbox.php">Outbox</a></li>
                    </ul>
                </div>
                <div class="column content">
                    <div id="Message-Header-Sender">
                        <?php echo 'From: '.$message->__get('Sender_Name');?>
                    <?php echo ', To: '.$message->__get('Receiver_Name');?> </div>
                    <div id="Message-Header-Time"><?php echo 'Date: '.$message->__get('datee'); ?></div>
                    <div class="clr"></div>
                    <div id="Message">
                        <?php echo $message->__get('Message'); ?></div>
                </div>
            </div>
        </div>
        <div class="footer"><center>Developed by Mahmoud Matrawy</center></div>
    </body>
</html>

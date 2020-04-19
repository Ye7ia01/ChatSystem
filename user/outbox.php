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
    $user->__set('conn',$conn);        
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Chat System - Sent</title>
        <link rel="stylesheet" href="../css/home.css">
        <link rel="stylesheet" href="../css/fixit.css">
        <style>
            table {
                border-collapse: collapse;
                border: 1px solid black;
                width: 100%;
            }
            th {
                color: black;
                border: 4px solid rgba(0,0,0,0.7);
                //font-size: 20px
                    text-align: left;
                padding: 10px;
                text-align: center;
                background-color: #F5F3EE;
            }
            td {

                text-align: left;
                padding: 8px;
                border: 2px solid rgba(0,0,0,0.4);
            }
            td a{
                display: block;
                text-decoration: none;
                color:#FFA500;
            }
            td a:visited{
               color: white;
            }
            tr:hover {
                background-color: #0099cc;
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
                    <p>CyberPunks</p>
                </div>
                <div id="top-nav">
                    <ul>
                        <li><a href="home.php">Home</a></li>
                        <li><a href="inbox.php">Messages</a></li>
                        <li><a href="profile.php">Profile</a></li>
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
                        <li><a href="SendMessage.php">Send Message</a></li>
                        <li><a href="inbox.php">Inbox</a></li>
                        <li style="font-weight: bold;color:orange;"><a href="outbox.php">Sent Messages</a></li>
                    </ul>
                </div>
                <div class="column content">
                    <table id="inbox">
                        <col width="10px">
                        <col width="160px">
                        <tr>
                            <th>To</th>
                            <th>Title</th>
                        </tr>
                        <?php
                        $data = $user->get_Message_Outbox();
                        //echo '<script>alert("'.count($data).'")</script>';
                        if(count($data)==0)
                        {
                            echo '<p> Empty inbox</p>';
                            exit;
                        }
                        foreach($data as $row)
                        {
                            echo '<tr>';
                            echo '<td><a href=Read.php/?id='.
                                $row->__get('MID_Hashed').'&type=out>'.$row->__get('Receiver_Name').'</a></td>';
                            echo '<td><a href=Read.php/?id='.
                                $row->__get('MID_Hashed').'&type=out>'.$row->__get('Title').'</a></td></tr>';
                        }
                        $_SESSION['user']=serialize($user);

                        ?>
                    </table>
                </div>
            </div>
        </div>
    </body>

</html>

<?php SESSION_start();

if(!isset($_SESSION['user']))
    header("Location: http://localhost:80/Project");
else
{
    include_once  '../database.php';
    include_once  ('php_classes/user.php');
    include_once  ('php_classes/Message.php');
    $user =  unserialize($_SESSION['user']);
    $user->__set('conn',$conn);        
}
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Chat System - Inbox</title>
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
        <script>
            function addRowHandlers() {
                var table = document.getElementById("inbox");
                var rows = table.getElementsByTagName("tr");
                for (i = 0; i < rows.length; i++) {
                    var currentRow = table.rows[i];
                    var createClickHandler = 
                        function(row) 
                    {
                        return function() { 
                            var cell = row.getElementsByTagName("td")[1];
                            var id = cell.innerHTML;
                            alert("id:" + id);
                        };
                    };

                    currentRow.onclick = createClickHandler(currentRow);
                }
            }</script>
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
            <div class="clearfix">

                <div class="column menu">
                    <ul>
                        <li><a href="SendMessage.php">Send Message</a></li>
                        <li style="font-weight: bold;color:orange;"><a href="inbox.php">Inbox</a></li>
                        <li ><a href="outbox.php">Sent Messages</a></li>
                    </ul>
                </div>
                <div class="column content">
                    <table id="inbox">
                        <col width="10px">
                        <col width="160px">
                        <tr>
                            <th>Owner</th>
                            <th>Title</th>
                        </tr>
                        <?php
                        $data = $user->get_Message_Inbox();
                        //echo '<script>alert("'.count($data).'")</script>';
                        if(count($data)==0)
                        {
                            echo '<p> No Messages</p>';
                            exit;
                        }
                        foreach($data as $row)
                        {
                            echo '<tr>';
                            echo '<td><a href=Read.php/?id='.
                                $row->__get('MID_Hashed').'&type=in>'.$row->__get('Sender_Name').'</a></td>';
                            echo '<td><a href=Read.php/?id='.
                                $row->__get('MID_Hashed').'&type=in>'.$row->__get('Title').'</a></td></tr>';
                        }
                        $_SESSION['user']=serialize($user);

                        ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>

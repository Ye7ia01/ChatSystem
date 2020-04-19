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
        <title>Chat System</title>
        <link rel="stylesheet" href="../css/home.css">
        <link rel="stylesheet" href="../css/fixit.css">
        
        <style>
        p {
        padding: 25px;
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
            <p> &nbsp;&nbsp;&nbsp; Welcome&nbsp;:&nbsp;&nbsp; <?php echo $user->__get('Name'); ?>
            <br><br>&nbsp;&nbsp;&nbsp; Our site provides a secure messaging.<br>
            <p>Developed by: </p>
            <marquee>
           <span><b>Mahmoud Nabil Eletrrby </b></span>&nbsp;<br>
           <span><b>Yousef Mohamed Mokhaimer Hemaidah </b></span>&nbsp;
            </marquee>
            <div class="clr"></div>
        </div>
        <script  src="index.js"></script>
    </body>

</html>

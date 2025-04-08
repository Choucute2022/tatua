<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Title site</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container">
        <ul class="nav navbar-nav">
            <li><a href="#">Home</a></li>
            <li><a href="index.php">List User </a></li>
            <li><a href="adduser.php">Add User </a></li>
            <li><a href="#">About </a></li>
            <?php if (isset($_SESSION['UserLogin'])){   
                ?>
            <li><a href="#">Hello <?=$_SESSION['UserLogin']?></a></li>
            <li><a href="logout.php">Logout</a></li>
            <?php
            }?>
        </ul>
    </div>
</nav>

<div class="container"> <!-- Thẻ mở .container -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaTua Milktea</title>
   
    
    <style>
       .background-container {
            text-align: center; 
        }
        .logo {
            width: 400px; /* Adjust size */
            height: auto;
        }
        .navbar {
            background-color: #333;
            color: white;
            padding: 10px 0;
        }

        .container {
            display: flex;
            justify-content: flex-start;
        }

        .nav {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        .nav li {
            position: relative;
        }

        .nav > li > a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
        }

        .nav > li > a:hover {
            background-color: #555;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #444;
            list-style: none;
            padding: 0;
            margin: 0;
            width: 150px;
            border-radius: 0 0 5px 5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }

        .dropdown-menu li a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
        }

        .dropdown-menu li a:hover {
            background-color: #666;
        }

        .nav li:hover > .dropdown-menu {
            display: block;
        }

        .has-dropdown > a::after {
            content: '\25BE'; /* Unicode down arrow */
            margin-left: 5px;
        }
    </style>
</head>

<body>
<div class="background-container">
        <img src="http://localhost/duan01/duan01img/LOGO-removebg-preview.png" alt="TaTua Tea" class="logo">
    </div>
    <nav class="navbar">
        <div class="container">
            <ul class="nav">
                <li><a href="#">HOME</a></li>
                <li class="has-dropdown">
                    <a href="index.php">INTRODUCE</a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Our Story</a></li>
                        <li><a href="#">Our Mission</a></li>
                    </ul>
                </li>
                <li class="has-dropdown">
                    <a href="adduser.php">PRODUCT</a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Milk Tea</a></li>
                        <li><a href="#">Toppings</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</body>

</html>
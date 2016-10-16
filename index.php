<?php

include('dbconnect.php');
print_r($_POST);
if (isset($_POST['submit']))
{

    $errormessage = "";

     if (isset($_POST['username']) && isset($_POST['password']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $conn = setUpConnection();

        $sql = "SELECT username, password FROM users
                WHERE username='" . $username ."' AND
                        password='" .  $password . "' ";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            header("Location: upload.php");

        } else {
            echo "0 results";
            $errormessage = "Invalid username and/or password";
        }

        $conn->close();        

    }    
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sort It!</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/landing-page.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
       <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Sortify</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="links/about.php">About</a>
                    </li>
<!--                     <li>
                        <a href="#">Contact</a>
                    </li> -->
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>


    <!-- Header -->
    <a name="about"></a>
    <div class="main-Container">
        <div>
            <h1>Sortify</h1>
                        <h3>Albums for Everyone</h3>
                        <ul class="list-inline intro-social-buttons">
                            <li>
                            <h3><?php if (isset($errormessage)) echo $errormessage;?></h3>
                                <form action="index.php" method="post">
                                            
                                                <input type="text" name="username" placeholder="username" class="btn btn-default btn-lg">
                
                            </li>
                            
                            <li>
                                <!-- <form action="#"> -->
                                <input type="password" name="password" placeholder="*******" class="btn btn-default btn-lg">
                                
                                </li>
                            <li>
        <!--                         <a href="#" name="submit" class="btn btn-default btn-lg"> <span class="network-name">Log in</span></a> -->
                                       <input type="submit" name="submit" class="btn btn-default btn-lg" value="Log in">
                            </li>
                            </form>
                        </ul>
                        <p>Don't have an account? <a href="./links/signup.php"  id="g2"> Create One </a></p>
            
        </div>

           
                        
    </div>
        <!-- /.container -->
    <!-- /.intro-header -->


	<a  name="contact"></a>

    <!-- Footer -->
    <footer>
        <div class="container">
            
                
                    <ul class="list-inline">
                        <li>

                            <a href="index.html">Home</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                             <a href="../links/signup.html#s1">Sign up</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                            <a href="../links/about.html#s2">About Us</a>
                        </li>
                        
                    </ul>
                    <p class="copyright text-muted small">Copyright &copy; Sortify 2016. All Rights Reserved</p>
                
            
        </div>
    </footer>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>

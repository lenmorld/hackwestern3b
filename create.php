<?php 

$username = 'Lenny';
include('dbconnect.php');



if (isset($_POST['categories']))
{
    print_r($_POST['categories']);

    $categories = array();

    foreach ($_POST['categories'] as $check) {
        array_push($categories, $check);
    }
}
else
{
    $conn2 = setUpConnection();
    $sql2 = "SELECT * FROM keywords
            ORDER BY tally DESC LIMIT 10";
    // $sql2 = "SELECT * FROM keywords";
    $result2 = $conn2->query($sql2);

    print_r($result2);

    $top_categories = array();

    if ($result2->num_rows > 0) {
         while($row = $result2->fetch_assoc()) {
               array_push($top_categories, $row["keyword"]);
        }
    }
    print_r($top_categories);
    $conn2->close(); 

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
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
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

            <form action="create.php" method="post">
            <?php
                if (isset($top_categories)) {

                foreach ($top_categories as  $value) {
                echo '
                     <div class="checkbox">
                      <label><input type="checkbox" name="categories[]" value="' . $value .'">' . $value .'</label>
                    </div>
                ';
                }
            }
            ?>

            <input type="submit" value="SUBMIT"/>

            </form>
        </div>

        <div>
        <?php 
            if (isset($categories)) {
                foreach ($categories as  $value) {
                    echo "<button class='btn btn-primary'>" . $value . "<div>";
                }
            }
        ?>

        </div>

           
                        
    </div>


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

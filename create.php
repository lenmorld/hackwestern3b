<?php 

$username = 'Lenny';
include('dbconnect.php');


if (isset($_POST['categories']))
{
    // print_r($_POST['categories']);
    $categories = array();
    foreach ($_POST['categories'] as $check) {
        array_push($categories, $check);
    }
}
else
{
    $top_categories = array();
    $conn2 = setUpConnection();
    $sql2 = "SELECT * FROM keywords
            ORDER BY tally DESC LIMIT 10";
    // $sql2 = "SELECT * FROM keywords";
    $result2 = $conn2->query($sql2);

    print_r($result2); 

    if ($result2->num_rows > 0) {
         while($row = $result2->fetch_assoc()) {
               array_push($top_categories, $row["keyword"]);
        }
    }
    // print_r($top_categories);
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
                <a class="navbar-brand" href="create.php">Sortify</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="links/about.php">About</a>
                    </li>
                    <li>
                        <a href="create.php">Create</a>
                    </li>
<!--                     <li>
                        <a href="#">Contact</a>
                    </li> -->
                    <li>
                        <a href="upload.php">Upload</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Header -->
    <a name="about"></a>
    <!-- <hr /> -->
    <div class="main-Container">
        <div>
            <h1>Sortify</h1>
            <h3>Albums for Everyone</h3>
            <hr/>
            
            <?php
                if (isset($top_categories)) {

                echo '<form action="create.php" method="post">';
                foreach ($top_categories as  $value) {
                echo '
                     <div class="checkbox">
                      <label><strong><input type="checkbox" name="categories[]" value="' . $value .'">' . $value .'</strong></label>
                    </div>
                ';
                }
                echo '<input type="submit" value="CREATE ALBUM" class="btn btn-success"/>
                </form>';
            }
            if (isset($categories)) {

                $conn2 = setUpConnection();
                $sql2 = "SELECT category, photo FROM photos
                         WHERE username='" . $username . "' ";
                // $sql2 = "SELECT * FROM keywords";
                $result2 = $conn2->query($sql2);
                // print_r($result2);
                
                $tag_images = array();
                $images1 = array();
                if ($result2->num_rows > 0) {
                     while($row = $result2->fetch_assoc()) {
                            $photo = $row["photo"];
                            $tags = $row["category"];
                            $tags_pack = explode(",", $tags);
                            foreach ($categories as  $cat) {
                                //each category will have a set of images
                                
                                foreach ($tags_pack as  $t) {
                                    if (!empty($t) && ($t == $cat)) {   //match with category and image
                                        array_push($images1, $photo);
                                        $tag_images[$t] = $images1;
                                        // print_r($tag_images);
                                    }
                                }
                                // if (strpos($row["category"],  $cat)  ) {
                                //         $photo = $row["photo"] ;
                                //         $tags = $row["category"];
                                //         $images1[$cat] = $photo;
                                //         // array_push($images1, $value2 );
                                // }    
                            }
                           // $keyword1= $row["keyword"] ;
                           // $tags = $row["categories"];
                           // $images1[$keyword1] = $tags;
                    }
                }
                // print_r($top_categories);
                $conn2->close(); 


                foreach ($tag_images as $tag => $pic_set) {
                    // echo "<hr/>";
                    echo "<div>";
                    echo "<h3>" . $tag ."</h3>";
                    foreach ($pic_set as  $pic) {
                        echo "<img src=./uploads/" . $pic ." width='100px'>";
                    }
                   // $filename = 'uploads\' . $pic;

                    
                    echo "</div>";
                }   
            }
            ?>
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
<!--         <div class="container">      
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
                
            
        </div> -->
    </footer>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>

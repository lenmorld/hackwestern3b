
<?php

// print_r($_POST);
// print_r($_FILES);

$username = 'Lenny';
include('dbconnect.php');


// when uploading files
if (isset($_FILES['files']))
{
    $target_dir = "uploads/";
    $filenameArray = array();
    //$JSONstrArray = array();
    
    foreach ($_FILES['files']['name'] as $f => $name)       // for each image
    {

        $filename = basename($name);

        $target_file = $target_dir . $filename;

        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["files"]["tmp_name"][$f]);
            if($check !== false) {
                // echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                // echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            // echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["files"]["size"][$f] > 50000000) {
            // echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats

        $imageFileType  = strtolower($imageFileType);

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            // echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            // echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["files"]["tmp_name"][$f], $target_file)) {
                // echo "The file ". $filename . " has been uploaded."; 
                //process clarifai

                // $file = 'https://samples.clarifai.com/metro-north.jpg';
                $NGROK = 'http://e088a456.ngrok.io/photoapp';
                $file = $NGROK . '/' . $target_file;
                // $str = `python clarifai1.py $file`;
                 
                // array_push($JSONstrArray, exec('python clarifai1.py ' . $file ));

                $JSONstr1 = exec('python clarifai1.py ' . $file );
                $filenameArray[$filename] = $JSONstr1;
                $keywords = explode(",", $JSONstr1);
                $term = array();
                $final_keywords = array();
                
                foreach ($keywords as $value) {     // for every keyword-package
                    $pieces = explode(":", $value);
                    $keyword = $pieces[0];
                    $keyword = str_replace(" u'", "'", $keyword);
                    $keyword = str_replace("u'", "'", $keyword);
                    $keyword = str_replace("'", "", $keyword);
                    $keyword = str_replace("{", "", $keyword);
                    $accuracy = $pieces[1];

                    if (floatval($accuracy) > 0.97) {
                         $term[$keyword] = $accuracy;     
                    }

                }   // end for every image

                $keywords_string = "";
                // echo "FINAL KEYWORDS:";
                // print_r($term);
                foreach ($term as $key => $value) {
                    // echo "<input type='radio' name='" . "_x_" . $filename . "' class='btn btn-default' value=" . $key  .">" . $key;
                    $keywords_string .= ($key . ',');
                    // echo "KEYWORD:";
                    // echo $keywords_string;

                    //check keyword if exists, add if doesnt
                    $conn2 = setUpConnection();
                    $sql2 = "SELECT tally FROM keywords
                            WHERE keyword='" . $key ."'";
                    $result2 = $conn2->query($sql2);

                    if ($result2->num_rows == 1) {      // keyword already in the database, just add to tally
                        $row2 = $result2->fetch_assoc();
                        $tally = $row2["tally"];
                        // echo "keyword in DB, just add 1";
                        $conn = setUpConnection();
                        //save keyword starting with tally 1
                        $sql = "UPDATE keywords SET tally=" . ($tally+1) . " WHERE keyword='" . $key . "' " ;

                        if ($conn->query($sql) === TRUE) {
                            // echo  $key . " keyword successfully";
                        } else {
                            // echo "Error: " . $sql . "<br>" . $conn->error;
                        }   
                         $conn->close();  
                    } else {
                        // echo "keyword not yet in DB";
                        $conn = setUpConnection();
                        //save keyword starting with tally 1
                        $sql = "INSERT INTO keywords(keyword, tally)
                                VALUES ('" . $key ."',". 1 . ")";

                        if ($conn->query($sql) === TRUE) {
                            // echo  $key . " keyword successfully";
                        } else {
                            // echo "Error: " . $sql . "<br>" . $conn->error;
                        }   
                         $conn->close();  
                    }
                    $conn2->close(); 

                } 

                // print_r($final_keywords);
                $conn3 = setUpConnection();
                //save image with top keywords, saved as a string
                $sql3 = "INSERT INTO photos(photo, username, category)
                        VALUES ('" . $filename ."','". $username ."','" . $keywords_string . "')";
                // echo $sql3;
                if ($conn3->query($sql3) === TRUE) {
                    // echo "New photo added to DB successfully";
                } else {
                    // echo "Error: " . $sql3 . "<br>" . $conn->error;
                }
                $conn3->close();
                //print_r($term);

            } else {
                // echo "Sorry, there was an error uploading your file.";
            }
        }
    } // end for each image
}


// when loading page, must get user's files and display it
if (isset($username)) {

        $userPhotos = NULL;

        $family = array();
        $pet = array();
        $vacation = array();

        $conn = setUpConnection();

        $sql = "SELECT photoid, photo, category FROM photos
                WHERE username='" . $username ."' ";

        $result = $conn->query($sql);
        $categories = array();

        // $fruits = array('red' => array('strawberry','apple'),
        //         'yellow' => array('banana'));

        if ($result->num_rows > 0) {
            $userPhotos = $result;
            // output data of each row
            while($row = $result->fetch_assoc()) {
                if ($row["category"] == 'Family')
                {
                    array_push($family, $row["photo"]);
                }
                else if ($row["category"] == 'Pets')
                {
                    array_push($pet, $row["photo"]);
                }
                else if ($row["category"] == 'Vacation')
                {
                    array_push($vacation, $row["photo"]);
                }
                // echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
            }
        } else {
            echo "0 results";
        }
        $conn->close();  
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
    <title>PhotoSort</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/portfolio-item.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

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
                <a class="navbar-brand" href="#">Home</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="links/about.php">About</a>
                    </li>
<!--                     <li>
                        <a href="#">Contact</a>
                    </li> -->
                    <li>
                        <a href="create.php">Create</a>
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

    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Upload Photos Here
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">

            <div class="col-md-8 photos-Container">
                <!--<img class="img-responsive" src="http://placehold.it/750x500" alt="" id="upload">-->
                <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=350%C3%97150&w=350&h=150" class="photos" id="upload">
                <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=350%C3%97150&w=350&h=150" class="photos">
                 <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=350%C3%97150&w=350&h=150" class="photos">
                 <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=350%C3%97150&w=350&h=150" class="photos">
                 <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=350%C3%97150&w=350&h=150" class="photos">
                 <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=350%C3%97150&w=350&h=150" class="photos">
                 <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=350%C3%97150&w=350&h=150" class="photos">
            </div>

            <div class="col-md-4">

<!--             <form>
             <input type="file" onchange="previewFile()"><br>
             <input type="submit" value="Create Albums">
            </form> -->


 <form enctype="multipart/form-data" action="upload.php" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->


    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="files[]" type="file" value="" onchange="previewFile()" multiple="multiple" accept="image/*"/>
    <input type="submit" value="Create Albums" />
</form>            

<!--                 <h3>Album Suggestions</h3>
                  <ul>
                    <li>Lorem Ipsum</li>
                    <li>Dolor Sit Amet</li>
                    <li>Consectetur</li>
                    <li>Adipiscing Elit</li>
                </ul>
 -->

                <?php 

                if (isset($filenameArray)) {
                    echo "<h4>RAW KEYWORDS</h4>";
                    print_r($filenameArray);

                    $filename_keywords_pair = array();
                    foreach ($filenameArray as $filen => $JSONstr1) {
                            echo "<div class='well'>";
                            echo "<h3>" . $filen ."</h3>";
                                $ctr = 0;
                                $keywords = explode(",", $JSONstr1);
                                $term = array();
                                foreach ($keywords as $value) {
                                    $pieces = explode(":", $value);
                                    $keyword = $pieces[0];
                                    $keyword = str_replace(" u'", "'", $keyword);
                                    $keyword = str_replace("u'", "'", $keyword);
                                    $accuracy = $pieces[1];
                                    $term[$keyword] = $accuracy;   
                                }
                                //print_r($term);
                                $final_keywords = array();

                                //do analysis of the keywords
                                foreach ($term as $key => $value) {
                                    if (floatval($value)>0.97)      // if higher than .97 accuracy, include this
                                    {
                                        array_push($final_keywords, $key);
                                        // echo "<input type='radio' name='" . "_x_" . $filename . "' class='btn btn-default' value=" . $key  .">" . $key;
                                    }
                                }
                                $filename_keywords_pair[$filen] = $final_keywords;    
                                
                            // echo "</div>";            
                    }

                }
                    // if (isset($JSONstrArray)) {

                    //     // echo $JSONstr;
                    //     var_dump($JSONstrArray);

                    //     $all_pics_keywords = array();
                    //     $ctr = 0;

                    //     foreach ($JSONstrArray as $JSONstr1) {
                    //         $keywords = explode(",", $JSONstr1);
                    //         $term = array();
                    //         foreach ($keywords as $value) {
                    //             $pieces = explode(":", $value);
                    //             $keyword = $pieces[0];
                    //             $keyword = str_replace(" u'", "'", $keyword);
                    //             $keyword = str_replace("u'", "'", $keyword);
                    //             $accuracy = $pieces[1];
                    //             $term[$keyword] = $accuracy;   
                    //         }
                    //         print_r($term);


                    //         $final_keywords = array();

                    //         //do analysis of the keywords
                    //         foreach ($term as $key => $value) {
                    //             if (floatval($value)>0.97)      // if higher than .97 accuracy, include this
                    //             {
                    //                 array_push($final_keywords, $key);
                    //             }
                    //         }

                    //         $all_pics_keywords[$ctr] = $final_keywords;
                    //         $ctr += 1;
                    //     }   //end for each term
                    // }

                ?>
 
            </div>

        </div>
        <!-- /.row -->
        <!-- Related Projects Row -->

        <?php 
            // if (isset($all_pics_keywords)) {
            // foreach ($all_pics_keywords as $key => $value) {

            //     echo "<div class='well'>" . $key . "</div>"
                
            //     echo "<button class='btn btn'>" . $key . print_r($value) . "</button>";
            // }
        // }
        ?>

        <div class="row albums-C">
            <div class="col-lg-12">
                <h3 class="page-header">Current Albums</h3>
            </div>
            <h2>Family</h2>
            <?php 
                // loop through categories displaying them
                $folder = 'uploads/';
            foreach ($family as $f_photo) {
                    
                echo '
                <div class="col-sm-3 col-xs-6">
                <h4>Family</h4>
                    <a href="#">
                        <img class="img-responsive portfolio-item" src="' . $folder . $f_photo . '" alt="">
                    </a>
                </div>';
            }
            ?>
            <h2>Pets</h2>
            <?php 
                // loop through categories displaying them
                $folder = 'uploads/';
            foreach ($pet as $f_photo) {
                    
                echo '
                <div class="col-sm-3 col-xs-6">
                <h4>Family</h4>
                    <a href="#">
                        <img class="img-responsive portfolio-item" src="' . $folder . $f_photo . '" alt="">
                    </a>
                </div>';
            }
            ?>
            <h2>Vacation</h2>
            <?php 
                // loop through categories displaying them

                $folder = 'uploads/';
            foreach ($vacation as $f_photo) {
                    
                echo '
                <div class="col-sm-3 col-xs-6">
                <h4>Family</h4>
                    <a href="#">
                        <img class="img-responsive portfolio-item" src="' . $folder . $f_photo . '" alt="">
                    </a>
                </div>';
            }
            ?>

<!-- 
            <div class="col-sm-3 col-xs-6">
            <h4>Family</h4>
                <a href="#">
                    <img class="img-responsive portfolio-item" src="http://www.skorthodontics.com/images/family-home.png" alt="">
                </a>
            </div>

            <div class="col-sm-3 col-xs-6">
            <h4>Vacation</h4>
                <a href="#">
                    <img class="img-responsive portfolio-item" src="https://www.cjmproperty.net/wp-content/uploads/2015/06/vacation.jpg" alt="">
                </a>
            </div>

            <div class="col-sm-3 col-xs-6">
            <h4>Home</h4>
                <a href="#">
                    <img class="img-responsive portfolio-item" src="http://energy.gov/sites/prod/files/styles/borealis_article_hero_respondlarge/public/Wetzel-home.png?itok=Gl6fw2wQ" alt="">
                </a>
            </div>

            <div class="col-sm-3 col-xs-6">
            <h4>Pets</h4>
                <a href="#">
                    <img class="img-responsive portfolio-item" src="https://s-media-cache-ak0.pinimg.com/564x/72/2a/3b/722a3b3e65e990bd3bba7ca5fd9a0af2.jpg" alt="">
                </a>
            </div>
 -->
        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>PhotoSort 2016 &copy;</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <script src="js/index.js"></script>

</body>

</html>

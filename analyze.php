<?php
// echo $_SERVER['PHP_SELF'];
// echo $_SERVER["SERVER_ADDR"];
// echo $_SERVER["DOCUMENT_ROOT"];
$file = 'https://samples.clarifai.com/metro-north.jpg';

// $str = `python clarifai1.py $file`;
$str = exec('python clarifai1.py ' . $file);
echo $str;
?>
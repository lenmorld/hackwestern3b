<?php


$str = "{u'summer': 0.9006376, u'business': 0.76717526, u'family': 0.79327387, u'nature': 0.9361651, u'tree': 0.8830238, u'travel': 0.9380516, u'sky': 0.88870734, u'illustration': 0.86465806, u'tropical': 0.7728292, u'water': 0.8795815, u'no person': 0.94221914, u'vector': 0.8400278, u'design': 0.7759897, u'stripe': 0.8304086, u'outdoors': 0.9292315, u'sun': 0.78976166, u'grass': 0.76745915, u'tourism': 0.7399452, u'landscape': 0.817667, u'architecture': 0.7761498}";

//echo $str;

$keywords = explode(",", $str);
$term = array();

foreach ($keywords as $value) {
	$pieces = explode(":", $value);
	$keyword = $pieces[0];
	$keyword = str_replace(" u'", "'", $keyword);
	$keyword = str_replace("u'", "'", $keyword);
	$accuracy = $pieces[1];
	$term[$keyword] = $accuracy;
}


print_r($term);



// foreach ($term as $key => $value)
// {
// 	echo $key . '=' . $value;


// }
?>
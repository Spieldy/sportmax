<?php
$sports = array(
	'1' => "hockey",
	'2' => "football",
	'3' => "basketball"
 );

$displays = array(
	'1' => "standing", 
	'2' => "calendar",
	'3' => "results",
	'4' => "teams"
);

$state_table = str_split($state);

include 'views/'.$sports[$state_table[0]].'/title.php';
include 'views/'.$sports[$state_table[0]].'/'.$displays[$state_table[1]].'.php';
?>
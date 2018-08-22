<?php
$num1 = 10010319;
$num2 = 1337;

$string = 'Num1: '.$num1.' and Num2: '.$num2; 

if( strstr($string, $num1) && strstr($string, $num2) ) {
    echo 'A';
} else {
    echo 'B';
}

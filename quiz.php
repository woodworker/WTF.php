#!/usr/bin/env php
<?php
require_once 'src/WtfQuiz.php';

$quiz = new WtfQuiz( __DIR__.'/wtfs/' );

$quiz->start();

<?php
require('models/Validators.php');
require('parser/CSVParser.php');

use parser\CSVParser;

$filename = "result.csv";
$parser = new CSVParser($filename);
$parser->readCSVFile();
$parser->createCSVFile();
$parser->createErrorsJson();
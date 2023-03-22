<?php
require('models/Validators.php');
require('parser/CSVParser.php');

use parser\CSVParser;


$parser = new CSVParser();
$parser->readCSVFile("result.csv");
$parser->createCSVFile();
$parser->createErrorsJson();
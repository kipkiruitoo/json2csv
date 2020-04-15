<?php
require_once __DIR__ . '/vendor/autoload.php';

use OzdemirBurak\JsonCsv\File\Json;

ini_set('memory_limit', '-1');
$file_tmp = $_FILES['jsonfile']['tmp_name'];
$file_name = $_FILES['jsonfile']['name'];

move_uploaded_file($file_tmp, "jsons/" . $file_name);
$strJsonFileContents = file_get_contents("jsons/" . $file_name);
// var_dump($strJsonFileContents); // show contents

$array = json_decode($strJsonFileContents, true);

// var_dump($array);
$cleaned = [];
foreach ($array as $a) {
    $arr = $a;
    foreach ($a as $b => $value) {
        if (gettype($b) == "integer") {
            $newkey = "q" . $b;
            $arr[$newkey] = $arr[$b];
            unset($arr[$b]);
        }
    }
    array_push($cleaned, $arr);
}
// print_r($cleaned);
file_put_contents("jsons/cleaned.json", json_encode($cleaned));
// if (move_uploaded_file($file_tmp, "jsons/" . $file_name)) {
echo "Converting";

$json = new Json(__DIR__ . '/jsons/cleaned.json');
// To convert JSON to CSV string
$csvString = $json->convert();
// To set a conversion option then convert JSON to CSV and save
$json->setConversionKey('utf8_encoding', true);
$json->convertAndSave(__DIR__ . '/above.csv');
// To convert JSON to CSV and force download on browser
$json->convertAndDownload();

// }

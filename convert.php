<?php
require_once __DIR__ . '/vendor/autoload.php';

use OzdemirBurak\JsonCsv\File\Json;

// echo getcwd() . "\n";
$destination_path = getcwd() . DIRECTORY_SEPARATOR . 'jsons' . DIRECTORY_SEPARATOR;
if (isset($_FILES['json'])) {
    $errors = array();
    $file_name = $_FILES['json']['name'];
    $file_size = $_FILES['json']['size'];
    $file_tmp = $_FILES['json']['tmp_name'];
    $file_type = $_FILES['json']['type'];
    $RTU = explode('.', $_FILES['json']['name']);
    $EGH = end($RTU);
    $file_ext = strtolower($EGH);

    $target_path = $destination_path . basename($file_name);

    $extensions = array("json");

    if (in_array($file_ext, $extensions) === false) {
        $errors[] = "extension not allowed, please choose a JSON FILE.";
    }

    if ($file_size > 2097152) {
        $errors[] = 'File size must be excately 2 MB';
    }

    if (empty($errors) == true) {
        // echo $file_name;
        if (move_uploaded_file($file_tmp, $target_path)) {

            $strJsonFileContents = file_get_contents($target_path);
            // var_dump($strJsonFileContents); // show contents

            $array = json_decode($strJsonFileContents, true);

            // var_dump($array);
            // $cleaned = [];
            // foreach ($array as $a) {
            //     $arr = $a;
            //     foreach ($a as $b => $value) {
            //         // if (gettype($b) == "integer") {
            //         $newkey = "q" . $b;
            //         $arr[$newkey] = $arr[$b];
            //         unset($arr[$b]);
            //         // }
            //     }
            //     array_push($cleaned, $arr);
            // }

            // $cleaned = rsort($cleaned);
            // print_r($cleaned);
            file_put_contents("jsons/cleaned.json", json_encode($array));
            // if (move_uploaded_file($file_tmp, "jsons/" . $file_name)) {
            // echo "Converting";

            $json = new Json(__DIR__ . '/jsons/cleaned.json');
            // To convert JSON to CSV string
            $csvString = $json->convert();
            // To set a conversion option then convert JSON to CSV and save
            $json->setConversionKey('utf8_encoding', true);
            $json->convertAndSave(__DIR__ . '/above.csv');
            // To convert JSON to CSV and force download on browser
            $json->convertAndDownload();
        } else {
            echo "Ooops that didn't work";
        }
        // echo "Success";
    } else {
        print_r($errors);
    }
}

// if (move_uploaded_file($file_tmp, "jsons/" . $file_name)) {
//     $strJsonFileContents = file_get_contents("jsons/" . $file_name);
//     var_dump($strJsonFileContents); // show contents

//     $array = json_decode($strJsonFileContents, true);

//     // var_dump($array);
//     $cleaned = [];
//     foreach ($array as $a) {
//         $arr = $a;
//         foreach ($a as $b => $value) {
//             if (gettype($b) == "integer") {
//                 $newkey = "q" . $b;
//                 $arr[$newkey] = $arr[$b];
//                 unset($arr[$b]);
//             }
//         }
//         array_push($cleaned, $arr);
//     }
//     // print_r($cleaned);
//     file_put_contents("jsons/cleaned.json", json_encode($cleaned));
//     // if (move_uploaded_file($file_tmp, "jsons/" . $file_name)) {
//     echo "Converting";

//     $json = new Json(__DIR__ . '/jsons/cleaned.json');
//     // To convert JSON to CSV string
//     $csvString = $json->convert();
//     // To set a conversion option then convert JSON to CSV and save
//     $json->setConversionKey('utf8_encoding', true);
//     $json->convertAndSave(__DIR__ . '/above.csv');
//     // To convert JSON to CSV and force download on browser
//     $json->convertAndDownload();
// } else {
//     echo "Ooops that didn't work";
// }


// }

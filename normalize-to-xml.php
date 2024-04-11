<?php

include("config.php");
include("utils.php");
function processFile($fileName, $getHeaders, $getDataByCategory, $key, $value)
{
    $inputFile = fopen($fileName, "r");
    $headers = $getHeaders($inputFile);
    $dom = new DOMDocument("1.0", "UTF-8");
    $dom->preserveWhiteSpace = false; // Ensure that whitespace is not preserved
    $dom->formatOutput = true; // Enable automatic formatting
    $root = $dom->createElement("station");
    $root->setAttribute("id", $key);
    $root->setAttribute("name", $value);
    $dom->appendChild($root);

    while (($line = fgets($inputFile)) !== false) {
        $raw_data = $getDataByCategory($line, $headers);
        $root->setAttribute("geocode", implode(",", [$raw_data["Lat"], $raw_data["Long"]]));

        $record = $dom->createElement("rec");
        $record->setAttribute("ts", $raw_data["TS"]);
        $record->setAttribute("nox", $raw_data["NOx"]);
        $record->setAttribute("no", $raw_data["NO"]);
        $record->setAttribute("no2", $raw_data["NO2"]);

        $root->appendChild($record);
    }
    fclose($inputFile);
    return $dom;
}

function saveXml($dom, $fileName)
{
    $dom->save("data-xml/data-$fileName.xml");
}

foreach (MONITORS as $key => $value) {
    $fileName = "data-csv/data-$key.csv";
    $dom = processFile($fileName, $getHeaders, $getDataByCategory, $key, $value);
    saveXml($dom, $key);
}

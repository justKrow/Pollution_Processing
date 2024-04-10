<?php

include("config.php");
include("utils.php");

function processFile($fileName, $getHeaders, $getDataByCategory)
{
    $inputFile = fopen($fileName, "r");
    $headers = $getHeaders($inputFile);
    $dom = new DOMDocument("1.0", "UTF-8");
    $dom->preserveWhiteSpace = false; // Ensure that whitespace is not preserved
    $dom->formatOutput = true; // Enable automatic formatting
    $root = $dom->createElement("station");
    $dom->appendChild($root);

    while (($line = fgets($inputFile)) !== false) {
        $raw_data = $getDataByCategory($line, $headers);
        $root->setAttribute("id", $raw_data["SiteID"]);
        $root->setAttribute("name", $raw_data["Loc"]);
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
    $dom = processFile($fileName, $getHeaders, $getDataByCategory);
    saveXml($dom, $key);
}

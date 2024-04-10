<?php
date_default_timezone_set("GMT");
ini_set("memory_limit", "512M");
ini_set("max_execution_time", 300);
ini_set("auto_detect_line_endings", true);

include("config.php");
include("utils.php");

$inputFile = fopen('air-quality-data-2003-2022.csv', 'r');

$headers = $getHeaders($inputFile);

$generateCSV = function ($monitors) {
  return array_map(function ($key) {
    return fopen("data-csv/data-$key.csv", "w");
  }, array_keys($monitors));
};

$outputFiles = $generateCSV(MONITORS); // Call the returned closure to open files

$addHeaders = function ($outputFile, $filtered_headers) {
  fputs($outputFile, implode(";", $filtered_headers) . PHP_EOL);
};

array_walk($outputFiles, function ($outputFile) use ($addHeaders) {
  $addHeaders($outputFile, FILTERED_HEADERS);
});


while (($line = fgets($inputFile)) !== false) {
  // Determine the category of the line
  $raw_data = $getDataByCategory($line, $headers);

  if (empty($raw_data["NOx"]) && empty($raw_data["CO"])) {
    continue;
  }

  list($latitude, $longitude) = explode(", ", $raw_data["geo_point_2d"],);

  $data = [
    $raw_data["SiteID"],
    $formatDate($raw_data[$headers[0]]),
    $raw_data["NOx"],
    $raw_data["NO2"],
    $raw_data["NO"],
    $raw_data["PM10"],
    $raw_data["NVPM10"],
    $raw_data["VPM10"],
    $raw_data["NVPM2.5"],
    $raw_data["PM2.5"],
    $raw_data["VPM2.5"],
    $raw_data["CO"],
    $raw_data["O3"],
    $raw_data["SO2"],
    $raw_data["Location"],
    $latitude,
    $longitude
  ];

  fputs(fopen("data-csv/data-" . $raw_data["SiteID"] . ".csv", "a"), implode(";", $data) . PHP_EOL);
}

array_map("fclose", $outputFiles);

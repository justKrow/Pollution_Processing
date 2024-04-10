<?php
date_default_timezone_set("GMT");
ini_set("memory_limit", "512M");
ini_set("max_execution_time", 300);
ini_set("auto_detect_line_endings", true);

include("config.php");

$inputFile = fopen('air-quality-data-2003-2022.csv', 'r');

if (($line = fgets($inputFile)) !== false) {
  $headers = str_getcsv($line, ";");
} else {
  echo "Could not read file";
  exit;
}

$generateCSV = function ($monitors) {
  return array_map(function ($key) {
    return fopen("data/data-$key.csv", "w");
  }, array_keys($monitors));
};

$handles = $generateCSV(MONITORS); // Call the returned closure to open files

$addHeaders = function ($handle, $filtered_headers) {
  fputs($handle, implode(";", $filtered_headers) . PHP_EOL);
};

array_walk($handles, function ($handle) use ($addHeaders) {
  $addHeaders($handle, FILTERED_HEADERS);
});

$getData = function ($line, $headers) {
  $value_array = str_getcsv($line, ";");
  $combined_array = array_combine($headers, $value_array);
  return $combined_array;
};

$formatDate = function ($date) {
  $date_after_timezone_removed = substr($date, 0, 19); #remove time zone
  $timestamp = new DateTime($date_after_timezone_removed);
  return $timestamp->format('M d, Y h:i A');
};

while (($line = fgets($inputFile)) !== false) {
  // Determine the category of the line
  $raw_data = $getData($line, $headers);

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

  fputs(fopen("data/data-" . $raw_data["SiteID"] . ".csv", "a"), implode(";", $data) . PHP_EOL);
}

array_map("fclose", $handles);

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

$closeCSV = function ($handles) {
  foreach ($handles as $handle) {
    fclose($handle);
  }
};

$handles = $generateCSV(MONITORS); // Call the returned closure to open files
if (!$headers) {
  echo "Could not read CSV headers";
  exit;
}

$addHeaders = function ($handle, $filtered_headers) {
  fputs($handle, implode(";", $filtered_headers) . PHP_EOL);
};


foreach ($handles as $handle) {
  if ($handle === false) {
    echo "Could not open file";
    exit;
  }
  $addHeaders($handle, FILTERED_HEADERS);
}

array_walk($handles, function ($handle) use ($addHeaders, $headers) {
  $addHeaders($handle, $headers);
});

array_map("fclose", $handles);

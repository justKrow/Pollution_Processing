<?php

$getHeaders = function ($inputFile) {
  if (($line = fgets($inputFile)) !== false) {
    $headers = str_getcsv($line, ";");
    return $headers;
  } else {
    echo "Could not read file";
    exit;
  }
};

$getDataByCategory = function ($line, $headers) {
  $value_array = str_getcsv($line, ";");
  $combined_array = array_combine($headers, $value_array);
  return $combined_array;
};

$formatDateToTimeStamp = function ($date) {
  $dateTime = new DateTime($date);
  return $dateTime->getTimestamp();
};

$formatTimestampToDate = function ($timestamp) {
  $dateTime = new DateTime('@' . $timestamp);
  return $dateTime->format('Y-m-d H:i:s');
};

$getRecord = function ($id) {
  $xml = simplexml_load_file("data-xml/data-$id.xml");
  foreach ($xml->rec as $record) {
  }
};

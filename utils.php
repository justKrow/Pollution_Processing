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

$formatDate = function ($date) {
  $date_after_timezone_removed = substr($date, 0, 19); #remove time zone
  $timestamp = new DateTime($date_after_timezone_removed);
  return $timestamp->format('M d, Y h:i A');
};

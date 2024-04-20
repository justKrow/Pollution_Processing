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

$getRecords = function ($monitor, $query) {
  $dom = new DOMDocument();
  $dom->load('../data-xml/data-' . $monitor . '.xml');
  if (!$dom) {
    throw new Exception("Failed to load XML file: $monitor .xml");
  }
  $xpath = new DOMXPath($dom);
  return $xpath->query($query);
};

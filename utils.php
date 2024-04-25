<?php

// function to retrieve headers from a CSV file
$getHeaders = function ($inputFile) {
  if (($line = fgets($inputFile)) !== false) {
    $headers = str_getcsv($line, ";");
    return $headers;
  } else {
    echo "Could not read file";
    exit;
  }
};

// function to extract data fields by category from a CSV line
$getDataByCategory = function ($line, $headers) {
  $value_array = str_getcsv($line, ";");
  $combined_array = array_combine($headers, $value_array);
  return $combined_array;
};

// function to format a date string to a Unix timestamp
$formatDateToTimeStamp = function ($date) {
  $dateTime = new DateTime($date);
  return $dateTime->getTimestamp();
};

// function to retrieve records from an XML document using XPath query
$getRecords = function ($monitor, $query) {
  $dom = new DOMDocument();
  $dom->load('../data-xml/data-' . $monitor . '.xml');
  if (!$dom) {
    throw new Exception("Failed to load XML file: $monitor .xml");
  }
  $xpath = new DOMXPath($dom);
  return $xpath->query($query);
};

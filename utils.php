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

$getMonthDate = function ($timestamp) {
  $dateTime = new DateTime('@' . $timestamp);
  return $dateTime->format('m-d');
};

$getHour = function ($timestamp) {
  $dateTime = new DateTime('@' . $timestamp);
  return $dateTime->format('H');
};

$getYear = function ($timestamp) {
  $dateTime = new DateTime('@' . $timestamp);
  return $dateTime->format('Y');
};

$checkValidDate = function ($rec, $formatTimestampToDate) {
  $date = $formatTimestampToDate((int)$rec['ts']);
  if ($date >= new DateTime('2015-01-01') && $date <= new DateTime('2022-12-31')) {
    return true;
  } else {
    return false;
  }
};

<?php

include("../utils.php");
include("../config.php");

$filterDataForLineChart = function ($monitor, $selected_date, $getRecords) {
  $timestampStart = strtotime($selected_date);
  $timestampEnd = strtotime($selected_date . " + 1 day"); // End of the selected day
  $records = $getRecords($monitor, "//rec[@ts >= $timestampStart and @ts < $timestampEnd]");
  return $records;
};

$processDataForChart = function ($filtered_records) {
  foreach ($filtered_records as $record) {
    $hour = date("H", $record->getAttribute("ts"));
    $data[$hour]["no"] = $record->getAttribute("no");
    $data[$hour]["nox"] = $record->getAttribute("nox");
    $data[$hour]["no2"] = $record->getAttribute("no2");
  }
  ksort($data);
  return $data;
};

try {
  $monitor =  $_GET['monitor'] ?? 452;
  $selected_date = $_GET['date_picker'] ?? '2022-01-01';
  $filtered_records = $filterDataForLineChart($monitor, $selected_date, $getRecords);
  $data_for_chart = $processDataForChart($filtered_records);
  echo $data_for_chart;
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}

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
  $data = [];
  foreach ($filtered_records as $record) {
    $hour = date("H", $record->getAttribute("ts"));
    $data[$hour]["no"] = $record->getAttribute("nox");
    $data[$hour]["nox"] = $record->getAttribute("no");
    $data[$hour]["no2"] = $record->getAttribute("no2");
  }
  return $data;
};

try {
  $monitor =  $_GET['monitor'] ?? 203;
  $selected_date = $_GET['selected_date'] ?? '2022-01-01';
  $filtered_records = $filterDataForLineChart($monitor, $selected_date, $getRecords);
  $data_for_chart = $processDataForChart($filtered_records);
  ksort($data_for_chart);
  echo json_encode($data_for_chart);
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}

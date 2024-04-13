<?php

include("../utils.php");
include("../config.php");

$selected_monitor =  $_GET['monitor'];
function loadDataFromXML($file, $getHour)
{
  $data = [];
  $xml = simplexml_load_file($file);
  if ($xml === false) {
    throw new Exception("Failed to load XML file: $file");
  }

  foreach ($xml->rec as $record) {
    $timestamp = (int)$record['ts'];
    $hour = $getHour($timestamp);
    if ($hour == 8) {
      if ($timestamp >= strtotime('2015-01-01') && $timestamp <= strtotime('2022-12-31')) {
        $year_month = date('Y-m', $timestamp);
        $data[$year_month][] = (float)$record['no'];
      }
    }
  }
  return $data;
}

function calculateAverages($data)
{
  $averages = [];
  foreach ($data as $year_month => $records) {
    $averages[$year_month] = round(array_sum($records) / count($records), 2);
  }
  return $averages;
}

function prepareChartData($averages)
{
  $data_points = [];
  foreach ($averages as $year_month => $average) {
    $data_points[] = [
      "year_month" => $year_month,
      "average_NO" => $average
    ];
  }
  return $data_points;
}

try {
  $filtered_records = loadDataFromXML('../data-xml/data-' . $selected_monitor . '.xml', $getHour);
  $averages = calculateAverages($filtered_records);
  $data_for_chart = prepareChartData($averages);
  echo json_encode($data_for_chart);
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}

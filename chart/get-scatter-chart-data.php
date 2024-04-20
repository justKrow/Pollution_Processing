<?php

include("../utils.php");
include("../config.php");


$filterDataForScatterChart = function ($monitor, $getRecords) {
  $timestamp_start = strtotime('2015-01-01');
  $timestamp_end = strtotime('2022-12-31');
  $records = $getRecords($monitor, "//rec[number(@ts) >= $timestamp_start and number(@ts) <= $timestamp_end]");
  $data = [];

  // Process the selected records
  foreach ($records as $record) {
    $timestamp = (int)$record->getAttribute('ts');
    $hour = date('H', $timestamp);
    if ($hour == 8) {
      $year_month = date('Y-m', $timestamp);
      $data[$year_month][] = (float)$record->getAttribute('no');
    }
  }

  return $data;
};


$calculateAverages = function ($data) {
  $averages = [];
  foreach ($data as $year_month => $records) {
    $averages[$year_month] = round(array_sum($records) / count($records), 2);
  }
  return $averages;
};

$prepareChartData = function ($averages) {
  $data_points = [];
  foreach ($averages as $year_month => $average) {
    $data_points[] = [
      "year_month" => $year_month,
      "average_NO" => $average
    ];
  }
  return $data_points;
};

try {
  $monitor =  $_GET['monitor'] ?? 203;
  $selected_date = $_GET['date_picker'] ?? '2022-01-01';
  $filtered_records = $filterDataForScatterChart($monitor, $getRecords);
  $averages = $calculateAverages($filtered_records);
  $data_for_chart = $prepareChartData($averages);
  usort($data_for_chart, function ($a, $b) {
    return strtotime($a['year_month']) - strtotime($b['year_month']);
  });
  echo json_encode($data_for_chart);
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}

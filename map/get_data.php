<?php

include("../config.php");
include("../utils.php");

$filterDataForMap = function ($getRecords) {
  $data = [];

  foreach (MONITORS as $key => $value) {
    $data[$value] = [];
    $geocode = $getRecords($key, "/station/@geocode");
    $data[$value]["geocode"] = $geocode;

    $timestamp_start = strtotime('2015-01-01');
    $timestamp_end = strtotime('2022-12-31');
    $records = $getRecords($key, "//rec[number(@ts) >= $timestamp_start and number(@ts) <= $timestamp_end]");
    $data[$value]["records"] = $records;
  }

  foreach ($data as $key => $value) {
    if (count($value['records']) == 0) {
      unset($data[$key]);
    }
  }

  return $data;
};

$processData = function ($raw_data) {
  foreach ($raw_data as $monitor => $monitor_data) {
    $data[$monitor] = [];
    $data[$monitor]["geocode"] = $monitor_data["geocode"][0]->value;

    foreach ($monitor_data["records"] as $record) {
      $hourly_average = [];
      $timestamp = (int)$record->getAttribute('ts');
      $nox_value = (int)$record->getAttribute('nox');
      $no_value = (int)$record->getAttribute('no');
      $no2_value = (float)$record->getAttribute('no2');
      $pollutants = ["nox" => $nox_value, "no" => $no_value, "no2" => $no2_value];
      $hour_timestamp = date('H', $timestamp);

      foreach ($pollutants as $key => $value) {
        if (!isset($hourly_average[$key][$hour_timestamp])) {
          $hourly_average[$key][$hour_timestamp] = ["total" => 0, "count" => 0];
        }

        $hourly_average[$key][$hour_timestamp]["total"] += $no2_value;
        $hourly_average[$key][$hour_timestamp]["count"]++;

        $data[$monitor][$key][$hour_timestamp] = $hourly_average[$key][$hour_timestamp]["total"] / $hourly_average[$key][$hour_timestamp]["count"];

        ksort($data[$monitor][$key]);
      }
    }
  }
  return $data;
};

try {
  $raw_data = $filterDataForMap($getRecords);
  $processed_data = $processData($raw_data);
  echo json_encode($processed_data);
  // print_r($processed_data);
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}

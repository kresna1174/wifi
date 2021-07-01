<?php
namespace App\Libraries;

class Arr {
  public static function map($data, $index, $value) {
    $result = [];
    foreach ($data as $row) {
      if (is_array($row)) {        
        $result[$row[$index]] = $row[$value];
      } else {
        $result[$row->$index] = $row->$value;
      }
    }
    return $result;
  }
  
  public static function list($data, $value, $label) {
    $result = [];
    foreach($data as $row) {
      if (is_callable($value)) {
        $valueData = $value($row);
      } else {
        if (is_array($row)) {
          $valueData = $row[$value];
        } else {
          $valueData = $row->$value;
        }
      }
      
      if (is_callable($label)) {
        $labelData = $label($row);
      } else {
        if (is_array($row)) {
          $labelData = $row[$label];
        } else {
          $labelData = $row->$label;
        }
      }
      $result[$valueData] = $labelData;
    }
    return $result;
  }
}
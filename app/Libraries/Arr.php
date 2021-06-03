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
}
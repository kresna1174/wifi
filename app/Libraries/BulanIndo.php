<?php
namespace App\Libraries;

class BulanIndo {
  public static function tanggal_indo($tanggal){
    $bulan = array (
      1 =>   'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'November',
      'Desember'
    );
    $pecahkan = explode('-', $tanggal);
   
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
  }

  public static function month_list($placeholder = null, $placeholder_value = ''){
    if($placeholder) {
      $list[$placeholder_value] = $placeholder;
    }
    $lists['01'] = 'Januari';
        $lists['02'] = 'Februari';
        $lists['03'] = 'Maret';
        $lists['04'] = 'April';
        $lists['05'] = 'Mei';
        $lists['06'] = 'Juni';
        $lists['07'] = 'Juli';
        $lists['08'] = 'Agustus';
        $lists['09'] = 'September';
        $lists['10'] = 'Oktober';
        $lists['11'] = 'November';
        $lists['12'] = 'Desember';
        return $lists;
  }

  public static function month_label($placeholder = null, $placeholder_value = ''){
    if($placeholder) {
      $list[$placeholder_value] = $placeholder;
    }
    $lists['01'] = '01';
        $lists['02'] = '02';
        $lists['03'] = '03';
        $lists['04'] = '04';
        $lists['05'] = '05';
        $lists['06'] = '06';
        $lists['07'] = '07';
        $lists['08'] = '08';
        $lists['09'] = '09';
        $lists['10'] = '10';
        $lists['11'] = '11';
        $lists['12'] = '12';
        return $lists;
  }

  public static function year_list($placeholder = null, $placeholder_value = '') {
    if ($placeholder) {
        $lists[$placeholder_value] = $placeholder;
    }
    $lists = array();
    for ($y=date('Y'); $y >= 2015; $y--) {
        $lists[$y] = $y;
    }
    return $lists;
}
}
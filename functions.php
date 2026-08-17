<?php
function format_price($price)
{
  $formatted = number_format($price, 0, '', ' ');
  return $formatted . ' ₽';
}

function get_time_left($date)
{
  date_default_timezone_set('Euroepe/Moscow');
  $finale_date = date_create($date);
  $cur_date = date_create();

  $diff = date_diff($finale_date, $cur_date);
  $formate_diff = date_interval_format($diff, "%d %H %I");
  $arr = explode(" ", $formate_diff);

  $hours = $arr[0] * 24 + $arr[1];
  $minutes = intval($arr[2]);
  $hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
  $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);

  $res[] = $hours;
  $res[] = $minutes;

  return $res;
}

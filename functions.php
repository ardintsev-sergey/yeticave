<?php
function format_price($price)
{
  $formatted = number_format($price, 0, '', ' ');
  return $formatted . ' ₽';
}

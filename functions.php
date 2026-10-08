<?php
function format_price($price)
{
  $formatted = number_format($price, 0, '', ' ');
  return $formatted . ' ₽';
}

function get_time_left($date)
{
  date_default_timezone_set('Europe/Moscow');
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

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return stmt Подготовленное выражение
 */
function db_get_prepare_stmt_version($link, $sql, $data = [])
{
  $stmt = mysqli_prepare($link, $sql);

  if ($stmt === false) {
    $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
    die($errorMsg);
  }

  if ($data) {
    $types = '';
    $stmt_data = [];

    foreach ($data as $key => $value) {
      $type = 's';

      if (is_int($value)) {
        $type = 'i';
      } else if (is_double($value)) {
        $type = 'd';
      }

      if ($type) {
        $types .= $type;
        $stmt_data[] = $value;
      }
    }

    $values = array_merge([$stmt, $types], $stmt_data);
    mysqli_stmt_bind_param(...$values);

    if (mysqli_errno($link) > 0) {
      $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
      die($errorMsg);
    }
  }

  return $stmt;
}


function get_users_data($con)
{
  if (!$con) {
    $error = mysqli_connect_error();
    return $error;
  } else {
    $sql = "SELECT email, user_name FROM users";
    $result = mysqli_query($con, $sql);
    if ($result) {
      $users_data = get_arrow($result);
      return $users_data;
    }
    $error = mysqli_error($con);
    return $error;
  }
}


function get_query_create_user()
{
  return "INSERT INTO users (date_registration, email, user_password, user_name, contacts) VALUES (NOW(), ?, ?, ?, ?)";
}

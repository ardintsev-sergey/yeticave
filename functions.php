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

function get_login($con, $email)
{
  if (!$con) {
    return null;
  }

  $sql = "SELECT id, email, user_name, user_password FROM users WHERE email = ? LIMIT 1";
  $stmt = db_get_prepare_stmt_version($con, $sql, [$email]);

  if (!mysqli_stmt_execute($stmt)) {
    return null;
  }

  $result = mysqli_stmt_get_result($stmt);
  return $result ? mysqli_fetch_assoc($result) : null;
}

function get_count_lots($link, $words)
{
  $sql = "SELECT COUNT(*) FROM lots WHERE MATCH(title, lot_description) AGAINST(?);";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, 's', $words);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  if ($res) {
    $count = mysqli_fetch_assoc($res)["cnt"];
    return $count;
  }
  $error = mysqli_error($con);
  return $error;
}

function get_found_lots($link, $words, $limit, $offset)
{
  $sql = "SELECT lots.id, lots.title, lots.start_price, lots.img, lots.date_finish, categories.name_category FROM lots JOIN categories ON lots.category_id = categories.id WHERE MATCH(title, lot_description) AGAINST(?) ORDER BY date_creation DESC LIMIT $limit OFFSET $offset;";

  $stmt = mysqli_prepare($link, $sql);
  mysqly_stmt_bind_param($stmt, 's', $words);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  if ($res) {
    $goods = get_arrow($res);
    return $goods;
  }
  $error = mysqli_error($con);
  return $error;
}

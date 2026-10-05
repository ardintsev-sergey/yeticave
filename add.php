<?php
require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("init.php");
require_once("models.php");

$categories = get_categories($con);
$categories_id = array_column($categories, 'id');
$errors = [];
$lot = [
  'lot-name' => '',
  'category' => '',
  'message' => '',
  'lot-rate' => '',
  'lot-step' => '',
  'lot-date' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $required = [
    'lot-name' => 'Наименование',
    'category' => 'Категория',
    'message' => 'Описание',
    'lot-rate' => 'Начальная цена',
    'lot-step' => 'Шаг ставки',
    'lot-date' => 'Дата окончания торгов',
  ];

  $rules = [
    'category' => function ($value) use ($categories_id) {
      return validate_category($value, $categories_id);
    },
    'lot-rate' => function ($value) {
      return validate_number($value);
    },
    'lot-step' => function ($value) {
      return validate_number($value);
    },
    'lot-date' => function ($value) {
      return validate_date($value);
    },
  ];

  $post_data = filter_input_array(INPUT_POST, [
    'lot-name' => FILTER_DEFAULT,
    'category' => FILTER_DEFAULT,
    'message' => FILTER_DEFAULT,
    'lot-rate' => FILTER_DEFAULT,
    'lot-step' => FILTER_DEFAULT,
    'lot-date' => FILTER_DEFAULT,
  ]);

  if (is_array($post_data)) {
    foreach ($lot as $field => $default_value) {
      $lot[$field] = trim((string)($post_data[$field] ?? $default_value));
    }
  }

  foreach ($required as $field => $label) {
    if ($lot[$field] === '') {
      $errors[$field] = "Поле «{$label}» нужно заполнить";
      continue;
    }

    if (isset($rules[$field])) {
      $error = $rules[$field]($lot[$field]);
      if ($error !== null) {
        $errors[$field] = $error;
      }
    }
  }

  $image_extension = null;
  $image = $_FILES['lot-img'] ?? null;

  if (!$image || $image['error'] === UPLOAD_ERR_NO_FILE) {
    $errors['lot-img'] = 'Необходимо добавить изображение';
  } elseif ($image['error'] !== UPLOAD_ERR_OK) {
    $errors['lot-img'] = 'Не удалось загрузить изображение';
  } else {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_type = $finfo ? finfo_file($finfo, $image['tmp_name']) : false;

    if ($finfo) {
      finfo_close($finfo);
    }

    $allowed_types = [
      'image/jpeg' => '.jpg',
      'image/png' => '.png',
    ];

    if (!isset($allowed_types[$file_type])) {
      $errors['lot-img'] = 'Неверный формат файла, допустимы JPG и PNG';
    } else {
      $image_extension = $allowed_types[$file_type];
    }
  }

  if (!$errors) {
    $filename = bin2hex(random_bytes(16)) . $image_extension;
    $relative_path = 'uploads/' . $filename;
    $upload_path = __DIR__ . DIRECTORY_SEPARATOR . $relative_path;

    if (!move_uploaded_file($image['tmp_name'], $upload_path)) {
      $errors['lot-img'] = 'Не удалось сохранить изображение';
    } else {
      $sql = get_query_create_lot(2);
      $data = [
        $lot['lot-name'],
        $lot['category'],
        $lot['message'],
        $lot['lot-rate'],
        $lot['lot-step'],
        $lot['lot-date'],
        $relative_path,
      ];
      $stmt = db_get_prepare_stmt_version($con, $sql, $data);
      $res = mysqli_stmt_execute($stmt);

      if ($res) {
        $lot_id = mysqli_insert_id($con);
        header("Location: /lot.php?id=$lot_id");
        exit;
      }

      unlink($upload_path);
      $errors['database'] = 'Не удалось добавить лот. Попробуйте ещё раз';
    }
  }
}

$page_content = include_template('add-lot.php', [
  'categories' => $categories,
  'errors' => $errors,
  'lot' => $lot,
]);

$layout_content = include_template('layout.php', [
  'content' => $page_content,
  'categories' => $categories,
  'title' => 'Добавление лота',
  'user_name' => $user_name,
  'is_auth' => $is_auth,
]);

print($layout_content);

<?php
require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("init.php");
require_once("models.php");

$categories = get_categories($con);
$user = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = filter_input_array(INPUT_POST, [
    'email' => FILTER_DEFAULT,
    'password' => FILTER_DEFAULT,
    'name' => FILTER_DEFAULT,
    'message' => FILTER_DEFAULT
  ], true) ?: [];

  $user['email'] = trim($user['email'] ?? '');
  $user['password'] = $user['password'] ?? '';
  $user['name'] = trim($user['name'] ?? '');
  $user['message'] = trim($user['message'] ?? '');

  $required = ['email', 'password', 'name', 'message'];
  $rules = [
    'email' => function ($value) {
      return validate_email($value);
    },
    'password' => function ($value) {
      return validate_length($value, 6, 8);
    },
    'message' => function ($value) {
      return validate_length($value, 12, 1000);
    }
  ];

  foreach ($required as $field) {
    $value = $user[$field];

    if ($value === '') {
      $errors[$field] = "Поле $field нужно заполнить";
    } elseif (isset($rules[$field])) {
      $errors[$field] = $rules[$field]($value);
    }
  }

  $errors = array_filter($errors);

  if (!$errors) {
    $users_data = get_users_data($con);
    $users_data = is_array($users_data) ? $users_data : [];
    $emails = array_column($users_data, 'email');
    $names = array_column($users_data, 'user_name');

    if (in_array($user['email'], $emails, true)) {
      $errors['email'] = 'Пользователь с таким email уже существует';
    }

    if (in_array($user['name'], $names, true)) {
      $errors['name'] = 'Пользователь с таким именем уже существует';
    }
  }

  if (!$errors) {
    $sql = get_query_create_user();
    $data = [
      $user['email'],
      password_hash($user['password'], PASSWORD_DEFAULT),
      $user['name'],
      $user['message']
    ];
    $stmt = db_get_prepare_stmt_version($con, $sql, $data);

    if (mysqli_stmt_execute($stmt)) {
      header('Location: /login.php');
      exit;
    }

    $errors['form'] = 'Не удалось зарегистрировать пользователя';
  }
}

$page_content = include_template('main-sign-up.php', [
  'categories' => $categories,
  'user' => $user,
  'errors' => $errors
]);

$layout_content = include_template('layout.php', [
  'content' => $page_content,
  'categories' => $categories,
  'title' => 'Регистрация',
  'is_auth' => $is_auth,
  'user_name' => $user_name
]);

print($layout_content);

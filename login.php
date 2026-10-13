<?php
require_once("helpers.php");
require_once("functions.php");
require_once("data.php");
require_once("init.php");
require_once("models.php");

$categories = get_categories($con);
$user_info = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_info = filter_input_array(INPUT_POST, [
    "email" => FILTER_DEFAULT,
    "password" => FILTER_DEFAULT
  ], true) ?: [];

  $email = trim($user_info['email'] ?? '');
  $password = $user_info['password'] ?? '';
  $user_info['email'] = $email;

  if ($email === '') {
    $errors['email'] = 'Поле email нужно заполнить';
  } else {
    $errors['email'] = validate_email($email);
  }

  if ($password === '') {
    $errors['password'] = 'Поле password нужно заполнить';
  }

  $errors = array_filter($errors);

  if (!$errors) {
    $user_data = get_login($con, $email);

    if (!$user_data) {
      $errors['email'] = 'Пользователь с таким email не зарегистрирован';
    } elseif (!password_verify($password, $user_data['user_password'])) {
      $errors['password'] = 'Вы ввели неверный пароль';
    } else {
      session_regenerate_id(true);
      $_SESSION['name'] = $user_data['user_name'];
      $_SESSION['id'] = $user_data['id'];

      header('Location: /index.php');
      exit;
    }
  }
}

$page_content = include_template('main-login.php', [
  'categories' => $categories,
  'user_info' => $user_info,
  'errors' => $errors
]);

$layout_content = include_template('layout.php', [
  'content' => $page_content,
  'categories' => $categories,
  'title' => 'Вход',
  'is_auth' => $is_auth,
  'user_name' => $user_name
]);

print($layout_content);

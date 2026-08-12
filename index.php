<?php
require_once('helpers.php');
require_once('functions.php');
require_once('data.php');

$page_content = include_template(
    'main.php',
    [
        'categories' => $categories,
        'goods' => $goods,
    ]
);

$layout_content = include_template(
    'layout.php',
    [
        'categories' => $categories,
        'content' => $page_content,
        'title' => 'Главная',
    ]
);

print($layout_content);

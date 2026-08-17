<?php
$categories = [
  'boards' => 'Доски и лыжи',
  'attachment' => 'Крепления',
  'boots' => 'Ботинки',
  'clothing' => 'Одежда',
  'tools' => 'Инструменты',
  'others' => 'Разное'
];
$goods = [
  [
    'name' => '2014 Rossignol District Snowboard',
    'project' => $categories['boards'],
    'price' => 10999,
    'img_url' => 'img/lot-1.jpg',
    'expiration' => date('Y-m-d', strtotime('+10 days'))
  ],
  [
    'name' => 'DC Ply Mens 2016/2017 Snowboard',
    'project' => $categories['boards'],
    'price' => 159999,
    'img_url' => 'img/lot-2.jpg',
    'expiration' => date('Y-m-d', strtotime('+1 day'))
  ],
  [
    'name' => 'Крепления Union Contact Pro 2015 года размер L/XLd',
    'project' => $categories['attachment'],
    'price' => 8000,
    'img_url' => 'img/lot-3.jpg',
    'expiration' => date('Y-m-d', strtotime('+3 days'))
  ],
  [
    'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
    'project' => $categories['boots'],
    'price' => 10999,
    'img_url' => 'img/lot-4.jpg',
    'expiration' => date('Y-m-d', strtotime('+1 day'))
  ],
  [
    'name' => 'Куртка для сноуборда DC Mutiny Charocal',
    'project' => $categories['clothing'],
    'price' => 7500,
    'img_url' => 'img/lot-5.jpg',
    'expiration' => date('Y-m-d', strtotime('+1 day'))
  ],
  [
    'name' => 'Маска Oakley Canopy',
    'project' => $categories['others'],
    'price' => 5400,
    'img_url' => 'img/lot-6.jpg',
    'expiration' => date('Y-m-d', strtotime('+1 day'))
  ],
];

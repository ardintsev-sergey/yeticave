<?php
$errors = $errors ?? [];
$lot = $lot ?? [];
$classname = $errors ? 'form--invalid' : '';
$escape = static function ($value) {
  return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};
?>
<form class="form form--add-lot container <?= $classname ?>" action="/add.php" method="post" enctype="multipart/form-data">
  <h2>Добавление лота</h2>
  <div class="form__container-two">
    <?php $classname = isset($errors['lot-name']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item <?= $classname ?>">
      <label for="lot-name">Наименование <sup>*</sup></label>
      <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?= $escape($lot['lot-name'] ?? '') ?>">
      <span class="form__error"><?= $escape($errors['lot-name'] ?? '') ?></span>
    </div>

    <?php $classname = isset($errors['category']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item <?= $classname ?>">
      <label for="category">Категория <sup>*</sup></label>
      <select id="category" name="category">
        <option value="">Выберите категорию</option>
        <?php foreach ($categories as $category): ?>
          <option value="<?= $escape($category['id']) ?>" <?= (string)($lot['category'] ?? '') === (string)$category['id'] ? 'selected' : '' ?>>
            <?= $escape($category['name_category']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <span class="form__error"><?= $escape($errors['category'] ?? '') ?></span>
    </div>
  </div>

  <?php $classname = isset($errors['message']) ? 'form__item--invalid' : ''; ?>
  <div class="form__item form__item--wide <?= $classname ?>">
    <label for="message">Описание <sup>*</sup></label>
    <textarea id="message" name="message" placeholder="Напишите описание лота"><?= $escape($lot['message'] ?? '') ?></textarea>
    <span class="form__error"><?= $escape($errors['message'] ?? '') ?></span>
  </div>

  <?php $classname = isset($errors['lot-img']) ? 'form__item--invalid' : ''; ?>
  <div class="form__item form__item--file <?= $classname ?>">
    <label>Изображение <sup>*</sup></label>
    <div class="form__input-file" id="lot-img-drop">
      <input class="visually-hidden" type="file" id="lot-img" name="lot-img" accept=".jpg,.jpeg,.png">
      <label for="lot-img">Добавить</label>
      <span class="form__file-name" id="lot-img-name" aria-live="polite">Файл не выбран</span>
      <span class="form__file-hint">или перетащите JPG/PNG сюда</span>
    </div>
    <span class="form__error"><?= $escape($errors['lot-img'] ?? '') ?></span>
  </div>

  <div class="form__container-three">
    <?php $classname = isset($errors['lot-rate']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item form__item--small <?= $classname ?>">
      <label for="lot-rate">Начальная цена <sup>*</sup></label>
      <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?= $escape($lot['lot-rate'] ?? '') ?>">
      <span class="form__error"><?= $escape($errors['lot-rate'] ?? '') ?></span>
    </div>

    <?php $classname = isset($errors['lot-step']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item form__item--small <?= $classname ?>">
      <label for="lot-step">Шаг ставки <sup>*</sup></label>
      <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?= $escape($lot['lot-step'] ?? '') ?>">
      <span class="form__error"><?= $escape($errors['lot-step'] ?? '') ?></span>
    </div>

    <?php $classname = isset($errors['lot-date']) ? 'form__item--invalid' : ''; ?>
    <div class="form__item <?= $classname ?>">
      <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
      <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= $escape($lot['lot-date'] ?? '') ?>" autocomplete="off">
      <span class="form__error"><?= $escape($errors['lot-date'] ?? '') ?></span>
    </div>
  </div>

  <?php if (isset($errors['database'])): ?>
    <span class="form__error"><?= $escape($errors['database']) ?></span>
  <?php endif; ?>
  <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
  <button type="submit" class="button">Добавить лот</button>
</form>

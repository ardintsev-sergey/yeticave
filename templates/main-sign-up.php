<nav class="nav">
  <ul class="nav__list container">
    <?php foreach ($categories as $category): ?>
      <li class="nav__item">
        <a href="pages/all-lots.html"><?= htmlspecialchars($category['name_category']) ?></a>
      </li>
    <?php endforeach; ?>
  </ul>
</nav>

<?php $form_class = !empty($errors) ? 'form--invalid' : ''; ?>
<form class="form container <?= $form_class ?>" action="sign-up.php" method="post" autocomplete="off">
  <h2>Регистрация нового аккаунта</h2>

  <?php $item_class = isset($errors['email']) ? 'form__item--invalid' : ''; ?>
  <div class="form__item <?= $item_class ?>">
    <label for="email">E-mail <sup>*</sup></label>
    <input id="email" type="text" name="email" placeholder="Введите e-mail"
      value="<?= htmlspecialchars($user['email'] ?? '') ?>">
    <span class="form__error"><?= htmlspecialchars($errors['email'] ?? '') ?></span>
  </div>

  <?php $item_class = isset($errors['password']) ? 'form__item--invalid' : ''; ?>
  <div class="form__item <?= $item_class ?>">
    <label for="password">Пароль <sup>*</sup></label>
    <input id="password" type="password" name="password" placeholder="Введите пароль">
    <span class="form__error"><?= htmlspecialchars($errors['password'] ?? '') ?></span>
  </div>

  <?php $item_class = isset($errors['name']) ? 'form__item--invalid' : ''; ?>
  <div class="form__item <?= $item_class ?>">
    <label for="name">Имя <sup>*</sup></label>
    <input id="name" type="text" name="name" placeholder="Введите имя"
      value="<?= htmlspecialchars($user['name'] ?? '') ?>">
    <span class="form__error"><?= htmlspecialchars($errors['name'] ?? '') ?></span>
  </div>

  <?php $item_class = isset($errors['message']) ? 'form__item--invalid' : ''; ?>
  <div class="form__item <?= $item_class ?>">
    <label for="message">Контактные данные <sup>*</sup></label>
    <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= htmlspecialchars($user['message'] ?? '') ?></textarea>
    <span class="form__error"><?= htmlspecialchars($errors['message'] ?? '') ?></span>
  </div>

  <?php if ($errors): ?>
    <span class="form__error form__error--bottom">
      <?= htmlspecialchars($errors['form'] ?? 'Пожалуйста, исправьте ошибки в форме.') ?>
    </span>
  <?php endif; ?>

  <button type="submit" class="button">Зарегистрироваться</button>
  <a class="text-link" href="/login.php">Уже есть аккаунт</a>
</form>

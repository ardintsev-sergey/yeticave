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
<form class="form container <?= $form_class ?>" action="login.php" method="post" autocomplete="off">
  <h2>Вход</h2>

  <?php $item_class = isset($errors['email']) ? 'form__item--invalid' : ''; ?>
  <div class="form__item <?= $item_class ?>">
    <label for="email">E-mail <sup>*</sup></label>
    <input id="email" type="text" name="email" placeholder="Введите e-mail"
      value="<?= htmlspecialchars($user_info['email'] ?? '') ?>">
    <span class="form__error"><?= htmlspecialchars($errors['email'] ?? '') ?></span>
  </div>

  <?php $item_class = isset($errors['password']) ? 'form__item--invalid' : ''; ?>
  <div class="form__item form__item--last <?= $item_class ?>">
    <label for="password">Пароль <sup>*</sup></label>
    <input id="password" type="password" name="password" placeholder="Введите пароль">
    <span class="form__error"><?= htmlspecialchars($errors['password'] ?? '') ?></span>
  </div>

  <?php if ($errors): ?>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
  <?php endif; ?>

  <button type="submit" class="button">Войти</button>
</form>

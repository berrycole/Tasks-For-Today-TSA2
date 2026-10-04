<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<section class="section"><div class="container form-container login-container"><p class="eyebrow">Welcome back</p><h1>A fresh start.</h1><p>Sign in to create, edit, and archive tasks.</p>
<?php if ($error): ?><p class="error-summary" role="alert"><?= esc($error) ?></p><?php endif ?>
<form class="editor-form" method="post" action="<?= site_url('login') ?>"><?= csrf_field() ?>
<label for="username">Username</label><input id="username" name="username" autocomplete="username" maxlength="50" required value="<?= esc($username, 'attr') ?>">
<label for="password">Password</label><input type="password" id="password" name="password" autocomplete="current-password" required>
<button class="button button-primary" type="submit">Sign in →</button>
</form><p class="login-note">Just looking around? <a href="<?= site_url('tasks') ?>">Browse the public task list.</a></p></div></section>
<?= $this->endSection() ?>

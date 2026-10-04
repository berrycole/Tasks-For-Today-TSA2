<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Plan your day with Tasks for Today.">
<title><?= esc($title) ?> | Tasks for Today</title>
<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/tasks.css') ?>">
</head>
<body>
<a class="skip-link" href="#main-content">Skip to content</a>
<header class="site-header"><div class="container header-inner">
<a class="brand" href="<?= site_url('/') ?>"><span class="brand-mark" aria-hidden="true">✓</span><span><strong>Tasks for Today</strong><small>Make room for what matters</small></span></a>
<nav class="primary-nav" aria-label="Primary navigation">
<?php foreach (['home' => ['', 'Welcome'], 'tasks' => ['tasks', 'Task List'], 'profile' => ['profile', 'Profile'], 'about' => ['about', 'About']] as $key => [$url, $label]): ?>
<a href="<?= site_url($url) ?>" <?= $activePage === $key ? 'aria-current="page"' : '' ?>><?= esc($label) ?></a>
<?php endforeach ?>
<?php if (session('isLoggedIn') === true): ?>
<form method="post" action="<?= site_url('logout') ?>" class="inline-form"><?= csrf_field() ?><button class="nav-button" type="submit">Sign out</button></form>
<?php else: ?><a href="<?= site_url('login') ?>" <?= $activePage === 'login' ? 'aria-current="page"' : '' ?>>Sign in</a><?php endif ?>
</nav></div></header>
<main id="main-content">
<?php if ($message = session()->getFlashdata('message')): ?><div class="container"><p class="notice" role="status"><?= esc($message) ?></p></div><?php endif ?>
<?= $this->renderSection('content') ?>
</main>
<footer class="site-footer"><div class="container footer-inner"><div><strong>Tasks for Today</strong><p>A little structure. A more focused day.</p></div><p>IT0049 · TSA2 · Built with CodeIgniter</p></div></footer>
</body></html>

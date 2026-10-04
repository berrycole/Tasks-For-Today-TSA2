<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<section class="page-hero"><div class="container"><p class="eyebrow">A place for every next step</p><h1>Task List</h1><p>Browse your schedule and track what is moving forward.</p>
<?php if (session('isLoggedIn') === true): ?><a class="button button-primary" href="<?= site_url('tasks/new') ?>">+ New task</a>
<?php else: ?><a class="button button-secondary" href="<?= site_url('login') ?>">Sign in to manage tasks</a><?php endif ?>
</div></section>
<section class="section"><div class="container"><div class="list-heading"><h2>All tasks</h2><span><?= count($tasks) ?> active records</span></div><?= $this->include('tasks/list') ?></div></section>
<?= $this->endSection() ?>

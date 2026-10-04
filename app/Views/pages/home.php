<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<section class="hero"><div class="container hero-grid"><div>
<p class="eyebrow">Your day, thoughtfully planned</p><h1>Small steps.<br>A clearer today.</h1>
<p class="hero-text">Keep your tasks in one place, focus on what is next, and make progress at your own pace.</p>
<div class="button-row"><a class="button button-primary" href="<?= site_url('tasks') ?>">Explore your tasks →</a><a class="button button-secondary" href="<?= site_url(session('isLoggedIn') === true ? 'tasks/new' : 'login') ?>"><?= session('isLoggedIn') === true ? 'New task' : 'Sign in to manage' ?></a></div>
</div><aside class="hero-panel"><p class="panel-label"><?= esc(date('l, F j')) ?></p><h2>Your daily overview</h2><p class="daily-count"><?= count($tasks) ?></p><p>tasks scheduled for today</p><p>One task at a time is still progress.</p></aside></div></section>
<section class="section"><div class="container"><div class="list-heading"><div><p class="eyebrow">Today’s focus</p><h2>On your agenda</h2></div><a href="<?= site_url('tasks') ?>">View all tasks →</a></div>
<?= $this->include('tasks/list') ?>
</div></section>
<?= $this->endSection() ?>

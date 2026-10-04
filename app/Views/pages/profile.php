<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<section class="section"><div class="container form-container"><p class="eyebrow">Meet the planner</p><h1>Profile</h1>
<?php if ($user): ?><article class="task-card"><span class="profile-avatar" aria-hidden="true"><?= esc(strtoupper(substr($user['full_name'], 0, 1))) ?></span><h2><?= esc($user['full_name']) ?></h2><p>@<?= esc($user['username']) ?></p><p><?= esc($user['email']) ?></p><p>This public demo profile belongs to the first account in the task workspace.</p></article>
<?php else: ?><p>No profile is available yet. Run the demo seeder to create one.</p><?php endif ?>
</div></section>
<?= $this->endSection() ?>

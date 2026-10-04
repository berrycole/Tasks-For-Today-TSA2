<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<section class="section"><div class="container form-container"><a href="<?= site_url('tasks') ?>">← Back to tasks</a><p class="eyebrow form-eyebrow">Make a little progress</p><h1><?= esc($title) ?></h1><p>Give your next step a name and a day.</p>
<?php if ($errors): ?><div class="error-summary" role="alert"><strong>Please correct the highlighted fields.</strong><ul><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul></div><?php endif ?>
<form class="editor-form" method="post" action="<?= site_url(isset($task['id']) ? 'tasks/' . $task['id'] : 'tasks') ?>">
<?= csrf_field() ?>
<label for="title">Task title <span>(required)</span></label>
<input id="title" name="title" value="<?= esc($task['title'], 'attr') ?>" maxlength="150" required <?= isset($errors['title']) ? 'aria-invalid="true"' : '' ?>>
<label for="description">Description <span>(optional)</span></label>
<textarea id="description" name="description" rows="4" maxlength="2000"><?= esc($task['description']) ?></textarea>
<div class="form-columns"><div><label for="task_date">Task date <span>(required)</span></label><input type="date" id="task_date" name="task_date" value="<?= esc($task['task_date'], 'attr') ?>" required <?= isset($errors['task_date']) ? 'aria-invalid="true"' : '' ?>></div>
<div><label for="status">Status</label><select id="status" name="status" required><?php foreach (['pending' => 'Pending', 'in_progress' => 'In progress', 'completed' => 'Completed'] as $value => $label): ?><option value="<?= $value ?>" <?= $task['status'] === $value ? 'selected' : '' ?>><?= $label ?></option><?php endforeach ?></select></div></div>
<div class="button-row"><button class="button button-primary" type="submit"><?= isset($task['id']) ? 'Save changes' : 'Create task' ?></button><a class="button button-secondary" href="<?= site_url('tasks') ?>">Cancel</a></div>
</form></div></section>
<?= $this->endSection() ?>

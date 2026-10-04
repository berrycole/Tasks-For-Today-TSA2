<?php if ($tasks === []): ?>
<div class="empty-state"><h3>A little breathing room</h3><p>No tasks to show here. Add a task to start planning.</p></div>
<?php else: ?><div class="task-grid">
<?php foreach ($tasks as $task): ?>
<article class="task-card">
<div class="task-meta"><span class="task-status status-<?= esc($task['status'], 'attr') ?>"><?= esc(ucwords(str_replace('_', ' ', $task['status']))) ?></span><time datetime="<?= esc($task['task_date'], 'attr') ?>"><?= esc(date('M j, Y', strtotime($task['task_date']))) ?></time></div>
<h3><?= esc($task['title']) ?></h3><p class="task-description"><?= nl2br(esc($task['description'])) ?></p>
<?php if (session('isLoggedIn') === true): ?>
<div class="task-actions"><a href="<?= site_url('tasks/' . $task['id'] . '/edit') ?>">Edit task</a>
<form method="post" action="<?= site_url('tasks/' . $task['id'] . '/delete') ?>" onsubmit="return confirm('Archive this task? It will be removed from the list, but its record will be kept.');"><?= csrf_field() ?><button class="archive-button" type="submit" aria-label="Archive <?= esc($task['title'], 'attr') ?>">Archive</button></form></div>
<?php endif ?>
</article><?php endforeach ?></div><?php endif ?>

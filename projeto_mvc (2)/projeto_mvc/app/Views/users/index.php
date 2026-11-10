<?php
$pageTitle = isset($title) && is_string($title) ? $title : '';
$userList = isset($users) && is_array($users) ? $users : [];
?>

<h1><?= e($pageTitle) ?></h1>
<ul class="lista">
<?php foreach ($userList as $u): ?>
<?php if (!is_array($u)) continue; ?>
<li>
<a href="<?= url('user/show/' . (int) ($u['id'] ?? 0)) ?>">
<?= e(isset($u['name']) && is_string($u['name']) ? $u['name'] : '') ?>
</a>
(<?= e(isset($u['last_name']) && is_string($u['last_name']) ? $u['last_name'] : '') ?>)
(<?= e(isset($u['email']) && is_string($u['email']) ? $u['email'] : '') ?>)
(<?= e(isset($u['cpf']) && is_string($u['cpf']) ? $u['cpf'] : '') ?>)
(<?= e(isset($u['birth_date']) && is_string($u['birth_date']) ? $u['birth_date'] : '') ?>)
</li>
<?php endforeach; ?>
</ul>

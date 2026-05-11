<?php /* @var $title */?>
<h1><?= e($title) ?></h1>

<p>Welcome to DarkDApp Framework.</p>
<form method="POST" action="/profile">

    <?= csrf() ?>

    <label for="name">name:</label>
    <input name="name" id="name" type="text" autocomplete="given-name" />
    <button type="submit">Save</button>
</form>

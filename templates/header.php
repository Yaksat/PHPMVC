<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Мой блог' ?></title>
    <link rel="stylesheet" href="/styles.css">
</head>
<body>

<table class="layout">
    <tr>
        <td colspan="2" class="header">
            Мой блог
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: right">
            <?php if(!empty($user)): ?>
                Привет, <?= $user->getNickname() ?> |
                <a href="/user/logout"> Выйти </a>
                <br>
                <img src="<?= '/uploads/' . $user->getAvatar(); ?>">
                <img src="D:\Programs\OpenServer\domains\PHPMVC\uploads\1636643683QFYW5287.jpg">
                <img src="<?= 'http://127.0.0.1:8080/../uploads/' . $user->getAvatar(); ?>">
                <img src="<?= 'http://phpmvc/uploads/' . $user->getAvatar(); ?>">
                <img src="/uploads/1636816953QFYW5287.jpg">
            <?php $y = '/uploads/' . $user->getAvatar() ?>
                <img src="/uploads/<?= $user->getAvatar() ?>">
                <img src="<?= $y ?>">
                <img src="<?= __DIR__ ?>/uploads/1.jpg">
            <?php else: ?>
                <a href="/users/login"> Войти</a> |
                <a href="/users/register"> Зарегистрироваться </a>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>
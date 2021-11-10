<?php include __DIR__ . '/../header.php'; ?>
    <h1>Админка - статьи</h1>
    <a href="/admin/comments">(Админка-комментарии)</a>
    <?php foreach ($articles as $article): ?>
        <h2><a href="/articles/<?= $article->getId() ?>"> <?= $article->getName() ?></a></h2>
        <p><?= $article->getText() ?></p>
        <p><a href="/articles/<?= $article->getId(); ?>/edit">Редактировать</a></p>
        <hr>
    <?php endforeach; ?>
<?php include __DIR__ . '/../footer.php'; ?>
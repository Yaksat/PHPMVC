<?php include __DIR__ . '/../header.php'; ?>
    <h1>Админка - комментарии</h1>
    <a href="/admin/articles">(Админка-статьи)</a>
    <?php if ($comments !== null): ?>
        <?php foreach ($comments as $comment): ?>
            <h4 id="comment<?= $comment->getId(); ?>"><?= $comment->getUser()->getNickname() . ' ' . $comment->getCreatedAt() ?></h4>
            <p><?= $comment->getText() ?></p>
            <p><a href="/comments/<?= $comment->getId(); ?>/edit">Редактировать</a></p>
            <p><a href="/articles/<?= $comment->getArticleId()?>#comment<?=$comment->getId()?>">Показать на странице</a></p>
        <?php endforeach; ?>
    <?php endif; ?>
<?php include __DIR__ . '/../footer.php'; ?>
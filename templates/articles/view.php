<?php include __DIR__ . '/../header.php'; ?>
    <h1><?= $article->getName() ?></h1>
    <p><?= $article->getText() ?></p>
    <p>Автор: <?= $article->getAuthor()->getNickname() ?></p>
    <?php if ($isEditable): ?>
        <p><a href="/articles/<?= $article->getId(); ?>/edit">Редактировать</a></p>
    <?php endif; ?>
    <br>
    <h3>Комментарии</h3>
    <?php if ($user !== null): ?>
        <form action="/articles/<?= $article->getId(); ?>/comments" method="post">
            <label for="comment">Новый комментарий</label><br>
            <textarea name="comment" id="comment" rows="10" cols="80"></textarea><br>
            <?php if (!empty($error)): ?>
                <div style="color: red;"><?= $error ?></div>
            <?php endif; ?>
            <input type="submit" value="Отправить">
        </form>
    <?php else: ?>
        <p>Для добавления комментария зарегистрируйтесь</p>
    <?php endif; ?>
    <?php foreach ($comments as $comment): ?>
        <?php if ($comment->getArticleId() === $article->getId()): ?>
            <h4><?= $comment->getUser()->getNickname() . ' ' . $comment->getCreatedAt() ?></h4>
            <p><?= $comment->getText() ?></p>
        <?php endif; ?>
    <?php endforeach; ?>
<?php include __DIR__ . '/../footer.php'; ?>
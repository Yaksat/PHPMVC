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
    <?php if ($comments !== null): ?>
        <?php foreach ($comments as $comment): ?>
            <h4 id="comment<?= $comment->getId(); ?>"><?= $comment->getUser()->getNickname() . ' ' . $comment->getCreatedAt() ?></h4>
            <p><?= $comment->getText() ?></p>
            <?php if ($user !== null): ?>
                <?php if (($user->getId() === $comment->getUserId()) || $user->isAdmin()): ?>
                    <p><a href="/comments/<?= $comment->getId(); ?>/edit">Редактировать</a></p>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
<?php include __DIR__ . '/../footer.php'; ?>
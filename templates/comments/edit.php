<?php include __DIR__ . '/../header.php'; ?>
    <h1>Редактирование комментария</h1>
    <form action="/comments/<?=$comment->getId();?>/edit" method="post">
        <label for="comment">Комментарий</label>
        <textarea name="comment" id="comment" rows="10" cols="80"><?=$comment->getText()?></textarea>
        <?php if (!empty($error)): ?>
            <div style="color: red;"><?= $error ?></div>
        <?php endif; ?>
        <input type="submit" value="Обновить">
    </form>
<?php include __DIR__ . '/../footer.php'; ?>

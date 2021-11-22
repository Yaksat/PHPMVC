<?php include __DIR__ . '/../header.php'; ?>
    <?php foreach ($articles as $article): ?>
        <h2><a href="/articles/<?= $article->getId() ?>"> <?= $article->getName() ?></a></h2>
        <p><?= $article->getParsedText() ?></p>
        <hr>
    <?php endforeach; ?>

    <div style="text-align: center">
        <?php if ($previousPageLink !== null): ?>
            <a href="<?= $previousPageLink ?>">&lt; Туда</a>
        <?php else: ?>
            <span style="color: gray">&lt; Туда</span>
        <?php endif; ?>
        &nbsp;&nbsp;&nbsp;
        <?php if ($nextPageLink !== null): ?>
            <a href="<?= $nextPageLink ?>">Сюда &gt;</a>
        <?php else: ?>
            <span style="color: gray">Сюда &gt;</span>
        <?php endif; ?>
    </div>

    <?php if ($user !== null): ?>
        <a href="/articles/add">Добавить статью</a>
    <?php endif; ?>
<?php include __DIR__ . '/../footer.php'; ?>
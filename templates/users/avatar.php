<?php include __DIR__ . '/../header.php'; ?>
    <h1>Настройка аватара</h1>
    <form enctype="multipart/form-data" action="/user/avatar" method="POST">
        <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
        <input type="hidden" name="MAX_FILE_SIZE" value="90000" />
        <!-- Название элемента input определяет имя в массиве $_FILES -->
        Выберите аватар: <input name="userfile" type="file" />
        <?php if (!empty($error)): ?>
            <div style="color: red;"><?= $error ?></div>
        <?php elseif (!empty($userFileName)): ?>
            <div style="color: green;">Файл <?= $userFileName ?> загружен</div>
        <?php endif; ?>
        <br>
        <input type="submit" value="Выбрать" >
        <input type="reset" value="Отменить">
    </form>
    <br>
    <form action="/user/defaultAvatar">
        <input type="submit" value="Установить аватар по умолчанию">
    </form>
<?php include __DIR__ . '/../footer.php'; ?>
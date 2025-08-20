<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <?php include dirname(__DIR__) . '/layouts/header.php'; ?>

    <main>
        <?php require $contentView; ?>
    </main>

    <?php include dirname(__DIR__) . '/layouts/footer.php'; ?>

</body>

</html>
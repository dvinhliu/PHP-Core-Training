<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'PHP Training'; ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <container class="flex flex-col min-h-screen bg-gray-50 font-sans">
        <?php include dirname(__DIR__) . '/layouts/header.php'; ?>

        <?php require $contentView; ?>

        <?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
    </container>
</body>

</html>
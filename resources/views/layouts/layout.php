<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'PHP Training'; ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <div class="flex flex-col min-h-screen bg-gray-50 font-sans">
        <?php include dirname(__DIR__) . '/layouts/header.php'; ?>

        <?php require $contentView; ?>

        <?php include dirname(__DIR__) . '/layouts/footer.php'; ?>
    </div>

    <!-- Toast -->
    <div id="toast" class="fixed top-5 right-5 max-w-sm w-full bg-white shadow-lg rounded-lg border border-gray-200 p-4 flex items-start space-x-3 transform transition-all duration-300 translate-x-full opacity-0">
        <div class="flex-1">
            <strong id="toast-title" class="block font-semibold text-gray-800"></strong>
            <p id="toast-message" class="text-sm text-gray-600"></p>
        </div>
        <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600 cursor-pointer">✕</button>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <script>
            window.onload = function() {
                showToast("✅ Thành công", "<?php echo addslashes($_SESSION['success']); ?>");
            };
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['errors']['general'])): ?>
        <script>
            window.onload = function() {
                showToast("❌ Lỗi", "<?php echo addslashes($_SESSION['errors']['general']); ?>");
            };
        </script>
        <?php unset($_SESSION['errors']['general']); ?>
    <?php endif; ?>

    <script>
        function showToast(title, message) {
            document.getElementById("toast-title").innerText = title;
            document.getElementById("toast-message").innerText = message;

            const toast = document.getElementById("toast");
            toast.classList.remove("translate-x-full", "opacity-0");
            toast.classList.add("translate-x-0", "opacity-100");

            setTimeout(hideToast, 2500);
        }

        function hideToast() {
            const toast = document.getElementById("toast");
            toast.classList.add("translate-x-full", "opacity-0");
            toast.classList.remove("translate-x-0", "opacity-100");
        }
    </script>
</body>

</html>
<div class="flex-1 container my-8 mx-auto min-h-screen">
    <div class="text-center font-semibold text-6xl mb-8">Xin chào <?= htmlspecialchars($_SESSION['user_name']) ?> <span class="text-[#63605F]">đến với APP CRUD</span></div>

    <?php if (!empty($users)): ?>
        <?php include __DIR__ . '/shared/listUser.php'; ?>
    <?php endif; ?>
</div>
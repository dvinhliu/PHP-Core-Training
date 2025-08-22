<?php
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
?>

<div class="flex-1 container mx-auto my-8 min-h-screen">
    <div class="text-center font-semibold text-6xl mb-8">Xin chào <?= htmlspecialchars($_SESSION['user_name']) ?> <span class="text-[#63605F]">đến với APP CRUD</span></div>

    <?php
    // if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'member') {
    include __DIR__ . '/shared/listUser.php';
    // }
    ?>
</div>
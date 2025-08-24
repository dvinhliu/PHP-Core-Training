<?php
echo '<pre>';
print_r($_SESSION);
echo '</pre>';

?>

<div class="flex-1 my-8 flex items-center justify-center">
    <div class="bg-[#FAF7F2] rounded shadow p-6 w-[600px]">
        <!-- Login form -->
        <div class="text-center font-semibold text-3xl mb-10" novalidate>Màn hình đăng nhập</div>
        <form action="<?= $router->route('auth.login.post') ?>" method="POST" class="space-y-4">
            <?= \App\Core\Csrf::tokenField() ?>
            <label for="user_name" class="block text-sm font-medium text-gray-700 p-0 m-0 mb-1">Username</label>
            <input type="text" name="user_name" placeholder="Username" value="<?= htmlspecialchars($old['user_name'] ?? '') ?>" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            <?= showError('user_name') ?>
            <label for="password" class="block text-sm font-medium text-gray-700 p-0 m-0 mb-1">Mật khẩu</label>
            <input type="password" name="password" placeholder="Mật khẩu" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            <?= showError('password') ?>
            <div class="flex justify-between mt-1">
                <div class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2 cursor-pointer">
                    <span class="translate-y-[-1px] block">Ghi nhớ đăng nhập</span>
                </div>
                <a href="<?= $router->route('auth.forgot.password') ?>" class="text-blue-500 cursor-pointer">Quên mật khẩu</a>
            </div>
            <button type="submit" class="w-full p-2 cursor-pointer bg-green-500 text-white rounded uppercase">Đăng nhập</button>
        </form>
    </div>
</div>
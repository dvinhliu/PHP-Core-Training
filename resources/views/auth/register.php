<?php
echo '<pre>';
print_r($_SESSION);
echo '</pre>';

?>

<div class="flex-1 my-8 flex items-center justify-center">
    <div class="bg-[#FAF7F2] rounded shadow p-6 w-[600px]">
        <!-- Login form -->
        <div class="text-center font-semibold text-5xl mb-10" novalidate>Đăng ký</div>
        <form action="<?= $router->route('auth.register.post') ?>" method="POST" class="space-y-4">
            <?= \App\Core\Csrf::tokenField() ?>
            <label for="user_name" class="block text-sm font-medium text-gray-700 p-0 m-0 mb-1">Username</label>
            <input type="text" name="user_name" placeholder="Username" value="<?= htmlspecialchars($old['user_name'] ?? '') ?>" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            <?= showError('user_name') ?>
            <label for="password" class="block text-sm font-medium text-gray-700 p-0 m-0 mb-1">Mật khẩu</label>
            <input type="password" name="password" placeholder="Mật khẩu" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            <?= showError('password') ?>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 p-0 m-0 mb-1">Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            <?= showError('password_confirmation') ?>
            <label for="email" class="block text-sm font-medium text-gray-700 p-0 m-0 mb-1">Email</label>
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            <?= showError('email') ?>
            <label for="role_id" class="block text-sm font-medium text-gray-700 p-0 m-0 mb-1">Vai trò</label>
            <select name="role_id" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="" disabled selected>Chọn vai trò</option>
                <?php foreach (\App\Models\RoleType::registerRoles() as $role): ?>
                    <option value="<?= $role->value ?>"><?= $role->label() ?></option>
                <?php endforeach; ?>
            </select>
            <?= showError('role_id') ?>
            <button type="submit" class="w-full p-2 cursor-pointer bg-green-500 text-white rounded uppercase">Đăng ký</button>
        </form>
    </div>
</div>
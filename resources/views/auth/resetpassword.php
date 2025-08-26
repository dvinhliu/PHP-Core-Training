<div class="flex-1 my-8 flex items-center justify-center">
    <div class="bg-[#FAF7F2] rounded shadow p-6 w-[600px]">
        <div class="text-center font-semibold text-3xl mb-10" novalidate>Màn hình đặt lại mật khẩu</div>
        <form action="<?= $router->route('auth.reset.post') ?>" method="POST" class="space-y-4">
            <?= \App\Core\Csrf::tokenField() ?>
            <label for="password" class="block text-sm font-medium text-gray-700 p-0 m-0 mb-1">Mật khẩu</label>
            <input type="password" name="password" placeholder="Mật khẩu" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            <?= showError('password') ?>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 p-0 m-0 mb-1">Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            <?= showError('password_confirmation') ?>
            <button type="submit" class="w-full p-2 cursor-pointer bg-green-500 text-white rounded uppercase">Đặt lại mật khẩu</button>
        </form>
    </div>
</div>
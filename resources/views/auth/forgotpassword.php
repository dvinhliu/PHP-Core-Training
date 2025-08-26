<div class="flex-1 my-8 flex items-center justify-center">
    <div class="bg-[#FAF7F2] rounded shadow p-6 w-[600px]">
        <!-- Register form -->
        <div class="text-center font-semibold text-3xl mb-10" novalidate>Màn hình quên mật khẩu</div>
        <form action="<?= $router->route('auth.forgot.password.post') ?>" method="POST" class="space-y-4">
            <?= \App\Core\Csrf::tokenField() ?>
            <label for="email" class="block text-sm font-medium text-gray-700 p-0 m-0 mb-1">Email</label>
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            <?= showError('email') ?>
            <button type="submit" class="w-full p-2 cursor-pointer bg-green-500 text-white rounded uppercase">Gửi yêu cầu</button>
        </form>
    </div>
</div>
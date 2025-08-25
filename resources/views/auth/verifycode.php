<?php
echo '<pre>';
print_r($_SESSION);
echo '</pre>';

?>

<div class="flex-1 my-8 flex items-center justify-center">
    <div class="bg-[#FAF7F2] rounded shadow p-6 w-[600px]">
        <!-- Register form -->
        <div class="text-center font-semibold text-3xl mb-10" novalidate>Màn hình xác thực</div>
        <form action="<?= $router->route('auth.verify.post') ?>" method="POST" class="space-y-4">
            <?= \App\Core\Csrf::tokenField() ?>
            <label for="verification_code" class="block text-sm font-medium text-gray-700 p-0 m-0 mb-1">Mã xác thực</label>
            <input type="text" name="verification_code" placeholder="Mã xác thực" value="<?= htmlspecialchars($old['verification_code'] ?? '') ?>" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            <div class="flex justify-end mt-1">
                <a href="<?= $router->route('auth.forgot.password') ?>" class="text-blue-500 cursor-pointer">Quên mật khẩu</a>
            </div>
            <button type="submit" class="w-full p-2 cursor-pointer bg-green-500 text-white rounded uppercase">Xác thực</button>
        </form>
    </div>
</div>
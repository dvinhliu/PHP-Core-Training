<div class="flex-1 my-8 flex items-center justify-center">
    <div class="bg-[#FAF7F2] rounded shadow p-6 w-[600px]">
        <!-- Login form -->
        <div class="text-center font-semibold text-5xl mb-10" novalidate>Màn hình chỉnh sửa</div>
        <div class="flex flex-col-2 gap-3">
            <div class="flex-1">
                <div class="space-y-6">
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700 font-semibold">Username</label>
                        <input type="text" class="w-2/3 border p-2 rounded bg-gray-100" value="<?= htmlspecialchars($user->getUserName()) ?>">
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700 font-semibold">Mật khẩu</label>
                        <input type="password" placeholder="Password" class="w-2/3 border p-2 rounded bg-gray-100">
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700 font-semibold">Xác nhận mật khẩu</label>
                        <input type="password_confirmation" placeholder="Confirm Password" class="w-2/3 border p-2 rounded bg-gray-100">
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700 font-semibold">Email</label>
                        <input type="email" placeholder="Email" class="w-2/3 border p-2 rounded bg-gray-100" value="<?= htmlspecialchars($user->getEmail()) ?>">
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700 font-semibold">Vai trò</label>
                        <?php if ($_SESSION['role_id'] === \App\Models\RoleType::ADMIN->value): ?>
                            <select class="w-2/3 border p-2 rounded bg-gray-100">
                                <?php foreach (\App\Models\RoleType::updateRoles() as $role): ?>
                                    <option value="<?= $role->value ?>" <?= $role->value === $user->getRoleId() ? 'selected' : '' ?>><?= $role->label() ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <input type="text" class="w-2/3 border p-2 rounded bg-gray-100" value="<?= \App\Models\RoleType::tryFromValue($user->getRoleId())->label() ?>" disabled>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end mt-6">
            <a href="<?= $router->route('user.update', ['id' => $user->getId()]) ?>" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Cập nhật</a>
        </div>
    </div>
</div>
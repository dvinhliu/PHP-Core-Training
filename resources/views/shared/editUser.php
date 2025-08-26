<div class="flex-1 my-8 flex items-center justify-center">
    <div class="bg-[#FAF7F2] rounded shadow p-6 w-[600px]">
        <!-- Login form -->
        <div class="text-center font-semibold text-3xl mb-10" novalidate>Màn hình chỉnh sửa</div>
        <form action="<?= $router->route('user.edit.post') ?>" method="POST" class="space-y-4" novalidate>
            <?= \App\Core\Csrf::tokenField() ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars(encode($user->getId())) ?>">
            <input type="hidden" name="updated_at" value="<?= htmlspecialchars($user->getUpdatedAt()) ?>">
            <div class="flex flex-col-2 gap-3">
                <div class="flex-1">
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <label for="user_name" class="w-1/3 text-gray-700 font-semibold">Username</label>
                            <div class="w-2/3 flex flex-col">
                                <input type="text" name="user_name" class="border p-2 rounded bg-gray-100" value="<?= htmlspecialchars($user->getUserName()) ?>">
                                <?= showError('user_name') ?>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label for="password" class="w-1/3 text-gray-700 font-semibold">Mật khẩu</label>
                            <div class="w-2/3 flex flex-col">
                                <input type="password" name="password" placeholder="Password" class="border p-2 rounded bg-gray-100">
                                <?= showError('password') ?>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label for="password_confirmation" class="w-1/3 text-gray-700 font-semibold">Xác nhận mật khẩu</label>
                            <div class="w-2/3 flex flex-col">
                                <input type="password" name="password_confirmation" placeholder="Confirm Password" class="border p-2 rounded bg-gray-100">
                                <?= showError('password_confirmation') ?>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label for="email" class="w-1/3 text-gray-700 font-semibold">Email</label>
                            <div class="w-2/3 flex flex-col">
                                <input type="email" name="email" placeholder="Email" class="border p-2 rounded bg-gray-100" value="<?= htmlspecialchars($user->getEmail()) ?>">
                                <?= showError('email') ?>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label class="w-1/3 text-gray-700 font-semibold">Vai trò</label>
                            <?php if ($_SESSION['role_id'] === \App\Models\RoleType::ADMIN->value): ?>
                                <?php if ($user->getRoleId() === \App\Models\RoleType::GUEST->value): ?>
                                    <input type="text" class="w-2/3 border p-2 rounded bg-gray-100"
                                        value="<?= \App\Models\RoleType::tryFromValue($user->getRoleId())->label() ?>" disabled>
                                    <input type="hidden" name="role_id" value="<?= $user->getRoleId() ?>">
                                <?php else: ?>
                                    <select name="role_id" class="w-2/3 border p-2 rounded bg-gray-100">
                                        <?php foreach (\App\Models\RoleType::updateRoles() as $role): ?>
                                            <option value="<?= $role->value ?>" <?= $role->value === $user->getRoleId() ? 'selected' : '' ?>>
                                                <?= $role->label() ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            <?php else: ?>
                                <input type="text" class="w-2/3 border p-2 rounded bg-gray-100"
                                    value="<?= \App\Models\RoleType::tryFromValue($user->getRoleId())->label() ?>" disabled>
                                <input type="hidden" name="role_id" value="<?= $user->getRoleId() ?>">
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center">
                            <label for="description" class="w-1/3 text-gray-700 font-semibold">Description</label>
                            <div class="w-2/3 flex flex-col">
                                <textarea name="description" placeholder="Description" class="h-[200px] resize-none border p-2 rounded bg-gray-100"><?= htmlspecialchars($user->getDescription()) ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 cursor-pointer">Cập nhật</button>
            </div>
        </form>
    </div>
</div>
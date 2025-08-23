<?php

use App\Models\RoleType;
?>

<div class="flex flex-col min-h-screen p-6 bg-[#FAF7F2]">
    <h2 class="text-3xl text-center font-bold mb-6">Danh sách người dùng</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 bg-white shadow-lg rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left">#</th>
                    <th class="px-6 py-3 text-left">Username</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-3"><?= htmlspecialchars($user->getId()) ?></td>
                        <td class="px-6 py-3"><?= htmlspecialchars($user->getUserName()) ?></td>
                        <td class="px-6 py-3"><?= htmlspecialchars($user->getEmail()) ?></td>
                        <td class="flex justify-center px-6 py-3 space-x-2">
                            <?php if ($_SESSION['role_id'] === RoleType::ADMIN->value): ?>
                                <!-- Admin thao tác với tất cả -->
                                <a href="#" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Description</a>
                                <a href="<?= $router->route('user.edit', ['id' => $user->getId()]) ?>" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</a>
                                <a href="<?= $router->route('user.view', ['id' => $user->getId()]) ?>" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">View</a>
                                <a href="#" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" onclick="return confirm('Xóa người dùng này?')">Delete</a>

                            <?php elseif ($_SESSION['role_id'] === RoleType::MEMBER->value && $_SESSION['user_id'] == $user->getId()): ?>
                                <!-- Member chỉ thao tác với chính mình -->
                                <a href="#" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Description</a>
                                <a href="<?= $router->route('user.edit', ['id' => $user->getId()]) ?>" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</a>
                                <a href="<?= $router->route('user.view', ['id' => $user->getId()]) ?>" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">View</a>
                                <a href="#" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" onclick="return confirm('Xóa tài khoản của bạn?')">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
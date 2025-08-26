<?php

use App\Models\RoleType;
?>
<div class="flex-1 my-8 flex items-center justify-center">
    <div class="bg-[#FAF7F2] rounded shadow p-6 w-[600px]">
        <!-- Login form -->
        <div class="text-center font-semibold text-3xl mb-10" novalidate>Màn hình chi tiết</div>
        <div class="flex flex-col-2 gap-3">
            <div class="flex-1">
                <div class="space-y-6">
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700 font-semibold">Username</label>
                        <div class="w-2/3 border p-2 rounded bg-gray-100"><?= htmlspecialchars($user->getUserName()) ?></div>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700 font-semibold">Email</label>
                        <div class="w-2/3 border p-2 rounded bg-gray-100"><?= htmlspecialchars($user->getEmail()) ?></div>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700 font-semibold">Vai trò</label>
                        <div class="w-2/3 border p-2 rounded bg-gray-100"><?= htmlspecialchars(RoleType::tryFromValue($user->getRoleId())->label()) ?></div>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700 font-semibold">Description</label>
                        <div class="w-2/3 border p-2 rounded bg-gray-100"><?= htmlspecialchars($user->getDescription()) ?: 'N/A' ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end mt-6">
            <a href="<?= $router->route('user.edit', ['id' => encode($user->getId())]) ?>" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 cursor-pointer">Chỉnh sửa</a>
        </div>
    </div>
</div>
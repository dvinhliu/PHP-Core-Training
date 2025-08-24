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
                <?php foreach ($users as $index => $user): ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-3">
                            <?= ($page - 1) * 10 + $index + 1 ?>
                        </td>
                        <td class="px-6 py-3"><?= htmlspecialchars($user->getUserName()) ?></td>
                        <td class="px-6 py-3"><?= htmlspecialchars($user->getEmail()) ?></td>
                        <td class="flex justify-center px-6 py-3 space-x-2">
                            <?php if ($_SESSION['role_id'] === RoleType::ADMIN->value): ?>
                                <!-- Admin thao tác với tất cả -->
                                <a href="#" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Description</a>
                                <a href="<?= $router->route('user.edit', ['id' => encode($user->getId())]) ?>" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</a>
                                <a href="<?= $router->route('user.view', ['id' => encode($user->getId())]) ?>" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">View</a>
                                <a href="?action=delete_confirm&id=<?= encode($user->getId()) ?>"
                                    class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                    Delete
                                </a>


                            <?php elseif ($_SESSION['role_id'] === RoleType::MEMBER->value && $_SESSION['user_id'] == $user->getId()): ?>
                                <!-- Member chỉ thao tác với chính mình -->
                                <a href="#" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Description</a>
                                <a href="<?= $router->route('user.edit', ['id' => encode($user->getId())]) ?>" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</a>
                                <a href="<?= $router->route('user.view', ['id' => encode($user->getId())]) ?>" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">View</a>
                                <a href="?action=delete_confirm&id=<?= encode($user->getId()) ?>"
                                    class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                    Delete
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (!empty($users)): ?>
            <div class="mt-6 flex justify-center space-x-2">
                <!-- Nút Prev -->
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>"
                        class="px-3 py-1 border rounded bg-white hover:bg-gray-100">Prev</a>
                <?php endif; ?>

                <!-- Các số trang -->
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>"
                        class="px-3 py-1 border rounded 
               <?= $i == $page ? 'bg-blue-500 text-white' : 'bg-white hover:bg-gray-100' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <!-- Nút Next -->
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>"
                        class="px-3 py-1 border rounded bg-white hover:bg-gray-100">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>


<?php if ($action === 'delete_confirm' && isset($_GET['id'])): ?>
    <?php
    $userToDelete = null;
    foreach ($users as $u) {
        if ($u->getId() == decode($_GET['id'])) {
            $userToDelete = $u;
            break;
        }
    }
    ?>
    <?php if ($userToDelete): ?>
        <dialog id="deleteDialog" open class="fixed inset-0 z-50 m-auto w-full max-w-md p-6 rounded-lg shadow-2xl">
            <div class="bg-white rounded-lg p-4">
                <h3 class="text-2xl font-semibold mb-4">Xác nhận xóa tài khoản</h3>
                <p class="text-gray-700 mb-6">
                    Bạn có chắc muốn xóa tài khoản
                    <strong><?= htmlspecialchars($userToDelete->getUserName()) ?></strong>
                    này không?
                </p>
                <div class="flex justify-between">
                    <button type="button"
                        onclick="window.location.href='<?= $router->route('user.home') ?>'"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 cursor-pointer">
                        Hủy
                    </button>
                    <form method="POST" action="<?= $router->route('user.delete', ['id' => $_GET['id']]); ?>" novalidate>
                        <?= \App\Core\Csrf::tokenField() ?>
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 cursor-pointer">
                            Xóa
                        </button>
                    </form>
                </div>
            </div>
        </dialog>
    <?php endif; ?>
<?php endif; ?>
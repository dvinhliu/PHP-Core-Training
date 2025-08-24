<?php

use App\Models\RoleType;

$current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$action = $_GET['action'] ?? null;
?>
<header class="flex justify-center items-center py-4 px-6 bg-[#F2EAEA] shadow">
  <?php if (!isset($_SESSION['user_name'])): ?>
    <div class="flex items-center space-x-12">
      <a href="<?= $router->route('auth.login') ?>"
        class="text-xl cursor-pointer <?= ($current == '/login' && $action !== 'logout_confirm') ? 'font-bold text-black' : '' ?>">
        Đăng nhập
      </a>
    </div>
  <?php else: ?>
    <div class="flex items-center space-x-12">
      <a href="<?= $router->route('user.home') ?>"
        class="text-xl cursor-pointer <?= ($current == '/' && $action !== 'logout_confirm') ? 'font-bold text-black' : '' ?>">
        Trang chủ
      </a>
      <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] === RoleType::ADMIN->value): ?>
        <a href="<?= $router->route('auth.register') ?>"
          class="text-xl cursor-pointer <?= ($current == '/register' && $action !== 'logout_confirm') ? 'font-bold text-black' : '' ?>">
          Đăng ký
        </a>
      <?php endif; ?>
      <a href="<?= $current ?>?action=logout_confirm"
        class="text-xl cursor-pointer <?= ($action === 'logout_confirm') ? 'font-bold text-black' : '' ?>">
        Đăng xuất
      </a>
    </div>
  <?php endif; ?>
</header>

<?php if ($action === 'logout_confirm'): ?>
  <dialog open class="fixed inset-0 z-50 m-auto w-full max-w-md p-6 rounded-lg shadow-2xl">
    <div class="bg-white rounded-lg p-4">
      <h3 class="text-2xl font-semibold mb-4">Xác nhận đăng xuất</h3>
      <p class="text-gray-700 mb-6">Bạn có chắc muốn đăng xuất khỏi tài khoản không?</p>
      <div class="flex justify-between">
        <!-- Hủy: quay về URL gốc, giữ bold theo $current -->
        <a href="<?= $current ?>"
          class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 cursor-pointer">
          Hủy
        </a>
        <form method="GET" action="<?= $router->route('auth.logout'); ?>">
          <button type="submit"
            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 cursor-pointer">
            Đăng xuất
          </button>
        </form>
      </div>
    </div>
  </dialog>
<?php endif; ?>
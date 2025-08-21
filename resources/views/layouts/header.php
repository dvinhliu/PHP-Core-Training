<header class="flex justify-center items-center py-4 px-6 bg-[#F2EAEA] shadow">
  <div class="flex items-center space-x-12">
    <a href="<?= $router->route('auth.home') ?>" class="text-xl cursor-pointer">Trang chủ</a>
    <a href="<?= $router->route('auth.login') ?>" class="text-xl cursor-pointer">Đăng nhập</a>
    <a href="<?= $router->route('auth.register') ?>" class="text-xl cursor-pointer">Đăng ký</a>
  </div>
</header>
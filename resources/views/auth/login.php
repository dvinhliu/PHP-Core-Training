<div class="flex-1 flex items-center justify-center">
    <div class="bg-[#FAF7F2] rounded shadow p-6 w-[600px]">
        <!-- Login form -->
        <div class="text-center font-semibold text-5xl mb-10" novalidate>Đăng nhập</div>
        <form action="#" method="POST" class="space-y-4">
            <label for="username" class="block text-sm font-medium text-gray-700 p-0 m-0 mb-1">Username</label>
            <input type="text" name="username" placeholder="Username" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            <label for="password" class="block text-sm font-medium text-gray-700 p-0 m-0 mb-1">Mật khẩu</label>
            <input type="password" name="password" placeholder="Mật khẩu" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            <div class="flex justify-between mt-1">
                <div class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2 cursor-pointer">
                    <span class="translate-y-[-1px] block">Ghi nhớ đăng nhập</span>
                </div>
                <a href="#" class="text-blue-500 cursor-pointer">Quên mật khẩu</a>
            </div>
            <button type="submit" class="w-full p-2 cursor-pointer bg-green-500 text-white rounded uppercase">Đăng nhập</button>
        </form>
    </div>
</div>
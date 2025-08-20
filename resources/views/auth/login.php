<div class="flex-1 flex items-center justify-center">
    <div class="bg-[#FAF7F2] rounded shadow p-6 w-[600px]">
        <!-- Login form -->
        <div class="text-center font-semibold text-6xl mb-10">Login</div>
        <div class="mb-4 p-3 text-red-700 bg-red-100 border border-red-300 rounded">
        </div>
        <form action="#" method="POST" class="space-y-4">
            <input type="email" name="email" placeholder="Email" class="w-full p-2 border border-gray-300 rounded">
            <input type="password" name="password" placeholder="Password" class="w-full p-2 border border-gray-300 rounded">
            <button type="submit" class="w-full p-2 cursor-pointer bg-green-500 text-white rounded">Login</button>
        </form>
        <div class="text-center mt-4">
            <span class="text-gray-600">Don't have an account? </span>
            <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Register</a>
        </div>
    </div>
</div>
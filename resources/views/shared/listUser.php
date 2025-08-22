<div class="flex flex-col min-h-screen p-6 bg-[#FAF7F2]">
    <h2 class="text-3xl text-center font-bold mb-6">Danh sách người dùng</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 bg-white shadow-lg rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">Username</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-3">1</td>
                    <td class="px-6 py-3">user1</td>
                    <td class="px-6 py-3">user1@example.com</td>
                    <td class="flex justify-center px-6 py-3 space-x-2">
                        <a href="#" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Description</a>
                        <a href="#" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</a>
                        <a href="#" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">View</a>
                        <a href="#" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" onclick="return confirm('Xóa người dùng này?')">Delete</a>
                    </td>
                </tr>
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-3">2</td>
                    <td class="px-6 py-3">user2</td>
                    <td class="px-6 py-3">user2@example.com</td>
                    <td class="flex justify-center px-6 py-3 space-x-2">
                        <a href="#" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Description</a>
                        <a href="#" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</a>
                        <a href="#" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">View</a>
                        <a href="#" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" onclick="return confirm('Xóa người dùng này?')">Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
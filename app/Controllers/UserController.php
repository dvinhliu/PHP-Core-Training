<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\RoleType;
use App\Requests\EditRequest;
use App\Core\Csrf;

class UserController extends Controller
{
    public function index()
    {
        // Gọi middleware để đảm bảo login + quyền
        \App\Middleware\AuthMiddleware::check(['view_users']);
        // -> yêu cầu quyền view_users mới vào được

        $perPage = 10;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $perPage;

        $totalUsers = User::countAll();
        $totalPages = (int) ceil($totalUsers / $perPage);

        $users = User::getUsersPaginated($perPage, $offset);

        // Nếu là member → đưa chính mình lên đầu
        if ($_SESSION['role_id'] === RoleType::MEMBER->value) {
            usort($users, function ($a, $b) {
                if ($a->getId() == $_SESSION['user_id']) return -1;
                if ($b->getId() == $_SESSION['user_id']) return 1;
                return 0;
            });
        }

        unset($_SESSION['errors'], $_SESSION['old']);
        // Render view
        $this->view('home', [
            'title'      => 'Trang chủ',
            'users'      => $users,
            'page'       => $page,
            'totalPages' => $totalPages,
            'action'     => $_GET['action'] ?? null
        ]);
    }

    public function page404()
    {
        $this->view('shared/404', ['title' => '404 Không tìm thấy trang']);
    }

    public function viewUser(string $id)
    {
        try {

            $id = decode($id);
            if (!$id) {
                $this->redirect('/404');
            }

            $user = User::getUserById($id);

            if (!$user) {
                $this->redirect('/404');
            }

            $this->view('shared/viewUser', [
                'title' => 'Chi tiết người dùng',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            $this->redirect('/404');
        }
    }

    public function editUser(string $id)
    {
        $id = decode($id);

        if (!$id) {
            $this->redirect('/404');
        }

        $user = User::getUserById($id);

        if (!$user) {
            $this->redirect('/404');
        }

        // Lấy errors + old từ session
        $errors = $_SESSION['errors'] ?? [];
        $old    = $_SESSION['old'] ?? [];

        // Xoá luôn để không dính khi reload lại
        unset($_SESSION['old']);

        $this->view('shared/editUser', [
            'title'  => 'Chỉnh sửa người dùng',
            'user'   => $user,
            'errors' => $errors,
            'old'    => $old
        ]);
    }

    public function editUserPost()
    {
        $id = decode($_POST['id']);

        if (!$id) {
            $this->redirect('/404');
        }

        $user = User::getUserById($id);

        if (!$user) {
            $this->redirect('/404');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("editUser/" . encode($id));
        }

        // CSRF check
        if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
            $_SESSION['errors'] = ['general' => 'Token không hợp lệ'];
            $_SESSION['old']    = $_POST;
            $this->redirect("editUser/" . encode($id));
        }

        // Validate dữ liệu
        $request = new EditRequest($_POST, $id);

        if ($request->fails()) {
            $_SESSION['errors'] = $request->errors();
            $_SESSION['old']    = $_POST;
            $this->redirect("editUser/" . encode($id));
        }

        // Update user
        $updatedAt = $_POST['updated_at'] ?? null;

        $success = User::updateUser([
            'id'        => $id,
            'user_name' => $_POST['user_name'],
            'email'     => $_POST['email'],
            'password'  => $_POST['password'],
            'role_id'   => $_POST['role_id'],
            'description' => $_POST['description'],
            'updated_at' => $updatedAt
        ]);

        if ($success) {
            $_SESSION['success'] = 'Chỉnh sửa thành công';

            if ($_SESSION['user_id'] == $id) {
                $this->redirect('/logout');
            }
            $this->redirect('/');
        } else {
            $_SESSION['errors'] = ['general' => 'Dữ liệu này đã bị thay đổi.'];
            $_SESSION['old']    = $_POST;
            $this->redirect("editUser/" . encode($id));
        }
    }

    public function deleteUser(string $id)
    {
        $id = decode($id);

        if (!$id) {
            $this->redirect('/404');
        }

        $user = User::getUserById($id);

        if (!$user) {
            $_SESSION['errors'] = ['general' => 'Xóa không thành công.'];
            $this->redirect('/');
        }

        // CSRF check
        if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
            $_SESSION['errors'] = ['general' => 'Token không hợp lệ'];
            $this->redirect('/');
        }

        // Delete user
        $success = User::deleteUser($id);

        if ($success) {
            $_SESSION['success'] = 'Xóa thành công';
            $this->redirect('/');
        } else {
            $_SESSION['errors'] = ['general' => 'Xóa không thành công'];
            $this->redirect('/');
        }
    }
}

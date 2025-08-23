<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\RoleType;

class UserController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user_name'])) {
            header("Location: /login");
            exit;
        }

        // Quyền xem danh sách
        $canViewUsers = in_array($_SESSION['role_id'], [
            RoleType::ADMIN->value,
            RoleType::MEMBER->value
        ]);

        $users = [];

        if ($canViewUsers) {
            $users = User::getAllUsers();

            // Nếu là member, đưa user của chính nó lên đầu danh sách
            if ($_SESSION['role_id'] === RoleType::MEMBER->value) {
                usort($users, function ($a, $b) {
                    // user login lên đầu
                    if ($a->getId() == $_SESSION['user_id']) return -1;
                    if ($b->getId() == $_SESSION['user_id']) return 1;
                    return 0;
                });
            }
        }

        // Gửi users sang home.php
        $this->view('home', [
            'title' => 'Trang chủ',
            'users' => $users
        ]);
    }

    public function viewUser($id)
    {
        try {
            $user = User::getUserById($id);

            if (!$user) {
                header("HTTP/1.0 404 Not Found");
                echo "User not found";
                exit;
            }

            $this->view('shared/viewUser', [
                'title' => 'Chi tiết người dùng',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function editUser($id)
    {
        $user = User::getUserById($id);

        if (!$user) {
            header("HTTP/1.0 404 Not Found");
            echo "User not found";
            exit;
        }

        $this->view('shared/editUser', [
            'title' => 'Chỉnh sửa người dùng',
            'user' => $user
        ]);
    }
}

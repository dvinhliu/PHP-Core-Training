<?php

namespace App\Models;

use App\Core\Database;   // import class Database
use PDO;
use DateTime;

class User
{
    private $table = 'users';

    private ?int $id;
    private string $user_name;
    private string $email;
    private string $password;
    private int $role_id;
    private int $is_active;
    private ?DateTime $email_verified_at;
    private ?string $reset_token;
    private ?DateTime $reset_token_expires;
    private ?DateTime $created_at;
    private ?DateTime $updated_at;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->user_name = $data['user_name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->role_id = $data['role_id'] ?? 0;
        $this->is_active = $data['is_active'] ?? 1;

        $this->email_verified_at   = !empty($data['email_verified_at']) ? new DateTime($data['email_verified_at']) : null;

        $this->reset_token         = $data['reset_token'] ?? null;
        $this->reset_token_expires = !empty($data['reset_token_expires']) ? new DateTime($data['reset_token_expires']) : null;
        $this->created_at          = !empty($data['created_at']) ? new DateTime($data['created_at']) : null;
        $this->updated_at          = !empty($data['updated_at']) ? new DateTime($data['updated_at']) : null;
    }

    // Getter
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        return $this->user_name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoleId(): int
    {
        return $this->role_id;
    }

    public function isActive(): bool
    {
        return (bool)$this->is_active;
    }

    public function getEmailVerifiedAt(?string $format = 'Y-m-d H:i:s'): ?string
    {
        return $this->email_verified_at?->format($format);
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function getResetTokenExpires(?string $format = 'Y-m-d H:i:s'): ?string
    {
        return $this->reset_token_expires?->format($format);
    }

    public function getCreatedAt(?string $format = 'Y-m-d H:i:s'): ?string
    {
        return $this->created_at?->format($format);
    }

    public function getUpdatedAt(?string $format = 'Y-m-d H:i:s'): ?string
    {
        return $this->updated_at?->format($format);
    }


    public static function getUserByCredentials(string $username, string $password): ?User
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE user_name = :user_name LIMIT 1");
            $stmt->execute(['user_name' => $username]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return null; // user không tồn tại
            }

            if (!password_verify($password, $data['password'])) {
                return null; // mật khẩu không đúng
            }

            return new User($data);
        } catch (\Exception $e) {
            die("Error finding user: " . $e->getMessage());
        }
    }

    public static function getAllUsers(): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM users");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map(fn($item) => new User($item), $data);
        } catch (\Exception $e) {
            die("Error fetching users: " . $e->getMessage());
        }
    }

    public static function getUserById(int $id): ?User
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ? new User($data) : null;
        } catch (\Exception $e) {
            die("Error fetching user: " . $e->getMessage());
        }
    }

    public static function getUsersPaginated(int $limit, int $offset): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM users ORDER BY user_name ASC LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(fn($item) => new User($item), $data);
        } catch (\Exception $e) {
            die("Error fetching paginated users: " . $e->getMessage());
        }
    }

    public static function countAll(): int
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT COUNT(*) FROM users");
            return (int) $stmt->fetchColumn();
        } catch (\Exception $e) {
            die("Error counting users: " . $e->getMessage());
        }
    }


    public static function checkUnique(string $table, string $column, $value): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM $table WHERE $column = :value");
            $stmt->execute(['value' => $value]);
            return $stmt->fetchColumn() === 0;
        } catch (\Exception $e) {
            die("Error checking unique: " . $e->getMessage());
        }
    }

    public static function checkIdExists(string $table, string $column, $value, int $id): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM $table WHERE $column = :value AND id != :id");
            $stmt->execute(['value' => $value, 'id' => $id]);
            return $stmt->fetchColumn() === 0;
        } catch (\Exception $e) {
            die("Error checking ID existence: " . $e->getMessage());
        }
    }

    public static function checkEmailExists(string $email): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            return $stmt->fetchColumn() > 0;
        } catch (\Exception $e) {
            die("Error checking email existence: " . $e->getMessage());
        }
    }

    public static function createUser(array $data): ?User
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO users (user_name, email, password, role_id) VALUES (:user_name, :email, :password, :role_id)");
            $stmt->execute([
                'user_name' => $data['user_name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'role_id' => $data['role_id'],
            ]);

            return new User(array_merge($data, ['id' => $db->lastInsertId()]));
        } catch (\Exception $e) {
            die("Error creating user: " . $e->getMessage());
        }
    }

    public static function updateUser(array $data): bool
    {
        try {
            $db = Database::getConnection();

            // Lấy user hiện tại để giữ nguyên password nếu không nhập mới
            $stmt = $db->prepare("SELECT password FROM users WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $data['id']]);
            $current = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$current) {
                return false; // User không tồn tại
            }

            // Nếu có nhập password mới thì hash lại, ngược lại giữ nguyên
            $password = !empty($data['password'])
                ? password_hash($data['password'], PASSWORD_BCRYPT)
                : $current['password'];

            $stmt = $db->prepare("
            UPDATE users 
            SET user_name = :user_name, 
                email = :email, 
                password = :password, 
                role_id = :role_id, 
                updated_at = NOW() 
            WHERE id = :id
        ");

            return $stmt->execute([
                'user_name' => $data['user_name'],
                'email'     => $data['email'],
                'password'  => $password,
                'role_id'   => $data['role_id'],
                'id'        => $data['id'],
            ]);
        } catch (\Exception $e) {
            die("Error updating user: " . $e->getMessage());
        }
    }
    public static function deleteUser(int $id): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (\Exception $e) {
            die("Error deleting user: " . $e->getMessage());
        }
    }

    public static function setResetToken(string $email, string $token): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE users SET reset_token = :token, reset_token_expires = NOW() + INTERVAL 5 MINUTE WHERE email = :email");
            return $stmt->execute([
                'token' => $token,
                'email' => $email
            ]);
        } catch (\Exception $e) {
            die("Error setting reset token: " . $e->getMessage());
        }
    }

    public static function verifyEmail(string $email, string $token): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE users SET email_verified_at = NOW() WHERE email = :email");
            return $stmt->execute(['email' => $email]);
        } catch (\Exception $e) {
            die("Error verifying email: " . $e->getMessage());
        }
    }
}

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
    private string $description;
    private ?string $reset_token;
    private ?DateTime $reset_token_expires;
    private ?string $remember_token;
    private ?DateTime $remember_expired_at;
    private ?DateTime $created_at;
    private ?DateTime $updated_at;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->user_name = $data['user_name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->role_id = $data['role_id'] ?? 0;
        $this->description = $data['description'] ?? '';
        $this->reset_token         = $data['reset_token'] ?? null;
        $this->reset_token_expires = !empty($data['reset_token_expires']) ? new DateTime($data['reset_token_expires']) : null;
        $this->remember_token      = $data['remember_token'] ?? null;
        $this->remember_expired_at = !empty($data['remember_expired_at']) ? new DateTime($data['remember_expired_at']) : null;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function getResetTokenExpires(?string $format = 'Y-m-d H:i:s'): ?string
    {
        return $this->reset_token_expires?->format($format);
    }

    public function getRememberToken(): ?string
    {
        return $this->remember_token;
    }

    public function getRememberTokenExpires(?string $format = 'Y-m-d H:i:s'): ?string
    {
        return $this->remember_expired_at?->format($format);
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

    public static function getUserByRememberToken(string $rawToken): ?User
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE remember_expired_at > NOW() LIMIT 1");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($data as $row) {
                if (password_verify($rawToken, $row['remember_token'])) {
                    return new User($row);
                }
            }
            return null;
        } catch (\Exception $e) {
            die("Error fetching user by remember token: " . $e->getMessage());
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

    public static function checkVerificationCode(string $email, string $code): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE users SET reset_token = NULL, reset_token_expires = NULL WHERE email = :email AND reset_token = :code AND reset_token_expires > NOW()");
            $stmt->execute(['email' => $email, 'code' => $code]);
            return $stmt->rowCount() > 0;
        } catch (\Exception $e) {
            die("Error checking verification code: " . $e->getMessage());
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

            // Lấy password hiện tại nếu người dùng không nhập mới
            $stmt = $db->prepare("SELECT password FROM users WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $data['id']]);
            $current = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$current) {
                return false;
            }

            $password = !empty($data['password'])
                ? password_hash($data['password'], PASSWORD_BCRYPT)
                : $current['password'];

            // Optimistic Locking bằng updated_at
            $stmt = $db->prepare("
            UPDATE users 
            SET user_name = :user_name, 
                email = :email, 
                password = :password, 
                role_id = :role_id,
                description = :description,
                updated_at = NOW()
            WHERE id = :id AND updated_at = :updated_at
        ");

            $stmt->execute([
                'user_name'  => $data['user_name'],
                'email'      => $data['email'],
                'password'   => $password,
                'role_id'    => $data['role_id'],
                'description' => $data['description'] ?? '',
                'id'         => $data['id'],
                'updated_at' => $data['updated_at'],
            ]);

            return $stmt->rowCount() > 0;
        } catch (\Exception $e) {
            die("Error updating user: " . $e->getMessage());
        }
    }


    public static function updatePasswordByEmail(string $email, string $newPassword): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE users SET password = :password WHERE email = :email");
            return $stmt->execute([
                'password' => password_hash($newPassword, PASSWORD_BCRYPT),
                'email'    => $email
            ]);
        } catch (\Exception $e) {
            die("Error updating password: " . $e->getMessage());
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

    public static function setRememberToken(int $userId, ?string $token, ?string $expires): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE users SET remember_token = :token, remember_expired_at = :expires WHERE id = :id");
            return $stmt->execute([
                'token'   => $token,
                'expires' => $expires,
                'id'     => $userId
            ]);
        } catch (\Exception $e) {
            die("Error setting remember token: " . $e->getMessage());
        }
    }

    public static function getPermissions(int $userId)
    {
        try {
            $db = Database::getConnection();
            $sql = "
            SELECT p.name 
            FROM users u
            JOIN roles r ON u.role_id = r.id
            JOIN role_permission rp ON r.id = rp.role_id
            JOIN permissions p ON rp.permission_id = p.id
            WHERE u.id = ?
        ";

            $stmt = $db->prepare($sql);
            $stmt->execute([$userId]);

            return $stmt->fetchAll(\PDO::FETCH_COLUMN); // trả về mảng ['login', 'view_users', ...]
        } catch (\Exception $e) {
            die("Error getting permissions: " . $e->getMessage());
        }
    }
}

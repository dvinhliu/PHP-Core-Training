<?php

namespace App\Models;

enum RoleType: int
{
    case ADMIN = 1;
    case MEMBER = 2;
    case GUEST = 3;

    public function label(): string
    {
        return match ($this) {
            self::ADMIN  => 'Admin',
            self::MEMBER => 'Member',
            self::GUEST  => 'Guest',
        };
    }

    public static function tryFromValue(int $role): ?self
    {
        return self::tryFrom($role);
    }

    public static function isValidRole(int $role): bool
    {
        return self::tryFrom($role) !== null;
    }

    /**
     * Lấy danh sách role cho phép khi đăng ký
     */
    public static function registerRoles(): array
    {
        return [self::ADMIN, self::MEMBER];
    }

    public static function updateRoles(): array
    {
        return [self::ADMIN, self::MEMBER];
    }
}

<?php

namespace App\Modules\Users\Models;

use CodeIgniter\Model;

class UserRoleModel extends Model
{
    protected $table = 'user_roles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'role_id'];

    public function getRolesByUserIds(array $userIds): array
    {
        if (!$userIds) return [];
        $rows = $this->select('user_roles.user_id, roles.id as role_id, roles.name')
            ->join('roles', 'roles.id = user_roles.role_id')
            ->whereIn('user_roles.user_id', $userIds)
            ->findAll();

        $map = [];
        foreach ($rows as $r) {
            $map[$r['user_id']][] = ['role_id' => $r['role_id'], 'name' => $r['name']];
        }
        return $map;
    }

    public function getRolesByUserId(int $userId): array
    {
        return $this->select('role_id')->where('user_id', $userId)->findAll();
    }

    public function deleteByUser(int $userId): void
    {
        $this->where('user_id', $userId)->delete();
    }

    public function syncUserRoles(int $userId, array $roleIds): void
    {
        $this->deleteByUser($userId);
        $data = [];
        foreach ($roleIds as $rid) {
            $data[] = ['user_id' => $userId, 'role_id' => (int)$rid];
        }
        if ($data) {
            $this->insertBatch($data);
        }
    }
}

<?php

namespace App\Modules\Users\Controllers;

use App\Controllers\BaseController;
use App\Modules\Users\Models\UserModel;
use App\Modules\Users\Models\RoleModel;
use App\Modules\Users\Models\UserRoleModel;
use CodeIgniter\Shield\Models\UserIdentityModel; // dosyanın başına ekleyin
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Entities\User as ShieldUser;

class Users extends BaseController
{
    protected UserModel $users;
    protected RoleModel $roles;
    protected UserRoleModel $userRoles;

    public function __construct()
    {
        $this->users = new UserModel();
        $this->roles = new RoleModel();
        $this->userRoles = new UserRoleModel();
    }

    public function index()
    {
        $all = $this->users->orderBy('id', 'desc')->findAll(); // Entity listesi
        $roles = $this->roles->findAll();

        // Kullanıcı ID'lerini toparla
        $ids = array_map(static fn($e) => $e->id, $all);

        // E-postaları identities tablosundan çek
        $emails = [];
        if (! empty($ids)) {
            $ui = new UserIdentityModel();
            $rows = $ui->select('user_id, secret')
                ->where('type', 'email_password')
                ->whereIn('user_id', $ids)
                ->findAll();

            foreach ($rows as $r) {
                // $r dizi gelebilir; her iki olasılığı da güvene alalım
                $userId = is_array($r) ? (int)$r['user_id'] : (int)$r->user_id;
                $secret = is_array($r) ? $r['secret']       : $r->secret; // email burada
                $emails[$userId] = $secret;
            }
        }

        // Kullanıcıların rol haritası
        $userRoles = $this->userRoles->getRolesByUserIds($ids);

        return view('\App\Modules\Users\Views\Users\index', [
            'title'        => 'Kullanıcılar',
            'users'        => $all,
            'roles'        => $roles,
            'userRolesMap' => $userRoles,
            'emails'       => $emails,
        ]);
    }


    public function new()
    {
        $roles = $this->roles->findAll();
        return view('\App\Modules\Users\Views\Users\new', ['title' => 'Yeni Kullanıcı', 'roles' => $roles]);
    }

    public function create(): RedirectResponse
    {
        $data = [
            'email'    => $this->request->getPost('email'),
            'username' => $this->request->getPost('username') ?: null,
            'password' => $this->request->getPost('password'),
        ];

        $user = new ShieldUser($data);
        $this->users->save($user);
        $id = (int)$this->users->getInsertID();

        $roles = (array)$this->request->getPost('roles');
        $this->userRoles->syncUserRoles($id, $roles);

        return redirect()->to(route_to('admin.users'))->with('message', 'Kullanıcı oluşturuldu.');
    }

    public function edit(int $id)
    {
        $user = $this->users->find($id);
        if (! $user) {
            return redirect()->to(route_to('admin.users'))->with('error', 'Kullanıcı bulunamadı.');
        }
        $roles = $this->roles->findAll();
        $userRoles = $this->userRoles->getRolesByUserId($id);

        // Email'i identities'ten çek
        $ui = new UserIdentityModel();
        $row = $ui->select('secret')
            ->where(['user_id' => $id, 'type' => 'email_password'])
            ->first();
        $email = is_array($row) ? ($row['secret'] ?? '') : ($row->secret ?? '');

        return view('\App\Modules\Users\Views\Users\edit', [
            'title'     => 'Kullanıcı Düzenle',
            'user'      => $user,     // Entity
            'roles'     => $roles,
            'userRoles' => $userRoles,
            'email'     => $email,
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $user = $this->users->find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Kullanıcı bulunamadı.');
        }

        $email    = $this->request->getPost('email');
        $username = $this->request->getPost('username') ?: null;
        $password = $this->request->getPost('password');

        $payload = ['id' => $id, 'email' => $email, 'username' => $username];
        if (!empty($password)) {
            $payload['password'] = $password;
        }

        $entity = new ShieldUser($payload);
        $this->users->save($entity);

        $roles = (array)$this->request->getPost('roles');
        $this->userRoles->syncUserRoles($id, $roles);

        return redirect()->to(route_to('admin.users'))->with('message', 'Kullanıcı güncellendi.');
    }

    public function delete(int $id): RedirectResponse
    {
        $this->users->delete($id);
        $this->userRoles->deleteByUser($id);
        return redirect()->to(route_to('admin.users'))->with('message', 'Kullanıcı silindi.');
    }
}

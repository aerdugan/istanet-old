<?php

namespace App\Modules\Users\Controllers;

use App\Controllers\BaseController;
use App\Modules\Users\Models\RoleModel;
use App\Modules\Users\Models\PermissionModel;

class Roles extends BaseController
{
    protected $roleModel;
    protected $permissionModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->permissionModel = new PermissionModel();
    }

    public function index()
    {
        if (! user_can('Users.Roles.index')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $data['roles'] = $this->roleModel->findAll();
        return view('App\Modules\Users\Views\roles\index', $data);
    }

    public function create()
    {
        if (! user_can('Users.Roles.create')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

//        if (!user_can('Roles.RoleController.create')) {
//            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
//        }
        return view('App\Modules\Users\Views\create');
    }

    public function store()
    {
        if (! user_can('Users.Roles.store')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }


        $this->roleModel->insert([
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to(locale().'/admin/roles')->with('message', 'Rol başarıyla eklendi.');
    }

    public function edit($id)
    {
        if (! user_can('Users.Roles.edit')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }
        $role = $this->roleModel->find($id);

        if (!$role) {
            return redirect()->to(locale().'/admin/roles')->with('error', 'Rol bulunamadı.');
        }

        return view('Modules\Roles\Views\edit', ['role' => $role]);
    }

    public function update($id)
    {
        if (! user_can('Users.Roles.update')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }
        $role = $this->roleModel->find($id);

        if (!$role) {
            return redirect()->to(locale().'/admin/roles')->with('error', 'Rol bulunamadı.');
        }

        $this->roleModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to(locale().'/admin/roles')->with('message', 'Rol başarıyla güncellendi.');
    }



    public function delete($id)
    {
        if (! user_can('Users.Roles.delete')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

//        if (!user_can('Roles.RoleController.delete')) {
//            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
//        }
        $this->roleModel->delete($id);
        return redirect()->to(locale().'/admin/roles')->with('message', 'Rol silindi.');
    }

    public function editPermissions($role_id)
    {
        if (! user_can('Users.Roles.editPermissions')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

//        if (!user_can('Roles.RoleController.editPermissions')) {
//            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
//        }
        $role = $this->roleModel->find($role_id);
        $permissions = $this->permissionModel->findAll();

        $rolePermissionModel = new \App\Modules\Users\Models\RolePermissionModel();
        $assigned = $rolePermissionModel
            ->where('role_id', $role_id)
            ->findAll();

        $assignedPermissions = array_column($assigned, 'permission_id');

        return view('App\Modules\Users\Views\roles\permissions', [
            'role' => $role,
            'permissions' => $permissions,
            'assignedPermissions' => $assignedPermissions,
        ]);
    }

    public function updatePermissions($role_id)
    {
        if (! user_can('Users.Roles.updatePermissions')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

//        if (!user_can('Roles.RoleController.updatePermissions')) {
//            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
//        }
        $permissions = $this->request->getPost('permissions') ?? [];

        $rolePermissionModel = new \App\Modules\Users\Models\RolePermissionModel();

        // Önce eski yetkileri sil
        $rolePermissionModel->where('role_id', $role_id)->delete();

        // Yeni yetkileri ekle
        foreach ($permissions as $permId) {
            $rolePermissionModel->insert([
                'role_id' => $role_id,
                'permission_id' => $permId,
            ]);
        }

        return redirect()->to('/admin/roles')->with('message', 'Yetkiler güncellendi.');
    }

    public function syncPermissions()
    {
        if (! user_can('Users.Roles.syncPermissions')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }
        $permissions = [];

        $modulesPath = ROOTPATH . 'Modules';

        foreach (scandir($modulesPath) as $module) {
            if ($module === '.' || $module === '..') continue;

            $controllerPath = "$modulesPath/$module/Controllers";
            if (!is_dir($controllerPath)) continue;

            foreach (glob($controllerPath . '/*.php') as $file) {
                $className = "App\\Modules\\$module\\Controllers\\" . pathinfo($file, PATHINFO_FILENAME);

                if (!class_exists($className)) require_once($file);
                if (!class_exists($className)) continue;

                $methods = get_class_methods($className);

                foreach ($methods as $method) {
                    $reflect = new \ReflectionMethod($className, $method);

                    if (
                        $reflect->isConstructor() ||
                        !$reflect->isPublic() ||
                        strpos($method, '__') === 0 ||
                        in_array($method, ['initController', 'beforeFilter', 'afterFilter'])
                    ) {
                        continue;
                    }

                    $slug = strtolower($module . '.' . lcfirst((new \ReflectionClass($className))->getShortName()) . '.' . $method);

                    $permissions[] = [
                        'name' => $slug,
                        'description' => ucfirst(str_replace('.', ' ', $slug)),
                    ];
                }
            }
        }

        // Veritabanını güncelle
        $model = new \App\Modules\Users\Models\PermissionModel();
        $model->truncate(); // eski izinleri sil
        $model->insertBatch($permissions); // yenilerini ekle

        return redirect()->to(locale().'/admin/roles')->with('message', count($permissions) . ' permission güncellendi.');
    }

}
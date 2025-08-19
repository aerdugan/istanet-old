<?php

namespace App\Modules\Users\Controllers;

use App\Controllers\BaseController;
use App\Modules\Users\Services\PermissionScanner;

class Permissions extends BaseController
{
    public function scan()
    {
        if (! user_can('Popup.Popup.index')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $prune = (bool) $this->request->getPost('prune'); // formdan gelir (POST)

        $scanner = new PermissionScanner();
        $res = $scanner->scan($prune);

        $msg = 'İzin tarama tamamlandı. '
            . 'Eklendi: ' . count($res['added'])
            . ', Korundu: ' . count($res['kept'])
            . ($prune ? ', Silinen: ' . count($res['pruned']) : '');

        return redirect()->to(site_url('admin/roles'))->with('message', $msg);
    }
}
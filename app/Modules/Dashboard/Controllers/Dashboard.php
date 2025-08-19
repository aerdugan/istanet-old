<?php
namespace App\Modules\Dashboard\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function index(): ResponseInterface|string
    {
        // ↳ İzin adın senin şemanla uyumluysa bırak; yoksa alias ile aynı yapmanı öneririm.
        if (! user_can('Dashboard.Dashboard.index')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            // ❌ '/dashboard' yerine:
            return redirect()->to(route_to('backend.dashboard.index')); // veya redirect()->to('/admin')
        }

        // helper('auth'); // gerek yoksa kaldırılabilir
        $data = ['title' => 'Yönetim Paneli'];

        // ✅ View yolu (module içi güvenli kullanım)
        return view('App\Modules\Dashboard\Views\index', $data);
    }

    public function change(string $langCode)
    {
        if (! user_can('Dashboard.Dashboard.change')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            // ❌ '/dashboard' yerine:
            return redirect()->to(route_to('backend.dashboard.index')); // veya redirect()->to('/admin')
        }

        // ❌ helper('language');
        helper('admin'); // ✅ getActiveLanguages() burada

        $codes = array_column(getActiveLanguages(), 'shorten');
        if (in_array($langCode, $codes, true)) {
            session()->set('data_lang', $langCode);
            service('request')->setLocale($langCode);
        }

        // Her durumda admin ana sayfaya dön ve Swal göster
        return redirect()->to(route_to('backend.dashboard.index'))->with('swal', [
            'type'    => 'success',
            'title'   => 'Dil Değiştirildi',
            'message' => 'Admin dili güncellendi.',
        ]);
    }
}
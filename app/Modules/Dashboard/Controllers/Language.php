<?php
namespace App\Modules\Dashboard\Controllers;

use App\Controllers\BaseController;

class Language extends BaseController
{
    public function switch()
    {
        helper('admin');

        $code = strtolower($this->request->getPost('code') ?? '');
        $active = array_column(getActiveLanguages(), 'shorten');

        if (! in_array($code, $active, true)) {
            return redirect()->back()->with('swal', [
                'type'    => 'error',
                'title'   => 'Dil Değiştirme',
                'message' => 'Geçersiz dil kodu.',
            ]);
        }

        setSessionLanguage($code);

        // Admin alanında kalmak istediğimiz için dil segmenti kullanmıyoruz;
        // sadece geri dön.
        return redirect()->to(route_to('dashboard'))->with('swal', [
            'type'    => 'success',
            'title'   => 'Dil Değiştirildi',
            'message' => 'Admin dili güncellendi.',
        ]);
    }
}
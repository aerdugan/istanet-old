<?php

namespace App\Controllers;

class Site extends BaseController
{
    public function redirectToDefault()
    {
        helper('admin');
        $code = getDefaultSiteLanguage(); // rank=0 (ör. 'tr')
        // Session & request locale set değilse filtre zaten set ediyor, burada sadece redirect var
        return redirect()->to('/' . $code);
    }
}

<?php

namespace App\Modules\Settings\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class Settings extends BaseController
{
    private array $themeKeys = [
        'colorCode',
        'colorCode2',
        'setHeader',
        'setMobileHeader',
        'setFooter',
        'setMobileFooter',
        'setSlider',
        'setMobileSlider',
        'setBreadcrumbStatus',
        'setMobileBreadcrumbStatus',
        'setBreadcrumb',
        'setMobileBreadcrumb',
        'setTheme',            // int (select)
        'isMobile',            // bool
        'setMobileTheme',      // int (select)
        'setLogo',             // int
        'setWhiteLogo',        // int
        'setMobileLogo',       // int
        'setMobileWhiteLogo',  // int
        'headerSocial',
        'mobileHeaderSocial',
        'headerLang',
        'mobileHeaderLang',
        'setFoterSocial',
        'setMobileFoterSocial',
        'setFooterContact',
        'setMobileFooterContact',
        'setFooterNewsLetter',
        'setMobileFooterNewsLetter',
        'copyright',           // text
        'openClose',           // text
        'siteStatus',          // bool
        'siteCloseMessage',    // longtext
    ];

    private array $themeBooleanKeys = [
        'setBreadcrumbStatus',
        'setMobileBreadcrumbStatus',
        'isMobile',
        'headerSocial',
        'mobileHeaderSocial',
        'headerLang',
        'mobileHeaderLang',
        'setFoterSocial',
        'setMobileFoterSocial',
        'setFooterContact',
        'setMobileFooterContact',
        'setFooterNewsLetter',
        'setMobileFooterNewsLetter',
        'siteStatus',
    ];


    public function emailSettings()
    {
        if (! user_can('Settings.Settings.emailSettings')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $settings = service('settings');

        $hasEncPass = (bool) $settings->get('Email.SMTPPassEnc');

        return view('\App\Modules\Settings\Views\email', [
            'title'       => 'E-Posta Ayarları',
            'protocol'    => $settings->get('Email.protocol') ?? 'smtp',
            'SMTPHost'    => $settings->get('Email.SMTPHost') ?? '',
            'SMTPUser'    => $settings->get('Email.SMTPUser') ?? '',
            'SMTPPass'    => '',
            'hasEncPass'  => $hasEncPass,
            'SMTPPort'    => $settings->get('Email.SMTPPort') ?? 587,
            'fromEmail'   => $settings->get('Email.fromEmail') ?? '',
            'fromName'    => $settings->get('Email.fromName') ?? '',
        ]);
    }

    public function emailSettingsSave(): RedirectResponse
    {
        if (! user_can('Settings.Settings.emailSettingsSave')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $s = service('settings');

        $s->set('Email.protocol', $this->request->getPost('protocol'));
        $s->set('Email.SMTPHost', $this->request->getPost('SMTPHost'));
        $s->set('Email.SMTPUser', $this->request->getPost('SMTPUser'));
        $s->set('Email.SMTPPort', (int) $this->request->getPost('SMTPPort'));
        $s->set('Email.fromEmail', $this->request->getPost('fromEmail'));
        $s->set('Email.fromName', $this->request->getPost('fromName'));

        $plainPass = trim((string) $this->request->getPost('SMTPPass'));
        if ($plainPass !== '') {
            $encrypter = Services::encrypter();
            $cipher    = base64_encode($encrypter->encrypt($plainPass));

            // Yeni: şifreli alan
            $s->set('Email.SMTPPassEnc', $cipher);

            // Eski düz alanı temizle (artık tutmayalım)
            $s->set('Email.SMTPPass', null);
        }

        return redirect()
            ->to(route_to('admin.settings.email'))
            ->with('message', 'Ayarlar kaydedildi.');
    }

    public function emailSendTest()
    {
        if (! user_can('Settings.Settings.emailSendTest')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        // JSON veya form-data destekleyelim
        $to = $this->request->getPost('email');
        if (! $to && $json = $this->request->getJSON(true)) {
            $to = $json['email'] ?? null;
        }
        if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Geçersiz e-posta'])->setStatusCode(422);
        }

        // Parolayı güvenli şekilde çöz
        $password = $this->resolveSmtpPassword(); // aşağıda helper metod

        $config = [
            'protocol' => setting('Email.protocol') ?? 'smtp',
            'SMTPHost' => setting('Email.SMTPHost') ?? '',
            'SMTPUser' => setting('Email.SMTPUser') ?? '',
            'SMTPPass' => $password ?? '',
            'SMTPPort' => (int) (setting('Email.SMTPPort') ?? 587),
            'mailType' => 'html',
            'charset'  => 'utf-8',
        ];

        $email = Services::email();
        $email->initialize($config);

        $fromEmail = setting('Email.fromEmail') ?? (setting('Email.SMTPUser') ?? 'no-reply@example.com');
        $fromName  = setting('Email.fromName')  ?? 'CI4';

        $email->setFrom($fromEmail, $fromName);
        $email->setTo($to);
        $email->setSubject('CI4 Test E-postası');
        $email->setMessage('<p>Bu bir test e-postasıdır. Tarih: ' . date('Y-m-d H:i:s') . '</p>');

        if ($email->send()) {
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => $email->printDebugger(['headers', 'subject'])
        ])->setStatusCode(500);
    }

    /**
     * SMTP şifresini güvenli olarak elde eder:
     * 1) Varsa şifreli 'Email.SMTPPassEnc' -> decrypt
     * 2) Yoksa GERİYE DÖNÜK: 'Email.SMTPPass' (düz) kullanılır
     */
    private function resolveSmtpPassword(): ?string
    {

        $enc = setting('Email.SMTPPassEnc');
        if ($enc) {
            try {
                $encrypter = Services::encrypter();
                return $encrypter->decrypt(base64_decode($enc));
            } catch (\Throwable $e) {
                // Decrypt başarısızsa loglayıp düz alana düşelim
                log_message('error', 'SMTPPass decrypt failed: ' . $e->getMessage());
            }
        }
        // Backward compatibility (eski projeler için)
        $legacy = setting('Email.SMTPPass');
        return $legacy ?: null;
    }

    public function shieldSettings()
    {
        if (! user_can('Settings.Settings.shieldSettings')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }
        $s = service('settings');

        return view('\App\Modules\Settings\Views\shield', [
            'title'             => 'Shield Ayarları',
            'allowRegistration' => (bool) ($s->get('Shield.allowRegistration') ?? false),
            'allowRemembering'  => (bool) ($s->get('Shield.allowRemembering') ?? false),
            'mfa'               => (bool) ($s->get('Shield.mfa') ?? false),
            'magicOnly'         => (bool) ($s->get('Shield.magicOnly') ?? false),
            'resetByEmail'      => (bool) ($s->get('Shield.resetByEmail') ?? true),
            'loginBy'           => (string) ($s->get('Shield.loginBy') ?? 'email'),
            'bruteforceMax'     => (int) ($s->get('Shield.bruteforceMax') ?? 5),
        ]);
    }
    public function shieldSettingsSave()
    {
        if (! user_can('Settings.Settings.shieldSettingsSave')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }
        $s = service('settings');

        $s->set('Shield.allowRegistration', (bool) $this->request->getPost('allowRegistration'));
        $s->set('Shield.allowRemembering',  (bool) $this->request->getPost('allowRemembering'));
        $s->set('Shield.mfa',               (bool) $this->request->getPost('mfa'));
        $s->set('Shield.magicOnly',         (bool) $this->request->getPost('magicOnly'));
        $s->set('Shield.resetByEmail',      (bool) $this->request->getPost('resetByEmail'));

        $loginBy = $this->request->getPost('loginBy') === 'username' ? 'username' : 'email';
        $s->set('Shield.loginBy', $loginBy);

        $max = (int) $this->request->getPost('bruteforceMax');
        if ($max < 0) $max = 0;
        $s->set('Shield.bruteforceMax', $max);

        return redirect()->to(site_url('admin/settings/shield'))
            ->with('message', 'Shield ayarları kaydedildi.');
    }
    public function themeSettings()
    {
        if (! user_can('Settings.Settings.themeSettings')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        helper('setting');

        $data = [];
        foreach ($this->themeKeys as $k) {
            $data[$k] = setting('theme.' . $k);
        }

        // Varsayılanlar
        $data['colorCode']  = $data['colorCode']  ?? '#000000';
        $data['colorCode2'] = $data['colorCode2'] ?? '#ffffff';

        $data['title'] = 'Tema Ayarları';

        return view('\App\Modules\Settings\Views\themeSettings', $data);
    }
    public function themeSettingsSave(): RedirectResponse
    {
        if (! user_can('Settings.Settings.themeSettingsSave')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $post = $this->request->getPost() ?? [];

        foreach ($this->themeBooleanKeys as $bk) {
            $post[$bk] = isset($post[$bk]) && (string)$post[$bk] === '1' ? 1 : 0;
        }

        $rules = [
            'colorCode'                   => 'permit_empty|max_length[10]',
            'colorCode2'                  => 'permit_empty|max_length[10]',
            'setHeader'                   => 'permit_empty|max_length[255]',
            'setMobileHeader'             => 'permit_empty|max_length[255]',
            'setFooter'                   => 'permit_empty|max_length[255]',
            'setMobileFooter'             => 'permit_empty|max_length[255]',
            'setSlider'                   => 'permit_empty|max_length[255]',
            'setMobileSlider'             => 'permit_empty|max_length[255]',
            'setBreadcrumb'               => 'permit_empty|max_length[255]',
            'setMobileBreadcrumb'         => 'permit_empty|max_length[255]',
            'setTheme'                    => 'permit_empty|integer',
            'isMobile'                    => 'permit_empty|in_list[0,1]',
            'setMobileTheme'              => 'permit_empty|integer',
            'setLogo'                     => 'permit_empty|integer',
            'setWhiteLogo'                => 'permit_empty|integer',
            'setMobileLogo'               => 'permit_empty|integer',
            'setMobileWhiteLogo'          => 'permit_empty|integer',
            'headerSocial'                => 'permit_empty|in_list[0,1]',
            'mobileHeaderSocial'          => 'permit_empty|in_list[0,1]',
            'headerLang'                  => 'permit_empty|in_list[0,1]',
            'mobileHeaderLang'            => 'permit_empty|in_list[0,1]',
            'setFoterSocial'              => 'permit_empty|in_list[0,1]',
            'setMobileFoterSocial'        => 'permit_empty|in_list[0,1]',
            'setFooterContact'            => 'permit_empty|in_list[0,1]',
            'setMobileFooterContact'      => 'permit_empty|in_list[0,1]',
            'setFooterNewsLetter'         => 'permit_empty|in_list[0,1]',
            'setMobileFooterNewsLetter'   => 'permit_empty|in_list[0,1]',
            'copyright'                   => 'permit_empty',
            'openClose'                   => 'permit_empty',
            'siteStatus'                  => 'permit_empty|in_list[0,1]',
            'siteCloseMessage'            => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', 'Doğrulama hatası')->withInput();
        }

        $save = [];
        foreach ($this->themeKeys as $k) {
            if (array_key_exists($k, $post)) {
                $save['theme.' . $k] = $post[$k];
            }
        }

        $settings = service('settings');
        foreach ($save as $key => $value) {
            $settings->set($key, $value);
        }

        return redirect()->to(route_to('admin.settings.theme'))
            ->with('message', 'Tema ayarları kaydedildi.');
    }

}
<?php
namespace App\Modules\General\Controllers;

use App\Controllers\BaseController;
use App\Modules\General\Models\GeneralModel;
use App\Modules\General\Models\ContactModel;
use App\Models\CommonModel;
use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;


/**
 * Class Task
 */
class General extends BaseController
{
    private $common_model; // ← bu eksik!
    private $contact_model;
    private $general_model;

    function __construct()
    {
        helper('general_helper');
        $this->general_model = new GeneralModel();
        $this->contact_model = new ContactModel();
        $this->common_model = new CommonModel();

    }

    public function index()
    {
        if (! user_can('General.General.index')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $s = service('settings');

        // Her alanı ayrı ayrı (string) çekiyoruz
        $data['item'] = (object) [
            'company_name'    => (string) ($s->get('Site/Company.company_name')    ?? ''),
            'companyLongName' => (string) ($s->get('Site/Company.companyLongName') ?? ''),
            'taxOffice'       => (string) ($s->get('Site/Company.taxOffice')       ?? ''),
            'taxNumber'       => (string) ($s->get('Site/Company.taxNumber')       ?? ''),
            'ticariSicilNo'   => (string) ($s->get('Site/Company.ticariSicilNo')   ?? ''),
            'mersisNo'        => (string) ($s->get('Site/Company.mersisNo')        ?? ''),
        ];

        return view('App\Modules\General\Views\general\company', $data);
    }

    public function companySettingsUpdate(): RedirectResponse
    {
        if (! user_can('General.General.companySettingsUpdate')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $rules = [
            'company_name'     => 'required|max_length[255]',
            'companyLongName'  => 'permit_empty|max_length[255]',
            'taxOffice'        => 'permit_empty|max_length[100]',
            'taxNumber'        => 'permit_empty|max_length[100]',
            'ticariSicilNo'    => 'permit_empty|max_length[100]',
            'mersisNo'         => 'permit_empty|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->with('error', implode(' ', $this->validator->getErrors()))
                ->withInput();
        }

        $post = $this->request->getPost();
        $s    = service('settings');

        // Her birini ayrı SATIR (string) olarak yaz
        $s->set('Site/Company.company_name',    trim((string) ($post['company_name']    ?? '')));
        $s->set('Site/Company.companyLongName', trim((string) ($post['companyLongName'] ?? '')));
        $s->set('Site/Company.taxOffice',       trim((string) ($post['taxOffice']       ?? '')));
        $s->set('Site/Company.taxNumber',       trim((string) ($post['taxNumber']       ?? '')));
        $s->set('Site/Company.ticariSicilNo',   trim((string) ($post['ticariSicilNo']   ?? '')));
        $s->set('Site/Company.mersisNo',        trim((string) ($post['mersisNo']        ?? '')));

        // (Opsiyonel) Eski grup kaydını temizlemek istersen:
        // $s->set('Site.Company', null);

        session()->setFlashdata('message', 'Firma bilgileri başarıyla güncellendi.');
        session()->setFlashdata('alert-type', 'success');

        return redirect()->to('/admin/general');
    }
    public function contactSettings() {
        if (! user_can('General.General.contactSettings')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $data['contacts'] = $this->contact_model->getContactList()->getResult();

        return view('App\Modules\General\Views\general\contactSettings',$data);

    }
    public function store()
    {
        if (! user_can('General.General.store')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $userId = auth()->id(); // sadece ID

        $data = array(
            'title'             => $this->request->getPost('title'),
            'url'               =>  $this->request->getPost('title'),
            'phone_1'           => $this->request->getPost('phone_1'),
            'phone_2'           => $this->request->getPost('phone_2'),
            'fax_1'             => $this->request->getPost('fax_1'),
            'fax_2'             => $this->request->getPost('fax_2'),
            'email'             => $this->request->getPost('email'),
            'address'           => $this->request->getPost('address'),
            'address_location'  => $this->request->getPost('address_location'),
            'rank'              => 999,
            'isActive'          => 1,
            'createdUser'       => $userId
        );

        $update =  $this->contact_model->saveContact($data);

        if ($update) {
            session()->setFlashdata('message', 'Sosyal medya ayarları başarıyla güncellendi.');
            session()->setFlashdata('alert-type', 'success');
        } else {
            session()->setFlashdata('message', 'Sosyal medya ayarları güncellenirken bir hata oluştu.');
            session()->setFlashdata('alert-type', 'error');
        }

        return redirect()->to('/general/contact');
    }
    public function contactUpdate()
    {
        if (! user_can('General.General.contactUpdate')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $userId = auth()->id(); // sadece ID

        $id = $this->request->getPost('id');
        $data = array(
            'title'         => $this->request->getPost('title'),
            'phone_1'         => $this->request->getPost('phone_1'),
            'phone_2'         => $this->request->getPost('phone_2'),
            'fax_1'   => $this->request->getPost('fax_1'),
            'fax_2' => $this->request->getPost('fax_2'),
            'email'     => $this->request->getPost('email'),
            'address'        => $this->request->getPost('address'),
            'address_location'        => $this->request->getPost('address_location'),
            'lastUpdatedUser'   => $userId,
        );

        $update = $this->contact_model->updateContact($data, $id);

        if ($update) {
            session()->setFlashdata('message', 'İletişim ayarları başarıyla güncellendi.');
            session()->setFlashdata('alert-type', 'success');
        } else {
            session()->setFlashdata('message', 'İletişim ayarları güncellenirken bir hata oluştu.');
            session()->setFlashdata('alert-type', 'error');
        }


        return redirect()->to('/general/contact');
    }
    public function delete()
    {
        if (! user_can('General.General.delete')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $id = $this->request->getPost('id');
        $this->contact_model->deleteContact($id);
        return redirect()->to('/general/contact');
    }
    public function updateRank()
    {
        if (! user_can('General.General.updateRank')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        // JSON yanıt döndürmeyi unutmayın
        $response = ['status' => 'error'];

        try {
            $sliderOrder = $this->request->getPost('order'); // Sıralı ID'ler alınıyor

            if ($sliderOrder) {
                foreach ($sliderOrder as $rank => $id) {
                    // Her ID için rank değeri güncelleniyor
                    $data = ['rank' => $rank];
                    $this->contact_model->updateContact($data, $id);
                }
                $response['status'] = 'success';
            } else {
                $response['message'] = 'Sıralama verisi alınamadı.';
            }
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return $this->response->setJSON($response);

    }
    public function isActiveSetter()
    {
        if (! user_can('General.General.isActiveSetter')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $id = $this->request->getPost('id');
        $isActive = $this->request->getPost('isActive');

        $this->contact_model->updateContact(['isActive' => $isActive], $id);

        return $this->response->setJSON(['status' => 'success']);
    }
    public function otherSettings()
    {
        if (! user_can('General.General.otherSettings')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $s = service('settings');

        $data['item'] = (object) [
            'googleSiteKey'     => (string) ($s->get('Site/Other.googleSiteKey')     ?? ''),
            'googleSecurityKey' => (string) ($s->get('Site/Other.googleSecurityKey') ?? ''),
            'googleAnalytics'   => (string) ($s->get('Site/Other.googleAnalytics')   ?? ''),
            'chatBox'           => (string) ($s->get('Site/Other.chatBox')           ?? ''),
            'whatsappStatus'    => (string) ((int)($s->get('Site/Other.whatsappStatus') ?? 0)), // "0"/"1"
            'phoneNumber'       => (string) ($s->get('Site/Other.phoneNumber')       ?? ''),
            'messageSubject'    => (string) ($s->get('Site/Other.messageSubject')    ?? ''),
            'popupTitle'        => (string) ($s->get('Site/Other.popupTitle')        ?? ''),
            'popupDesc'         => (string) ($s->get('Site/Other.popupDesc')         ?? ''),
            'buttonCaptionName' => (string) ($s->get('Site/Other.buttonCaptionName') ?? ''),
            'message'           => (string) ($s->get('Site/Other.message')           ?? ''),
        ];

        return view('App\Modules\General\Views\general\otherSettings', $data);
    }
    public function otherSettingsUpdate(): RedirectResponse
    {
        if (! user_can('General.General.otherSettingsUpdate')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $rules = [
            'googleSiteKey'     => 'permit_empty|max_length[255]',
            'googleSecurityKey' => 'permit_empty|max_length[255]',
            'googleAnalytics'   => 'permit_empty|max_length[5000]',
            'chatBox'           => 'permit_empty|max_length[5000]',
            'phoneNumber'       => 'permit_empty|max_length[20]',
            'messageSubject'    => 'permit_empty|max_length[255]',
            'popupTitle'        => 'permit_empty|max_length[255]',
            'popupDesc'         => 'permit_empty|max_length[255]',
            'buttonCaptionName' => 'permit_empty|max_length[255]',
            'message'           => 'permit_empty|max_length[1000]',
            'whatsappStatus'    => 'permit_empty|in_list[0,1]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->with('error', implode(' ', $this->validator->getErrors()))
                ->withInput();
        }

        $post = $this->request->getPost();
        $s    = service('settings');

        // Her key'i ayrı string olarak yaz
        $s->set('Site/Other.googleSiteKey',     (string) ($post['googleSiteKey']     ?? ''));
        $s->set('Site/Other.googleSecurityKey', (string) ($post['googleSecurityKey'] ?? ''));
        $s->set('Site/Other.googleAnalytics',   (string) ($post['googleAnalytics']   ?? ''));
        $s->set('Site/Other.chatBox',           (string) ($post['chatBox']           ?? ''));
        $s->set('Site/Other.whatsappStatus',    isset($post['whatsappStatus']) ? (string)(int)$post['whatsappStatus'] : '0');
        $s->set('Site/Other.phoneNumber',       (string) ($post['phoneNumber']       ?? ''));
        $s->set('Site/Other.messageSubject',    (string) ($post['messageSubject']    ?? ''));
        $s->set('Site/Other.popupTitle',        (string) ($post['popupTitle']        ?? ''));
        $s->set('Site/Other.popupDesc',         (string) ($post['popupDesc']         ?? ''));
        $s->set('Site/Other.buttonCaptionName', (string) ($post['buttonCaptionName'] ?? ''));
        $s->set('Site/Other.message',           (string) ($post['message']           ?? ''));

        session()->setFlashdata('message', 'Diğer Ayarlar başarıyla güncellendi.');
        session()->setFlashdata('alert-type', 'success');

        return redirect()->to('/admin/general/other');
    }

    public function logoSettings()
    {
        if (! user_can('General.General.logoSettings')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $s = service('settings');

        return view('\App\Modules\General\Views\general\logoSettings', [
            'title' => 'Logo & Favicon',
            'item'  => [
                'logo'       => (string) ($s->get('Setting/Logo.logo') ?? ''),
                'secondLogo' => (string) ($s->get('Setting/Logo.secondLogo') ?? ''),
                'favicon'    => (string) ($s->get('Setting/Logo.favicon') ?? ''),
            ],
        ]);
    }
    public function logoSettingsUpdate(): RedirectResponse
    {
        if (! user_can('General.General.logoSettingsUpdate')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/admin/dashboard');
        }

        $s = service('settings');

        // View'daki input name'leri ile birebir
        $logo       = trim((string) $this->request->getPost('logo'));
        $secondLogo = trim((string) $this->request->getPost('secondLogo'));
        $favicon    = trim((string) $this->request->getPost('favicon'));

        // Tek tek kaydet (null/boş da kaydedilebilir; istersen boşları silersin)
        $s->set('Setting/Logo.logo',       $logo !== ''       ? $logo       : null);
        $s->set('Setting/Logo.secondLogo', $secondLogo !== '' ? $secondLogo : null);
        $s->set('Setting/Logo.favicon',    $favicon !== ''    ? $favicon    : null);

        return redirect()
            ->to("/admin/general/logo")  // route adın buysa
            ->with('alert-type', 'success')
            ->with('message', 'Logo ayarları kaydedildi.');
    }
    public function seoSettings()
    {
        if (! user_can('General.General.seoSettings')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $lang = session()->get('lang') ?? 'tr';
        $data = [];

        $data['item'] = $this->common_model->selectOne([
            'data_lang' => $lang
        ], 'seoSettings');

        if (!$data['item']) {
            $data['item'] = (object)[
                'siteDesc'     => '',
                'siteKeywords' => '[]', // Boş JSON dizi formatı
            ];
        } else {
            // Virgülle ayrılmış string'i JSON array formatına dönüştür (Tagify için)
            $keywords = explode(',', $data['item']->siteKeywords);
            $jsonTags = [];

            foreach ($keywords as $keyword) {
                $keyword = trim($keyword);
                if (!empty($keyword)) {
                    $jsonTags[] = ['value' => $keyword];
                }
            }

            $data['item']->siteKeywords = json_encode($jsonTags, JSON_UNESCAPED_UNICODE);
        }

        return view('App\Modules\General\Views\general\seoSettings', $data);
    }
    public function seoSettingsUpdate()
    {
        if (! user_can('General.General.seoSettingsUpdate')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $siteDesc = $this->request->getPost('siteDesc');
        $rawKeywords = $this->request->getPost('siteKeywords');

        // JSON formatını ayrıştır
        $decodedKeywords = json_decode($rawKeywords, true);
        $keywords = [];

        if (is_array($decodedKeywords)) {
            foreach ($decodedKeywords as $tag) {
                if (!empty($tag['value'])) {
                    $keywords[] = $tag['value'];
                }
            }
        }

        $siteKeywords = implode(',', $keywords); // "Alüminyum Üretim,ahmet,mehmet"

        if (!$siteDesc || empty($siteKeywords)) {
            session()->setFlashdata('message', 'Lütfen tüm alanları doldurun.');
            session()->setFlashdata('alert-type', 'error');
            return redirect()->back();
        }

        $db       = \Config\Database::connect();
        $builder  = $db->table('seoSettings');
        $lang     = session()->get('lang');
        $userId   = session()->get('id_user');

        $data = [
            'siteDesc'         => $siteDesc,
            'siteKeywords'     => $siteKeywords,
            'data_lang'        => $lang,
            'updatedAt'        => date('Y-m-d H:i:s'),
            'lastUpdatedUser'  => $userId,
        ];

        $existingRecord = $builder->where('data_lang', $lang)->get()->getRow();

        if ($existingRecord) {
            $updated = $builder->where('data_lang', $lang)->update($data);
            session()->setFlashdata('message', $updated ? 'SEO ayarları başarıyla güncellendi.' : 'SEO ayarları güncellenirken bir hata oluştu.');
            session()->setFlashdata('alert-type', $updated ? 'success' : 'error');
        } else {
            $inserted = $builder->insert($data);
            session()->setFlashdata('message', $inserted ? 'SEO ayarları başarıyla kaydedildi.' : 'SEO ayarları kaydedilirken bir hata oluştu.');
            session()->setFlashdata('alert-type', $inserted ? 'success' : 'error');
        }

        return redirect()->to('/general/seo');
    }
    private function setFlashAndRedirect(string $message, string $type)
    {
        session()->setFlashdata('message', $message);
        session()->setFlashdata('alert-type', $type);
        return redirect()->to('/general/seo');
    }

    public function socials()
    {
        if (! user_can('General.General.socials')) {
            return redirect()->to(route_to('backend.dashboard.index'))
                ->with('swal', ['type'=>'error','title'=>'Yetki Hatası','message'=>'Bu sayfayı görmeye yetkiniz yok.']);
        }

        $s = service('settings');

        // Settings’ten oku (array bekliyoruz)
        $socials = $s->get('Site.socials');
        if (is_string($socials)) {
            // olası eski kayıtlar için güvenli decode
            $try = @unserialize($socials);
            if ($try !== false || $socials === 'b:0;') {
                $socials = $try;
            } else {
                $json = json_decode($socials, true);
                if (json_last_error() === JSON_ERROR_NONE) $socials = $json;
            }
        }
        if (!is_array($socials)) $socials = [];

        return view('\App\Modules\General\Views\general\socialSettings', [
            'title'   => 'Sosyal Bağlantılar',
            'socials' => $socials, // [['platform'=>'', 'icon'=>'', 'username'=>'', 'url'=>''], ...]
        ]);
    }

    public function socialsSave()
    {
        if (! user_can('General.General.socialsSave')) {
            return redirect()->to(route_to('backend.dashboard.index'))
                ->with('swal', ['type'=>'error','title'=>'Yetki Hatası','message'=>'Bu sayfayı görmeye yetkiniz yok.']);
        }

        $items = $this->request->getPost('socials') ?? [];

        // Temizlik: boş satırları at
        $clean = [];
        foreach ((array)$items as $row) {
            $platform = trim((string)($row['platform'] ?? ''));
            $icon     = trim((string)($row['icon'] ?? ''));
            $username = trim((string)($row['username'] ?? ''));
            $url      = trim((string)($row['url'] ?? ''));

            if ($platform === '' && $icon === '' && $username === '' && $url === '') {
                continue;
            }
            $clean[] = compact('platform','icon','username','url');
        }

        // Settings’e array olarak yaz (CI4 Settings type=array olarak saklar)
        $s = service('settings');
        $s->set('Site.socials', $clean);

        return redirect()->to('/admin/general/socials')
            ->with('swal', [
                'type'    => 'success',
                'title'   => 'Kaydedildi',
                'message' => 'Sosyal bağlantılar güncellendi.',
            ]);
    }

}
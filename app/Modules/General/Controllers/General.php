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

        // Site.Company grubunu tek seferde al
        $company = setting('Site.Company');

        // Geriye-dönük uyumluluk: eğer grup yoksa boş dizi kullan
        if (!is_array($company)) {
            $company = [];
        }

        // View tarafında $item->alan şeklinde kullanabilmen için objeye çevir
        $data['item'] = (object) [
            'company_name'    => (string) ($company['company_name']    ?? ''),
            'companyLongName' => (string) ($company['companyLongName'] ?? ''),
            'taxOffice'       => (string) ($company['taxOffice']       ?? ''),
            'taxNumber'       => (string) ($company['taxNumber']       ?? ''),
            'ticariSicilNo'   => (string) ($company['ticariSicilNo']   ?? ''),
            'mersisNo'        => (string) ($company['mersisNo']        ?? ''),
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
        // Basit doğrulama
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

        // POST verileri
        $post = $this->request->getPost();

        $payload = [
            'company_name'    => trim((string) ($post['company_name']    ?? '')),
            'companyLongName' => trim((string) ($post['companyLongName'] ?? '')),
            'taxOffice'       => trim((string) ($post['taxOffice']       ?? '')),
            'taxNumber'       => trim((string) ($post['taxNumber']       ?? '')),
            'ticariSicilNo'   => trim((string) ($post['ticariSicilNo']   ?? '')),
            'mersisNo'        => trim((string) ($post['mersisNo']        ?? '')),
        ];

        // Settings’e yaz
        service('settings')->set('Site.Company', $payload);

        // Bildirim
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
        // Önce grup key'i tek seferde al
        $other = setting('Site.Other');

        if (!is_array($other) || $other === null) {
            // GERİYE DÖNÜK: Daha önce tek tek key'ler yazıldıysa buradan doldur
            $other = [
                'googleSiteKey'     => setting('Site.Other.googleSiteKey')     ?? '',
                'googleSecurityKey' => setting('Site.Other.googleSecurityKey') ?? '',
                'googleAnalytics'   => setting('Site.Other.googleAnalytics')   ?? '',
                'chatBox'           => setting('Site.Other.chatBox')           ?? '',
                'whatsappStatus'    => setting('Site.Other.whatsappStatus')    ?? 0,
                'phoneNumber'       => setting('Site.Other.phoneNumber')       ?? '',
                'messageSubject'    => setting('Site.Other.messageSubject')    ?? '',
                'popupTitle'        => setting('Site.Other.popupTitle')        ?? '',
                'popupDesc'         => setting('Site.Other.popupDesc')         ?? '',
                'buttonCaptionName' => setting('Site.Other.buttonCaptionName') ?? '',
                'message'           => setting('Site.Other.message')           ?? '',
            ];
        }

        // Tipleri normalize et (cast sırasında "Array to string" hatasını engeller)
        $data['item'] = (object) [
            'googleSiteKey'     => (string) ($other['googleSiteKey']     ?? ''),
            'googleSecurityKey' => (string) ($other['googleSecurityKey'] ?? ''),
            'googleAnalytics'   => (string) ($other['googleAnalytics']   ?? ''),
            'chatBox'           => (string) ($other['chatBox']           ?? ''),
            'whatsappStatus'    => (string) ((int)($other['whatsappStatus'] ?? 0)), // "0"/"1"
            'phoneNumber'       => (string) ($other['phoneNumber']       ?? ''),
            'messageSubject'    => (string) ($other['messageSubject']    ?? ''),
            'popupTitle'        => (string) ($other['popupTitle']        ?? ''),
            'popupDesc'         => (string) ($other['popupDesc']         ?? ''),
            'buttonCaptionName' => (string) ($other['buttonCaptionName'] ?? ''),
            'message'           => (string) ($other['message']           ?? ''),
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

        // TEK PAYLOAD (array) —>> 'Site.Other' altına JSON olarak gider
        $payload = [
            'googleSiteKey'     => (string) ($post['googleSiteKey']     ?? ''),
            'googleSecurityKey' => (string) ($post['googleSecurityKey'] ?? ''),
            'googleAnalytics'   => (string) ($post['googleAnalytics']   ?? ''),
            'chatBox'           => (string) ($post['chatBox']           ?? ''),
            'whatsappStatus'    => isset($post['whatsappStatus']) ? (int) $post['whatsappStatus'] : 0,
            'phoneNumber'       => (string) ($post['phoneNumber']       ?? ''),
            'messageSubject'    => (string) ($post['messageSubject']    ?? ''),
            'popupTitle'        => (string) ($post['popupTitle']        ?? ''),
            'popupDesc'         => (string) ($post['popupDesc']         ?? ''),
            'buttonCaptionName' => (string) ($post['buttonCaptionName'] ?? ''),
            'message'           => (string) ($post['message']           ?? ''),
        ];

        // DİKKAT: Grup key'e array set ediyoruz (alt keylere TEK TEK yazmıyoruz)
        service('settings')->set('Site.Other', $payload);

        // (Opsiyonel) Eskiden ayrı ayrı yazılmış alt anahtarlar varsa kafa karıştırmasın diye temizleyebilirsin:
        // foreach (['googleSiteKey','googleSecurityKey','googleAnalytics','chatBox','whatsappStatus','phoneNumber','messageSubject','popupTitle','popupDesc','buttonCaptionName','message'] as $k) {
        //     service('settings')->set('Site.Other.' . $k, null);
        // }

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

        // setting() helper autoload değilse:
        // helper('setting');

        $data['item'] = [
            'logo'        => setting('Site.Logo.logo')       ?? '',
            'secondLogo' => setting('Site.Logo.secondLogo') ?? '',
            'favicon'     => setting('Site.Logo.favicon')    ?? '',
        ];

        return view('App\Modules\General\Views\general\logoSettings', $data);
    }
    public function logoSettingsUpdate(): RedirectResponse
    {
        if (! user_can('General.General.logoSettingsUpdate')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $rules = [
            'logo'        => 'required|max_length[255]',
            'secondLogo' => 'required|max_length[255]',
            'favicon'     => 'required|max_length[255]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', implode(' ', $this->validator->getErrors()))->withInput();
        }

        $post = $this->request->getPost();

        $payload = [
            'logo'       => trim((string) ($post['logo']        ?? '')),
            'secondLogo' => trim((string) ($post['secondLogo'] ?? '')),
            'favicon'    => trim((string) ($post['favicon']     ?? '')),
        ];

        service('settings')->set('Site.Logo', $payload); // JSON olarak saklanır

        session()->setFlashdata('message', 'Firma Logoları başarıyla güncellendi.');
        session()->setFlashdata('alert-type', 'success');

        return redirect()->to('admin/general/logo');
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
    public function socialSettings1() {
        if (! user_can('General.General.socialSettings')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $data['item'] = $this->common_model->selectOne([], 'socialSettings');

        return view('App\Modules\General\Views\general\socialSettings',$data);

    }
    public function socialSettingsUpdate1()
    {
        if (! user_can('General.General.socialSettingsUpdate')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $facebook = $this->request->getPost('facebook');
        $facebookUserName = $this->request->getPost('facebookUserName');
        $instagram = $this->request->getPost('instagram');
        $instagramUserName = $this->request->getPost('instagramUserName');
        $twitter = $this->request->getPost('twitter');
        $twitterUserName = $this->request->getPost('twitterUserName');
        $youtube = $this->request->getPost('youtube');
        $youtubeUserName = $this->request->getPost('youtubeUserName');
        $linkedin = $this->request->getPost('linkedin');
        $linkedinUserName = $this->request->getPost('linkedinUserName');

        $db = \Config\Database::connect();
        $builder = $db->table('socialSettings'); // Tablonuzun adı

        // Kaydedilecek veri
        $data = [
            'facebook' => $facebook,
            'facebookUserName' => $facebookUserName,
            'instagram' => $instagram,
            'instagramUserName' => $instagramUserName,
            'twitter' => $twitter,
            'twitterUserName' => $twitterUserName,
            'youtube' => $youtube,
            'youtubeUserName' => $youtubeUserName,
            'linkedin' => $linkedin,
            'linkedinUserName' => $linkedinUserName,
            'updatedAt' => date('Y-m-d H:i:s'),
            'lastUpdatedUser' => session()->get('id_user'),
        ];

        $existingRecord = $builder->getWhere(['id' => 1])->getRow();

        if ($existingRecord) {
            $builder->where('id', 1);
            $update = $builder->update($data);
            if ($update) {
                session()->setFlashdata('message', 'Sosyal medya ayarları başarıyla güncellendi.');
                session()->setFlashdata('alert-type', 'success');
            } else {
                session()->setFlashdata('message', 'Sosyal medya ayarları güncellenirken bir hata oluştu.');
                session()->setFlashdata('alert-type', 'error');
            }
        } else {
            $data['id'] = 1; // Varsayılan olarak ID 1 kullanılır
            $data['created_at'] = date('Y-m-d H:i:s'); // Oluşturulma tarihi
            $insert = $builder->insert($data);

            if ($insert) {
                session()->setFlashdata('message', 'Sosyal medya ayarları başarıyla kaydedildi.');
                session()->setFlashdata('alert-type', 'success');
            } else {
                session()->setFlashdata('message', 'Sosyal medya ayarları kaydedilirken bir hata oluştu.');
                session()->setFlashdata('alert-type', 'error');
            }
        }
        return redirect()->to('/general/social');
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
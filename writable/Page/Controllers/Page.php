<?php
namespace App\Modules\Page\Controllers;

use App\Controllers\BaseController;
use App\Modules\Page\Models\PageModel;
use Throwable;


/**
 * Class Task
 */
class Page extends BaseController
{
    private $page_model;



    function __construct()
    {
        $this->page_model = new PageModel();
    }
    public function initController(\CodeIgniter\HTTP\RequestInterface $request,
                                   \CodeIgniter\HTTP\ResponseInterface $response,
                                   \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        helper('general_helper');
    }
    public function index()
    {
        helper('translate');

        $data['trItems'] = getDefaultPages();

        $db = \Config\Database::connect();
        $lang = session()->get('lang');

        $items = $db->table('pages')
            ->where('data_lang', $lang)
            ->orderBy('rank', 'ASC')
            ->get()
            ->getResult();

        $data['items'] = BuildTree($items);
        return view('App\Modules\Page\Views\page\index',$data);
    }
    public function store()
    {
        helper(['form', 'security']);

        if (!$this->validate([
            'title' => 'required|min_length[3]',
            'url'   => 'required'
        ])) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $originalTitle = esc($this->request->getPost('title'));
        $originalUrl   = esc($this->request->getPost('url'));

        $lastPage = $this->page_model->orderBy('id', 'DESC')->first();
        $referenceId = $lastPage ? $lastPage['id'] + 1 : 1;

        $sourceLang = getDefaultLanguage();
        $userId = session()->get('id_user');

        $data = [
            'title'             => $originalTitle,
            'url'               => $originalUrl,
            'mobileUrl'         => seoFriendly($originalTitle),
            'isWebEditor'       => 0,
            'isMobileEditor'    => 0,
            'rank'              => 999,
            'isActive'          => 0,
            'breadcrumbImageStatus' => 0,
            'data_lang'         => $sourceLang,
            'createdUser'       => $userId,
            'referenceID'       => $referenceId,
        ];

        if ($this->page_model->insert($data)) {
            session()->setFlashdata('swal', [
                'icon' => 'success',
                'title' => 'İşlem başarılı!',
                'text'  => 'Sayfa başarıyla eklendi.'
            ]);
        } else {
            session()->setFlashdata('swal', [
                'icon' => 'error',
                'title' => 'Hata oluştu!',
                'text'  => 'Sayfa eklenemedi.'
            ]);
        }

        return redirect()->to('/page');
    }
    public function addMissingLanguagePages($referenceId)
    {
        helper(['translate', 'language']);

        $defaultLang = getDefaultLanguage();
        $activeLanguages = getActiveLanguages();
        $onlyLang = $this->request->getGet('only');

        if ($onlyLang && !in_array($onlyLang, array_column($activeLanguages, 'shorten'))) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Geçersiz dil kodu']);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('pages');

        $originalPage = $builder->getWhere([
            'referenceID' => $referenceId,
            'data_lang' => $defaultLang
        ])->getRowArray();

        if (!$originalPage) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Orijinal sayfa bulunamadı.'
            ]);
        }

        $originalId = $originalPage['id'];
        $createdCount = 0;

        $fieldsToCopy = [
            'title',
            'url',
            'mobileUrl',
            'breadcrumbStatus',
            'breadcrumbTitle',
            'breadcrumbSlogan',
            'breadcrumbImageStatus',
            'breadcrumbImage',
            'inpHtml',
            'mobileHtml',
            'cBoxMainCss',
            'cBoxSectionCss',
            'cBoxContent',
            'cBoxMobileMainCss',
            'cBoxMobileSectionCss',
            'cBoxMobileContent',
            'isHeader',
            'isFooter',
            'isMobileFooter',
            'isMobile',
            'isWebEditor',
            'isMobileEditor',
            'createdAt',
            'updated_at'
        ];

        foreach ($activeLanguages as $lang) {
            $langCode = $lang['shorten'];

            if ($langCode === $defaultLang) continue;
            if ($onlyLang && $langCode !== $onlyLang) continue;

            $exists = $builder->getWhere([
                'referenceID' => $referenceId,
                'data_lang' => $langCode
            ])->getRowArray();

            if ($exists) continue;

            $newData = [];
            foreach ($fieldsToCopy as $field) {
                $newData[$field] = $originalPage[$field] ?? null;
            }

            // 🔁 Sadece bu alanlar çevrilecek
            $newData['title'] = translateWithGPT4o($originalPage['title'], $defaultLang, $langCode) ?: $originalPage['title'];
            $newData['url'] = seoFriendly(translateWithGPT4o($originalPage['url'], $defaultLang, $langCode) ?: $originalPage['url']);
            $newData['mobileUrl'] = seoFriendly($newData['title']);
            // Zorunlu alanlar
            $newData['referenceID'] = $originalId; // ✅ Orijinal sayfanın ID'si
            $newData['data_lang'] = $langCode;
            $newData['isActive'] = 0;
            $newData['createdAt'] = date('Y-m-d H:i:s');
            $newData['updated_at'] = date('Y-m-d H:i:s');

            if ($builder->insert($newData)) {
                $createdCount++;
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => "$createdCount eksik dilde sayfa oluşturuldu."
        ]);
    }
    public function translatePageFields($id)
    {
        $targetPage = $this->page_model->find($id);

        if (!$targetPage) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Sayfa bulunamadı.']);
        }

        $defaultLang = getDefaultLanguage();
        $targetLang = $targetPage['data_lang'];

        if ($targetLang === $defaultLang) {
            return $this->response->setJSON(['status' => 'info', 'message' => 'Varsayılan dildeki sayfa çevrilmez.']);
        }

        $originalPage = $this->page_model->where([
            'referenceID' => $targetPage['referenceID'],
            'data_lang'   => $defaultLang
        ])->first();

        if (!$originalPage) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Varsayılan dildeki sayfa bulunamadı.']);
        }

        $fieldsToTranslate = [
            'breadcrumbTitle',
            'breadcrumbSlogan',
            'inpHtml',
            'mobileHtml',
            'cBoxContent',
            'cBoxMobileContent'
        ];

        foreach ($fieldsToTranslate as $field) {
            $sourceValue = trim($originalPage[$field] ?? '');

            if ($sourceValue === '') {
                $targetPage[$field] = '';
                continue;
            }

            $translated = translateWithGPT4o($sourceValue, $defaultLang, $targetLang);
            $targetPage[$field] = $translated ?: $sourceValue;
        }


        $targetPage['updated_at'] = date('Y-m-d H:i:s');
        $this->page_model->update($id, $targetPage);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Alanlar başarıyla çevrildi.']);
    }


    public function generateSeoFields($id)
    {
        helper(['translate', 'language']);

        $page = $this->page_model->find($id);

        if (!$page) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Sayfa bulunamadı.']);
        }

        $langCode = $page['data_lang'];
        $fullContent = implode("\n", [
            $page['inpHtml'] ?? '',
            $page['mobileHtml'] ?? '',
            $page['cBoxContent'] ?? '',
            $page['cBoxMobileContent'] ?? ''
        ]);

        if (trim($fullContent) === '') {
            return $this->response->setJSON(['status' => 'info', 'message' => 'İçerik alanları boş.']);
        }

        $seo = generateSeoFromContent($fullContent, $langCode);

        if (!$seo || !isset($seo['desc'], $seo['keywords'])) {
            log_message('error', '❌ GPT JSON parse başarısız veya beklenen formatta değil.');
            return $this->response->setJSON(['status' => 'error', 'message' => 'GPT yanıtı geçerli değil.']);
        }

        $this->page_model->update($id, [
            'seoDesc' => $seo['desc'],
            'seoKeywords' => implode(', ', $seo['keywords']),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'SEO alanları oluşturuldu.',
            'desc' => $seo['desc'],
            'keywords' => implode(', ', $seo['keywords'])
        ]);

        // 1. JSON olarak dene
        $json = json_decode($content, true);
        if (is_array($json) && isset($json['desc'], $json['keywords'])) {
            return $json;
        }

// 2. DESC: / KEYWORDS: olarak ayrıştırmayı dene
        preg_match('/DESC[:：]?(.*?)KEYWORDS[:：]?/is', $content, $descMatch);
        preg_match('/KEYWORDS[:：]?(.*)/is', $content, $keywordsMatch);

        $desc = trim($descMatch[1] ?? '');
        $keywordsRaw = trim($keywordsMatch[1] ?? '');
        $keywords = array_map('trim', preg_split('/[,|;]+/', $keywordsRaw));

        if ($desc && count($keywords)) {
            return [
                'desc' => $desc,
                'keywords' => $keywords
            ];
        }

        log_message('error', "❌ GPT yanıtı parse edilemedi:\n" . $content);
        return null;
    }


    public function clonePage()
    {
        $lang = session()->get('lang');

        try {
            $clonePageId = $this->request->getPost('clonePageId');

            if (!$clonePageId) {
                return redirect()->back()->with('error', 'Lütfen klonlamak için bir sayfa seçin.');
            }

            $db = \Config\Database::connect();
            $builder = $db->table('pages');

            $originalPage = $builder->getWhere(['id' => $clonePageId])->getRowArray();

            if (!$originalPage) {
                return redirect()->back()->with('error', 'Seçilen sayfa bulunamadı.');
            }

            $cloneData = [
                'title' => $originalPage['title'] . ' (Klon)',
                'inpHtml' => $originalPage['inpHtml'],
                'mobileHtml' => $originalPage['mobileHtml'],
                'cBoxMainCss' => $originalPage['cBoxMainCss'],
                'cBoxSectionCss' => $originalPage['cBoxSectionCss'],
                'cBoxContent' => $originalPage['cBoxContent'],
                'cBoxMobileMainCss' => $originalPage['cBoxMobileMainCss'],
                'cBoxMobileSectionCss' => $originalPage['cBoxMobileSectionCss'],
                'cBoxMobileContent' => $originalPage['cBoxMobileContent'],
                'data_lang' => $lang,
                'isActive' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $builder->insert($cloneData);

            $newPageId = $db->insertID();

            if ($newPageId) {
                return redirect()->to('/page')->with('success', 'Sayfa başarıyla klonlandı.');
            } else {
                return redirect()->back()->with('error', 'Klonlama işlemi başarısız oldu.');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Sayfa klonlama hatası: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Bir hata oluştu. Lütfen tekrar deneyin.');
        }
    }
    public function edit()
    {
        $id = $this->request->getPost('id');
        $data = array(
            'name'          => $this->request->getPost('name'),
            'title'         => $this->request->getPost('title'),
            'desc1'         => $this->request->getPost('desc1'),
            'desc2'         => $this->request->getPost('desc2'),
            'allowButton'   => $this->request->getPost('allowButton'),
            'buttonCaption' => $this->request->getPost('buttonCaption'),
            'buttonUrl'     => $this->request->getPost('buttonUrl'),
            'imgUrl'        => $this->request->getPost('imgUrl'),
            'rank'          => 999,
            'isActive'      => 1,
            'data_lang'     => "tr",
            'lastUpdatedUser'   => session()->get('id_user'),
        );

        if ($this->page_model->updatePage($data, $id)) {

            $integration = new \App\Controllers\Integration;
            $integration->setLog('page-controller',$this->request->getPost('title'). ' updated-page');

            session()->setFlashdata('swal', [
                'icon' => 'success',
                'title' => 'İşlem başarılı!',
                'text'  => 'Sayfa başarıyla Düzenlendi.'
            ]);
        } else {
            session()->setFlashdata('swal', [
                'icon' => 'error',
                'title' => 'Hata oluştu!',
                'text'  => 'Sayfa düzenlenirken bir sorun oluştu.'
            ]);
        }

        return redirect()->to('/page');
    }
    public function pageDelete()
    {
        $id = $this->request->getPost('id');

        // Ensure ID exists
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid page ID.',
                'csrf_token' => csrf_hash() // Include new CSRF token
            ]);
        }

        // Delete the page using the model (assuming you have a delete method)
        if ($this->page_model->deletePage($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Page deleted successfully.',
                'csrf_token' => csrf_hash() // Include new CSRF token
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete the page.',
                'csrf_token' => csrf_hash() // Include new CSRF token
            ]);
        }
    }
    public function isActiveSetter()
    {
        $id = $this->request->getPost('id');
        $isActive = $this->request->getPost('isActive');

        $this->page_model->updatePage(['isActive' => $isActive], $id);

        return $this->response->setJSON([
            'status' => 'success',
            'csrf_token' => csrf_hash() // Yeni CSRF token'ı döndürüyoruz
        ]);
    }
    public function ordering()
    {
        if ($this->request->getPost('id')) {
            $result = $this->do_update($this->request->getPost('id'));

            if ($result) {
                // Başarılı yanıt
                echo json_encode([
                    'success' => true,
                    'message' => 'Sıralama başarıyla kaydedildi.',
                    'csrfName' => csrf_token(), // CSRF token adı
                    'csrfHash' => csrf_hash() // CSRF token değeri
                ]);
            } else {
                // Başarısız yanıt
                echo json_encode([
                    'success' => false,
                    'message' => 'Sıralama kaydedilirken bir hata oluştu.',
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash()
                ]);
            }
        } else {
            // POST verisi eksikse hata döndür
            echo json_encode([
                'success' => false,
                'message' => 'Geçersiz istek, sıralama verisi alınamadı.',
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ]);
        }
    }
    public function do_update($list, $parent_id = 0, &$m_order = 0)
    {
        $db = \Config\Database::connect(); // Veritabanı bağlantısını al
        $builder = $db->table('pages'); // Güncellenecek tabloyu tanımla

        foreach ($list as $item) {
            $m_order++;
            $data = [
                'parent_id' => $parent_id,
                'rank' => $m_order,
            ];

            // Kendi kendisinin ebeveyni olmaması için kontrol
            if ($parent_id != $item['id']) {
                // ID eşleşen kaydı güncelle
                $builder->where('id', $item['id']);
                if (!$builder->update($data)) {
                    return false; // Eğer güncelleme başarısız olursa false döndür
                }
            }

            // Eğer çocukları varsa, onları da rekürsif olarak güncelle
            if (array_key_exists("children", $item)) {
                if (!$this->do_update($item["children"], $item["id"], $m_order)) {
                    return false; // Eğer çocukları güncellerken hata olursa false döndür
                }
            }
        }

        return true; // Eğer her şey başarıyla yapılmışsa true döndür
    }
    public function updateForm($id)
    {

        $item = $this->page_model->getOne($id);

        // Eğer kayıt bulunamadıysa, boş bir nesne oluştur
        if (!$item) {
            $item = new \stdClass();
            $item->id = $id;
            $item->seoKeywords = '[]'; // boş olarak başlat
            $item->referenceID = $id;
        }

        // Eğer array geldiyse objeye dönüştür
        if (is_array($item)) {
            $item = (object) $item;
        }

        // seoKeywords: virgülle ayrılmış string → JSON (Tagify için)
        $keywords = explode(',', $item->seoKeywords ?? '');
        $tagifyKeywords = [];

        foreach ($keywords as $keyword) {
            $trimmed = trim($keyword);
            if (!empty($trimmed)) {
                $tagifyKeywords[] = ['value' => $trimmed];
            }
        }

        $item->seoKeywords = json_encode($tagifyKeywords, JSON_UNESCAPED_UNICODE);

        // Aktif dil ve varsayılan dil belirlenir
        $currentLang = session()->get('lang') ?? getDefaultLanguage();
        $defaultLang = getDefaultLanguage();

        // referenceID belirlenir
        $referenceID = !empty($item->referenceID) ? $item->referenceID : $item->id;

        // Veritabanından mevcut diller alınır
        $db = \Config\Database::connect();
        $builder = $db->table('pages');

        $existingLangs = $builder
            ->select('data_lang')
            ->where('referenceID', $referenceID)
            ->get()
            ->getResultArray();

        $existingLangCodes = array_column($existingLangs, 'data_lang');

        $activeLanguages = getActiveLanguages();
        $missingLanguages = [];

        foreach ($activeLanguages as $lang) {
            if (!in_array($lang['shorten'], $existingLangCodes)) {
                $missingLanguages[] = $lang;
            }
        }

        // View'e aktarılacak veri
        $data = [
            'item' => $item,
            'missingLanguages' => $missingLanguages,
            'currentLang' => $currentLang,
            'defaultLang' => $defaultLang
        ];

        return view('App\Modules\Page\Views\page\update', $data);
    }


    public function currentPageUpdate($id)
    {
        // Giriş verilerini al
        $title              = $this->request->getPost('title');
        $url                = $this->request->getPost('url');
        $seoDesc            = $this->request->getPost('seoDesc');
        $rawKeywords        = $this->request->getPost('seoKeywords');
        $isActive           = $this->request->getPost('isActive');
        $isHeader           = $this->request->getPost('isHeader');
        $breadcrumbStatus   = $this->request->getPost('breadcrumbStatus');
        $breadcrumbTitle    = $this->request->getPost('breadcrumbTitle');
        $breadcrumbSlogan   = $this->request->getPost('breadcrumbSlogan');
        $breadcrumbImage    = $this->request->getPost('breadcrumbImage');
        $isFooter           = $this->request->getPost('isFooter');
        $isWebEditor        = $this->request->getPost('isWebEditor');
        $isMobileEditor     = $this->request->getPost('isMobileEditor');

        if (!$id || !$title || !$url) {
            return redirect()->back()->withInput()->with('error', 'Lütfen gerekli tüm alanları doldurun.');
        }

        $seoKeywords = '';
        $decoded = json_decode($rawKeywords, true);
        if (is_array($decoded)) {
            $tags = [];
            foreach ($decoded as $tag) {
                if (!empty($tag['value'])) {
                    $tags[] = trim($tag['value']);
                }
            }
            $seoKeywords = implode(',', $tags);
        }

        $data = [
            'title'             => $title,
            'url'               => $url,
            'seoDesc'           => $seoDesc ?? null,
            'seoKeywords'       => $seoKeywords,
            'isActive'          => $isActive ?? 0,
            'isHeader'          => $isHeader ?? 0,
            'breadcrumbStatus'  => $breadcrumbStatus ?? 0,
            'breadcrumbTitle'   => $breadcrumbTitle ?? null,
            'breadcrumbSlogan'  => $breadcrumbSlogan ?? null,
            'breadcrumbImage'   => $breadcrumbImage ?? null,
            'isFooter'          => $isFooter ?? 0,
            'isWebEditor'       => $isWebEditor ?? 0,
            'isMobileEditor'    => $isMobileEditor ?? 0,
            'updated_at'        => date('Y-m-d H:i:s'),
            'lastUpdatedUser'   => session()->get('id_user'),
        ];

        $db      = \Config\Database::connect();
        $builder = $db->table('pages');

        $existingRecord = $builder->getWhere(['id' => $id])->getRow();

        if ($existingRecord) {
            $update = $builder->where('id', $id)->update($data);

            if ($update) {

                $integration = new \App\Controllers\Integration;
                $integration->setLog('page-controller',$this->request->getPost('title') . " update-page");

                session()->setFlashdata('swal', [
                    'icon' => 'success',
                    'title' => 'İşlem başarılı!',
                    'text'  => 'Sayfa başarıyla güncellendi.'
                ]);
            } else {
                session()->setFlashdata('swal', [
                    'icon' => 'error',
                    'title' => 'Hata oluştu!',
                    'text'  => 'Sayfa güncellenirken bir hata oluştu.'
                ]);
            }
        } else {
            session()->setFlashdata('swal', [
                'icon' => 'error',
                'title' => 'Hata oluştu!',
                'text'  => 'Güncellenmek istenen kayıt bulunamadı.'
            ]);
        }

        return redirect()->to('/page/updateForm/' . $id);
    }
    public function contentBuilderEdit($id)
    {
        $data['page'] = $this->page_model->getOne($id);
        return view('App\Modules\Page\Views\page\contentBox\contentBuilder',$data);
    }
    public function contentBuilderMobileEdit($id)
    {
        $data['page'] = $this->page_model->getOne($id);
        return view('App\Modules\Page\Views\page\contentBox\contentBuilderMobile',$data);
    }
    public function contentBuilderSave()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        $post = $request->getPost();
        $postID = $post['id'] ?? null;

        if ($postID) {

            $builder = $db->table('pages');
            $builder->where('id', $postID)->update($post);

        } else {
            $builder = $db->table('pages');
            $builder->insert($post);

            // Opsiyonel: eğer insert sonrası ID'yi almak istersen
            $postID = $db->insertID();
        }

        return redirect()->to("page/updateForm/$postID");
    }

    public function contentBuilderMobileSave()
    {
        $request = \Config\Services::request();
        $db = \Config\Database::connect();

        $post = $request->getPost();
        $postID = $post['id'] ?? null;

        if ($postID) {

            $builder = $db->table('pages');
            $builder->where('id', $postID)->update($post);

        } else {
            $builder = $db->table('pages');
            $builder->insert($post);

            // Opsiyonel: eğer insert sonrası ID'yi almak istersen
            $postID = $db->insertID();
        }

        return redirect()->to("page/updateForm/$postID");
    }
    private function translateHtmlContent($html, $targetLanguage)
    {
        $apiKey = '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';
        $url = 'https://api.openai.com/v1/chat/completions';

        $prompt = "Aşağıdaki HTML yapısında sadece görünen yazıları {$targetLanguage} diline çevir. HTML etiketlerine dokunma.\n\n" . $html;

        $data = [
            "model" => "gpt-4",
            "messages" => [
                ["role" => "system", "content" => "Profesyonel bir çevirmen gibi çalışıyorsun."],
                ["role" => "user", "content" => $prompt]
            ],
            "temperature" => 0.2,
        ];

        $headers = [
            'Authorization: Bearer ' . $apiKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        curl_close($ch);

        $responseData = json_decode($response, true);

        if (isset($responseData['choices'][0]['message']['content'])) {
            return trim($responseData['choices'][0]['message']['content']);
        }

        return ''; // hata olursa boş döner
    }
    public function contentBoxEdit($id)
    {
        $data['page'] = $this->page_model->getOne($id);

        return view('App\Modules\Page\Views\page\contentBox\index',$data);
    }
    public function contentBoxMobileEdit($id)
    {
        $data['page'] = $this->page_model->getOne($id);

        return view('App\Modules\Page\Views\page\contentBox\mobileIndex',$data);
    }
    public function sendCommand()
    {
        // Load the configuration
        $config = new FileConfig();
        $apiKey = $config->OPENAI_API_KEY;
        $url = 'https://api.openai.com/v1/chat/completions';

        $model = 'gpt-3.5-turbo';
        $DEFAULT_TEMPERATURE = 0.6;
        $DEFAULT_TOP_P = 0.9;
        $DEFAULT_NUM = 1;

        // Get the POST data
        $body = $this->request->getJSON(true);
        $question = $body['question'];
        $context = $body['context'] ?? '';
        $system = $body['system'];
        $functs = $body['functs'] ?? null;
        $temperature = $body['temperature'] ?? $DEFAULT_TEMPERATURE;
        $topP = $body['topP'] ?? $DEFAULT_TOP_P;
        $num = $body['num'] ?? $DEFAULT_NUM;

        // Create the message structure
        $messages = [
            ['role' => 'system', 'content' => $system],
            ['role' => 'assistant', 'content' => $context],
            ['role' => 'user', 'content' => $question]
        ];

        // Create the headers
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ];

        // Create the data array
        $data = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => floatval($temperature),
            'top_p' => floatval($topP),
            'n' => intval($num),
        ];

        if (!empty($functs)) {
            $data['functions'] = $functs;
        }

        // Send the request using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);

        if ($response === false) {
            // Handle the cURL error
            return $this->respond(['error' => curl_error($ch)], 500);
        } else {
            $responseData = json_decode($response, true);

            $answer = empty($functs)
                ? $responseData
                : ($responseData['choices'][0]['message']['function_call']
                    ? $responseData['choices'][0]['message']['function_call']['arguments']
                    : $responseData['choices'][0]['message']);

            curl_close($ch);

            return $this->respond(['answer' => $answer]);
        }
    }
    public function uploadFile()
    {
        // Load FileConfig settings
        $config = new FileConfig();
        $path = $config->path;   // Get the path from config
        $urlpath = $config->urlpath; // Get the URL path from config

        if ($this->request->getMethod() === 'post') {

            // Get the file from the request
            $file = $this->request->getFile('file');

            if (!$file->isValid()) {
                return $this->respond(['error' => 'Invalid file'], 400);
            }

            // Get file extension
            $ext = $file->getExtension();

            // Allowed file types
            $allowedTypes = ['jpg', 'jpeg', 'gif', 'png', 'webp', 'svg', 'wepm', 'ico', 'mp4', 'mp3'];
            if (!in_array($ext, $allowedTypes)) {
                return $this->respond(['error' => 'File type not allowed'], 400);
            }

            // Generate the new filename (original name + extension)
            $filename = pathinfo($file->getName(), PATHINFO_FILENAME) . '.' . $ext;

            // Move the file to the uploads directory
            if ($file->move($path, $filename)) {
                $uploadedUrl = $urlpath . $filename;
                return $this->respond(['url' => $uploadedUrl], 200);
            } else {
                return $this->respond(['error' => 'Failed to upload file'], 500);
            }
        }
    }
    public function saveContent()
    {
        $session = session();
        $requestData = $this->request->getJSON(true);

        $content = $requestData['cBoxContent'] ?? null;
        $mainCss = $requestData['cBoxMainCss'] ?? null;
        $sectionCss = $requestData['cBoxSectionCss'] ?? null;
        $pageId = $requestData['id'] ?? 0;

        if (!$pageId) {
            return $this->response->setJSON(['error' => 'ID bulunamadı']);
        }

        $session->set([
            'cBoxContent' => $content,
            'cBoxMainCss' => $mainCss,
            'cBoxSectionCss' => $sectionCss
        ]);

        $data = [
            'cBoxContent' => $content,
            'cBoxMainCss' => $mainCss,
            'cBoxSectionCss' => $sectionCss
        ];

        $updated = $this->page_model->updatePage($data, $pageId);

        // Eğer hata oluştuysa
        if ($updated) {
            return $this->response->setJSON(['error' => 'Güncelleme başarısız', 'details' => $this->page_model->errors()]);
        }

        return $this->response->setJSON(['success' => 'İçerik başarıyla güncellendi', 'content' => $content]);
    }
    public function saveMobileContent()
    {
        $session = session();
        $requestData = $this->request->getJSON(true);

        $content = $requestData['cBoxMobileContent'] ?? null;
        $mainCss = $requestData['cBoxMobileMainCss'] ?? null;
        $sectionCss = $requestData['cBoxMobileSectionCss'] ?? null;
        $pageId = $requestData['id'] ?? 0;

        if (!$pageId) {
            return $this->response->setJSON(['error' => 'ID bulunamadı']);
        }

        $session->set([
            'cBoxMobileContent' => $content,
            'cBoxMobileMainCss' => $mainCss,
            'cBoxMobileSectionCss' => $sectionCss
        ]);

        $data = [
            'cBoxMobileContent' => $content,
            'cBoxMobileMainCss' => $mainCss,
            'cBoxMobileSectionCss' => $sectionCss
        ];

        $updated = $this->page_model->updatePage($data, $pageId);

        if ($updated) {
            return $this->response->setJSON(['error' => 'Güncelleme başarısız', 'details' => $this->page_model->errors()]);
        }

        return $this->response->setJSON(['success' => 'İçerik başarıyla güncellendi', 'content' => $content]);
    }
    public function uploadBase64()
    {
        // Load FileConfig settings
        $config = new FileConfig();
        $path = $config->path;   // Get the path from config
        $urlpath = $config->urlpath; // Get the URL path from config

        if ($this->request->getMethod() === 'post') {
            // Get the request data
            $requestData = $this->request->getJSON(true);
            $b64str = $requestData['image'];
            $filename = $requestData['filename'];

            // Extract file extension
            $path_info = pathinfo($filename);
            $ext = strtolower($path_info['extension']);

            // Validate allowed file extensions
            $allowedTypes = ['jpg', 'jpeg', 'gif', 'png', 'webp', 'svg', 'wepm', 'ico', 'mp4', 'mp3'];
            if (!in_array($ext, $allowedTypes)) {
                return $this->respond(['error' => 'File type not allowed'], 400);
            }

            // Save the file to the path
            $fullPath = $path . $filename;
            if (file_put_contents($fullPath, base64_decode($b64str))) {
                // Return the file URL
                return $this->respond(['url' => $urlpath . $filename], 200);
            } else {
                return $this->respond(['error' => 'Failed to save file'], 500);
            }
        }
    }
    public function contentBoxPreview($id)
    {
        $data['page'] = $this->page_model->getOne($id);
        return view('App\Modules\Page\Views\page\contentBox\preview',$data);
    }
    public function contentBoxPageBlank()
    {
        return view('App\Modules\Page\Views\page\contentBox\blank');
    }
}
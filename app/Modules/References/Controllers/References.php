<?php
namespace App\Modules\References\Controllers;

use App\Controllers\BaseController;
use App\Modules\References\Models\ReferencesModel;
use App\Modules\References\Models\ReferencesCategoryModel;
use App\Modules\References\Models\ReferencesImagesModel;


/**
 * Class Task
 */
class References extends BaseController
{

    private $reference_model;
    protected $referenceCategory_model;
    protected $referenceImages_model;


    function __construct()
    {
        $this->reference_model = new ReferencesModel();
        $this->referenceCategory_model = new ReferencesCategoryModel();
        $this->referenceImages_model = new ReferencesImagesModel();

    }

    public function index()
    {
        $data['title'] = [
            'module' => lang("App.menu_reference_category"),
            'page'   => lang("App.menu_reference_category"),
            'icon'  => 'fas fa-user-lock'
        ];

        $data['breadcrumb'] = [
            ['title' => lang("App.menu_dashboard"), 'route' => "/home", 'active' => false],
            ['title' => lang("App.menu_reference_category"), 'route'  => "", 'active' => true]
        ];

        $data['btn_add'] = [
            'title' => lang("App.reference_add"),
            'route'   => '/references/newForm',
            'class'   => 'btn btn-lg btn-primary float-md-right',
            'icon'  => 'fas fa-plus'
        ];

        return view('App\Modules\References\Views\references\index',$data);
    }
    public function newForm()
    {
        $data['title'] = [
            'module' => lang("App.reference_category_add"),
            'page'   => lang("App.menu_reference_category"),
            'icon'  => 'fas fa-user-lock'
        ];

        $data['breadcrumb'] = [
            ['title' => lang("App.menu_dashboard"), 'route' => "/home", 'active' => false],
            ['title' => lang("App.menu_reference_category"), 'route'  => "", 'active' => true]
        ];

        return view('App\Modules\References\Views\references\newForm', $data);
    }
    public function store()
    {
        $db = \Config\Database::connect();

        $seoKeywordsJson = $this->request->getPost('seoKeywords');
        $seoKeywordsArr = json_decode($seoKeywordsJson, true);

        $seoKeywords = [];

        if (!empty($seoKeywordsArr)) {
            foreach ($seoKeywordsArr as $keyword) {
                if (!empty($keyword['value'])) {
                    $seoKeywords[] = trim($keyword['value']);
                }
            }
        }

        $originalTitle           = $this->request->getPost('title');
        $originalDescription     = $this->request->getPost('description');
        $originalLocation        = $this->request->getPost('location');
        $originalCategoryId      = $this->request->getPost('category_id');
        $originalSeoKeywords     = implode(',', $seoKeywords);
        $originalSeoDesc         = $this->request->getPost('seoDesc');
        $originalPicturePrice    = $this->request->getPost('picturePrice');
        $originalYear            = $this->request->getPost('year');

        $lastSlider = $db->table('references')->orderBy('id', 'DESC')->get(1)->getRowArray();
        $referenceID = $lastSlider ? $lastSlider['id'] + 1 : 1;

        $baseData = [
            'title'         => $originalTitle,
            'description'   => $originalDescription,
            'location'      => $originalLocation,
            'category_id'   => $originalCategoryId,
            'seoKeywords'   => $originalSeoKeywords,
            'seoDesc'       => $originalSeoDesc,
            'picturePrice'  => $originalPicturePrice,
            'year'          => $originalYear,
            'url'           => seoFriendly($originalTitle),
            'rank'          => 999,
            'isActive'      => 1,
            'createdAt'     => date('Y-m-d H:i:s'),
            'createdUser'   => session()->get('id_user'),
            'data_lang'     => 'tr',
            'referenceID'   => $referenceID,
        ];

        $integration = new \App\Controllers\Integration;
        $integration->setLog('references-controller', $originalTitle . ' add-new-references');

        if ($this->reference_model->insert($baseData)) {
            session()->setFlashdata('swal', [
                'icon' => 'success',
                'title' => 'İşlem başarılı!',
                'text'  => 'Referans başarıyla eklendi.'
            ]);
        } else {
            session()->setFlashdata('swal', [
                'icon' => 'error',
                'title' => 'Hata oluştu!',
                'text'  => 'Referans eklenirken bir sorun oluştu.'
            ]);
        }

        return redirect()->to('/references');
    }
    public function getReferences()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                "draw" => 0,
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => []
            ]);
        }

        $postData = $this->request->getPost();
        $draw = isset($postData['draw']) ? intval($postData['draw']) : 1;
        $start = isset($postData['start']) ? intval($postData['start']) : 0;
        $rowperpage = isset($postData['length']) ? intval($postData['length']) : 10;
        $columnIndex = isset($postData['order'][0]['column']) ? intval($postData['order'][0]['column']) : 0;
        $columnName = isset($postData['columns'][$columnIndex]['data']) ? $postData['columns'][$columnIndex]['data'] : 'createdAt';

        $columnSortOrder = isset($postData['order'][0]['dir']) ? $postData['order'][0]['dir'] : 'desc';
        $searchValue = isset($postData['search']['value']) ? $postData['search']['value'] : '';

        $language = session()->get('lang');

        $allowedColumns = ['title', 'category_id', 'createdAt', 'updatedAt','rank' ];
        if (!in_array($columnName, $allowedColumns)) {
            $columnName = 'createdAt';
        }

        // Toplam kayıt sayısı
        $totalRecords = $this->reference_model
            ->where('data_lang', $language)
            ->countAllResults();

        // Filtrelenmiş kayıt sayısı
        $filterQuery = $this->reference_model
            ->where('data_lang', $language);
        if (!empty($searchValue)) {
            $filterQuery->like('title', $searchValue);
        }
        $totalRecordsWithFilter = $filterQuery->countAllResults();

        // Kayıtları getir
        $recordQuery = $this->reference_model
            ->where('data_lang', $language);
        if (!empty($searchValue)) {
            $recordQuery->like('title', $searchValue);
        }
        $records = $recordQuery
            ->orderBy('rank', 'ASC')
            ->findAll($rowperpage, $start);

        // Aktif diller
        $activeLanguages = getActiveLanguages();
        $activeShortCodes = array_column($activeLanguages, 'shorten');

        $data = [];
        foreach ($records as $record) {
            $editLink = base_url('references/updateForm/' . $record['id']);
            $imageLink = base_url('references/imageForm/' . $record['id']);

            // Eksik dil versiyonlarını hesapla
            $refID = $record['referenceID'] ?? $record['id'];
            $existingLangs = $this->reference_model
                ->select('data_lang')
                ->where('referenceID', $refID)
                ->groupBy('data_lang')
                ->findColumn('data_lang') ?? [];
            $missingLangs = array_diff($activeShortCodes, $existingLangs);

            // Butonları hazırla
            $langButtons = '';
            foreach ($missingLangs as $lang) {
                $langButtons .= '<a href="#" class="btn btn-sm btn-info generate-reference-btn me-1"
                                data-id="' . $refID . '"
                                data-lang="' . $lang . '">' . strtoupper($lang) . ' Oluştur</a>';
            }

            $data[] = [
                'DT_RowId' => 'row_' . $record['id'], // Bunu mutlaka ekle
                "title"       => $record['title'],
                "category_id" => $record['category_id'],
                "createdAt"   => $record['createdAt'],
                "isActive"    => '
                <div class="form-check form-switch">
                    <input class="form-check-input toggle-active" type="checkbox" data-id="' . $record['id'] . '" ' . ($record['isActive'] ? 'checked' : '') . '>
                </div>',
                "isFront"     => '
                <div class="form-check form-switch">
                    <input class="form-check-input toggle-cover" type="checkbox" data-id="' . $record['id'] . '" ' . ($record['isFront'] ? 'checked' : '') . '>
                </div>',
                "options"     => '
                <div class="d-flex flex-column align-items-end gap-2">
                    <div class="d-flex justify-content-end flex-shrink-0">
                        <a href="' . $editLink . '" class="btn btn-icon btn-color-success btn-bg-secondary btn-active-color-success btn-sm me-2">
                            <i class="bi bi-pencil-square fs-2"></i>                                            
                        </a>  
                        <button type="button" class="btn btn-icon btn-color-danger btn-bg-secondary btn-active-color-danger btn-sm me-2 delete-reference" data-id="' . $record['id'] . '">
                           <i class="bi bi-trash fs-2"></i>                               
                        </button>
                        <a href="' . $imageLink . '" class="btn btn-icon btn-color-primary btn-bg-secondary btn-active-color-success btn-sm me-2">
                            <i class="bi bi-card-image fs-2"></i>                                      
                        </a>  
                        <div class="">' . $langButtons . '</div>
                    </div>
                </div>'
            ];
        }

        return $this->response->setJSON([
            "draw" => $draw,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData" => $data,
            "token" => csrf_hash()
        ]);
    }
    public function toggle()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Geçersiz istek']);
        }

        $id = $this->request->getPost('id');
        $field = $this->request->getPost('field');
        $value = $this->request->getPost('value');

        $allowedFields = ['isActive', 'isFront'];;
        if (!in_array($field, $allowedFields)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Geçersiz alan']);
        }

        $update = $this->reference_model->update($id, [$field => $value]);

        return $this->response->setJSON([
            'status' => $update ? 'success' : 'error',
            'token' => csrf_hash()
        ]);
    }
    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            $project = $this->reference_model->find($id);

            if (!$project) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Proje bulunamadı.',
                    'token' => csrf_hash()
                ]);
            }

            $projectSlug = seoFriendly($project['title']);
            $folderPath = FCPATH . 'uploads/references/' . $projectSlug;

            // Veritabanı kayıtlarını sil
            $this->referenceImages_model->where('reference_id', $id)->delete();
            $this->reference_model->delete($id);

            // Klasörü ve içeriğini sil
            if (is_dir($folderPath)) {
                helper('filesystem');
                delete_files($folderPath, true);
                @rmdir($folderPath);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Proje ve Fotoğraf Albümü silindi.',
                'token' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Geçersiz istek.',
            'token' => csrf_hash()
        ]);
    }
    public function updateForm($id)
    {
        $item = $this->reference_model->getOne($id);

        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Eğer object geliyorsa array'e çevir
        if (is_object($item)) {
            $item = (array) $item;
        }

        // Çoklu kategori varsa explode et
        $selectedCategories = explode(',', $item['category_id']);

        // SEO keyword’leri tagify için hazırla
        $keywords = explode(',', $item['seoKeywords'] ?? '');
        $tagifyKeywords = [];

        foreach ($keywords as $keyword) {
            $trimmed = trim($keyword);
            if (!empty($trimmed)) {
                $tagifyKeywords[] = ['value' => $trimmed];
            }
        }

        $item['seoKeywords'] = json_encode($tagifyKeywords, JSON_UNESCAPED_UNICODE);

        $data = [
            'item' => $item,
            'selectedCategories' => $selectedCategories,
            'title' => [
                'module' => lang("App.menu_reference_category"),
                'page'   => lang("App.menu_reference_category"),
                'icon'   => 'fas fa-user-lock'
            ],
            'breadcrumb' => [
                ['title' => lang("App.menu_dashboard"), 'route' => "/home", 'active' => false],
                ['title' => lang("App.menu_reference_category"), 'route' => "", 'active' => true]
            ]
        ];

        return view('App\Modules\References\Views\references\update', $data);
    }
    public function update($id)
    {
        helper(['form', 'url']);
        $validation = \Config\Services::validation();

        $rules = [
            'title'         => 'required|min_length[2]|max_length[255]',
            'description'   => 'permit_empty|string',
            'seoKeywords'   => 'permit_empty|string',
            'seoDesc'       => 'permit_empty|string',
            'location'      => 'permit_empty|string',
            'year'          => 'permit_empty|string',
            'category_id'   => 'required',
            'picturePrice'  => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('form_error', $validation->getErrors());
        }

        // Çoklu kategori
        $categoryIDs = $this->request->getPost('category_id');
        $categoryIDsStr = is_array($categoryIDs) ? implode(',', $categoryIDs) : '';

        // SEO keyword’leri işle
        $seoKeywordsJson = $this->request->getPost('seoKeywords');
        $seoKeywordsArr = json_decode($seoKeywordsJson, true);
        $keywords = [];

        if (!empty($seoKeywordsArr)) {
            foreach ($seoKeywordsArr as $keyword) {
                if (!empty($keyword['value'])) {
                    $keywords[] = trim($keyword['value']);
                }
            }
        }

        $seoKeywordsString = implode(',', $keywords);

        // Güncellenecek veri
        $data = [
            'title'         => $this->request->getPost('title'),
            'description'   => $this->request->getPost('description'),
            'seoKeywords'   => $seoKeywordsString,
            'seoDesc'       => $this->request->getPost('seoDesc'),
            'location'      => $this->request->getPost('location'),
            'year'          => $this->request->getPost('year'),
            'category_id'   => $categoryIDsStr,
            'picturePrice'  => $this->request->getPost('picturePrice'),
            'lastUpdatedUser' => session()->get('id_user'),
            'updatedAt'     => date('Y-m-d H:i:s')
        ];

        if ($this->reference_model->update($id, $data)) {
            return redirect()->to(base_url('references'))->with('success', 'Proje başarıyla güncellendi.');
        } else {
            return redirect()->back()->with('error', 'Güncelleme sırasında bir hata oluştu.');
        }
    }
    public function generateLangVersion($referenceID, $targetLang)
    {
        helper(['translate', 'language']); // kendi projendeki translate helper burada devreye giriyor

        $db = \Config\Database::connect();
        $defaultLang = 'tr';

        $original = $db->table('references')
            ->where('referenceID', $referenceID)
            ->where('data_lang', $defaultLang)
            ->get()
            ->getRowArray();

        if (!$original) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Orijinal kayıt bulunamadı.']);
        }

        $exists = $db->table('references')
            ->where('referenceID', $referenceID)
            ->where('data_lang', $targetLang)
            ->countAllResults();

        if ($exists > 0) {
            return $this->response->setJSON(['status' => 'info', 'message' => strtoupper($targetLang) . ' sürümü zaten var.']);
        }

        $translated = [
            'referenceID'   => $original['referenceID'],
            'title'         => translateSliderText($original['title'], $targetLang, $defaultLang),
            'description'   => translateSliderText($original['description'], $targetLang, $defaultLang),
            'location'      => translateSliderText($original['location'], $targetLang, $defaultLang),
            'seoKeywords'   => translateSliderText($original['seoKeywords'], $targetLang, $defaultLang),
            'seoDesc'       => translateSliderText($original['seoDesc'], $targetLang, $defaultLang),
            'year'          => $original['year'],
            'category_id'   => $original['category_id'],
            'picturePrice'  => $original['picturePrice'],
            'rank'          => $original['rank'],
            'isActive'      => 0,
            'createdUser'   => session()->get('id_user'),
            'data_lang'     => $targetLang,
            'url'           => seoFriendly(translateSliderText($original['title'], $targetLang, $defaultLang))
        ];

        if ($this->reference_model->insert($translated)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => strtoupper($targetLang) . ' dili için referans başarıyla oluşturuldu.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Oluşturma sırasında hata oluştu.'
        ]);
    }
    public function imageForm($id)
    {
        // Kayıt getir
        $item = $this->reference_model->getOne($id);

        // Resimleri getir
        $item_images = $this->referenceImages_model
            ->where('reference_id', $id)
            ->orderBy('rank', 'ASC')
            ->findAll();
        $data = [
            'item' => $item,
            'item_images' => $item_images, // <<< Bunu ekliyoruz
            'title' => [
                'module' => $item['title']. ' ' . 'Referans Resimleri' ,
                'page'   => '',
                'icon'   => 'fas fa-user-lock'
            ],
            'breadcrumb' => [
                ['title' => lang("App.menu_dashboard"), 'route' => "/home", 'active' => false],
                ['title' => 'Referans Resim Galerisi', 'route' => "", 'active' => true]
            ],
            'btn_add' => [
                'title' => lang("App.reference_category_add"),
                'route' => '/references/newForm',
                'class' => 'btn btn-lg btn-primary float-md-right',
                'icon' => 'fas fa-plus'
            ]
        ];

        return view('App\Modules\References\Views\references\referenceImages', $data);
    }
    public function image_upload($reference_id)
    {
        helper(['form', 'url']);

        $file = $this->request->getFile('file');
        $mode = $this->request->getGet('mode') ?? 'original';

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Geçersiz dosya.',
                'csrf' => [
                    'token' => csrf_token(),
                    'hash'  => csrf_hash()
                ]
            ]);
        }

        // Proje adını al (slug oluşturmak için)
        $reference = $this->reference_model->find($reference_id);
        $projectSlug = seoFriendly($reference['title']);
        $uploadPath = FCPATH . 'uploads/references/' . $projectSlug . '/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $extension = strtolower($file->getExtension());
        $randomName = pathinfo($file->getRandomName(), PATHINFO_FILENAME);
        $tempPath = $file->getTempName();

        if ($mode === 'original') {
            $finalName = $randomName . '.' . $extension;
            $file->move($uploadPath, $finalName);
        } else {
            // WebP'e çevir
            $finalName = $randomName . '.webp';

            switch ($extension) {
                case 'jpeg': case 'jpg': $image = imagecreatefromjpeg($tempPath); break;
                case 'png': $image = imagecreatefrompng($tempPath); break;
                case 'gif': $image = imagecreatefromgif($tempPath); break;
                case 'webp': $image = imagecreatefromwebp($tempPath); break;
                default:
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Desteklenmeyen dosya türü.',
                        'csrf' => [
                            'token' => csrf_token(),
                            'hash'  => csrf_hash()
                        ]
                    ]);
            }

            if ($mode === 'webp_resize') {
                $maxWidth = 1920;
                $maxHeight = 1080;
                $width = imagesx($image);
                $height = imagesy($image);
                if ($width > $maxWidth || $height > $maxHeight) {
                    $scale = min($maxWidth / $width, $maxHeight / $height);
                    $newWidth = (int)($width * $scale);
                    $newHeight = (int)($height * $scale);
                    $resized = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    imagedestroy($image);
                    $image = $resized;
                }
            }

            imagewebp($image, $uploadPath . $finalName, 80);
            imagedestroy($image);
        }

        $this->referenceImages_model->insert([
            'reference_id' => $reference_id,
            'img_url' => $projectSlug . '/' . $finalName, // klasörle birlikte kaydediyoruz
            'isActive' => 1,
            'isCover' => 0,
            'rank' => 999,
            'createdAt' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Dosya başarıyla yüklendi.',
            'csrf' => [
                'token' => csrf_token(),
                'hash'  => csrf_hash()
            ]
        ]);
    }


    public function refresh_image_list($reference_id)
    {
        $item_images = $this->referenceImages_model
            ->where('reference_id', $reference_id)
            ->orderBy('rank', 'ASC')
            ->findAll();


        return view('App\Modules\References\Views\references\partials\image_list', ['item_images' => $item_images]);
    }
    public function imageIsActiveSetter($id)
    {
        if ($this->request->isAJAX()) {
            $isActive = $this->request->getPost('isActive') ?? 0;

            $this->referenceImages_model->update($id, [
                'isActive' => $isActive
            ]);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Resim durumu başarıyla güncellendi.',
                'csrf' => [
                    'token' => csrf_token(),
                    'hash'  => csrf_hash()
                ]
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Geçersiz istek.',
            'csrf' => [
                'token' => csrf_token(),
                'hash'  => csrf_hash()
            ]
        ]);
    }
    public function imageDelete($id, $reference_id)
    {
        if ($this->request->isAJAX()) {
            $image = $this->referenceImages_model->find($id);

            if (!$image) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Resim bulunamadı.',
                    'csrf' => [
                        'token' => csrf_token(),
                        'hash'  => csrf_hash()
                    ]
                ]);
            }

            // Dosyayı da sil
            $filePath = ROOTPATH . 'public/uploads/references/' . $image['img_url'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Veritabanından sil
            $this->referenceImages_model->delete($id);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Resim başarıyla silindi.',
                'csrf' => [
                    'token' => csrf_token(),
                    'hash'  => csrf_hash()
                ]
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Geçersiz istek.',
            'csrf' => [
                'token' => csrf_token(),
                'hash'  => csrf_hash()
            ]
        ]);
    }
    public function deleteAllImages($reference_id)
    {
        // CSRF kontrolü yapılıyor zaten otomatik
        $images = $this->referenceImages_model->where('reference_id', $reference_id)->findAll();

        foreach ($images as $image) {
            $path = ROOTPATH . 'public/uploads/reference/' . $image['img_url'];
            if (is_file($path)) {
                @unlink($path);
            }
        }

        // Veritabanından da sil
        $this->referenceImages_model->where('reference_id', $reference_id)->delete();

        return $this->response->setJSON([
            'status' => 'success',
            'csrf' => [
                'token' => csrf_token(),
                'hash'  => csrf_hash()
            ]
        ]);
    }
    public function isCoverSetter($id = null, $reference_id = null)
    {
        if (!$this->request->isAJAX() || !$this->request->is('post')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Geçersiz istek.',
                'csrf' => [
                    'token' => csrf_token(),
                    'hash'  => csrf_hash()
                ]
            ]);
        }

        if ($id && $reference_id) {
            $this->referenceImages_model
                ->where('reference_id', $reference_id)
                ->set(['isCover' => 0])
                ->update();

            $this->referenceImages_model
                ->update($id, ['isCover' => 1]);

            return $this->response->setJSON([
                'status' => 'success',
                'csrf' => [
                    'token' => csrf_token(),
                    'hash'  => csrf_hash()
                ]
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Eksik parametre.',
            'csrf' => [
                'token' => csrf_token(),
                'hash'  => csrf_hash()
            ]
        ]);
    }

    public function referenceCategories()
    {
        $db = \Config\Database::connect();

        $lang = session()->get('lang');
        $data['categories'] = $db->table('referenceCategories')
            ->where('data_lang', $lang)
            ->orderBy('rank', 'ASC') // ✅ Sıralama buraya eklendi
            ->get()
            ->getResult();

        return view('App\Modules\References\Views\references\referenceCategories', $data);
    }
    public function categoryAdd()
    {
        $db = \Config\Database::connect();
        $db->transStart(); // Transaction başlatıyoruz

        $originalTitle = $this->request->getPost('title');
        if (empty($originalTitle)) {
            session()->setFlashdata('swal', [
                'icon' => 'warning',
                'title' => 'Başlık boş olamaz!',
                'text'  => 'Lütfen bir başlık girin.'
            ]);
            return redirect()->to('/references/referenceCategories');
        }

        $lastCategory = $db->table('referenceCategories')
            ->select('referenceID')
            ->orderBy('referenceID', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        $referenceID = $lastCategory ? $lastCategory['referenceID'] + 1 : 1;

        $data = [
            'title'         => $originalTitle,
            'url'           => seoFriendly($originalTitle),
            'rank'          => 999,
            'isActive'      => 1,
            'createdAt'     => date('Y-m-d H:i:s'),
            'createdUser'   => session()->get('id_user'),
            'referenceID'   => $referenceID,
            'data_lang'     => 'tr',
        ];

        $this->referenceCategory_model->insert($data);

        // Log kaydı
        $integration = new \App\Controllers\Integration();
        $integration->setLog('reference-controller', "$originalTitle - sadece TR dilinde referans kategorisi eklendi");

        $db->transComplete(); // Transactionu bitiriyoruz

        if ($db->transStatus() === false) {
            session()->setFlashdata('swal', [
                'icon' => 'error',
                'title' => 'Hata oluştu!',
                'text'  => 'Referans Kategorisi eklenirken bir hata meydana geldi.'
            ]);
        } else {
            session()->setFlashdata('swal', [
                'icon' => 'success',
                'title' => 'Başarılı!',
                'text'  => 'Referans Kategorisi başarıyla eklendi.'
            ]);
        }

        return redirect()->to('/references/referenceCategories');
    }
    public function categoryEdit()
    {
        $id = $this->request->getPost('id');
        $data = array(
            'title'         => $this->request->getPost('title'),
            'data_lang'     => "tr",
            'lastUpdatedUser'   => session()->get('id_user'),
        );

        if ($this->referenceCategory_model->updateCategories($data, $id)) {
            session()->setFlashdata('swal', [
                'icon' => 'success',
                'title' => 'İşlem başarılı!',
                'text'  => 'Slider başarıyla güncellendi.'
            ]);
        } else {
            session()->setFlashdata('swal', [
                'icon' => 'error',
                'title' => 'Hata oluştu!',
                'text'  => 'Slider güncellenirken bir sorun oluştu.'
            ]);
        }

        return redirect()->to('/references/referenceCategories');
    }
    public function categoryDelete()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Geçersiz ID'
            ]);
        }

        // Önce bu ID'ye ait referenceID'yi bul
        $item = $this->referenceCategory_model
            ->select('referenceID')
            ->find($id);

        if (!$item) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Kayıt bulunamadı'
            ]);
        }

        $referenceID = $item['referenceID'];

        // Şimdi aynı referenceID'ye sahip tüm kayıtları silelim
        $this->referenceCategory_model
            ->where('referenceID', $referenceID)
            ->delete();

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Kayıt ve tüm dillerdeki kopyaları başarıyla silindi.'
        ]);
    }
    public function categoryRankUpdate()
    {
        $response = ['status' => 'error'];
        $order = $this->request->getPost('order');

        if (is_array($order)) {
            foreach ($order as $rank => $id) {
                $this->referenceCategory_model->update($id, ['rank' => $rank]);
            }
            $response['status'] = 'success';
        } else {
            $response['message'] = 'Geçerli sıralama verisi alınamadı.';
        }

        return $this->response->setJSON($response);
    }
    public function categoryIsActiveSetter()
    {
        $id = $this->request->getPost('id');
        $isActive = $this->request->getPost('isActive');

        $this->referenceCategory_model->updateCategories(['isActive' => $isActive], $id);

        return $this->response->setJSON([
            'status' => 'success',
            'csrfToken' => csrf_hash()
        ]);
    }

    public function updateRank()
    {
        $ids = $this->request->getPost('order');

        if (!$ids || !is_array($ids)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Geçersiz sıralama verisi'
            ]);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('references');

        foreach ($ids as $rank => $id) {
            $builder->where('id', (int)$id)->update(['rank' => $rank]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'token' => csrf_hash()
        ]);
    }

    public function imageRankSetter()
    {
        $order = $this->request->getPost('order'); // ['ord-3','ord-1','ord-2',...]
        if ($order && is_array($order)) {
            foreach ($order as $rank => $idString) {
                $id = (int) str_replace('ord-', '', $idString);
                $this->referenceImages_model->update($id, ['rank' => $rank]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'csrf'   => [
                    'token' => csrf_token(),
                    'hash'  => csrf_hash()
                ]
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Sıralama verisi alınamadı'
        ]);
    }



}
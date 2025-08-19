<?php
namespace App\Modules\Blogs\Controllers;

use App\Controllers\BaseController;
use App\Modules\Blogs\Models\BlogsModel;
use App\Modules\Blogs\Models\BlogsCategoryModel;
use App\Modules\Blogs\Models\BlogsImagesModel;


/**
 * Class Task
 */
class Blogs extends BaseController
{

    private $blog_model;
    protected $blogCategory_model;
    protected $blogImages_model;


    function __construct()
    {
        $this->blog_model = new BlogsModel();
        $this->blogCategory_model = new BlogsCategoryModel();
        $this->blogImages_model = new BlogsImagesModel();

    }

    public function index()
    {
        $data['title'] = [
            'module' => lang("App.menu_blog_category"),
            'page'   => lang("App.menu_blog_category"),
            'icon'  => 'fas fa-user-lock'
        ];

        $data['breadcrumb'] = [
            ['title' => lang("App.menu_dashboard"), 'route' => "/home", 'active' => false],
            ['title' => lang("App.menu_blog_category"), 'route'  => "", 'active' => true]
        ];

        $data['btn_add'] = [
            'title' => lang("App.blog_category_add"),
            'route'   => '/blogs/newForm',
            'class'   => 'btn btn-lg btn-primary float-md-right',
            'icon'  => 'fas fa-plus'
        ];

        return view('App\Modules\Blogs\Views\blogs\index',$data);
    }
    public function newForm()
    {
        $data['title'] = [
            'module' => lang("App.blog_category_add"),
            'page'   => lang("App.menu_blog_category"),
            'icon'  => 'fas fa-user-lock'
        ];

        $data['breadcrumb'] = [
            ['title' => lang("App.menu_dashboard"), 'route' => "/home", 'active' => false],
            ['title' => lang("App.menu_blog_category"), 'route'  => "", 'active' => true]
        ];

        return view('App\Modules\Blogs\Views\blogs\newForm', $data);
    }
    public function store()
    {
        helper('translate');
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
        $originalDescription    = $this->request->getPost('description');
        $originalLocation       = $this->request->getPost('location');
        $originalCategoryId     = $this->request->getPost('category_id');
        $originalSeoKeywords = implode(',', $seoKeywords);
        $originalSeoDesc        = $this->request->getPost('seoDesc');
        $originalPicturePrice   = $this->request->getPost('picturePrice');
        $originalYear           = $this->request->getPost('year');

        $lastSlider = $db->table('blogs')->orderBy('id', 'DESC')->get(1)->getRowArray();
        $blogID = $lastSlider ? $lastSlider['id'] + 1 : 1;

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
            'blogID'   => $blogID,
        ];

        $integration = new \App\Controllers\Integration;
        $integration->setLog('blogs-controller', $originalTitle . ' add-new-blogs');

        if ($this->blog_model->insert($baseData)) {


            $titleEn         = translateWithGPT4o($originalTitle, 'en') ?: $originalTitle;
            $descriptionEn   = translateWithGPT4o($originalDescription, 'en') ?: $originalDescription;


            $dataEn = $baseData;
            $dataEn['title']          = $titleEn;
            $dataEn['description']    = $descriptionEn;
            $dataEn['location']       = $originalLocation;
            $dataEn['category_id']    = $originalCategoryId;
            $dataEn['picturePrice']   = $originalPicturePrice;
            $dataEn['year']           = $originalYear;
            $dataEn['url']           = seoFriendly($titleEn);
            $dataEn['createdAt']     = date('Y-m-d H:i:s');
            $dataEn['data_lang']      = 'en';
            $dataEn['blogID']    = $blogID;

            $this->blog_model->insert($dataEn);

            // Almanca çeviriler

            $titleDe         = translateWithGPT4o($originalTitle, 'de') ?: $originalTitle;
            $descriptionDe   = translateWithGPT4o($originalDescription, 'de') ?: $originalDescription;

            $dataDe = $baseData;
            $dataDe['title']          = $titleDe;
            $dataDe['description']    = $descriptionDe;
            $dataDe['location']       = $originalLocation;
            $dataDe['category_id']    = $originalCategoryId;
            $dataDe['seoKeywords']    = $originalSeoKeywords;
            $dataDe['year']           = $originalYear;
            $dataDe['url']           = seoFriendly($titleDe);
            $dataDe['data_lang']      = 'de';
            $dataDe['createdAt']     = date('Y-m-d H:i:s');
            $dataDe['blogID']    = $blogID;

            $this->blog_model->insert($dataDe);

            session()->setFlashdata('swal', [
                'icon' => 'success',
                'title' => 'İşlem başarılı!',
                'text'  => 'Referans ve kopyalar başarıyla eklendi.'
            ]);

        } else {
            session()->setFlashdata('swal', [
                'icon' => 'error',
                'title' => 'Hata oluştu!',
                'text'  => 'Referans eklenirken bir sorun oluştu.'
            ]);
        }

        return redirect()->to('/blogs');
    }
    public function getBlogs()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                "draw" => 0,
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => []
            ]);
        }

        // Güvenli POST verisi çekme
        $postData = $this->request->getPost();
        $draw = isset($postData['draw']) ? intval($postData['draw']) : 1;
        $start = isset($postData['start']) ? intval($postData['start']) : 0;
        $rowperpage = isset($postData['length']) ? intval($postData['length']) : 10;
        $columnIndex = isset($postData['order'][0]['column']) ? intval($postData['order'][0]['column']) : 0;
        $columnName = isset($postData['columns'][$columnIndex]['data']) ? $postData['columns'][$columnIndex]['data'] : 'createdAt';
        $columnSortOrder = isset($postData['order'][0]['dir']) ? $postData['order'][0]['dir'] : 'desc';
        $searchValue = isset($postData['search']['value']) ? $postData['search']['value'] : '';

        // Session'daki dili al
        $language = session()->get('lang'); // veya session()->get('locale') kullanıyorsan ona göre

        // Güvenli kolon ismi
        $allowedColumns = ['title', 'category_id', 'createdAt', 'updatedAt'];
        if (!in_array($columnName, $allowedColumns)) {
            $columnName = 'createdAt';
        }

        // Toplam kayıt sayısı (dile göre)
        $totalRecords = $this->blog_model
            ->where('data_lang', $language)
            ->select('id')
            ->countAllResults();

        // Filtrelenmiş kayıt sayısı
        $filterQuery = $this->blog_model
            ->where('data_lang', $language)
            ->select('id');

        if (!empty($searchValue)) {
            $filterQuery->like('title', $searchValue);
        }
        $totalRecordsWithFilter = $filterQuery->countAllResults();

        // Kayıtları getir
        $recordQuery = $this->blog_model
            ->where('data_lang', $language)
            ->select('*');

        if (!empty($searchValue)) {
            $recordQuery->like('title', $searchValue);
        }

        $records = $recordQuery
            ->orderBy($columnName, $columnSortOrder)
            ->findAll($rowperpage, $start);

        // Cevap dizisini hazırla
        $data = [];
        foreach ($records as $record) {
            $editLink = base_url('blogs/updateForm/' . $record['id']);
            $imageLink = base_url('blogs/imageForm/' . $record['id']);

            $data[] = [
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
            <div class="d-flex justify-content-end flex-shrink-0">
                <a href="' . $editLink . '" class="btn btn-icon btn-color-success btn-bg-secondary btn-active-color-success btn-sm me-3">
                    <i class="bi bi-pencil-square fs-2"></i>                                            
                </a>  
                <button type="button" class="btn btn-icon btn-color-danger btn-bg-secondary btn-active-color-danger btn-sm me-3 delete-blog" data-id="' . $record['id'] . '">
                   <i class="bi bi-trash fs-2"></i>                               
                </button>
                <a href="' . $imageLink . '" class="btn btn-icon btn-color-primary btn-bg-secondary btn-active-color-success btn-sm me-3">
                    <i class="bi bi-card-image fs-2"></i>                                      
                </a>  
            </div>'
            ];
        }

        // JSON döndür
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

        $update = $this->blog_model->update($id, [$field => $value]);

        return $this->response->setJSON([
            'status' => $update ? 'success' : 'error',
            'token' => csrf_hash()
        ]);
    }
    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');

            if (!$id) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Geçersiz ID.',
                    'token' => csrf_hash()
                ]);
            }

            if ($this->blog_model->delete($id)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Kayıt başarıyla silindi.',
                    'token' => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Kayıt silinemedi.',
                    'token' => csrf_hash()
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Geçersiz istek.',
            'token' => csrf_hash()
        ]);
    }
    public function updateForm($id)
    {
        $item = $this->blog_model->getOne($id);

        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Eğer object geliyorsa array'e çevir
        if (is_object($item)) {
            $item = (array) $item;
        }

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
            'title' => [
                'module' => lang("App.menu_blog_category"),
                'page'   => lang("App.menu_blog_category"),
                'icon'   => 'fas fa-user-lock'
            ],
            'breadcrumb' => [
                ['title' => lang("App.menu_dashboard"), 'route' => "/home", 'active' => false],
                ['title' => lang("App.menu_blog_category"), 'route' => "", 'active' => true]
            ],
            'btn_add' => [
                'title' => lang("App.blog_category_add"),
                'route' => '/blogs/newForm',
                'class' => 'btn btn-lg btn-primary float-md-right',
                'icon' => 'fas fa-plus'
            ]
        ];

        return view('App\Modules\Blogs\Views\blogs\update', $data);
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
            'category_id'   => 'required|integer',
            'picturePrice'  => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('form_error', $validation->getErrors());
        }

        // seoKeywords JSON çözüm
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

        // Model

        $data = [
            'title'         => $this->request->getPost('title'),
            'description'   => $this->request->getPost('description'),
            'seoKeywords'   => $seoKeywordsString,
            'seoDesc'       => $this->request->getPost('seoDesc'),
            'location'      => $this->request->getPost('location'),
            'year'          => $this->request->getPost('year'),
            'category_id'   => $this->request->getPost('category_id'),
            'picturePrice'  => $this->request->getPost('picturePrice'),
        ];

        if ($this->blog_model->update($id, $data)) {
            return redirect()->to(base_url('blogs'))->with('success', 'Proje başarıyla güncellendi.');
        } else {
            return redirect()->back()->with('error', 'Güncelleme sırasında bir hata oluştu.');
        }
    }
    public function imageForm($id)
    {
        // Kayıt getir
        $item = $this->blog_model->getOne($id);

        // Resimleri getir
        $item_images = $this->blogImages_model
            ->where('blog_id', $id)
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
                'title' => lang("App.blog_category_add"),
                'route' => '/blogs/newForm',
                'class' => 'btn btn-lg btn-primary float-md-right',
                'icon' => 'fas fa-plus'
            ]
        ];

        return view('App\Modules\Blogs\Views\blogs\blogImages', $data);
    }
    public function image_upload($blog_id)
    {
        helper(['form', 'url']);

        $file = $this->request->getFile('file');

        if ($file && $file->isValid()) {
            $originalName = $file->getClientName();
            $extension = strtolower($file->getExtension());

            // Random isim üret (uzantısız)
            $randomNameWithoutExtension = pathinfo($file->getRandomName(), PATHINFO_FILENAME);

            // Yeni dosya ismi .webp uzantılı olacak
            $newName = $randomNameWithoutExtension . '.webp';

            // Geçici dosya yolu
            $tempPath = $file->getTempName();

            // WebP'ye dönüştür
            $image = null;
            switch ($extension) {
                case 'jpeg':
                case 'jpg':
                    $image = imagecreatefromjpeg($tempPath);
                    break;
                case 'png':
                    $image = imagecreatefrompng($tempPath);
                    break;
                case 'gif':
                    $image = imagecreatefromgif($tempPath);
                    break;
                case 'webp':
                    $image = imagecreatefromwebp($tempPath);
                    break;
                default:
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Bu dosya türü desteklenmiyor.',
                        'csrf' => [
                            'token' => csrf_token(),
                            'hash'  => csrf_hash()
                        ]
                    ]);
            }

            if ($image) {
                $savePath = ROOTPATH . 'public/uploads/blog/' . $newName;
                imagewebp($image, $savePath, 80);
                imagedestroy($image);

                $this->blogImages_model->insert([
                    'blog_id' => $blog_id,
                    'img_url'      => $newName,
                    'isActive'     => 1,
                    'isCover'      => 0,
                    'rank'         => 0,
                    'createdAt'    => date('Y-m-d H:i:s')
                ]);

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Dosya başarıyla webp olarak kaydedildi.',
                    'csrf' => [
                        'token' => csrf_token(),
                        'hash'  => csrf_hash()
                    ]
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Resim dönüştürme başarısız oldu.',
                    'csrf' => [
                        'token' => csrf_token(),
                        'hash'  => csrf_hash()
                    ]
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Geçersiz dosya.',
            'csrf' => [
                'token' => csrf_token(),
                'hash'  => csrf_hash()
            ]
        ]);
    }
    public function refresh_image_list($blog_id)
    {
        $item_images = $this->blogImages_model
            ->where('blog_id', $blog_id)
            ->orderBy('rank', 'ASC')
            ->findAll();


        return view('App\Modules\Blogs\Views\blogs\partials\image_list', ['item_images' => $item_images]);
    }
    public function imageIsActiveSetter($id)
    {
        if ($this->request->isAJAX()) {
            $isActive = $this->request->getPost('isActive') ?? 0;

            $this->blogImages_model->update($id, [
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
    public function imageDelete($id, $blog_id)
    {
        if ($this->request->isAJAX()) {
            $image = $this->blogImages_model->find($id);

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
            $filePath = ROOTPATH . 'public/uploads/blog/' . $image['img_url'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Veritabanından sil
            $this->blogImages_model->delete($id);

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
    public function deleteAllImages($blog_id)
    {
        // CSRF kontrolü yapılıyor zaten otomatik
        $images = $this->blogImages_model->where('blog_id', $blog_id)->findAll();

        foreach ($images as $image) {
            $path = ROOTPATH . 'public/uploads/blog/' . $image['img_url'];
            if (is_file($path)) {
                @unlink($path);
            }
        }

        // Veritabanından da sil
        $this->blogImages_model->where('blog_id', $blog_id)->delete();

        return $this->response->setJSON([
            'status' => 'success',
            'csrf' => [
                'token' => csrf_token(),
                'hash'  => csrf_hash()
            ]
        ]);
    }
    public function isCoverSetter($id, $blog_id)
    {
        if ($this->request->isAJAX()) {
            // Önce bu referansa ait tüm resimlerin isCover değerini 0 yap
            $this->blogImages_model
                ->where('blog_id', $blog_id)
                ->set(['isCover' => 0])
                ->update();

            // Sonra sadece seçilen resmi isCover=1 yap
            $this->blogImages_model->update($id, [
                'isCover' => 1
            ]);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Kapak resmi başarıyla seçildi.',
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
    public function blogCategories()
    {
        $db = \Config\Database::connect();

        $lang = session()->get('lang');
        $data['categories'] = $db->table('blogCategories')
            ->where('data_lang', $lang)
            ->orderBy('rank', 'ASC') // ✅ Sıralama buraya eklendi
            ->get()
            ->getResult();

        return view('App\Modules\Blogs\Views\blogs\blogCategories', $data);
    }
    public function categoryAdd()
    {
        helper('translate');

        $db = \Config\Database::connect();
        $db->transStart(); // Transaction başlatıyoruz

        $originalTitle = $this->request->getPost('title');
        if (empty($originalTitle)) {
            session()->setFlashdata('swal', [
                'icon' => 'warning',
                'title' => 'Başlık boş olamaz!',
                'text'  => 'Lütfen bir başlık girin.'
            ]);
            return redirect()->to('/blogs/blogCategories');
        }

        $lastCategory = $db->table('blogCategories')
            ->select('blogID')
            ->orderBy('blogID', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        $blogID = $lastCategory ? $lastCategory['blogID'] + 1 : 1;

        $baseData = [
            'title'         => $originalTitle,
            'url'           => seoFriendly($originalTitle),
            'rank'          => 999,
            'isActive'      => 1,
            'createdAt'     => date('Y-m-d H:i:s'),
            'createdUser'   => session()->get('id_user'),
            'blogID'   => $blogID,
        ];

        $langs = ['tr', 'en', 'de'];
        $titles = [
            'tr' => $originalTitle,
            'en' => translateWithGPT4o($originalTitle, 'en') ?: $originalTitle,
            'de' => translateWithGPT4o($originalTitle, 'de') ?: $originalTitle,
        ];

        foreach ($langs as $lang) {
            $data = $baseData;
            $data['data_lang'] = $lang;
            $data['title'] = $titles[$lang];
            $data['url'] = seoFriendly($titles[$lang]);

            $this->blogCategory_model->insert($data);
        }

        // Log kaydı
        $integration = new \App\Controllers\Integration();
        $integration->setLog('blog-controller', "$originalTitle - çoklu dilde referans kategorisi eklendi");

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
                'text'  => 'Referans Kategorisi ve çevirileri başarıyla eklendi.'
            ]);
        }

        return redirect()->to('/blogs/blogCategories');
    }
    public function categoryEdit()
    {
        $id = $this->request->getPost('id');
        $data = array(
            'title'         => $this->request->getPost('title'),
            'data_lang'     => "tr",
            'lastUpdatedUser'   => session()->get('id_user'),
        );

        if ($this->blogCategory_model->updateCategories($data, $id)) {
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

        return redirect()->to('/blogs/blogCategories');
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

        // Önce bu ID'ye ait blogID'yi bul
        $item = $this->blogCategory_model
            ->select('blogID')
            ->find($id);

        if (!$item) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Kayıt bulunamadı'
            ]);
        }

        $blogID = $item['blogID'];

        // Şimdi aynı blogID'ye sahip tüm kayıtları silelim
        $this->blogCategory_model
            ->where('blogID', $blogID)
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
                $this->blogCategory_model->update($id, ['rank' => $rank]);
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

        $this->blogCategory_model->updateCategories(['isActive' => $isActive], $id);

        return $this->response->setJSON([
            'status' => 'success',
            'csrfToken' => csrf_hash()
        ]);
    }

}
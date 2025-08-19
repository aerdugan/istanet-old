<?php
namespace App\Modules\Slider\Controllers;

use App\Controllers\BaseController;
use App\Modules\Slider\Models\SliderModel;


/**
 * Class Task
 */
class Slider extends BaseController
{

    private $slider_model;

    function __construct()
    {
        $this->slider_model = new SliderModel();
    }
    public function index()
    {
        if (!user_can('slider.slider.index')) {
            // SweetAlert mesajı ayarlıyoruz
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            return redirect()->to('/dashboard');
        }

        $db = \Config\Database::connect();

        $lang = session()->get('data_lang');


        $sliders = $db->table('sliders')
            ->where('data_lang', $lang)
            ->orderBy('rank', 'ASC')
            ->get()
            ->getResult();

        // Tüm diller için referenceID bazlı grup çekiyoruz
        $sliderLangs = $db->table('sliders')
            ->select('referenceID, data_lang')
            ->get()
            ->getResult();

        $referenceLangs = [];
        foreach ($sliderLangs as $item) {
            $referenceLangs[$item->referenceID][] = $item->data_lang;
        }

        $data = [
            'sliders' => $sliders,
            'referenceLangs' => $referenceLangs,
            'activeLanguages' => getActiveLanguages(), // örn: ['en', 'de']
        ];

        return view('App\Modules\Slider\Views\index', $data);
    }

    public function store()
    {
        if (!user_can('slider.slider.index')) {
            // SweetAlert mesajı ayarlıyoruz
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            return redirect()->to('/dashboard');
        }
        $lang = session()->get('data_lang');

        $db = \Config\Database::connect();

        $originalName         = $this->request->getPost('name');
        $originalTitle        = $this->request->getPost('title');
        $originalDesc1        = $this->request->getPost('desc1');
        $originalDesc2        = $this->request->getPost('desc2');
        $originalAllowButton  = $this->request->getPost('allowButton');
        $originalButtonCaption= $this->request->getPost('buttonCaption');
        $originalButtonUrl    = $this->request->getPost('buttonUrl');
        $imgUrl               = $this->request->getPost('imgUrl');

        $lastSlider = $db->table('sliders')->orderBy('id', 'DESC')->get(1)->getRowArray();
        $referenceID = $lastSlider ? $lastSlider['id'] + 1 : 1;
        $userId = auth()->id(); // sadece ID

        $baseData = [
            'name'          => $originalName,
            'title'         => $originalTitle,
            'desc1'         => $originalDesc1,
            'desc2'         => $originalDesc2,
            'allowButton'   => $originalAllowButton,
            'buttonCaption' => $originalButtonCaption,
            'buttonUrl'     => $originalButtonUrl,
            'imgUrl'        => $imgUrl,
            'rank'          => 999,
            'isActive'      => 1,
            'createdUser'   => $userId,
            'data_lang'     => $lang,
            'referenceID'   => $referenceID,
        ];


        if ($this->slider_model->insert($baseData)) {
            session()->setFlashdata('swal', [
                'icon' => 'success',
                'title' => 'İşlem başarılı!',
                'text'  => 'Slider başarıyla eklendi.'
            ]);
        } else {
            session()->setFlashdata('swal', [
                'icon' => 'error',
                'title' => 'Hata oluştu!',
                'text'  => 'Slider eklenirken bir sorun oluştu.'
            ]);
        }

        return redirect()->to('/admin/slider');
    }

    public function edit()
    {
        if (!user_can('slider.slider.edit')) {
            // SweetAlert mesajı ayarlıyoruz
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            return redirect()->to('/dashboard');
        }
        $id = $this->request->getPost('id');
        $userId = auth()->id(); // sadece ID

        $data = array(
            'name'          => $this->request->getPost('name'),
            'title'         => $this->request->getPost('title'),
            'desc1'         => $this->request->getPost('desc1'),
            'desc2'         => $this->request->getPost('desc2'),
            'allowButton'   => $this->request->getPost('allowButton'),
            'buttonCaption' => $this->request->getPost('buttonCaption'),
            'buttonUrl'     => $this->request->getPost('buttonUrl'),
            'imgUrl'        => $this->request->getPost('imgUrl'),
            'data_lang'     => "tr",
            'lastUpdatedUser'   => $userId,
        );

        if ($this->slider_model->updateSlider($data, $id)) {
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

        return redirect()->to('/admin/slider');
    }
    public function delete()
    {
        if (!user_can('slider.slider.delete')) {
            // SweetAlert mesajı ayarlıyoruz
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            return redirect()->to('/dashboard');
        }

        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Geçersiz ID'
            ]);
        }

        // Önce bu ID'ye ait referenceID'yi bul
        $item = $this->slider_model
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
        $this->slider_model
            ->where('referenceID', $referenceID)
            ->delete();

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Kayıt ve tüm dillerdeki kopyaları başarıyla silindi.'
        ]);
    }
    public function updateRank()
    {
        if (!user_can('slider.slider.updateRank')) {
            // SweetAlert mesajı ayarlıyoruz
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            return redirect()->to('/dashboard');
        }

        $response = ['status' => 'error'];

        try {
            $sliderOrder = $this->request->getPost('order');

            if ($sliderOrder && is_array($sliderOrder)) {
                foreach ($sliderOrder as $rank => $id) {
                    $this->slider_model->update($id, ['rank' => $rank]);
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
        if (!user_can('slider.slider.isActiveSetter')) {
            // SweetAlert mesajı ayarlıyoruz
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            return redirect()->to('/dashboard');
        }

        $id = $this->request->getPost('id');
        $isActive = $this->request->getPost('isActive');

        $this->slider_model->updateSlider(['isActive' => $isActive], $id);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function generateLangVersion($referenceID, $targetLang)
    {
        if (!user_can('slider.slider.generateLangVersion')) {
            // SweetAlert mesajı ayarlıyoruz
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            return redirect()->to('/dashboard');
        }

        helper(['translate', 'language']);

        $db = \Config\Database::connect();
        $defaultLang = 'tr';
        $userId = auth()->id(); // sadece ID

        $original = $db->table('sliders')
            ->where('referenceID', $referenceID)
            ->where('data_lang', $defaultLang)
            ->get()
            ->getRowArray();

        if (!$original) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Orijinal kayıt bulunamadı.']);
        }

        $exists = $db->table('sliders')
            ->where('referenceID', $referenceID)
            ->where('data_lang', $targetLang)
            ->countAllResults();
        if ($exists > 0) {
            return $this->response->setJSON(['status' => 'info', 'message' => strtoupper($targetLang) . ' sürümü zaten var.']);
        }
        $translated = [
            'referenceID'     => $original['referenceID'],
            'imgUrl'          => $original['imgUrl'],
            'allowButton'     => $original['allowButton'],
            'rank'            => $original['rank'],
            'isActive'        => 0,
            'createdUser'     => $userId,
            'data_lang'       => $targetLang,
            'name'            => translateSliderText($original['name'], $targetLang, $defaultLang),
            'title'           => translateSliderText($original['title'], $targetLang, $defaultLang),
            'desc1'           => translateSliderText($original['desc1'], $targetLang, $defaultLang),
            'desc2'           => translateSliderText($original['desc2'], $targetLang, $defaultLang),
            'buttonCaption'   => translateSliderText($original['buttonCaption'], $targetLang, $defaultLang),
        ];
        if ($this->slider_model->insert($translated)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => strtoupper($targetLang) . ' dili için slider başarıyla oluşturuldu.'
            ]);
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Oluşturma sırasında hata oluştu.'
        ]);
    }
}
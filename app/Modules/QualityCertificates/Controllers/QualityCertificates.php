<?php
namespace App\Modules\QualityCertificates\Controllers;

use App\Controllers\BaseController;
use App\Modules\QualityCertificates\Models\QualityCertificatesModel;


/**
 * Class Task
 */
class QualityCertificates extends BaseController
{

    private $quality_model;

    function __construct()
    {
        $this->quality_model = new QualityCertificatesModel();
    }
    public function index()
    {
        $db = \Config\Database::connect();

        $lang = session()->get('data_lang');
        $data['quality_certificates'] = $db->table('qualityCertificates')
            ->where('data_lang', $lang)
            ->orderBy('rank', 'ASC')   // rank sütununa göre küçükten büyüğe sırala
            ->get()
            ->getResult();

        return view('App\Modules\QualityCertificates\Views\index', $data);
    }


    public function store()
    {
        $title = $this->request->getPost('title');
        $lang = session()->get('data_lang');
        $userId = auth()->id(); // sadece ID

        // Boş kontrolü
        if (empty($title)) {
            return redirect()->to('/admin/qualityCertificates')->with('error', 'Başlık boş olamaz!');
        }

        // Aynı başlık kontrolü
        $existing = $this->quality_model
            ->where('title', $title)
            ->where('data_lang', $lang)
            ->first();

        if ($existing) {
            return redirect()->to('/admin/qualityCertificates')->with('error', 'Bu başlık zaten kayıtlı!');
        }

        $data = [
            'title'         => $title,
            'imgUrl'        => $this->request->getPost('imgUrl'),
            'iconUrl'       => $this->request->getPost('iconUrl'),
            'rank'          => 999,
            'isActive'      => 1,
            'data_lang'     => $lang,
            'createdUser'   => $userId,
        ];

        $this->quality_model->createQualityCertificate($data);
        return redirect()->to('/admin/qualityCertificates')->with('success', 'Kayıt başarılı.');
    }



    public function edit()
    {
        $id    = (int) $this->request->getPost('id');
        $title = trim((string) $this->request->getPost('title'));

        if ($title === '') {
            return redirect()->back()->with('error', 'Başlık boş olamaz!');
        }

        $data = [
            'title'           => $title,
            'imgUrl'          => $this->request->getPost('imgUrl'),
            'iconUrl'         => $this->request->getPost('iconUrl'),
            'lastUpdatedUser' => function_exists('user_id') ? user_id() : session()->get('id_user'),
        ];

        // Opsiyonel: değişiklik yoksa erken çık
        $old = $this->quality_model->find($id);
        if ($old && $old['title'] === $data['title'] && $old['imgUrl'] === $data['imgUrl'] && $old['iconUrl'] === $data['iconUrl']) {
            return redirect()->to('/admin/qualityCertificates')->with('info', 'Değişiklik yapılmadı.');
        }

        $this->quality_model->updateQualityCertificates($data, $id);
        return redirect()->to('/admin/qualityCertificates')
            ->with('success', 'Kayıt başarılı şekilde güncellendi.');
    }

    public function delete()
    {
        // Sadece POST/AJAX kabul et (opsiyonel ama faydalı)
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Geçersiz istek.'
            ])->setStatusCode(405);
        }

        $id = (int) $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Geçersiz ID'
            ])->setStatusCode(422);
        }

        try {
            // $this->quality_model veya $this->qualityModel — sen hangisini kullanıyorsan OLSUN.
            // Aşağıdaki satırı kendi property adına göre düzelt.
            $ok = $this->quality_model->deleteQualityCertificates($id);  // veya ->delete($id)

            if (!$ok) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Kayıt silinemedi.'
                ])->setStatusCode(500);
            }

            // CSRF token yenile (JS bunu yakalayıp meta’ya yazar)
            return $this->response->setJSON([
                'status'    => 'success',
                'csrfToken' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            // FK kısıtı vb. durumlarda buraya düşebilir
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Silme hatası: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function updateRank()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Geçersiz istek.'
            ])->setStatusCode(405);
        }

        // items = [{id: 12, rank: 1}, ...]  veya  order = [12, 7, 5, ...]
        $items = $this->request->getPost('items');
        $order = $this->request->getPost('order');

        try {
            if (is_array($items) && !empty($items)) {
                // Nesne formatı
                foreach ($items as $row) {
                    $id   = (int) ($row['id']   ?? 0);
                    $rank = (int) ($row['rank'] ?? 0);
                    if ($id > 0) {
                        $this->quality_model->updateQualityCertificates(['rank' => $rank], $id);
                    }
                }
            } elseif (is_array($order) && !empty($order)) {
                // Liste formatı
                foreach (array_values($order) as $idx => $id) {
                    $id = (int) $id;
                    if ($id > 0) {
                        $this->quality_model->updateQualityCertificates(['rank' => $idx + 1], $id);
                    }
                }
            } else {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Geçersiz veri (items/order boş).'
                ])->setStatusCode(422);
            }

            return $this->response->setJSON([
                'status'    => 'success',
                'csrfToken' => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Hata: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function isActiveSetter()
    {
        $id = $this->request->getPost('id');
        $isActive = $this->request->getPost('isActive');

        $this->quality_model->updateQualityCertificates(['isActive' => $isActive], $id);

        return $this->response->setJSON(['status' => 'success']);
    }
}
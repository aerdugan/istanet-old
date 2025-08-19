<?php
namespace App\Modules\Popup\Controllers;

use App\Controllers\BaseController;

/**
 * Class Task
 */
class Popup extends BaseController
{
    private $popup_model;

    function __construct()
    {
        helper('general_helper');

        if (!is_dir(FCPATH . 'modules/popups/')) {
            mkdir(FCPATH . 'modules/popups/', 0755, true);
        }

        $this->popup = FCPATH . 'modules/popups/';

    }

    public function index()
    {
        if (! user_can('Popup.Popup.index')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $allItems = array_diff(scandir($this->popup), ['.', '..']);
        $folders = [];

        foreach ($allItems as $item) {
            if (is_dir($this->popup . $item)) {
                $folders[] = $item;
            }
        }

        return view('App\Modules\Popup\Views\popup\index', ['files' => $folders]);
    }
    public function popupStore()
    {
        if (! user_can('Popup.Popup.popupStore')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $name = $this->request->getPost('name');
        $seoName = seoFriendly($name); // Yardımcı fonksiyonla SEO uyumlu hale getir
        $folderPath = $this->popup . $seoName;

        if (!is_dir($folderPath)) {
            // Klasörü oluştur
            mkdir($folderPath, 0755, true);

            // Dosya yollarını belirle
            $htmlPath = $folderPath . '/index.php';
            $cssPath  = $folderPath . '/style.css';
            $jsPath   = $folderPath . '/script.js';

            // Dosya içeriklerini yaz
            file_put_contents($htmlPath, "<div class='container'>\n    <!-- popup içeriği -->\n</div>");
            file_put_contents($cssPath, "/* popup CSS */\n.container { padding: 20px; }");
            file_put_contents($jsPath, "// popup JS\nconsole.log('popup yüklendi');");

            // Başarı mesajı
            session()->setFlashdata('success', "$name popup başarıyla oluşturuldu.");
        } else {
            // Zaten varsa hata ver
            session()->setFlashdata('error', "$name popup zaten mevcut.");
        }

        return redirect()->to('/admin/popup');
    }
    public function popupEdit($folder)
    {
        if (! user_can('Popup.Popup.popupEdit')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $folderPath = $this->popup . $folder;

        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Belirtilen klasör mevcut değil.");
            return redirect()->to('/admin/popup');
        }

        // Dosyaların yolunu kontrol et
        $indexFile = $folderPath . '/index.php';
        $styleFile = $folderPath . '/style.css';
        $scriptFile = $folderPath . '/script.js';

        // Dosya içeriklerini yükle
        $data = [
            'folder' => $folder,
            'indexContent' => file_exists($indexFile) ? file_get_contents($indexFile) : '',
            'styleContent' => file_exists($styleFile) ? file_get_contents($styleFile) : '',
            'scriptContent' => file_exists($scriptFile) ? file_get_contents($scriptFile) : '',
        ];

        return view('App\Modules\Popup\Views\popup\popupEdit', $data);
    }
    public function popupUpdate($folder)
    {
        if (! user_can('Popup.Popup.popupUpdate')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $folderPath = $this->popup . $folder;

        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Belirtilen klasör mevcut değil.");
            return redirect()->to('/admin/popup');
        }

        // Gelen içerikleri al
        $indexContent = $this->request->getPost('indexContent');
        $styleContent = $this->request->getPost('styleContent');
        $scriptContent = $this->request->getPost('scriptContent');

        // Doğru uzantılarla dosya yolları
        $indexFile = $folderPath . '/index.php';
        $styleFile = $folderPath . '/style.css';
        $scriptFile = $folderPath . '/script.js';

        // Güncelle (varsa yoksa oluştur)
        file_put_contents($indexFile, $indexContent ?? '');
        file_put_contents($styleFile, $styleContent ?? '');
        file_put_contents($scriptFile, $scriptContent ?? '');

        session()->setFlashdata('success', "$folder içeriği başarıyla güncellendi.");
        return redirect()->to('/admin/popup');
    }
    public function popupRename($currentFolder)
    {
        if (! user_can('Popup.Popup.popupRename')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $newName = $this->request->getPost('newName');
        $seoNewName = seoFriendly($newName); // Helper ile SEO uyumlu hale getir
        $currentPath = $this->popup . $currentFolder;
        $newPath = $this->popup . $seoNewName;

        // Kontroller
        if (!is_dir($currentPath)) {
            session()->setFlashdata('error', "Mevcut klasör bulunamadı.");
            return redirect()->to('/admin/popup');
        }

        if (is_dir($newPath)) {
            session()->setFlashdata('error', "Yeni klasör adı zaten mevcut.");
            return redirect()->to('/admin/popup');
        }

        // Klasörü yeniden adlandır
        if (rename($currentPath, $newPath)) {
            session()->setFlashdata('success', "$currentFolder başarıyla $newName olarak yeniden adlandırıldı.");
        } else {
            session()->setFlashdata('error', "Klasör adı değiştirilemedi.");
        }

        return redirect()->to('/admin/popup');
    }
    public function delete($folder)
    {
        if (! user_can('Popup.Popup.delete')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $folderPath = $this->popup . $folder;

        // Kontrol: Klasör mevcut mu?
        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Silinmek istenen klasör bulunamadı.");
            return redirect()->to('/admin/popup');
        }

        // Klasörü ve içeriğini sil
        $this->deleteFolder($folderPath);

        // Başarı mesajını oturuma ekle
        session()->setFlashdata('success', "$folder başarıyla silindi.");
        return redirect()->to('/admin/popup');
    }
    private function deleteFolder($folderPath)
    {
        if (! user_can('Popup.Popup.deleteFolder')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        // Klasördeki tüm dosyaları ve alt klasörleri tarar
        $files = array_diff(scandir($folderPath), ['.', '..']);

        foreach ($files as $file) {
            $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;

            // Eğer bir klasörse, recursive olarak tekrar çağır
            if (is_dir($filePath)) {
                $this->deleteFolder($filePath);
            } else {
                // Dosyayı sil
                unlink($filePath);
            }
        }

        // Boş klasörü sil
        rmdir($folderPath);
    }
    public function popupView($sliderName)
    {
        if (! user_can('Popup.Popup.popupView')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $data['sliderName'] = $sliderName;
        // View'e içerikleri gönder
        return view('App\Modules\Popup\Views\popup\popupView',$data );
    }


}
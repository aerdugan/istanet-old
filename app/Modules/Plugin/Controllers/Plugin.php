<?php
namespace App\Modules\Plugin\Controllers;

use App\Controllers\BaseController;

/**
 * Class Task
 */
class Plugin extends BaseController
{
    protected $plugin;

    function __construct()
    {
        helper('general_helper');

        if (!is_dir(FCPATH . 'modules/plugins/')) {
            mkdir(FCPATH . 'modules/plugins/', 0755, true);
        }

        $this->plugin = FCPATH . 'modules/plugins/';

    }
    public function index()
    {
        if (! user_can('Plugin.Plugin.index')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        // Eğer klasör yoksa oluştur

        // Klasör boş olsa da hata vermesin diye böyle yazıyoruz
        $allItems = [];
        if (is_dir($this->plugin)) {
            $items = scandir($this->plugin);
            if ($items !== false) {
                $allItems = array_diff($items, ['.', '..']);
            }
        }

        $folders = [];

        foreach ($allItems as $item) {
            if (is_dir($this->plugin . $item)) {
                $folders[] = $item;
            }
        }
        return view('App\Modules\Plugin\Views\plugin\index', ['files' => $folders]);
    }
    public function pluginStore()
    {
        if (! user_can('Plugin.Plugin.pluginStore')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $name = $this->request->getPost('name');
        $seoName = seoFriendly($name); // Yardımcı fonksiyonla SEO uyumlu hale getir
        $folderPath = $this->plugin . $seoName;

        if (!is_dir($folderPath)) {
            // Klasörü oluştur
            mkdir($folderPath, 0755, true);

            // Dosya yollarını belirle
            $htmlPath = $folderPath . '/index.php';
            $cssPath  = $folderPath . '/style.css';
            $jsPath   = $folderPath . '/script.js';

            // Dosya içeriklerini yaz
            file_put_contents($htmlPath, "<div class='container'>\n    <!-- Plugin içeriği -->\n</div>");
            file_put_contents($cssPath, "/* Plugin CSS */\n.container { padding: 20px; }");
            file_put_contents($jsPath, "// Plugin JS\nconsole.log('Plugin yüklendi');");

            // Başarı mesajı
            session()->setFlashdata('success', "$name plugin başarıyla oluşturuldu.");
        } else {
            // Zaten varsa hata ver
            session()->setFlashdata('error', "$name plugin zaten mevcut.");
        }

        return redirect()->to('/admin/plugin');
    }
    public function pluginEdit($folder)
    {
        if (! user_can('Plugin.Plugin.pluginEdit')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $folderPath = $this->plugin . $folder;

        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Belirtilen klasör mevcut değil.");
            return redirect()->to('/admin/plugin');
        }

        // Güncel dosya adları (.html, .css, .js)
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

        return view('App\Modules\Plugin\Views\plugin\pluginEdit', $data);
    }
    public function pluginUpdate($folder)
    {
        if (! user_can('Plugin.Plugin.pluginUpdate')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $folderPath = $this->plugin . $folder;

        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Belirtilen klasör mevcut değil.");
            return redirect()->to('/admin/plugin');
        }

        // Gelen içerikleri al
        $indexContent = $this->request->getPost('indexContent');
        $styleContent = $this->request->getPost('styleContent');
        $scriptContent = $this->request->getPost('scriptContent');

        // Güncel dosya uzantılarına göre ayarla
        $indexFile = $folderPath . '/index.php';
        $styleFile = $folderPath . '/style.css';
        $scriptFile = $folderPath . '/script.js';

        // Dosyaları kaydet (mevcut değilse bile oluşturulur)
        file_put_contents($indexFile, $indexContent ?? '');
        file_put_contents($styleFile, $styleContent ?? '');
        file_put_contents($scriptFile, $scriptContent ?? '');

        session()->setFlashdata('success', "$folder içeriği başarıyla güncellendi.");
        return redirect()->to('/admin/plugin');
    }
    public function pluginRename($currentFolder)
    {
        if (! user_can('Plugin.Plugin.pluginRename')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $newName = $this->request->getPost('newName');
        $seoNewName = seoFriendly($newName); // Helper ile SEO uyumlu hale getir
        $currentPath = $this->plugin . $currentFolder;
        $newPath = $this->plugin . $seoNewName;

        // Kontroller
        if (!is_dir($currentPath)) {
            session()->setFlashdata('error', "Mevcut klasör bulunamadı.");
            return redirect()->to('/admin/plugin');
        }

        if (is_dir($newPath)) {
            session()->setFlashdata('error', "Yeni klasör adı zaten mevcut.");
            return redirect()->to('/admin/plugin');
        }

        // Klasörü yeniden adlandır
        if (rename($currentPath, $newPath)) {
            session()->setFlashdata('success', "$currentFolder başarıyla $newName olarak yeniden adlandırıldı.");
        } else {
            session()->setFlashdata('error', "Klasör adı değiştirilemedi.");
        }

        return redirect()->to('/admin/plugin');
    }
    public function pluginDelete($folder)
    {
        if (! user_can('Plugin.Plugin.pluginDelete')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $folderPath = $this->plugin . $folder;

        // Kontrol: Klasör mevcut mu?
        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Silinmek istenen klasör bulunamadı.");
            return redirect()->to('/admin/plugin');
        }

        // Klasörü ve içeriğini sil
        $this->deleteFolder($folderPath);

        // Başarı mesajını oturuma ekle
        session()->setFlashdata('success', "$folder başarıyla silindi.");
        return redirect()->to('/admin/plugin');
    }
    private function deleteFolder($folderPath)
    {
        if (! user_can('Plugin.Plugin.deleteFolder')) {
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
    public function pluginView($sliderName)
    {
        if (! user_can('Plugin.Plugin.pluginView')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }
        $data['sliderName'] = $sliderName;
        // View'e içerikleri gönder
        return view('App\Modules\Plugin\Views\plugin\pluginView',$data );
    }


}
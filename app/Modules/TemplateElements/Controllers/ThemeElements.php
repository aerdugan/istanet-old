<?php
namespace App\Modules\TemplateElements\Controllers;
use App\Controllers\BaseController;

/**
 * Class Task
 */
class ThemeElements extends BaseController
{
    protected $themeElements;

    function __construct()
    {
        helper('general_helper');

        if (!is_dir(FCPATH . 'modules/themeElements/')) {
            mkdir(FCPATH . 'modules/themeElements/', 0755, true);
        }

        $this->themeElements = FCPATH . 'modules/themeElements/';

    }

    public function index()
    {
        if (!user_can('TemplateElements.ThemeElements.index')) {
            // SweetAlert mesajı ayarlıyoruz
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
        if (is_dir($this->themeElements)) {
            $items = scandir($this->themeElements);
            if ($items !== false) {
                $allItems = array_diff($items, ['.', '..']);
            }
        }

        $folders = [];

        foreach ($allItems as $item) {
            if (is_dir($this->themeElements . $item)) {
                $folders[] = $item;
            }
        }
        return view('App\Modules\TemplateElements\Views\theme\index', ['files' => $folders]);
    }
    public function themeElementsStore()
    {
        if (!user_can('TemplateElements.ThemeElements.themeElementsStore')) {
            // SweetAlert mesajı ayarlıyoruz
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            return redirect()->to('/dashboard');
        }

        $name = $this->request->getPost('name');
        $seoName = seoFriendly($name); // Yardımcı fonksiyonla SEO uyumlu hale getir
        $folderPath = $this->themeElements . $seoName;

        if (!is_dir($folderPath)) {
            // Klasörü oluştur
            mkdir($folderPath, 0755, true);

            // Dosya yollarını belirle
            $htmlPath = $folderPath . '/index.php';
            $cssPath  = $folderPath . '/style.css';
            $jsPath   = $folderPath . '/script.js';

            // Dosya içeriklerini yaz
            file_put_contents($htmlPath, "<div class='container'>\n    <!-- themeElements içeriği -->\n</div>");
            file_put_contents($cssPath, "/* themeElements CSS */\n.container { padding: 20px; }");
            file_put_contents($jsPath, "// themeElements JS\nconsole.log('Plugin yüklendi');");

            // Başarı mesajı
            session()->setFlashdata('success', "$name themeElements başarıyla oluşturuldu.");
        } else {
            // Zaten varsa hata ver
            session()->setFlashdata('error', "$name themeElements zaten mevcut.");
        }

        return redirect()->to('/admin/themeElements');
    }
    public function themeElementsEdit($folder)
    {
        if (!user_can('TemplateElements.ThemeElements.themeElementsEdit')) {
            // SweetAlert mesajı ayarlıyoruz
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            return redirect()->to('/dashboard');
        }

        $folderPath = $this->themeElements . $folder;

        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Belirtilen klasör mevcut değil.");
            return redirect()->to('/admin/themeElements');
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

        return view('App\Modules\TemplateElements\Views\theme\themeElementsEdit', $data);
    }
    public function themeElementsUpdate($folder)
    {
        if (!user_can('TemplateElements.ThemeElements.themeElementsUpdate')) {
            // SweetAlert mesajı ayarlıyoruz
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            return redirect()->to('/dashboard');
        }
        $folderPath = $this->themeElements . $folder;

        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Belirtilen klasör mevcut değil.");
            return redirect()->to('/admin/themeElements');
        }

        $indexContent = $this->request->getPost('indexContent');
        $styleContent = $this->request->getPost('styleContent');
        $scriptContent = $this->request->getPost('scriptContent');

        $indexFile = $folderPath . '/index.php';
        $styleFile = $folderPath . '/style.css';
        $scriptFile = $folderPath . '/script.js';

        file_put_contents($indexFile, $indexContent ?? '');
        file_put_contents($styleFile, $styleContent ?? '');
        file_put_contents($scriptFile, $scriptContent ?? '');

        session()->setFlashdata('success', "$folder içeriği başarıyla güncellendi.");
        return redirect()->to('/admin/themeElements');
    }
    public function themeElementsRename($currentFolder)
    {
        if (!user_can('TemplateElements.ThemeElements.themeElementsRename')) {
            // SweetAlert mesajı ayarlıyoruz
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            return redirect()->to('/dashboard');
        }

        $newName = $this->request->getPost('newName');
        $seoNewName = seoFriendly($newName); // Helper ile SEO uyumlu hale getir
        $currentPath = $this->themeElements . $currentFolder;
        $newPath = $this->themeElements . $seoNewName;
        if (!is_dir($currentPath)) {
            session()->setFlashdata('error', "Mevcut klasör bulunamadı.");
            return redirect()->to('/admin/themeElements');
        }
        if (is_dir($newPath)) {
            session()->setFlashdata('error', "Yeni klasör adı zaten mevcut.");
            return redirect()->to('/admin/themeElements');
        }
        if (rename($currentPath, $newPath)) {
            session()->setFlashdata('success', "$currentFolder başarıyla $newName olarak yeniden adlandırıldı.");
        } else {
            session()->setFlashdata('error', "Klasör adı değiştirilemedi.");
        }
        return redirect()->to('/admin/themeElements');
    }
    public function themeElementsDelete($folder)
    {
        if (!user_can('TemplateElements.ThemeElements.themeElementsDelete')) {
            // SweetAlert mesajı ayarlıyoruz
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            return redirect()->to('/dashboard');
        }

        $folderPath = $this->themeElements . $folder;
        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Silinmek istenen klasör bulunamadı.");
            return redirect()->to('/admin/themeElements');
        }
        $this->deleteFolder($folderPath);
        session()->setFlashdata('success', "$folder başarıyla silindi.");
        return redirect()->to('/admin/themeElements');
    }
    private function deleteFolder($folderPath)
    {
        $files = array_diff(scandir($folderPath), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
            if (is_dir($filePath)) {
                $this->deleteFolder($filePath);
            } else {
                unlink($filePath);
            }
        }
        rmdir($folderPath);
    }

    public function themeElementsView($themeElements)
    {
        if (!user_can('TemplateElements.ThemeElements.themeElementsView')) {
            // SweetAlert mesajı ayarlıyoruz
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);

            return redirect()->to('/dashboard');
        }

        $data['themeElements'] = $themeElements;
        // View'e içerikleri gönder
        return view('App\Modules\TemplateElements\Views\theme\themeElementsView',$data );
    }
}
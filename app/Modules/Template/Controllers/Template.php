<?php
namespace App\Modules\Template\Controllers;
//namespace Modules\Template\Controllers;

use App\Controllers\BaseController;
use App\Modules\Template\Models\ThemeModel;



/**
 * Class Task
 */
class Template extends BaseController
{
    private $template_model;

    function __construct()
    {
        $this->template_model = new ThemeModel();

        $this->header = FCPATH . 'template/header/';
        $this->footer = FCPATH . 'template/footer/';
        $this->breadCrumb = FCPATH . 'template/breadCrumb/';
        $this->slider = FCPATH . 'template/slider/';


    }

    public function index()
    {
        $headerFiles = getFolderFiles(FCPATH . 'template/header/');
        $footerFiles = getFolderFiles(FCPATH . 'template/footer/');
        $breadcrumbFiles = getFolderFiles(FCPATH . 'template/breadCrumb/');
        $sliderFolders = getFolderNames(FCPATH . 'template/slider/');
        $row = $this->template_model->find(1);

        return view('App\Modules\Template\Views\template\index', [
            'headerFiles' => $headerFiles,
            'footerFiles' => $footerFiles,
            'breadcrumbFiles' => $breadcrumbFiles,
            'sliderFolders' => $sliderFolders,
            'row' => $row, // Bu şekilde veriyi key-value olarak gönderiyoruz
        ]);
    }
    public function templateUpdate()
    {

        $postData = $this->request->getPost();

        // Veritabanına kaydedilecek veriler
        $data = [
            'colorCode'         => $postData['colorCode'] ?? null,
            'colorCode2'        => $postData['colorCode2'] ?? null,
            'setHeader'         => $postData['setHeader'] ?? null,
            'setLogo'           => $postData['setLogo'] ?? null,
            'headerSocial'      => $postData['headerSocial'] ?? null,
            'headerLang'        => $postData['headerLang'] ?? null,
            'setFooter'         => $postData['setFooter'] ?? null,
            'setFooterLogo'     => $postData['setFooterLogo'] ?? null,
            'footerSocial'      => $postData['footerSocial'] ?? null,
            'setSlider'         => $postData['setSlider'] ?? null,
            'setBreadcrumb'     => $postData['setBreadcrumb'] ?? null,
            'setBreadcrumbStatus'  => $postData['setBreadcrumbStatus'] ?? null,
            'copyright'         => $postData['copyright'] ?? null,
            'updatedAt'         => date('Y-m-d H:i:s'),
            'lastUpdatedUser' => session()->get('id_user'),
        ];


        $updateResult = $this->template_model->update(1, $data);

        if ($updateResult) {
            // Güncelleme başarılı
            session()->setFlashdata('success', 'Tema ayarları başarıyla güncellendi.');
        } else {
            // Güncelleme başarısız
            session()->setFlashdata('error', 'Tema ayarları güncellenemedi.');
        }
        return redirect()->to('/admin/template');
    }
    public function updateSiteStatus()
    {
        try {
            $input = $this->request->getJSON();

            // Gelen veriyi kontrol edin
            if (!isset($input->siteStatus) || !in_array($input->siteStatus, ['1', '2'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Geçersiz site durumu.']);
            }

            // Veritabanında güncelleme işlemini yap
            $updateData = ['siteStatus' => $input->siteStatus];
            $updateResult = $this->template_model->update(1, $updateData);

            if ($updateResult) {
                return $this->response->setJSON(['success' => true, 'message' => 'Site durumu başarıyla güncellendi.']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Site durumu güncellenemedi.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /** Header */
    public function header()
    {
        $files = array_diff(scandir($this->header), ['.', '..']);
        return view('App\Modules\Template\Views\template\header', ['files' => $files]);
    }
    public function headerStore()
    {
        $name = $this->request->getPost('name');
        $seoName = seoFriendly($name); // Helper ile SEO uyumlu hale getir
        $filePath = $this->header . $seoName . '.php';

        if (!file_exists($filePath)) {
            file_put_contents($filePath,'/* ' . $name . ' Header Kodlarınızı Buraya Ekleyin */');
            // Başarı mesajını oturuma ekle
            session()->setFlashdata('success', "$name header başarıyla oluşturuldu.");
        } else {
            // Hata mesajını oturuma ekle
            session()->setFlashdata('error', "$name header zaten mevcut.");
        }
        return redirect()->to('/admin/template/header');
    }
    public function headerEdit($file)
    {
        $filePath = $this->header . $file;
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            return view('App\Modules\Template\Views\template\headerEdit', ['file' => $file, 'content' => $content]);
        }

        return redirect()->to('/admin/template/header');
    }
    public function headerUpdate($file)
    {
        $newContent = $this->request->getPost('content');
        $filePath = $this->header . $file;

        // Dosyayı güncelle
        if (file_exists($filePath)) {
            file_put_contents($filePath, $newContent);
        }

        return redirect()->to('/admin/template/header');
    }
    public function headerDelete($file)
    {
        $filePath = $this->header . $file;

        // Dosyayı sil
        if (file_exists($filePath)) {
            unlink($filePath);
            // Başarı mesajını oturuma ekle
            session()->setFlashdata('success', "$file header başarıyla silindi.");
        } else {
            // Hata mesajını oturuma ekle
            session()->setFlashdata('error', "$file bulunamadı.");
        }

        return redirect()->to('/admin/template/header');
    }
    public function headerRename($file)
    {
        $newName = $this->request->getPost('newName');
        $seoNewName = seoFriendly($newName); // Helper ile SEO uyumlu hale getir
        $oldPath = $this->header . $file;
        $newPath = $this->header . $seoNewName . '.php';

        if (file_exists($oldPath)) {
            rename($oldPath, $newPath);

            // Başarı mesajını oturuma ekle
            session()->setFlashdata('success', "$newName header adı başarıyla güncellendi.");
        } else {
            // Hata mesajını oturuma ekle
            session()->setFlashdata('error', "Dosya bulunamadı.");
        }

        return redirect()->to('/admin/template/header');
    }
    /** Header */

    /** Footer */
    public function footer()
    {
        // Klasördeki PHP dosyalarını listele
        $files = array_diff(scandir($this->footer), ['.', '..']);
        return view('App\Modules\Template\Views\template\footer', ['files' => $files]);
    }
    public function footerStore()
    {
        $name = $this->request->getPost('name');
        $seoName = seoFriendly($name); // Helper ile SEO uyumlu hale getir
        $filePath = $this->footer . $seoName . '.php';

        if (!file_exists($filePath)) {
            file_put_contents($filePath,'/* ' . $name . ' footer Kodlarınızı Buraya Ekleyin */');
            // Başarı mesajını oturuma ekle
            session()->setFlashdata('success', "$name footer başarıyla oluşturuldu.");
        } else {
            // Hata mesajını oturuma ekle
            session()->setFlashdata('error', "$name footer zaten mevcut.");
        }

        return redirect()->to('/admin/template/footer');
    }
    public function footerEdit($file)
    {
        $filePath = $this->footer . $file;
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            return view('App\Modules\Template\Views\template\footerEdit', ['file' => $file, 'content' => $content]);
        }

        return redirect()->to('/admin/template/footer');
    }
    public function footerUpdate($file)
    {
        $newContent = $this->request->getPost('content');
        $filePath = $this->footer . $file;

        // Dosyayı güncelle
        if (file_exists($filePath)) {
            file_put_contents($filePath, $newContent);
        }

        return redirect()->to('/admin/template/footer');
    }
    public function footerDelete($file)
    {
        $filePath = $this->footer . $file;

        // Dosyayı sil
        if (file_exists($filePath)) {
            unlink($filePath);
            // Başarı mesajını oturuma ekle
            session()->setFlashdata('success', "$file footer başarıyla silindi.");
        } else {
            // Hata mesajını oturuma ekle
            session()->setFlashdata('error', "$file bulunamadı.");
        }

        return redirect()->to('/admin/template/footer');
    }
    public function footerRename($file)
    {
        $newName = $this->request->getPost('newName');
        $seoNewName = seoFriendly($newName); // Helper ile SEO uyumlu hale getir
        $oldPath = $this->footer . $file;
        $newPath = $this->footer . $seoNewName . '.php';

        if (file_exists($oldPath)) {
            rename($oldPath, $newPath);

            // Başarı mesajını oturuma ekle
            session()->setFlashdata('success', "$newName footer adı başarıyla güncellendi.");
        } else {
            // Hata mesajını oturuma ekle
            session()->setFlashdata('error', "Dosya bulunamadı.");
        }

        return redirect()->to('/admin/template/footer');
    }
    /** Footer */

    /** BreadCrumb */
    public function breadCrumb()
    {
        // Klasördeki PHP dosyalarını listele
        $files = array_diff(scandir($this->breadCrumb), ['.', '..']);
        return view('App\Modules\Template\Views\template\breadCrumb', ['files' => $files]);
    }
    public function breadCrumbStore()
    {
        $name = $this->request->getPost('name');
        $seoName = seoFriendly($name); // Helper ile SEO uyumlu hale getir
        $filePath = $this->breadCrumb . $seoName . '.php';

        if (!file_exists($filePath)) {
            file_put_contents($filePath,'/* ' . $name . ' BreadCrumb Kodlarınızı Buraya Ekleyin */');
            // Başarı mesajını oturuma ekle
            session()->setFlashdata('success', "$name breadCrumb başarıyla oluşturuldu.");
        } else {
            // Hata mesajını oturuma ekle
            session()->setFlashdata('error', "$name breadCrumb zaten mevcut.");
        }

        return redirect()->to('/admin/template/breadCrumb');
    }
    public function breadCrumbEdit($file)
    {
        $filePath = $this->breadCrumb . $file;
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            return view('App\Modules\Template\Views\template\breadCrumbEdit', ['file' => $file, 'content' => $content]);
        }

        return redirect()->to('/admin/template/breadCrumb');
    }
    public function breadCrumbUpdate($file)
    {
        $newContent = $this->request->getPost('content');
        $filePath = $this->breadCrumb . $file;

        // Dosyayı güncelle
        if (file_exists($filePath)) {
            file_put_contents($filePath, $newContent);
        }

        return redirect()->to('/admin/template/breadCrumb');
    }
    public function breadCrumbDelete($file)
    {
        $filePath = $this->breadCrumb . $file;

        // Dosyayı sil
        if (file_exists($filePath)) {
            unlink($filePath);
            // Başarı mesajını oturuma ekle
            session()->setFlashdata('success', "$file breadCrumb başarıyla silindi.");
        } else {
            // Hata mesajını oturuma ekle
            session()->setFlashdata('error', "$file breadCrumb bulunamadı.");
        }

        return redirect()->to('/admin/template/breadCrumb');
    }
    public function breadCrumbRename($file)
    {
        $newName = $this->request->getPost('newName');
        $seoNewName = seoFriendly($newName); // Helper ile SEO uyumlu hale getir
        $oldPath = $this->breadCrumb . $file;
        $newPath = $this->breadCrumb . $seoNewName . '.php';

        if (file_exists($oldPath)) {
            rename($oldPath, $newPath);

            // Başarı mesajını oturuma ekle
            session()->setFlashdata('success', "$newName footer adı breadCrumb güncellendi.");
        } else {
            // Hata mesajını oturuma ekle
            session()->setFlashdata('error', "breadCrumb Dosya bulunamadı.");
        }

        return redirect()->to('/admin/template/breadCrumb');
    }
    /** BreadCrumb */

    /** Slider */
    public function slider()
    {
        // Klasördeki PHP dosyalarını listele
        $files = array_diff(scandir($this->slider), ['.', '..']);
        return view('App\Modules\Template\Views\template\slider', ['files' => $files]);
    }
    public function sliderStore()
    {
        $name = $this->request->getPost('name');
        $seoName = seoFriendly($name); // Helper ile SEO uyumlu hale getir
        $folderPath = $this->slider . $seoName;

        if (!is_dir($folderPath)) {
            // Klasörü oluştur
            mkdir($folderPath, 0755, true);

            // Klasör içine dosyaları oluştur
            file_put_contents($folderPath . '/index.php', '/* ' . $name . ' Slider Kodlarınızı Buraya Ekleyin */');
            file_put_contents($folderPath . '/style.php', '/* ' . $name . ' Slider Kodlarınızı Buraya Ekleyin */');
            file_put_contents($folderPath . '/script.php', '/* ' . $name . ' Slider Kodlarınızı Buraya Ekleyin */');

            // Başarı mesajını oturuma ekle
            session()->setFlashdata('success', "$name slider başarıyla oluşturuldu.");
        } else {
            // Hata mesajını oturuma ekle
            session()->setFlashdata('error', "$name slider zaten mevcut.");
        }

        return redirect()->to('/admin/template/slider');
    }
    public function sliderEdit($folder)
    {
        $folderPath = $this->slider . $folder;

        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Belirtilen klasör mevcut değil.");
            return redirect()->to('/admin/template/slider');
        }

        // Dosyaların yolunu kontrol et
        $indexFile = $folderPath . '/index.php';
        $styleFile = $folderPath . '/style.php';
        $scriptFile = $folderPath . '/script.php';

        // Dosya içeriklerini yükle
        $data = [
            'folder' => $folder,
            'indexContent' => file_exists($indexFile) ? file_get_contents($indexFile) : '',
            'styleContent' => file_exists($styleFile) ? file_get_contents($styleFile) : '',
            'scriptContent' => file_exists($scriptFile) ? file_get_contents($scriptFile) : '',
        ];

        return view('App\Modules\Template\Views\template\sliderEdit', $data);
    }
    public function sliderUpdate($folder)
    {
        $folderPath = $this->slider . $folder;

        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Belirtilen klasör mevcut değil.");
            return redirect()->to('/admin/template/slider');
        }

        // Gelen içerikleri al
        $indexContent = $this->request->getPost('indexContent');
        $styleContent = $this->request->getPost('styleContent');
        $scriptContent = $this->request->getPost('scriptContent');

        // Dosyaların yolunu kontrol et
        $indexFile = $folderPath . '/index.php';
        $styleFile = $folderPath . '/style.php';
        $scriptFile = $folderPath . '/script.php';

        // Dosyaları güncelle
        if (file_exists($indexFile)) {
            file_put_contents($indexFile, $indexContent);
        }
        if (file_exists($styleFile)) {
            file_put_contents($styleFile, $styleContent);
        }
        if (file_exists($scriptFile)) {
            file_put_contents($scriptFile, $scriptContent);
        }

        session()->setFlashdata('success', "$folder içeriği başarıyla güncellendi.");
        return redirect()->to('/admin/template/slider');
    }
    public function sliderRename($currentFolder)
    {
        $newName = $this->request->getPost('newName');
        $seoNewName = seoFriendly($newName); // Helper ile SEO uyumlu hale getir
        $currentPath = $this->slider . $currentFolder;
        $newPath = $this->slider . $seoNewName;

        // Kontroller
        if (!is_dir($currentPath)) {
            session()->setFlashdata('error', "Mevcut klasör bulunamadı.");
            return redirect()->to('/admin/template/breadCrumb');
        }

        if (is_dir($newPath)) {
            session()->setFlashdata('error', "Yeni klasör adı zaten mevcut.");
            return redirect()->to('/admin/template/slider');
        }

        // Klasörü yeniden adlandır
        if (rename($currentPath, $newPath)) {
            session()->setFlashdata('success', "$currentFolder başarıyla $newName olarak yeniden adlandırıldı.");
        } else {
            session()->setFlashdata('error', "Klasör adı değiştirilemedi.");
        }

        return redirect()->to('/admin/template/slider');
    }
    public function sliderDelete($folder)
    {
        $folderPath = $this->slider . $folder;

        // Kontrol: Klasör mevcut mu?
        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Silinmek istenen klasör bulunamadı.");
            return redirect()->to('/admin/template/slider');
        }

        // Klasörü ve içeriğini sil
        $this->deleteFolder($folderPath);

        // Başarı mesajını oturuma ekle
        session()->setFlashdata('success', "$folder başarıyla silindi.");
        return redirect()->to('/admin/template/slider');
    }
    private function deleteFolder($folderPath)
    {
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
    /** Slider */
}
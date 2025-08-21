<?php
namespace App\Modules\Template\Controllers;

use App\Controllers\BaseController;
use App\Modules\Template\Models\ThemeModel;

class Template extends BaseController
{
    private ThemeModel $template_model;

    private string $header;
    private string $footer;
    private string $breadCrumb;
    private string $slider;

    public function __construct()
    {
        $this->template_model = new ThemeModel();

        $this->header     = FCPATH . 'template/header/';
        $this->footer     = FCPATH . 'template/footer/';
        $this->breadCrumb = FCPATH . 'template/breadCrumb/';
        $this->slider     = FCPATH . 'template/slider/';
    }

    /** -------------------- Helpers -------------------- */
    private function sanitizeSlug(string $slug): string
    {
        // Sadece a-z 0-9 _ - karakterlerine izin ver (diğerlerini - yap)
        $slug = preg_replace('/[^a-z0-9_-]+/i', '-', $slug);
        // Kenar boşlukları ve fazla tireleri toparla
        $slug = trim($slug, "-_");
        return $slug;
    }

    private function filePathFromSlug(string $baseDir, string $slug): string
    {
        return rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $slug . '.php';
    }

    /** -------------------- Index -------------------- */
    public function index()
    {
        $headerFiles     = getFolderFiles($this->header);
        $footerFiles     = getFolderFiles($this->footer);
        $breadcrumbFiles = getFolderFiles($this->breadCrumb);
        $sliderFolders   = getFolderNames($this->slider);
        $row             = $this->template_model->find(1);

        return view('App\Modules\Template\Views\template\index', [
            'headerFiles'     => $headerFiles,
            'footerFiles'     => $footerFiles,
            'breadcrumbFiles' => $breadcrumbFiles,
            'sliderFolders'   => $sliderFolders,
            'row'             => $row,
        ]);
    }

    public function templateUpdate()
    {
        $postData = $this->request->getPost();
        $data = [
            'colorCode'           => $postData['colorCode'] ?? null,
            'colorCode2'          => $postData['colorCode2'] ?? null,
            'setHeader'           => $postData['setHeader'] ?? null,
            'setLogo'             => $postData['setLogo'] ?? null,
            'headerSocial'        => $postData['headerSocial'] ?? null,
            'headerLang'          => $postData['headerLang'] ?? null,
            'setFooter'           => $postData['setFooter'] ?? null,
            'setFooterLogo'       => $postData['setFooterLogo'] ?? null,
            'footerSocial'        => $postData['footerSocial'] ?? null,
            'setSlider'           => $postData['setSlider'] ?? null,
            'setBreadcrumb'       => $postData['setBreadcrumb'] ?? null,
            'setBreadcrumbStatus' => $postData['setBreadcrumbStatus'] ?? null,
            'copyright'           => $postData['copyright'] ?? null,
            'updatedAt'           => date('Y-m-d H:i:s'),
            'lastUpdatedUser'     => session()->get('id_user'),
        ];

        $ok = $this->template_model->update(1, $data);
        session()->setFlashdata($ok ? 'success' : 'error', $ok ? 'Tema ayarları başarıyla güncellendi.' : 'Tema ayarları güncellenemedi.');
        return redirect()->to('/admin/template');
    }

    public function updateSiteStatus()
    {
        try {
            $input = $this->request->getJSON();
            if (!isset($input->siteStatus) || !in_array($input->siteStatus, ['1', '2'], true)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Geçersiz site durumu.']);
            }
            $ok = $this->template_model->update(1, ['siteStatus' => $input->siteStatus]);
            return $this->response->setJSON(['success' => (bool)$ok, 'message' => $ok ? 'Site durumu başarıyla güncellendi.' : 'Site durumu güncellenemedi.']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /** -------------------- Header -------------------- */
    public function header()
    {
        $files = array_diff(scandir($this->header), ['.', '..']);
        return view('App\Modules\Template\Views\template\header', ['files' => $files]);
    }

    public function headerStore()
    {
        $name    = (string) $this->request->getPost('name');
        $seoName = $this->sanitizeSlug(seoFriendly($name));
        if ($seoName === '') {
            session()->setFlashdata('error', 'Geçersiz ad.');
            return redirect()->to('/admin/template/header');
        }

        $filePath = $this->filePathFromSlug($this->header, $seoName);
        if (!file_exists($filePath)) {
            file_put_contents($filePath, '/* ' . $name . ' Header Kodlarınızı Buraya Ekleyin */');
            session()->setFlashdata('success', "$name header başarıyla oluşturuldu.");
        } else {
            session()->setFlashdata('error', "$name header zaten mevcut.");
        }
        return redirect()->to('/admin/template/header');
    }

    public function headerEdit(string $file)
    {
        $slug     = $this->sanitizeSlug($file);
        $filePath = $this->filePathFromSlug($this->header, $slug);

        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            return view('App\Modules\Template\Views\template\headerEdit', [
                'file'    => $slug,  // uzantısız
                'content' => $content,
            ]);
        }
        return redirect()->to('/admin/template/header');
    }

    public function headerUpdate(string $file)
    {
        $slug       = $this->sanitizeSlug($file);
        $newContent = (string) $this->request->getPost('content');
        $filePath   = $this->filePathFromSlug($this->header, $slug);

        if (file_exists($filePath)) {
            file_put_contents($filePath, $newContent);
        }
        return redirect()->to('/admin/template/header');
    }

    public function headerDelete(string $file)
    {
        $slug     = $this->sanitizeSlug($file);
        $filePath = $this->filePathFromSlug($this->header, $slug);

        if (file_exists($filePath)) {
            unlink($filePath);
            session()->setFlashdata('success', "$slug header başarıyla silindi.");
        } else {
            session()->setFlashdata('error', "$slug bulunamadı.");
        }
        return redirect()->to('/admin/template/header');
    }

    public function headerRename(string $file)
    {
        $slug       = $this->sanitizeSlug($file);
        $newNameRaw = (string) $this->request->getPost('newName');
        $newSlug    = $this->sanitizeSlug(seoFriendly($newNameRaw));

        $oldPath = $this->filePathFromSlug($this->header, $slug);
        $newPath = $this->filePathFromSlug($this->header, $newSlug);

        if (!file_exists($oldPath)) {
            session()->setFlashdata('error', "Dosya bulunamadı.");
            return redirect()->to('/admin/template/header');
        }
        if ($newSlug === '') {
            session()->setFlashdata('error', "Geçersiz yeni ad.");
            return redirect()->to('/admin/template/header');
        }
        if ($oldPath === $newPath) {
            session()->setFlashdata('success', "Değişiklik yok.");
            return redirect()->to('/admin/template/header');
        }

        if (!file_exists($newPath) && rename($oldPath, $newPath)) {
            session()->setFlashdata('success', "$slug başarıyla $newSlug olarak güncellendi.");
        } else {
            session()->setFlashdata('error', "Yeni ad zaten var veya yeniden adlandırma başarısız.");
        }
        return redirect()->to('/admin/template/header');
    }

    /** -------------------- Footer -------------------- */
    public function footer()
    {
        $files = array_diff(scandir($this->footer), ['.', '..']);
        return view('App\Modules\Template\Views\template\footer', ['files' => $files]);
    }

    public function footerStore()
    {
        $name    = (string) $this->request->getPost('name');
        $seoName = $this->sanitizeSlug(seoFriendly($name));
        if ($seoName === '') {
            session()->setFlashdata('error', 'Geçersiz ad.');
            return redirect()->to('/admin/template/footer');
        }

        $filePath = $this->filePathFromSlug($this->footer, $seoName);
        if (!file_exists($filePath)) {
            file_put_contents($filePath, '/* ' . $name . ' footer Kodlarınızı Buraya Ekleyin */');
            session()->setFlashdata('success', "$name footer başarıyla oluşturuldu.");
        } else {
            session()->setFlashdata('error', "$name footer zaten mevcut.");
        }
        return redirect()->to('/admin/template/footer');
    }

    public function footerEdit(string $file)
    {
        $slug     = $this->sanitizeSlug($file);
        $filePath = $this->filePathFromSlug($this->footer, $slug);

        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            return view('App\Modules\Template\Views\template\footerEdit', [
                'file'    => $slug,
                'content' => $content
            ]);
        }
        return redirect()->to('/admin/template/footer');
    }

    public function footerUpdate(string $file)
    {
        $slug       = $this->sanitizeSlug($file);
        $newContent = (string) $this->request->getPost('content');
        $filePath   = $this->filePathFromSlug($this->footer, $slug);

        if (file_exists($filePath)) {
            file_put_contents($filePath, $newContent);
        }
        return redirect()->to('/admin/template/footer');
    }

    public function footerDelete(string $file)
    {
        $slug     = $this->sanitizeSlug($file);
        $filePath = $this->filePathFromSlug($this->footer, $slug);

        if (file_exists($filePath)) {
            unlink($filePath);
            session()->setFlashdata('success', "$slug footer başarıyla silindi.");
        } else {
            session()->setFlashdata('error', "$slug bulunamadı.");
        }
        return redirect()->to('/admin/template/footer');
    }

    public function footerRename(string $file)
    {
        $slug       = $this->sanitizeSlug($file);
        $newNameRaw = (string) $this->request->getPost('newName');
        $newSlug    = $this->sanitizeSlug(seoFriendly($newNameRaw));

        $oldPath = $this->filePathFromSlug($this->footer, $slug);
        $newPath = $this->filePathFromSlug($this->footer, $newSlug);

        if (!file_exists($oldPath)) {
            session()->setFlashdata('error', "Dosya bulunamadı.");
            return redirect()->to('/admin/template/footer');
        }
        if ($newSlug === '') {
            session()->setFlashdata('error', "Geçersiz yeni ad.");
            return redirect()->to('/admin/template/footer');
        }
        if ($oldPath === $newPath) {
            session()->setFlashdata('success', "Değişiklik yok.");
            return redirect()->to('/admin/template/footer');
        }

        if (!file_exists($newPath) && rename($oldPath, $newPath)) {
            session()->setFlashdata('success', "$slug başarıyla $newSlug olarak güncellendi.");
        } else {
            session()->setFlashdata('error', "Yeni ad zaten var veya yeniden adlandırma başarısız.");
        }
        return redirect()->to('/admin/template/footer');
    }

    /** -------------------- BreadCrumb -------------------- */
    public function breadCrumb()
    {
        $files = array_diff(scandir($this->breadCrumb), ['.', '..']);
        return view('App\Modules\Template\Views\template\breadCrumb', ['files' => $files]);
    }

    public function breadCrumbStore()
    {
        $name    = (string) $this->request->getPost('name');
        $seoName = $this->sanitizeSlug(seoFriendly($name));
        if ($seoName === '') {
            session()->setFlashdata('error', 'Geçersiz ad.');
            return redirect()->to('/admin/template/breadCrumb');
        }

        $filePath = $this->filePathFromSlug($this->breadCrumb, $seoName);
        if (!file_exists($filePath)) {
            file_put_contents($filePath, '/* ' . $name . ' BreadCrumb Kodlarınızı Buraya Ekleyin */');
            session()->setFlashdata('success', "$name breadCrumb başarıyla oluşturuldu.");
        } else {
            session()->setFlashdata('error', "$name breadCrumb zaten mevcut.");
        }

        return redirect()->to('/admin/template/breadCrumb');
    }

    public function breadCrumbEdit(string $file)
    {
        $slug     = $this->sanitizeSlug($file);
        $filePath = $this->filePathFromSlug($this->breadCrumb, $slug);

        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            return view('App\Modules\Template\Views\template\breadCrumbEdit', [
                'file'    => $slug,
                'content' => $content
            ]);
        }
        return redirect()->to('/admin/template/breadCrumb');
    }

    public function breadCrumbUpdate(string $file)
    {
        $slug       = $this->sanitizeSlug($file);
        $newContent = (string) $this->request->getPost('content');
        $filePath   = $this->filePathFromSlug($this->breadCrumb, $slug);

        if (file_exists($filePath)) {
            file_put_contents($filePath, $newContent);
        }
        return redirect()->to('/admin/template/breadCrumb');
    }

    public function breadCrumbDelete(string $file)
    {
        $slug     = $this->sanitizeSlug($file);
        $filePath = $this->filePathFromSlug($this->breadCrumb, $slug);

        if (file_exists($filePath)) {
            unlink($filePath);
            session()->setFlashdata('success', "$slug breadCrumb başarıyla silindi.");
        } else {
            session()->setFlashdata('error', "$slug breadCrumb bulunamadı.");
        }
        return redirect()->to('/admin/template/breadCrumb');
    }

    public function breadCrumbRename(string $file)
    {
        $slug       = $this->sanitizeSlug($file);
        $newNameRaw = (string) $this->request->getPost('newName');
        $newSlug    = $this->sanitizeSlug(seoFriendly($newNameRaw));

        $oldPath = $this->filePathFromSlug($this->breadCrumb, $slug);
        $newPath = $this->filePathFromSlug($this->breadCrumb, $newSlug);

        if (!file_exists($oldPath)) {
            session()->setFlashdata('error', "breadCrumb Dosya bulunamadı.");
            return redirect()->to('/admin/template/breadCrumb');
        }
        if ($newSlug === '') {
            session()->setFlashdata('error', "Geçersiz yeni ad.");
            return redirect()->to('/admin/template/breadCrumb');
        }
        if ($oldPath === $newPath) {
            session()->setFlashdata('success', "Değişiklik yok.");
            return redirect()->to('/admin/template/breadCrumb');
        }

        if (!file_exists($newPath) && rename($oldPath, $newPath)) {
            session()->setFlashdata('success', "$slug footer adı breadCrumb güncellendi.");
        } else {
            session()->setFlashdata('error', "Yeni ad zaten var veya yeniden adlandırma başarısız.");
        }
        return redirect()->to('/admin/template/breadCrumb');
    }

    /** -------------------- Slider (klasör) -------------------- */
    public function slider()
    {
        $files = array_diff(scandir($this->slider), ['.', '..']);
        return view('App\Modules\Template\Views\template\slider', ['files' => $files]);
    }

    public function sliderStore()
    {
        $name     = (string) $this->request->getPost('name');
        $seoName  = $this->sanitizeSlug(seoFriendly($name));
        if ($seoName === '') {
            session()->setFlashdata('error', 'Geçersiz ad.');
            return redirect()->to('/admin/template/slider');
        }

        $folderPath = rtrim($this->slider, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $seoName;

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0755, true);
            file_put_contents($folderPath . '/index.php',  '/* ' . $name . ' Slider Kodlarınızı Buraya Ekleyin */');
            file_put_contents($folderPath . '/style.php',  '/* ' . $name . ' Slider CSS Kodlarınızı Buraya Ekleyin */');
            file_put_contents($folderPath . '/script.php', '/* ' . $name . ' Slider JS Kodlarınızı Buraya Ekleyin */');

            session()->setFlashdata('success', "$name slider başarıyla oluşturuldu.");
        } else {
            session()->setFlashdata('error', "$name slider zaten mevcut.");
        }

        return redirect()->to('/admin/template/slider');
    }

    public function sliderEdit(string $folder)
    {
        $slug       = $this->sanitizeSlug($folder);
        $folderPath = rtrim($this->slider, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $slug;

        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Belirtilen klasör mevcut değil.");
            return redirect()->to('/admin/template/slider');
        }

        $indexFile  = $folderPath . '/index.php';
        $styleFile  = $folderPath . '/style.php';
        $scriptFile = $folderPath . '/script.php';

        $data = [
            'folder'        => $slug,
            'indexContent'  => file_exists($indexFile)  ? file_get_contents($indexFile)  : '',
            'styleContent'  => file_exists($styleFile)  ? file_get_contents($styleFile)  : '',
            'scriptContent' => file_exists($scriptFile) ? file_get_contents($scriptFile) : '',
        ];

        return view('App\Modules\Template\Views\template\sliderEdit', $data);
    }

    public function sliderUpdate(string $folder)
    {
        $slug       = $this->sanitizeSlug($folder);
        $folderPath = rtrim($this->slider, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $slug;

        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Belirtilen klasör mevcut değil.");
            return redirect()->to('/admin/template/slider');
        }

        $indexContent  = (string) $this->request->getPost('indexContent');
        $styleContent  = (string) $this->request->getPost('styleContent');
        $scriptContent = (string) $this->request->getPost('scriptContent');

        $indexFile  = $folderPath . '/index.php';
        $styleFile  = $folderPath . '/style.php';
        $scriptFile = $folderPath . '/script.php';

        if (file_exists($indexFile))  file_put_contents($indexFile,  $indexContent);
        if (file_exists($styleFile))  file_put_contents($styleFile,  $styleContent);
        if (file_exists($scriptFile)) file_put_contents($scriptFile, $scriptContent);

        session()->setFlashdata('success', "$slug içeriği başarıyla güncellendi.");
        return redirect()->to('/admin/template/slider');
    }

    public function sliderRename(string $currentFolder)
    {
        $oldSlug   = $this->sanitizeSlug($currentFolder);
        $newName   = (string) $this->request->getPost('newName');
        $newSlug   = $this->sanitizeSlug(seoFriendly($newName));

        $currentPath = rtrim($this->slider, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $oldSlug;
        $newPath     = rtrim($this->slider, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newSlug;

        if (!is_dir($currentPath)) {
            session()->setFlashdata('error', "Mevcut klasör bulunamadı.");
            return redirect()->to('/admin/template/slider');
        }

        if ($newSlug === '') {
            session()->setFlashdata('error', "Geçersiz yeni ad.");
            return redirect()->to('/admin/template/slider');
        }

        if (is_dir($newPath)) {
            session()->setFlashdata('error', "Yeni klasör adı zaten mevcut.");
            return redirect()->to('/admin/template/slider');
        }

        if (rename($currentPath, $newPath)) {
            session()->setFlashdata('success', "$oldSlug başarıyla $newSlug olarak yeniden adlandırıldı.");
        } else {
            session()->setFlashdata('error', "Klasör adı değiştirilemedi.");
        }

        return redirect()->to('/admin/template/slider');
    }

    public function sliderDelete(string $folder)
    {
        $slug       = $this->sanitizeSlug($folder);
        $folderPath = rtrim($this->slider, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $slug;

        if (!is_dir($folderPath)) {
            session()->setFlashdata('error', "Silinmek istenen klasör bulunamadı.");
            return redirect()->to('/admin/template/slider');
        }

        $this->deleteFolder($folderPath);
        session()->setFlashdata('success', "$slug başarıyla silindi.");
        return redirect()->to('/admin/template/slider');
    }

    private function deleteFolder(string $folderPath): void
    {
        $files = array_diff(scandir($folderPath), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
            if (is_dir($filePath)) {
                $this->deleteFolder($filePath);
            } else {
                @unlink($filePath);
            }
        }
        @rmdir($folderPath);
    }
}
<?php

namespace App\Modules\Files\Controllers;

use App\Controllers\BaseController;
use App\Modules\Files\Config\FileManager;
use CodeIgniter\API\ResponseTrait;

class Files extends BaseController
{
    use ResponseTrait;
    private $filemanagerConfig;
    private $user_model;
    private $oauth_model;
    private $activity_model;

    function __construct()
    {
        $this->filemanagerConfig = new FileManager();
    }


    public function index()
    {
        if (! user_can('files.files.index')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        return view('App\Modules\Files\Views\index');
    }

    private function getFilesAndFolders($folderPath)
    {

        $items = scandir($this->filemanagerConfig->path . $folderPath);

        $folders = [];
        $files = [];

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $itemPath = $this->filemanagerConfig->path . $folderPath . '/' . $item;
            $isDirectory = is_dir($itemPath);

            $url = $this->filemanagerConfig->urlpath . $folderPath . '/' . $item;
            $url = str_replace('//', '/', $url); // to fix duplicate '//' if $folderPath empty
            $url = str_replace(':/', '://', $url);

            $itemObj = [
                'name' => $item,
                'type' => $isDirectory ? 'folder' : 'file',
                'url' => $url,
                'created' => date('Y-m-d H:i:s', filectime($itemPath)),
                'modified' => date('Y-m-d H:i:s', filemtime($itemPath)),
                'size' => $isDirectory ? '-' : $this->getFileSize(filesize($itemPath))
            ];

            if ($isDirectory) {
                $folders[] = $itemObj;
            } else {
                $files[] = $itemObj;
            }
        }

        // Sort the items by modified date in descending order
        usort($files, function ($a, $b) {
            return strtotime($b['modified']) - strtotime($a['modified']);
        });

        $fileList = array_merge($folders, $files);

        return $fileList;
    }
    private function getFileSize($fileSizeInBytes)
    {
        $fileSizeFormatted = '';

        if ($fileSizeInBytes >= 1024 * 1024) {
            $fileSizeFormatted = number_format($fileSizeInBytes / (1024 * 1024), 1) . ' MB';
        } else {
            $fileSizeFormatted = number_format($fileSizeInBytes / 1024, 0) . ' KB';
        }

        return $fileSizeFormatted;
    }
    private function generateFolderStructure($directoryPath, $parentPath = '')
    {

        $folders = [];

        $items = scandir($directoryPath);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $itemPath = $directoryPath . '/' . $item;
            $relativePath = $parentPath . '/' . $item;

            $stat = stat($itemPath);

            if (is_dir($itemPath)) {
                $subfolders = $this->generateFolderStructure($itemPath, $relativePath);
                $folders[] = [
                    'name' => $item,
                    'path' => $relativePath,
                    'subfolders' => $subfolders
                ];
            }
        }

        return $folders;
    }
    private function generateRandomString($length)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = rand(0, strlen($characters) - 1);
            $result .= $characters[$randomIndex];
        }
        return $result;
    }
    private function generateRandomFileName($s)
    {
        $randomLength = 5;
        $randomString = $this->generateRandomString($randomLength);
        if ($s) {
            return "ai-$randomString-$s";
        } else {
            return "ai-$randomString";
        }
    }
    /* olaylar */
    public function listFiles()
    {
        if (! user_can('files.files.listFiles')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        if (!$this->request->is('post')) {
            return $this->failForbidden('Only POST requests allowed.');
        }

        $requestData = json_decode(file_get_contents('php://input'), true);
        $folderPath = $requestData['folderPath'] ?? '';

        $fileList = $this->getFilesAndFolders($folderPath);
        return $this->respond(['contents' => $fileList]);
    }
    public function listFolders()
    {
        if (! user_can('files.files.listFolders')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }
        $folderStructure = $this->generateFolderStructure($this->filemanagerConfig->path);

        return $this->respond(['folders' => $folderStructure]);
    }
    public function deleteFile()
    {
        if (! user_can('files.files.deleteFile')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $requestData = json_decode(file_get_contents('php://input'), true);
        $folderPath = $requestData['folderPath'] ?? '';
        $selectedItems = $requestData['selectedItems'] ?? [];

        foreach ($selectedItems as $item) {
            $itemPath = FCPATH . 'uploads/' . $folderPath . '/' . $item;
            $this->deleteItem($itemPath);
        }

        return $this->respond(['message' => 'Seçilen dosyalar/klasörler başarıyla silindi.']);
    }
    public function moveFile()
    {
        if (! user_can('files.files.moveFile')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }
        if ($this->request->isAJAX()) return $this->failForbidden();
        $requestData = json_decode(file_get_contents('php://input'), true);
        $selectedItems = $requestData['selectedItems'];
        $folderPath = $requestData['folderPath'];
        $targetPath = $requestData['targetPath'];

        foreach ($selectedItems as $item) {
            $sourcePath = $this->filemanagerConfig->path . $folderPath . '/' . $item;
            $destinationPath = $this->filemanagerConfig->path . $targetPath . '/' . $item;
            rename($sourcePath, $destinationPath);
        }

        return $this->respond(['message' => 'Selected files moved successfully.']);
    }
    public function createFolder()
    {
        if (! user_can('files.files.createFolder')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }
        if ($this->request->isAJAX()) return $this->failForbidden();
        $requestData = json_decode(file_get_contents('php://input'), true);
        $folderPath = $requestData['folderPath'];
        $folderName = $requestData['folderName'];

        $itemPath = $this->filemanagerConfig->path . $folderPath . '/' . $folderName;

        if (file_exists($itemPath)) {
            return $this->respond(['error' => 'Folder already exists.'], 400);
            return;
        }

        try {
            mkdir($itemPath); // Create the new folder
            return $this->respond(['message' => 'Folder created successfully.']);
        } catch (Exception $e) {
            return $this->respond(['error' => 'Error creating folder.'], 500);
        }
    }
    public function fileUpload()
    {
        if (! user_can('files.files.fileUpload')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        if ($this->request->isAJAX()) return $this->failForbidden();
        $folderPath = $_POST['folderPath'];

        $uploadPath = $this->filemanagerConfig->path . $folderPath . '/';

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $uploadedFiles = [];
        $errors = [];

        foreach ($_FILES['file']['name'] as $key => $val) {
            $filename = $_FILES['file']['name'][$key];
            $uploadedFiles[] = $filename;

            $targetFile = $uploadPath . $filename;

            if (move_uploaded_file($_FILES['file']['tmp_name'][$key], $targetFile)) {
                $uploadedFiles[] = $filename;
            } else {
                $errors[] = 'Failed to upload ' . $filename;
            }
        }
        return $this->respond(['message' => 'Upload complete.']);
    }
    public function renameFile()
    {
        if (! user_can('files.files.renameFile')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        if ($this->request->isAJAX()) return $this->failForbidden();
        $requestData = json_decode(file_get_contents('php://input'), true);
        $currentName = $requestData['currentName'];
        $newName = $requestData['newName'];
        $folderPath = $requestData['folderPath'];

        $currentItemPath = $this->filemanagerConfig->path . $folderPath . '/' . $currentName;
        $newItemPath = $this->filemanagerConfig->path . $folderPath . '/' . $newName;

        if (rename($currentItemPath, $newItemPath)) {
            return $this->respond(['message' => 'Renamed successfully.']);
        } else {
            return $this->respond(['error' => 'API response invalid: ' . $response], 500);
        }
    }
    public function getModels()
    {
        if (! user_can('files.files.getModels')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }
        $getModelsUrl = 'https://api.getimg.ai/v1/models';

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $headers = array(
                'Authorization: Bearer ' . $this->filemanagerConfig->GETIMG_API_KEY,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $getModelsUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 200) {
                return $this->respond(['models' => json_decode($response)]);
            } else {
                return $this->respond(['error' => 'API response invalid: ' . $response], 500);
            }
        }
    }
    public function textToImage()
    {
        if (! user_can('files.files.textToImage')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $textToImageUrl = 'https://api.getimg.ai/v1/stable-diffusion/text-to-image';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestData = json_decode(file_get_contents('php://input'), true);
            $prompt = $requestData['prompt'];
            $negative_prompt = $requestData['negative_prompt'];
            $model = $requestData['model'] ?? 'realistic-vision-v3';
            $width = $requestData['width'] ?? 512;
            $height = $requestData['height'] ?? 512;
            $steps = $requestData['steps'] ?? 75;
            $guidance = $requestData['guidance'] ?? 9;
            $scheduler = $requestData['scheduler'] ?? 'dpmsolver++';
            $output_format = $requestData['output_format'] ?? 'jpeg';
            $folder_path = $requestData['folder_path'];

            $messages = [
                'model' => $model,
                'prompt' => $prompt,
                'negative_prompt' => $negative_prompt,
                'width' => $width,
                'height' => $height,
                'steps' => $steps,
                'guidance' => $guidance,
                'scheduler' => $scheduler,
                'output_format' => $output_format
            ];
            $jsonPayload = json_encode($messages);

            $headers = array(
                'Authorization: Bearer ' . $this->filemanagerConfig->GETIMG_API_KEY,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $textToImageUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 200) {
                $responseData = json_decode($response, true);
                if (!isset($responseData['error'])) {
                    $randomFileName = $this->generateRandomFileName('');
                    $filePath = $this->filemanagerConfig->path . $folder_path . '/' . $randomFileName . '.jpg';
                    $fileUrl = $this->filemanagerConfig->urlpath . $folder_path . '/' . $randomFileName . '.jpg';

                    if (!file_exists($filePath)) {
                        file_put_contents($filePath, base64_decode($responseData['image']));
                        return $this->respond(['url' => $fileUrl]);
                    } else {
                        return $this->respond(['ok' => true, 'status' => 500, 'error' => 'Something went wrong.'], 500);
                    }
                } else {
                    return $this->respond(['error' => 'Something went wrong.'], 500);
                }
            } else {
                return $this->respond(['error' => 'Something went wrong.'], 500);
            }
        }
    }
    public function upscaleImage()
    {
        if (! user_can('files.files.upscaleImage')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $upscaleImageUrl = 'https://api.getimg.ai/v1/enhacements/upscale';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestData = json_decode(file_get_contents('php://input'), true);
            $image = $requestData['image'];
            $folder_path = $requestData['folder_path'];

            $messages = [
                'model' => 'real-esrgan-4x',
                'image' => $image,
                'scale' => 4,
                'output_format' => 'jpeg'
            ];

            $jsonPayload = json_encode($messages);

            $headers = array(
                'Authorization: Bearer ' . $this->filemanagerConfig->GETIMG_API_KEY,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $upscaleImageUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 200) {
                $responseData = json_decode($response, true);
                if (!isset($responseData['error'])) {
                    $randomFileName = $this->generateRandomFileName('lg');
                    $filePath = $this->filemanagerConfig->path . $folder_path . '/' . $randomFileName . '.jpg';
                    $fileUrl = $this->filemanagerConfig->urlpath . $folder_path . '/' . $randomFileName . '.jpg';

                    if (!file_exists($filePath)) {
                        file_put_contents($filePath, base64_decode($responseData['image']));
                        return $this->respond(['url' => $fileUrl]);
                    } else {
                        return $this->respond(['ok' => true, 'status' => 500, 'error' => 'Something went wrong.'], 500);
                    }
                } else {
                    return $this->respond(['error' => 'Something went wrong.'], 500);
                }
            } else {
                return $this->respond(['error' => 'Something went wrong.'], 500);
            }
        }
    }
    public function controlNet()
    {
        if (! user_can('files.files.controlNet')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        if ($this->request->isAJAX()) return $this->failForbidden();
        $controlNetUrl = 'https://api.getimg.ai/v1/stable-diffusion/controlnet';

        $requestData = json_decode(file_get_contents('php://input'), true);
        $prompt = $requestData['prompt'];
        $negative_prompt = $requestData['negative_prompt'];
        $model = $requestData['model'] ?? 'realistic-vision-v3';
        $width = $requestData['width'] ?? 512;
        $height = $requestData['height'] ?? 512;
        $image = $requestData['image'];
        $controlnet = $requestData['controlnet'];
        $steps = $requestData['steps'] ?? 75;
        $guidance = $requestData['guidance'] ?? 9;
        $scheduler = $requestData['scheduler'] ?? 'dpmsolver++';
        $output_format = $requestData['output_format'] ?? 'jpeg';
        $folder_path = $requestData['folder_path'];

        $messages = [
            'model' => $model,
            'prompt' => $prompt,
            'negative_prompt' => $negative_prompt,
            'width' => $width,
            'height' => $height,
            'image' => $image,
            'controlnet' => $controlnet,
            'steps' => $steps,
            'guidance' => $guidance,
            'scheduler' => $scheduler,
            'output_format' => $output_format
        ];

        $jsonPayload = json_encode($messages);

        $headers = array(
            'Authorization: Bearer ' . $this->filemanagerConfig->GETIMG_API_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $controlNetUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode == 200) {
            $responseData = json_decode($response, true);
            if (!isset($responseData['error'])) {
                $randomFileName = $this->generateRandomFileName('');
                $filePath = $this->filemanagerConfig->path . $folder_path . '/' . $randomFileName . '.jpg';
                $fileUrl = $this->filemanagerConfig->urlpath . $folder_path . '/' . $randomFileName . '.jpg';

                if (!file_exists($filePath)) {
                    file_put_contents($filePath, base64_decode($responseData['image']));
                    http_response_code(200);
                    return $this->respond(['url' => $fileUrl]);
                } else {
                    return $this->respond(['error' => 'Something went wrong.'], 500);
                }
            } else {
                return $this->respond(['error' => 'Something went wrong.'], 500);
            }
        } else {
            return $this->respond(['error' => 'Something went wrong.'], 500);
        }
    }
    public function saveText()
    {
        if (! user_can('files.files.saveText')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        if ($this->request->isAJAX()) return $this->failForbidden();
        $requestData = json_decode(file_get_contents('php://input'), true);
        $fileName = $requestData['fileName'];
        $folderPath = $requestData['folderPath'];
        $text = $requestData['text'];

        $itemPath = $this->filemanagerConfig->path . $folderPath . '/' . $fileName;

        $result = file_put_contents($itemPath, $text);
        if ($result !== false) {
            return $this->respond(['message' => 'File saved successfully.']);
        } else {
            return $this->respond(['error' => 'Failed to save the file.'], 500);
        }
    }
    public function getModals()
    {
        if (! user_can('files.files.getModals')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        if(env('demo.mode')??false){
            session()->setFlashdata('sweet', ['warning',lang("App.general_demo_mode")]);
            return redirect()->to('/settings');
        }
        return view('App\Modules\Files\Views\fileModal');
    }
    public function fileManagerFiles()
    {
        if (! user_can('files.files.fileManagerFiles')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        if(env('demo.mode')??false){
            session()->setFlashdata('sweet', ['warning',lang("App.general_demo_mode")]);
            return redirect()->to('/settings');
        }
        return view('App\Modules\Files\Views\files');
    }
    private function deleteItem($itemPath) {
        if (file_exists($itemPath)) {
            if (is_file($itemPath)) {
                unlink($itemPath);
            } elseif (is_dir($itemPath)) {
                $files = scandir($itemPath);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $this->deleteItem($itemPath . '/' . $file);
                    }
                }
                rmdir($itemPath);
            }
        }
    }

}

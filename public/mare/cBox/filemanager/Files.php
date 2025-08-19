<?php

namespace App\Controllers;

use App\Models\ActivityModel;
use App\Models\BackupModel;
use App\Models\CronTabModel;
use App\Models\NotificationModel;
use App\Models\SettingsModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use Config\Filemanager;

class Files extends BaseController
{
    use ResponseTrait;
    private $filemanagerConfig;
    private $integration;
    private $user_model;
    private $notification_model;
    private $crontab_model;
    private $settings_model;
    private $activity_model;
    private $backup_model;

    function __construct()
    {
        $this->integration = new Integration();
        $this->user_model = new UserModel();
        $this->notification_model = new NotificationModel();
        $this->settings_model = new SettingsModel();
        $this->crontab_model = new CronTabModel();
        $this->activity_model = new ActivityModel();
        $this->backup_model = new BackupModel();
        $this->filemanagerConfig = new Filemanager();
    }

    private function getFilesAndFolders($folderPath)
    {
        //$this->filemanagerConfig
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
    private function deleteItem($itemPath) {
        if (file_exists($itemPath)) {
            $stats = stat($itemPath);

            if (is_file($itemPath)) {
                unlink($itemPath); // Delete file
            } else if (is_dir($itemPath)) {
                $files = scandir($itemPath);
                foreach ($files as $file) {
                    if ($file === '.' || $file === '..') {
                        continue;
                    }
                    $filePath = $itemPath . '/' . $file;
                    deleteItem($filePath); // Recursively delete files and folders
                }
                rmdir($itemPath); // Delete directory
            }
        }
    }

    /* olaylar */
    public function listFiles()
    {

        if ($this->request->isAJAX()) return $this->failForbidden();
        $requestData = json_decode(file_get_contents('php://input'), true);
        $folderPath = $requestData['folderPath'];

        $fileList = $this->getFilesAndFolders($folderPath);

        return $this->respond(['contents' => $fileList]);
    }

    public function listFolders()
    {
        $folderStructure = $this->generateFolderStructure($this->filemanagerConfig->path);

        return $this->respond(['folders' => $folderStructure]);
    }

    public function delete()
    {
        if ($this->request->isAJAX()) return $this->failForbidden();
        $requestData = json_decode(file_get_contents('php://input'), true);
        $folderPath = $requestData['folderPath'];
        $selectedItems = $requestData['selectedItems'];

        foreach ($selectedItems as $item) {
            $itemPath = $this->filemanagerConfig->path . $folderPath . '/' . $item;
            deleteItem($itemPath);
        }

        return $this->respond(['message' => 'Selected files and folders deleted successfully.']);
    }

    public function fileMove()
    {
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
    public function fileRename()
    {
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
            return $this->respond(['error' => 'Something went wrong.'], 500);
        }
    }
    public function getModels()
    {
        $getModelsUrl = 'https://api.getimg.ai/v1/models';

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $headers = array(
                'Authorization: Bearer ' . $$this->filemanagerConfig->GETIMG_API_KEY,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $getModelsUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode == 200) {
                return $this->respond(['models' => json_decode($response)]);
            } else {
                return $this->respond(['error' => 'Something went wrong.'], 500);
            }

            curl_close($ch);
        }
    }
    public function textToImage()
    {
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

            // $prompt = 'a minimalist furniture design, a small simple yellow vase, shot in a photo studio, wide angle, clean and bright background, lots of white space, minimalist look, pastel color, soft lighting';
            // $negative_prompt = '';
            // $model = 'realistic-vision-v3';
            // $width = 512;
            // $height = 512;
            // $steps = 75;
            // $guidance = 9;
            // $scheduler = 'dpmsolver++';
            // $output_format = 'jpeg';
            // $folder_path = '';

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
                'Authorization: Bearer ' . $$this->filemanagerConfig->GETIMG_API_KEY,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $textToImageUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode == 200) {
                $responseData = json_decode($response, true);
                if (!isset($responseData['error'])) {
                    $randomFileName = $this->generateRandomFileName('');
                    $filePath = $this->filemanagerConfig->path . $folder_path . '/' . $randomFileName . '.jpg';
                    $fileUrl = $this->filemanagerConfig->urlpath . $folder_path . '/' . $randomFileName . '.jpg';

                    if (!file_exists($filePath)) { // Do not replace if file exists
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

            curl_close($ch);
        }
    }
    public function upscaleImage()
    {
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
                'Authorization: Bearer ' . $$this->filemanagerConfig->GETIMG_API_KEY,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $upscaleImageUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode == 200) {
                $responseData = json_decode($response, true);
                if (!isset($responseData['error'])) {
                    $randomFileName = $this->generateRandomFileName('lg');
                    $filePath = $this->filemanagerConfig->path . $folder_path . '/' . $randomFileName . '.jpg';
                    $fileUrl = $this->filemanagerConfig->urlpath . $folder_path . '/' . $randomFileName . '.jpg';

                    if (!file_exists($filePath)) { // Do not replace if file exists
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

            curl_close($ch);
        }
    }
    public function controlNet()
    {
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
            'Authorization: Bearer ' . $$this->filemanagerConfig->GETIMG_API_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $controlNetUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode == 200) {
            $responseData = json_decode($response, true);
            if (!isset($responseData['error'])) {
                $randomFileName = $this->generateRandomFileName('');
                $filePath = $this->filemanagerConfig->path . $folder_path . '/' . $randomFileName . '.jpg';
                $fileUrl = $this->filemanagerConfig->urlpath . $folder_path . '/' . $randomFileName . '.jpg';

                if (!file_exists($filePath)) { // Do not replace if file exists
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

        curl_close($ch);
    }
    public function saveText()
    {
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

}

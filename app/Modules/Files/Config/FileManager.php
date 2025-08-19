<?php

namespace App\Modules\Files\Config;

use CodeIgniter\Config\BaseConfig;

class FileManager extends BaseConfig
{
    public $path;      // Dosya yükleme dizini
    public $urlpath;   // URL'den erişim yolu
    public $GETIMG_API_KEY;
    public $OPENAI_API_KEY;

    // Eğer AWS S3 kullanıyorsan
    public $AWS_KEY;
    public $AWS_SECRET;
    public $AWS_BUCKET;
    public $AWS_URL;
    public $AWS_REGION;
    public $prefix;

    public function __construct()
    {
        parent::__construct();

        // Burada ENV veya sabit değerleri ayarlıyoruz
        $this->path           = ROOTPATH . 'public/uploads/';
        $this->urlpath        = '/uploads/';
        $this->GETIMG_API_KEY = getenv('OPENAI_API_KEY') ?: '';
        $this->OPENAI_API_KEY = getenv('OPENAI_API_KEY') ?: '';

        // AWS bilgilerini istersen ENV'den çekebilirsin
        $this->AWS_KEY    = getenv('AWS_ACCESS_KEY_ID') ?: 'YOUR_KEY';
        $this->AWS_SECRET = getenv('AWS_SECRET_ACCESS_KEY') ?: 'YOUR_SECRET';
        $this->AWS_BUCKET = getenv('AWS_BUCKET') ?: 'bucketname';
        $this->AWS_URL    = getenv('AWS_URL') ?: 'http://bucketname.s3-website-us-east-1.amazonaws.com';
        $this->AWS_REGION = getenv('AWS_REGION') ?: 'us-east-1';
        $this->prefix     = ''; // İstersen bir dizin ön eki verebilirsin (opsiyonel)
    }
}

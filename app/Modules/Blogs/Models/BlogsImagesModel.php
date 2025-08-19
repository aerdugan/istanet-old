<?php
namespace App\Modules\Blogs\Models;
use CodeIgniter\Model;

class BlogsImagesModel extends Model
{
    protected $table      = 'blogImages';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'blog_id', 'img_url', 'isActive', 'isCover', 'rank', 'createdAt'
    ];

    public $timestamps = false;
}
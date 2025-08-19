<?php
namespace App\Modules\References\Models;
use CodeIgniter\Model;

class ReferencesImagesModel extends Model
{
    protected $table      = 'referenceImages';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'reference_id', 'img_url', 'isActive', 'isCover', 'rank', 'createdAt'
    ];

    public $timestamps = false;
}
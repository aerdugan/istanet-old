<?php
namespace App\Modules\General\Models;

use CodeIgniter\Model;


class GeneralModel extends Model
{
    protected $table = 'task';
    protected $primaryKey = 'id_task';
    protected $allowedFields = [
        'title',
        'description'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
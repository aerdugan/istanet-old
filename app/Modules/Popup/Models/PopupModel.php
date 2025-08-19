<?php
namespace App\Modules\Popup\Models;

class PopupModel extends \App\Models\BaseModel
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
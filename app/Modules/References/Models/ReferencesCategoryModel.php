<?php
namespace App\Modules\References\Models;
use CodeIgniter\Model;

class ReferencesCategoryModel extends Model

{
    protected $table = 'referenceCategories'; // 👈 tabloyu belirtiyoruz
    protected $primaryKey = 'id'; // 👈 primary key
    protected $allowedFields = [
        'referenceID', 'title', 'url', 'isActive', 'rank',
        'data_lang', 'createdAt', 'updatedAt', 'createdUser', 'lastUpdatedUser'
    ];

    protected $useTimestamps = false; // created_at, updated_at yoksa

    public function getReferences()
    {
        return $this->orderBy('rank', 'ASC')->findAll();
    }
    public function saveReference($data)
    {
        return $this->insert($data);
    }


    public function updateCategories($data, $id)
    {
        return $this->update($id, $data);
    }

    public function getOne($id)
    {
        return $this->find($id);
    }

    public function deleteCategory($id)
    {
        return $this->delete($id);
    }

    public function getLastReference()
    {
        return $this->db->table('references')->orderBy('id', 'DESC')->get(1)->getRowArray();
    }
}
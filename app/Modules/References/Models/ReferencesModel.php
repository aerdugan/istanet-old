<?php
namespace App\Modules\References\Models;
use CodeIgniter\Model;

class ReferencesModel extends Model

{
    protected $table = 'references'; // 👈 tabloyu belirtiyoruz
    protected $primaryKey = 'id'; // 👈 primary key
    protected $allowedFields = [
        'referenceID','referenceImageID', 'url', 'title', 'description', 'location', 'category_id','rank',
        'rank', 'isActive', 'isFront', 'data_lang', 'seoKeywords', 'seoDesc','picturePrice','year','createdAt','updatedAt','createdUser','lastUpdatedUser',
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


    public function updateReference($data, $id)
    {
        return $this->update($id, $data);
    }

    public function getOne($id)
    {
        return $this->find($id);
    }

    public function deleteReference($id)
    {
        return $this->delete($id);
    }

    public function getLastReference()
    {
        return $this->db->table('references')->orderBy('id', 'DESC')->get(1)->getRowArray();
    }
}
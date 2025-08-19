<?php
namespace App\Modules\QualityCertificates\Models;

use CodeIgniter\Model;

class QualityCertificatesModel extends Model
{
    protected $table         = 'qualityCertificates'; // tablo adın
    protected $primaryKey    = 'id';

    protected $allowedFields = [
        'title',
        'imgUrl',
        'iconUrl',
        'rank',
        'isActive',
        'data_lang',
        'createdUser',
        'lastUpdatedUser',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;        // created_at / updated_at otomatik
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Listeleme
    public function getQualityCertificates(array $where = [], string $orderBy = 'rank ASC')
    {
        return $this->where($where)->orderBy($orderBy)->findAll();
    }

    // Ekleme
    public function createQualityCertificate(array $data)
    {
        return $this->insert($data);
    }

    // Güncelleme
    public function updateQualityCertificates(array $data, $id)
    {
        return $this->update($id, $data);
    }

    // Silme
    public function deleteQualityCertificates($id)
    {
        return $this->delete($id);
    }
}
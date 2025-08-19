<?php
namespace App\Modules\Slider\Models;
use CodeIgniter\Model;

class SliderModel extends Model

{
    protected $table = 'sliders'; // 👈 tabloyu belirtiyoruz
    protected $primaryKey = 'id'; // 👈 primary key
    protected $allowedFields = [
        'name', 'title', 'desc1', 'desc2', 'allowButton', 'buttonCaption',
        'buttonUrl', 'imgUrl', 'rank', 'isActive', 'data_lang', 'createdUser','referenceID',
    ];

    protected $useTimestamps = false; // created_at, updated_at yoksa

    public function getSliders()
    {
        return $this->orderBy('rank', 'ASC')->findAll();
    }
    public function saveSlider($data)
    {
        return $this->insert($data);
    }


    public function updateSlider($data, $id)
    {
        return $this->update($id, $data);
    }

    public function getOne($id)
    {
        return $this->find($id);
    }

    public function deleteSlider($id)
    {
        return $this->delete($id);
    }

    public function getLastSlider()
    {
        return $this->db->table('sliders')->orderBy('id', 'DESC')->get(1)->getRowArray();
    }
}
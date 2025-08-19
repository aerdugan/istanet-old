<?php
namespace App\Modules\Blogs\Models;
use CodeIgniter\Model;

class BlogsCategoryModel extends Model

{
    protected $table = 'blogCategories'; // 👈 tabloyu belirtiyoruz
    protected $primaryKey = 'id'; // 👈 primary key
    protected $allowedFields = [
        'blogID', 'title', 'url', 'isActive', 'rank',
        'data_lang', 'createdAt', 'updatedAt', 'createdUser', 'lastUpdatedUser'
    ];

    protected $useTimestamps = false; // created_at, updated_at yoksa

    public function getBlogs()
    {
        return $this->orderBy('rank', 'ASC')->findAll();
    }
    public function saveBlog($data)
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

    public function getLastBlog()
    {
        return $this->db->table('blogs')->orderBy('id', 'DESC')->get(1)->getRowArray();
    }
}
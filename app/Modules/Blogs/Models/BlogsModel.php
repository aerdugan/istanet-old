<?php
namespace App\Modules\Blogs\Models;
use CodeIgniter\Model;

class BlogsModel extends Model

{
    protected $table = 'blogs'; // 👈 tabloyu belirtiyoruz
    protected $primaryKey = 'id'; // 👈 primary key
    protected $allowedFields = [
        'blogImageID', 'url', 'title', 'description', 'location', 'category_id',
        'rank', 'isActive', 'isFront', 'data_lang', 'seoKeywords', 'seoDesc','picturePrice','year','createdAt','updatedAt','createdUser','lastUpdatedUser',
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


    public function updateBlog($data, $id)
    {
        return $this->update($id, $data);
    }

    public function getOne($id)
    {
        return $this->find($id);
    }

    public function deleteBlog($id)
    {
        return $this->delete($id);
    }

    public function getLastBlog()
    {
        return $this->db->table('blogs')->orderBy('id', 'DESC')->get(1)->getRowArray();
    }
}
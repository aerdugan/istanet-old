<?php
namespace App\Modules\General\Models;
use CodeIgniter\Model;
class ContactModel extends Model

{
    public function getContactList()
    {
        $builder = $this->db->table('contactSettings');
        $builder->select('*');
        $builder->orderBy('rank', 'ASC'); // Rank'a göre artan sırada sıralama yap
        return $builder->get();
    }
    public function saveContact($data){
        $query = $this->db->table('contactSettings')->insert($data);
        return $query;
    }

    public function updateContact($data, $id)
    {
        $query = $this->db->table('contactSettings')->update($data, array('id' => $id));
        return $query;
    }

    public function deleteContact($id)
    {
        $query = $this->db->table('contactSettings')->delete(array('id' => $id));
        return $query;
    }
}
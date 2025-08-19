<?php

namespace App\Models;

use CodeIgniter\Model;

class CommonModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    public function lists($where = [], $table, $select = '*', $order = 'id ASC')
    {
        // Query Builder'ı başlatıyoruz
        $builder = $this->db->table($table);

        // Seçilecek kolonlar
        $builder->select($select);

        // Where koşullarını ekle
        if (!empty($where)) {
            $builder->where($where);
        }

        // Sıralama ekle
        $builder->orderBy($order);

        // Sorguyu çalıştır ve sonuçları döndür
        return $builder->get()->getResult();
    }

    public function selectOne($where = [], $table, $select = '*')
    {
        // Query Builder ile sorgu oluşturma
        $builder = $this->db->table($table);
        $builder->select($select);

        // Where koşulları ekleniyor
        if (!empty($where)) {
            $builder->where($where);
        }

        // Tek bir satır döndürmek için getRow() kullanıyoruz
        return $builder->get()->getRow();
    }


}

<?php
namespace App\Modules\Database\Controllers;

use App\Controllers\BaseController;

/**
 * Class Task
 */
class Database extends BaseController
{

    protected $db;
    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {

        if (! user_can('Database.Database.store')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }

        $tables = $this->db->listTables(); // Tüm tabloları al
        $excludedTables = ['auth_groups_users', 'auth_identities','auth_logins','auth_permissions_users','auth_remember_tokens','auth_token_logins','migrations','permissions','roles','role_permissions','settings','users','user_roles','wg_userDetails','wg_user_group','wg_user_oauth','wg_backup','wg_socialSettings','wg_seoSettings','wg_themeSettings','wg_otherSettings','wg_mobileThemeSettings','wg_logoSettings','wg_contactSettings']; // Hariç tutulacak tablolar
        $filteredTables = array_filter($tables, function ($table) use ($excludedTables) {
            return !in_array($table, $excludedTables);
        });

        // Tablolar ve satır sayıları
        $tableData = [];
        foreach ($filteredTables as $table) {
            $query = $this->db->query("SELECT COUNT(*) as row_count FROM $table");
            $result = $query->getRow();
            $rowCount = $result->row_count;

            $tableData[] = [
                'table_name' => $table,
                'row_count' => $rowCount,
            ];
        }
        $session = session();
        return view('App\Modules\Database\Views\index', [
            'tables' => $tableData,
            'successMessage' => $session->getFlashdata('successMessage'),
        ]);
    }

    public function downloadSQL($tableName)
    {
        if (! user_can('Database.Database.downloadSQL')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }
        $excludedTables = ['IW_activity', 'IW_clients'];
        if (in_array($tableName, $excludedTables)) {
            return "Bu tablo indirilemez.";
        }

        $structureQuery = $this->db->query("SHOW CREATE TABLE $tableName");
        $structure = $structureQuery->getRowArray()['Create Table'] . ";";

        $dataQuery = $this->db->query("SELECT * FROM $tableName");
        $rows = $dataQuery->getResultArray();

        $sqlDump = "-- Tablo Yapısı\n";
        $sqlDump .= $structure . "\n\n";
        $sqlDump .= "-- Tablo Verileri\n";

        foreach ($rows as $row) {
            $values = array_map(function ($value) {
                return $this->db->escape($value);
            }, array_values($row));
            $sqlDump .= "INSERT INTO $tableName VALUES (" . implode(',', $values) . ");\n";
        }

        $filename = "$tableName.sql";
        header("Content-Type: application/sql");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        echo $sqlDump;

        // Başarı mesajını ayarla
        session()->setFlashdata('successMessage', 'İndirme işlemi başarılı!');
        exit;
    }

    public function truncate($tableName)
    {
        if (! user_can('Database.Database.truncate')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }
        $excludedTables = ['IW_activity', 'IW_clients'];
        if (in_array($tableName, $excludedTables)) {
            return "Bu tablo boşaltılamaz.";
        }

        $this->db->query("TRUNCATE TABLE $tableName");
        session()->setFlashdata('successMessage', 'Boşaltma işlemi başarılı!');
        return redirect()->to('/admin/database');
    }

    public function uploadSQL()
    {
        if (! user_can('Database.Database.uploadSQL')) {
            session()->setFlashdata('swal', [
                'type'    => 'error',
                'title'   => 'Yetki Hatası',
                'message' => 'Bu sayfayı görmeye yetkiniz yok.',
            ]);
            return redirect()->to('/dashboard');
        }
        $file = $this->request->getFile('sqlFile');
        $tableName = $this->request->getPost('tableName');
        $excludedTables = ['IW_activity', 'IW_clients'];

        if ($file->isValid() && !$file->hasMoved()) {
            $filePath = WRITEPATH . 'uploads/' . $file->getRandomName(); // daha güvenli
            $file->move(WRITEPATH . 'uploads', basename($filePath));

            $sql = file_get_contents($filePath);
            try {
                $this->db->transStart();
                $this->db->query("SET FOREIGN_KEY_CHECKS=0");
                $this->db->query("TRUNCATE TABLE `$tableName`");
                $this->db->query($sql);
                $this->db->query("SET FOREIGN_KEY_CHECKS=1");
                $this->db->transComplete();

                if ($this->db->transStatus()) {
                    session()->setFlashdata('successMessage', 'Yükleme işlemi başarılı!');
                } else {
                    session()->setFlashdata('successMessage', 'Veritabanı işlemi başarısız!');
                }
            } catch (\Exception $e) {
                session()->setFlashdata('successMessage', 'Hata: ' . $e->getMessage());
            }
            return redirect()->to('/admin/database');
        }

        if (in_array($tableName, $excludedTables)) {
            return "Bu tabloya veri yüklenemez.";
        }

        if ($file->isValid() && !$file->hasMoved()) {
            $filePath = WRITEPATH . 'uploads/' . $file->getName();
            $file->move(WRITEPATH . 'uploads');

            $sql = file_get_contents($filePath);
            $this->db->query("SET FOREIGN_KEY_CHECKS=0");
            $this->db->query("TRUNCATE TABLE $tableName");
            $this->db->query($sql);
            $this->db->query("SET FOREIGN_KEY_CHECKS=1");

            session()->setFlashdata('successMessage', 'Yükleme işlemi başarılı!');
            return redirect()->to('/database');
        }

        session()->setFlashdata('successMessage', 'Yükleme işlemi başarısız!');
        return redirect()->to('/admin/database');
    }
}
<?php
namespace App\Modules\Page\Models;
use CodeIgniter\Model;

class PageModel extends Model
{
    protected $table = 'pages'; // Veritabanı tablonun adı
    protected $primaryKey = 'id'; // Birincil anahtar
    protected $allowedFields = ['id', 'referenceID', 'title', 'parent_id', 'url', 'mobileUrl', 'rank', 'mobileRank', 'breadcrumbStatus',
        'breadcrumbTitle', 'breadcrumbSlogan', 'breadcrumbImageStatus', 'breadcrumbImage', 'inpHtml', 'mobileHtml', 'cBoxMainCss', 'cBoxSectionCss',
        'cBoxContent', 'cBoxMobileMainCss', 'cBoxMobileSectionCss', 'cBoxMobileContent', 'isActive', 'isHeader', 'isFooter', 'isMobileFooter',
        'isMobile', 'isWebEditor', 'isMobileEditor', 'data_lang', 'setStyle', 'seoKeywords', 'seoDesc', 'createdAt', 'createdUser',
        'updated_at', 'lastUpdatedUser'];


    // Eğer otomatik zaman damgası istiyorsan (created_at, updated_at için)
    protected $useTimestamps = false;

    public function getPage()
    {
        return $this->orderBy('rank', 'ASC')->findAll();
    }

    public function savePage($data)
    {
        return $this->insert($data);
    }

    public function updatePage($data, $id)
    {
        return $this->update($id, $data);
    }

    public function getOne($id)
    {
        return $this->find($id);
    }

    public function deletePage($id)
    {
        return $this->delete($id);
    }
}

<?php

use App\Models\LanguageModel;

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */


// Bu dosyada helper(), service(), session() ÇAĞIRMAYIN.

// Örnek: basit bir yardımcı fonksiyon
if (!function_exists('app_version')) {
    function app_version(): string
    {
        return '1.0.0';
    }
}

if (!function_exists('getPages')) {
    function getPages($db)
    {
        $query = $db->table('pages')
            ->select('*')
            ->where('isActive', 1)
            ->orderBy('rank', 'ASC')
            ->get();

        return $query->getResult();
    }
}

// BURADA buildTree TANIMI OLMAYACAK.

use CodeIgniter\Settings\Settings;

if (! function_exists('setting')) {
    function setting(?string $key = null, $value = null)
    {
        $setting = service('settings'); /** @var Settings $setting */

        if (empty($key)) {
            return $setting;
        }
        if (func_num_args() === 1) {
            return $setting->get($key);
        }
        $setting->set($key, $value);
    }
}
if (! function_exists('getActiveLanguages')) {
    function getActiveLanguages(): array
    {
        return model(LanguageModel::class)
            ->select('id,title,shorten,rank,isActive')
            ->where('isActive', 1)
            ->orderBy('rank', 'ASC')
            ->findAll();
    }
}

if (! function_exists('getDefaultSiteLanguage')) {
    // Site varsayılanı: rank=0 (ör: 'tr')
    function getDefaultSiteLanguage(): string
    {
        return getLanguageByRank(0) ?? 'tr';
    }
}
if (! function_exists('getLanguageByRank')) {
    function getLanguageByRank(int $rank = 0): ?string
    {
        $row = model(LanguageModel::class)
            ->select('shorten')
            ->where('isActive', 1)
            ->where('rank', $rank)
            ->first();

        return $row['shorten'] ?? null;
    }
}
if (!function_exists('adminTheme')) {
    function adminTheme(){
        echo "https://cdn.istanet.com/admin/metronic_39_831/";
    }
}
if (! function_exists('user_can')) {
    /**
     * @param string $permission Slug (ör: 'files.files.index')
     * @param bool   $forceRefresh true ise session cache yok sayılır
     */
    function user_can(string $permission, bool $forceRefresh = false): bool
    {
        helper('auth');

        $user = auth()->user();
        if (! $user) {
            return false;
        }
        $userId = (int) $user->id;

        // normalize
        $permission = strtolower(trim($permission));

        // --- Session cache
        $cacheKey = "perm_cache_user_{$userId}";
        if (! $forceRefresh) {
            $cached = session()->get($cacheKey);
            if (is_array($cached)) {
                return in_array($permission, $cached, true);
            }
        }

        // --- Roller
        $urm = new \App\Modules\Users\Models\UserRoleModel();
        $roles = $urm->getRolesByUserId($userId);
        $roleIds = [];
        foreach ($roles as $r) {
            $roleIds[] = (int) (is_array($r) ? $r['role_id'] : $r->role_id);
        }
        if (empty($roleIds)) {
            session()->set($cacheKey, []);
            return false;
        }

        // --- Join'li tek sorgu: isimleri çekelim
        $db = db_connect();
        $names = $db->table('user_roles ur')
            ->select('LOWER(p.name) AS name', false)
            ->join('role_permissions rp', 'rp.role_id = ur.role_id')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('ur.user_id', $userId)
            ->groupBy('p.name')
            ->get()->getResultArray();

        $list = array_map(fn($x) => $x['name'], $names);

        session()->set($cacheKey, $list);

        return in_array($permission, $list, true);
    }
}
if (! function_exists('getSessionLanguageTitle')) {
    /**
     * Session'daki data_lang (shorten) için languages.title döndürür.
     * Yoksa uygun fallback döner.
     *
     * @param string|null $default Fallback başlık (varsayılan "Türkçe")
     */
    function getSessionLanguageTitle(?string $default = 'Türkçe'): string
    {
        static $cache = []; // ['tr' => 'Türkçe', 'en' => 'İngilizce', ...]

        $code = getSessionLanguageCode();
        if ($code === null) {
            return $default ?? 'Türkçe';
        }

        if (isset($cache[$code])) {
            return $cache[$code];
        }

        // DB'den çek
        $m = model(LanguageModel::class);

        // Önce exact match (aktif/pasif fark etmeksizin)
        $row = $m->select('title')->where('shorten', $code)->first();
        if (! $row) {
            // Fallback: aktif en düşük ranklı dil
            $row = $m->select('title')->where('isActive', 1)->orderBy('rank', 'ASC')->first();
        }

        $title = $row['title'] ?? ($default ?? 'Türkçe');
        // Basit cache
        $cache[$code] = $title;

        return $title;
    }
}
if (! function_exists('getSessionLanguageCode')) {
    /**
     * Session'daki dil kodunu döndürür (ör: 'tr').
     * Yoksa null döner.
     */
    function getSessionLanguageCode(): ?string
    {
        $code = session('data_lang');
        return is_string($code) && $code !== '' ? $code : null;
    }
}

function seoFriendly($string)
{
    $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    $string = preg_replace('/[^A-Za-z0-9\s\-]/', '', $string);
    $string = preg_replace('/\s+/', ' ', $string);
    $string = str_replace(' ', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    $string = trim($string, '-');
    $string = strtolower($string);
    return $string;
}
if (!function_exists('sweetAlert')) {
    function sweetAlert()
    {
        try {
            $session = session();
            $alert = $session->getFlashdata('sweet');

            if (is_array($alert) && count($alert) === 2) {
                // Basit bilgi veya uyarı bildirimi
                return "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            position: 'center',
                            icon: '".htmlspecialchars($alert[0], ENT_QUOTES)."',
                            title: '".htmlspecialchars($alert[1], ENT_QUOTES)."',
                            showConfirmButton: false,
                            timer: 2000,
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                    });
                </script>";
            }

            if (is_array($alert) && count($alert) === 4) {
                // Onaylı silme işlemi gibi durumlar
                return "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: '".htmlspecialchars($alert[1], ENT_QUOTES)."',
                            text: '".htmlspecialchars($alert[2], ENT_QUOTES)."',
                            icon: '".htmlspecialchars($alert[0], ENT_QUOTES)."',
                            showCancelButton: true,
                            confirmButtonColor: '#f34141',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Evet, sil!',
                            cancelButtonText: 'Vazgeç',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '".htmlspecialchars($alert[3], ENT_QUOTES)."';
                            }
                        });
                    });
                </script>";
            }

        } catch (Exception $ex) {
            // Hataları sessizce geç
        }

        return ''; // alert yoksa boş dön
    }
}
function sweetAlert2()
{
    if (session()->has('swal')) {
        $swal = session('swal');
        echo "<script>
            Swal.fire({
                icon: '{$swal['icon']}',
                title: '{$swal['title']}',
                text: '{$swal['text']}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>";
    }
}
if (! function_exists('getDefaultAdminLanguage')) {
    // Admin varsayılanı: rank=1
    function getDefaultAdminLanguage(): string
    {
        return getLanguageByRank(1) ?? (getLanguageByRank(0) ?? 'tr');
    }
}
if (! function_exists('getDefaultLanguage')) {
    /**
     * Varsayılan dilin sadece shorten değerini döndürür. (ör: 'en')
     */
    function getDefaultLanguage(): ?string
    {
        $model = model(\App\Models\LanguageModel::class);

        // garantili: rank=1
        $row = $model->where('rank', 1)->first();

        if (is_object($row)) {
            return $row->shorten ?? null;
        } elseif (is_array($row)) {
            return $row['shorten'] ?? null;
        }
        return null;
    }
}
if (!function_exists('ol_tree_menu')) {
    function ol_tree_menu($child = array())
    {
        if (!empty($child)) {
            echo '<ol class="dd-list">';
            foreach ($child as $item) {
                echo '<li class="dd-item" data-id="' . $item->id . '">';
                echo '<div class="dd-handle">' . $item->title . '</div>';
                echo '<div style="position:absolute;right:0;top:7px;z-index:99;">';
                echo '<div class="row" style="height: 0px !important;padding-right:30px !important;">';
                echo '<div class="d-flex justify-content-end flex-shrink-0" style="padding-right: 10px;padding-top: 1px!important;">';

                // Clone page form
                echo '<div class="col-md-6" style="padding-right: 35px"></div>';

                // Edit button
                echo '<div class="col-md-2" style="margin-right: 20px;">';
                echo '<a href="' . base_url("admin/page/updateForm/$item->id") . '" class="btn btn-info btn-sm">';
                echo '<i class="fa fa-edit"></i>';
                echo '</a>';
                echo '</div>';

                // Delete button
                echo '<div class="col-md-2" style="margin-right: 20px;">';
                echo '<button data-id="' . $item->id . '" class="btn btn-danger btn-sm btn-delete">';
                echo '<i class="fa fa-trash"></i>';
                echo '</button>';
                echo '</div>';

                // Toggle Active/Inactive
                echo '<div class="col-md-2" style="margin-right: 12px;">';
                echo '<label class="el-switch">';
                echo '<input type="checkbox" name="switch" class="toggle-isActive" data-id="' . $item->id . '" ' . ($item->isActive ? 'checked' : '') . ' hidden>';
                echo '<span class="el-switch-style"></span>';
                echo '</label>';
                echo '</div>';

                echo '</div>'; // d-flex
                echo '</div>'; // row
                echo '</div>'; // dd-handle container

                // Eğer çocukları varsa
                if (!empty($item->children)) {
                    ol_tree_menu($item->children);
                }

                echo '</li>';
            }
            echo '</ol>';
        }
    }
}

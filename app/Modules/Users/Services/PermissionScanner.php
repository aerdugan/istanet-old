<?php

namespace App\Modules\Users\Services;

use App\Modules\Users\Config\Permissions as PermissionsConfig;
use ReflectionClass;
use ReflectionMethod;

class PermissionScanner
{
    public function scan(bool $prune = false): array
    {
        $cfg   = new PermissionsConfig();
        $table = $cfg->table ?? 'permissions';
        $db    = db_connect();
        $tb    = $db->table($table);

        $found = $this->discover();
        $added = [];
        $kept  = [];

        // 1) Yeni izinleri ekle
        foreach ($found as $perm) {
            $exists = $tb->where('name', $perm)->countAllResults();
            $tb->resetQuery();
            if ($exists > 0) {
                $kept[] = $perm;
                continue;
            }
            $tb->insert(['name' => $perm, 'description' => $this->prettyDesc($perm)]);
            $tb->resetQuery();
            $added[] = $perm;
        }

        // 2) Gereksizleri temizle (opsiyonel)
        $pruned = [];
        if ($prune) {
            $dbPerms = $tb->select('id, name')->get()->getResultArray();
            $remove  = array_filter($dbPerms, fn($r) => !in_array($r['name'], $found, true));

            if ($remove) {
                $ids = array_column($remove, 'id');

                $db->table('role_permissions')->whereIn('permission_id', $ids)->delete();
                $tb->whereIn('id', $ids)->delete();

                $pruned = array_column($remove, 'name');
            }
        }

        return [
            'added'  => $added,
            'kept'   => $kept,
            'pruned' => $pruned,
            'found'  => $found,
        ];
    }

    private function prettyDesc(string $perm): string
    {
        return ucwords(str_replace('.', ' ', $perm));
    }

    private function discover(): array
    {
        $targets = [
            [
                'path'      => rtrim(APPPATH . 'Controllers', DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR,
                'nsPrefix'  => 'App\\Controllers\\',
            ],
            [
                'path'      => rtrim(APPPATH . 'Modules', DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR,
                'nsPrefix'  => 'App\\Modules\\',
                'isModules' => true,
            ],
        ];

        $perms = [];

        foreach ($targets as $t) {
            $basePath  = $t['path'];
            $nsPrefix  = $t['nsPrefix'];
            $isModules = !empty($t['isModules']);

            if (! is_dir($basePath)) {
                continue;
            }

            $rii = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($basePath, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($rii as $file) {
                if (!$file->isFile() || $file->getExtension() !== 'php') {
                    continue;
                }
                if (strcasecmp($file->getFilename(), 'BaseController.php') === 0) {
                    continue;
                }

                $pathname = $file->getPathname();
                $relative = str_replace($basePath, '', $pathname);
                $relativeNoExt = substr($relative, 0, -4);

                if ($isModules) {
                    $parts = preg_split('#[\\\\/]#', $relativeNoExt);
                    if (!$parts || count($parts) < 2) {
                        continue;
                    }
                    $moduleName = array_shift($parts);
                    $controllersIdx = array_search('Controllers', $parts, true);
                    if ($controllersIdx === false) {
                        $controllersIdx = array_search('controllers', array_map('strtolower', $parts), true);
                    }
                    if ($controllersIdx === false) {
                        continue;
                    }
                    $classSegments = array_slice($parts, $controllersIdx + 1);
                    if (empty($classSegments)) {
                        continue;
                    }

                    $classPart = $moduleName . '\\Controllers\\' . implode('\\', $classSegments);
                    $fqcn      = $nsPrefix . $classPart;
                } else {
                    $classPart = str_replace(['/', '\\'], '\\', $relativeNoExt);
                    $fqcn      = $nsPrefix . $classPart;
                }

                if (!class_exists($fqcn)) {
                    require_once $pathname;
                }
                if (!class_exists($fqcn)) {
                    continue;
                }

                $ref = new ReflectionClass($fqcn);
                if (!$ref->isSubclassOf(\CodeIgniter\Controller::class)) {
                    continue;
                }
                if (strcasecmp($ref->getShortName(), 'BaseController') === 0) {
                    continue;
                }

                if ($isModules) {
                    $prettyNs = strtolower(str_replace('\\', '.', $classPart));
                    $prettyNs = preg_replace('#\.controllers\.#i', '.', $prettyNs);
                } else {
                    $prettyNs = strtolower(str_replace('\\', '.', $classPart));
                }

                foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $m) {
                    if ($m->class !== $fqcn) continue;

                    $name = $m->name;
                    $lc   = strtolower($name);

                    if (
                        $m->isConstructor() ||
                        $name === 'initController' ||
                        str_starts_with($name, '__') ||
                        str_starts_with($name, '_')  ||
                        in_array($name, ['isAuthorized','isAllowed'], true) ||
                        $lc === 'init' ||
                        str_starts_with($lc, 'init')
                    ) {
                        continue;
                    }

                    $perms[] = "{$prettyNs}.{$name}";
                }
            }
        }

        $perms = array_values(array_unique($perms));
        sort($perms);

        return $perms;
    }
}
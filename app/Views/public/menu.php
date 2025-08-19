<?php
$permission = getMenuControl();
$menus = [];
if(file_exists(APPPATH . "Views/menus/Backend.json")){
    $menus = file_get_contents(APPPATH . "Views/menus/Backend.json");
    $menus = @json_decode($menus);
}
?>
<?php
//Generation of menus
$module_menu = file_exists(APPPATH . "Modules/Modules.json");
if (($menus && is_array($menus) && count($menus))) {
    foreach ($menus as $menu) {
        if(isset($menu->divider)){
            if($menu->divider == "App.menu_modules"){
                //Generation of modules
                if($module_menu) {
                    $mods = file_get_contents(APPPATH . "Modules/Modules.json");
                    $mods = @json_decode($mods);
                    $enable_module = false;
                    $permission_module = false;
                    foreach ($mods as $item) {
                        if ($item->status) {
                            $enable_module = true;
                        }
                        foreach ($item->rules as $rules){
                            if($rules == session()->get('group')){
                                $permission_module = true;
                            }
                        }
                    }
                    if ($enable_module && $permission_module) {
                        if (($mods && is_array($mods) && count($mods))) {
                            echo '<div class="menu-item menu-labels"><div class="menu-content d-flex flex-stack fw-bold text-gray-600 text-uppercase fs-7"><span class="menu-heading ps-1">' . lang($menu->divider) . '</span></div></div>';
                            foreach ($mods as $item) {
                                if ($item->status) {
                                    if (file_exists(APPPATH . "Modules/" . $item->directory . '/app.json')) {
                                        $app = file_get_contents(APPPATH . "Modules/" . $item->directory . '/app.json');
                                        $app = @json_decode($app);
                                        if ($app && $app->type == 'module') {
                                            if (($app && is_array($app->menu) && count($app->menu))) {
                                                foreach ($app->menu as $menu) {
                                                    $submenu = '';
                                                    foreach ($menu->children as $children) {
                                                        if ($children->external) {
                                                            $submenu .= '<div class="menu-item"><a class="menu-link" target="_blank" href="' . $children->url . '"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">' . lang($children->title) . '</span></a></div>';
                                                        } else {
                                                            $submenu .= '<div class="menu-item"><a class="menu-link" href="' . site_url($children->url) . '"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">' . lang($children->title) . '</span></a></div>';
                                                        }
                                                    }
                                                    $submenu = count($menu->children) > 0 ? '<div class="menu-sub menu-sub-accordion">' . $submenu . '</div>' : '';
                                                    if (empty($submenu)) {
                                                        if ($menu->external) {
                                                            echo '<div class="menu-item"><a class="menu-link " target="_blank" href="' . $menu->url . '"><span class="menu-icon"><i class="' . $menu->icon . '"></i></span><span class="menu-title">' . lang($menu->title) . '</span></a></div>';
                                                        } else {
                                                            echo '<div class="menu-item"><a class="menu-link " href="' . site_url($menu->url) . '"><span class="menu-icon"><i class="' . $menu->icon . '"></i></span><span class="menu-title">' . lang($menu->title) . '</span></a></div>';
                                                        }
                                                    } else {
                                                        echo '<div data-kt-menu-trigger="click" class="menu-item"><span class="menu-link"><span class="menu-icon"><i class="' . $menu->icon . '"></i></span><span class="menu-title">' . lang($menu->title) . '</span><span class="menu-arrow"></span></span>' . $submenu . '</div>';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                if(session()->get('group') == "115b5ad39b853084209caf6824224f6b")
                    echo '<div class="menu-item menu-labels"><div class="menu-content d-flex flex-stack fw-bold text-gray-600 text-uppercase fs-7"><span class="menu-heading ps-1">'.lang($menu->divider).'</span></div></div>';
            }
        }else{
            if($menu->permission_controller == "" || count(getArrayItem($permission,'name',$menu->permission_controller)) > 0)
            {
                $submenu = '';
                foreach ($menu->children as $children) {
                    if($children->external){
                        $submenu .= '<div class="menu-item"><a class="menu-link" target="_blank" href="'.$children->url.'"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">'.lang($children->title).'</span></a></div>';
                    }else{
                        $submenu .=  '<div class="menu-item"><a class="menu-link" href="'.site_url($children->url).'"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">'.lang($children->title).'</span></a></div>';
                    }
                }
                $submenu = count($menu->children) > 0 ? '<div class="menu-sub menu-sub-accordion">'.$submenu.'</div>' : '';
                if(empty($submenu)){
                    if($menu->external){
                        echo '<div class="menu-item"><a class="menu-link " target="_blank" href="'.$menu->url.'"><span class="menu-icon"><i class="'.$menu->icon.'"></i></span><span class="menu-title">'.lang($menu->title).'</span></a></div>';
                    }else{
                        if($menu->url == "settings/module"){
                            if($module_menu){
                                echo '<div class="menu-item"><a class="menu-link " href="'.site_url($menu->url).'"><span class="menu-icon"><i class="'.$menu->icon.'"></i></span><span class="menu-title">'.lang($menu->title).'</span></a></div>';
                            }
                        }else{
                            echo '<div class="menu-item"><a class="menu-link " href="'.site_url($menu->url).'"><span class="menu-icon"><i class="'.$menu->icon.'"></i></span><span class="menu-title">'.lang($menu->title).'</span></a></div>';
                        }
                    }
                }else{
                    echo '<div data-kt-menu-trigger="click" class="menu-item"><span class="menu-link"><span class="menu-icon"><i class="'.$menu->icon.'"></i></span><span class="menu-title">'.lang($menu->title).'</span><span class="menu-arrow"></span></span>'.$submenu.'</div>';
                }
            }
        }
    }
}
?>
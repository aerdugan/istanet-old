<?php

if (!function_exists('getTrPages')) {
    function getTrPages($lang = 'tr') {
        // Get the database connection
        $db = \Config\Database::connect();

        // Ensure the table name is set
        $builder = $db->table('pages'); // Specify the table 'pages'

        // Query the 'pages' table where 'data_lang' is the specified language
        $query = $builder->where('data_lang', $lang)
            ->orderBy('rank', 'ASC')  // Optional: Order by 'rank'
            ->get();

        // Return the result as an array of objects
        return $query->getResult();
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
if (!function_exists('setContentBox')) {
    function setContentBox(){
        echo "https://cdn.istanet.com/cBox/cBox5830/";
    }
}

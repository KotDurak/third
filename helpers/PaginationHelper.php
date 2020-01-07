<?php

namespace app\helpers;


use yii\helpers\Url;

class PaginationHelper
{
    public static function getPageCounts($currentPage,  $counts = [10, 20, 30, 50, 100, 200, 500])
    {
        $html = '';

        foreach ($counts as $count) {
            $selected = $currentPage == $count ? 'selected="selected"' : '';
            $html .= '<option value="'. Url::current(['per-page' => $count, 'page' => null]).'" '.$selected.'>';
            $html .= $count == -1 ? 'Все' : $count;
            $html .= '</option>';
        }

        return $html;
    }
}
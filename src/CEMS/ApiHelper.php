<?php
/**
 * Created by PhpStorm.
 * User: pnghai
 * Date: 7/12/14
 * Time: 1:51 PM
 */

namespace CEMS;

/**
 * Class ApiHelper contains some helpers for manipulation string for compatible with CEMS API callback convention
 * @package CEMS
 */
class ApiHelper
{

    /**
     * Get sub string between another sub strings in a parent string
     *
     * @param string $content
     * @param string $start
     * @param string $end
     *
     * @return string
     */
    static function getBetween($content = '', $start = '', $end = '')
    {
        $r = explode($start, $content);
        if (isset($r[1])) {
            $r = explode($end, $r[1]);

            return $r[0];
        }

        return '';
    }
} 
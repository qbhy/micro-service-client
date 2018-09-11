<?php
/**
 * User: qbhy
 * Date: 2018/9/11
 * Time: 上午10:58
 */

if (!function_exists('unserializeUid')) {
    function unserializeUid($serialized): int
    {
        $decode = base64_decode($serialized);
        list($aid, $origin) = explode('-', $decode);
        $final = (int)substr($origin, 3); // 这个就是解出的 ID
        return $final;
    }
}

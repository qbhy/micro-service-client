<?php
/**
 * User: qbhy
 * Date: 2018/7/25
 * Time: 下午3:51
 */

namespace Qbhy\MicroServiceClient\UserCenter\Auth;


interface UserCenterSubject
{
    public static function findFromSocialite(array $socialite);

    public function getGuid();

    public function getIuid();

    public function getPartIndex();

    public function getRealName();

    public function isCheckName();

}
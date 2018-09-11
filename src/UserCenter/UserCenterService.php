<?php
/**
 * User: qbhy
 * Date: 2018/7/24
 * Time: 上午11:38
 */

namespace Qbhy\MicroServiceClient\UserCenter;

use Qbhy\MicroServiceClient\Service;

class UserCenterService extends Service
{
    public function internalUser($id): array
    {
        return $this->request('GET', "/app-user/{$id}");
    }

    public function getServiceName(): string
    {
        return env('USER_CENTER_PREFIX', 'ucenter-internal');
    }


}
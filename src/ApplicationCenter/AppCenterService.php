<?php
/**
 * User: qbhy
 * Date: 2018/8/24
 * Time: 下午2:32
 */

namespace Qbhy\MicroServiceClient\ApplicationCenter;

use Qbhy\MicroServiceClient\Service;

class AppCenterService extends Service
{
    public function fullInformation()
    {
        return $this->request('GET', '/full-information');
    }

    public function getServiceName(): string
    {
        return env('APP_CENTER_PREFIX', 'application');
    }

}
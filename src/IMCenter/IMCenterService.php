<?php
/**
 * User: qbhy
 * Date: 2018/7/24
 * Time: 上午11:38
 */

namespace Qbhy\MicroServiceClient\TradeCenter;

use Qbhy\MicroServiceClient\Service;

class IMCenterService extends Service
{
    public function getServiceName(): string
    {
        return config('micro-service-client.im_center_prefix');
    }


}
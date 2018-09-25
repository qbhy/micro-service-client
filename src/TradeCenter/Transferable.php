<?php
/**
 * User: qbhy
 * Date: 2018/9/25
 * Time: 下午5:36
 */

namespace Qbhy\MicroServiceClient\TradeCenter;

trait Transferable
{
    public function getRealName()
    {
        return $this->real_name;
    }

    public function isCheckName()
    {
        return (bool)$this->real_name;
    }
}
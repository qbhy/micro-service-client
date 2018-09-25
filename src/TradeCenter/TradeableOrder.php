<?php
/**
 * Created by PhpStorm.
 * User: xiejianlai
 * Date: 2018/9/11
 * Time: 上午11:45
 */

namespace Qbhy\MicroServiceClient\TradeCenter;


interface TradeableOrder
{
    public function getChannel(int $channel);

    public function getPaymentFee();

    public function getSubject();

    public function getBody();

    public function getOutTradeNo();

    public function getClientIp();

    public function savePaymentInfo(array $paymentInfo);

    public function saveTransferInfo(array $transferInfo);
}
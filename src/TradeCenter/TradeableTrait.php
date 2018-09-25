<?php
/**
 * User: qbhy
 * Date: 2018/9/11
 * Time: 上午11:56
 */

namespace Qbhy\MicroServiceClient\TradeCenter;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait TradeableTrait
 *
 * @property int    $amount_payable          应付金额
 * @property string $body                    订单body
 * @property string $subject                 订单subject
 * @property string $trade_id                交易编号
 * @property string $client_ip               用户IP
 * @property string $wechat_prepay_id        微信预支付ID
 * @property string $notify_num              剩余几次模板消息机会
 * @property string $trade_center_trade_id   交易中心的编号
 * @property string $trade_center_payment_id 交易中心支付单号
 *
 * @package Qbhy\MicroServiceClient\TradeCenter
 * @mixin Model
 */
trait TradeableTrait
{
    public function getChannel(int $channel)
    {
        return static::CHANNELS[$channel];
    }

    public function getPaymentFee()
    {
        return $this->amount_payable;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getOutTradeNo()
    {
        return $this->trade_id;
    }

    public function getClientIp()
    {
        return $this->client_ip;
    }

    public function savePaymentInfo(array $paymentInfo)
    {
        if (!empty($paymentInfo['wx_config'])) {
            $this->wechat_prepay_id = explode('=', $paymentInfo['wx_config']['package'])[1];
            $this->notify_num       = 3;
        }

        $this->trade_center_trade_id   = $paymentInfo['code'];
        $this->trade_center_payment_id = $paymentInfo['payment_id'];
        $this->save();
    }

    public function saveTransferInfo(array $paymentInfo)
    {
        $this->trade_center_trade_id   = $paymentInfo['code'];
        $this->trade_center_payment_id = $paymentInfo['transfer_id'];
        $this->save();
    }


}
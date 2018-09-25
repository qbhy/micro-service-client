<?php
/**
 * User: qbhy
 * Date: 2018/7/24
 * Time: 上午11:38
 */

namespace Qbhy\MicroServiceClient\TradeCenter;

use GuzzleHttp\RequestOptions;
use Qbhy\MicroServiceClient\Service;
use Qbhy\MicroServiceClient\UserCenter\Auth\UserCenterSubject;

class TradeCenterService extends Service
{

    /**
     * @param TradeableOrder    $order
     * @param UserCenterSubject $user
     *
     * @return array
     */
    public function pay(TradeableOrder $order, UserCenterSubject $user, int $channel): array
    {
        $params   = [
            'channel'      => $order->getChannel($channel),
            'payment_fee'  => $order->getPaymentFee(),
            'subject'      => $order->getSubject(),
            'body'         => $order->getBody(),
            'out_trade_no' => $order->getOutTradeNo(),
            'client_ip'    => $order->getClientIp(),
            'guid'         => $user->getGuid(),
            'iuid'         => $user->getIuid(),
            'part_index'   => $user->getPartIndex(),
            //                'extends'      => ''
        ];
        $response = $this->request('POST', '/payment', [
            RequestOptions::JSON => $params
        ]);

        $order->savePaymentInfo($response);

        return $response['wx_config'];
    }

    /**
     * 转账
     *
     * @param TradeableOrder $order
     *
     * @return array
     * @throws TransferException
     */
    public function transfer(TradeableOrder $order, UserCenterSubject $user): array
    {
        try {
            $transferInfo = $this->request('/wechat-transfer', [
                'amount'       => $order->getPaymentFee(),
                'description'  => $order->getBody(),
                'out_trade_no' => $order->getOutTradeNo(),
                'client_ip'    => $order->getClientIp(),
                'guid'         => $user->getGuid(),
                'iuid'         => $user->getIuid(),
                'part_index'   => $user->getPartIndex(),
                'check_name'   => $user->isCheckName(),
                'real_name'    => $user->getRealName(),
            ]);

            $order->saveTransferInfo($transferInfo);

            return $transferInfo;
        } catch (\Exception $exception) {
            throw new TransferException($exception->getMessage());
        }
    }

    /**
     * 转账
     *
     * @param TradeableOrder $order
     *
     * @return string
     */
    public function refund(TradeableOrder $order): string
    {
        // 接入交易中心获取支付数据

        return 'refunding bank';
    }

    public function getServiceName(): string
    {
        return config('micro-service-client.trade_center_prefix');
    }


}
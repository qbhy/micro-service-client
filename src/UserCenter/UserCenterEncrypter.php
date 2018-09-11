<?php
/**
 * User: qbhy
 * Date: 2018/7/24
 * Time: 上午11:40
 */

namespace Qbhy\MicroServiceClient\UserCenter;


use Qbhy\SimpleJwt\AbstractEncrypter;
use Qbhy\SimpleJwt\Encoders\Base64UrlSafeEncoder;
use Qbhy\SimpleJwt\Interfaces\Encoder;

class UserCenterEncrypter extends AbstractEncrypter
{
    protected $encoder;

    public function __construct(string $secret, Encoder $encoder = null)
    {
        parent::__construct($secret);
        $this->encoder = $encoder ?? new Base64UrlSafeEncoder();
    }

    public function signature(string $signatureString): string
    {
        return $this->encoder->encode(hash_hmac('SHA256', $signatureString, $this->secret, true));
    }

    public static function alg(): string
    {
        return 'HS256';
    }

    public function check(string $signatureString, string $signature): bool
    {
        return $this->signature($signatureString) === $this->encoder->encode($signature);
    }


}
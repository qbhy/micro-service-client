<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qbhy\MicroServiceClient\UserCenter\JwtParser;

use Illuminate\Http\Request;
use Qbhy\MicroServiceClient\UserCenter\Parser;

class InputSource implements Parser
{
    use KeyTrait;

    /**
     * Try to parse the token from the request input source.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return null|string
     */
    public function parse(Request $request)
    {
        return $request->input($this->key);
    }
}

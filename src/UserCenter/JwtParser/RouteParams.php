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

class RouteParams implements \Qbhy\MicroServiceClient\UserCenter\Parser
{
    use KeyTrait;

    /**
     * Try to get the token from the route parameters.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return null|string
     */
    public function parse(Request $request)
    {
        $route = $request->route();

        // Route may not be an instance of Illuminate\Routing\Route
        // (it's an array in Lumen <5.2) or not exist at all
        // (if the request was never dispatched)
        if (is_callable([$route, 'parameter'])) {
            return $route->parameter($this->key);
        }
    }
}

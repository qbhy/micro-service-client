<?php
/**
 * User: qbhy
 * Date: 2018/9/28
 * Time: 下午4:43
 */

namespace Qbhy\MicroServiceClient;

use Illuminate\Support\Collection;

class Config extends Collection
{
    /**
     * @param null|string $name
     *
     * @return array
     * @throws ApplicationException
     */
    public function getAppConfig($name = null)
    {
        $applications = $this->get('applications');

        $name = $name ?? $this->get('default');

        if (isset($applications[$name])) {
            return $applications[$name];
        }
        throw new ApplicationException("{$name} application is not defined!");
    }
}
<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        return ip2long($Call['Value']);
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        if (is_int($Call['Value']))
            return long2ip($Call['Value']);
        else
            return null;
    });

    setFn('Populate', function ($Call)
    {
        return long2ip(rand());
    });
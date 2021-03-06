<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: 
     * @package Codeine
     * @version 8.x
     */

    setFn('Read', function ($Call)
    {
        $Result = jd($Call['Value'], true);
        if (json_last_error() > 0)
        {
            F::Log('JSON: '.json_last_error_msg(), LOG_ERR);
            F::Log($Call['Value'], LOG_ERR);
        }

        return $Result;
    });

    setFn('Write', function ($Call)
    {
        return j($Call['Value'],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    });

    setFn('Write.Call', function ($Call)
    {
        $Call['Value'] = j($Call['Value']);
        return $Call;
    });

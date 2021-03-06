<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: 
     * @package Codeine
     * @version 8.x
     * @date 13.08.11
     * @time 22:37
     */

    setFn ('Open', function ($Call)
    {
        if (F::file_exists($Call['Directory'].'/'.$Call['Filename']))
            $Call['Link'] = (array) jd(file_get_contents($Call['Directory'].'/'.$Call['Filename']), true);
        else
            $Call['Link'] = [];

        return $Call['Link'];
    });

    setFn ('Read', function ($Call)
    {
        $Result = array_shift($Call['Link']);

        file_put_contents($Call['Directory'].'/'.$Call['Filename'],
            j($Call['Link'], JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return $Result;
    });

    setFn ('Write', function ($Call)
    {
        array_push($Call['Link'], $Call['Data']);

        return file_put_contents($Call['Directory'].'/'.$Call['Filename'],
            j($Call['Link'], JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    });

    setFn ('Count', function ($Call)
    {
        return count($Call['Link']);
    });

    setFn ('Close', function ($Call)
    {
        return true;
    });
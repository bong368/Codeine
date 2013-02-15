<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    setFn('Sort', function ($Call)
    {
        if (isset($Call['List']['Sort']))
            $Call['Sort'] = $Call['List']['Sort'];

        if (isset($Call['Request']['sort']))
        {
            $Call['Sort'] = [$Call['Request']['sort'] => true];
            $Call['PageURLPostfix'] = '?sort='.$Call['Request']['sort'];
        }
        else
        {
            if( isset($Call['Request']['rsort']))
            {
                $Call['Sort'] = [$Call['Request']['rsort'] => false];
                $Call['PageURLPostfix'] = '?rsort='.$Call['Request']['rsort'];
            }
        }

        return $Call;
    });
<?php

    /* Codeine
     * @author BreathLess
     * @description Транслитерация по правилам загранпаспортов 
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        return strtr($Call['Value'], $Call['Map'][$Call['From']][$Call['To']]);
    });
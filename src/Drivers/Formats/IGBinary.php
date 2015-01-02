<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 8.x
     */

    setFn('Read', function ($Call)
    {
        return igbinary_unserialize($Call['Value']);
    });

    setFn('Write', function ($Call)
    {
        return igbinary_serialize($Call['Value']);
    });
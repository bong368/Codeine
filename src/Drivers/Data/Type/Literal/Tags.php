<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Write', function ($Call)
    {
        return explode(',', $Call['Value']);
    });

    self::setFn('Read', function ($Call)
    {
        return $Call['Value'];
    });
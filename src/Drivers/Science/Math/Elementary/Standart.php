<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Log', function ($Call)
    {
        return log($Call['X'], F::Live($Call['Base']));
    });
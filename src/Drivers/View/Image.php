<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Process', function ($Call)
    {
        $Call['HTTP']['Headers']['Content-type:'] = 'image/png'; // FIXME
        $Call['Output'] = $Call['Image'];

        return $Call;
    });
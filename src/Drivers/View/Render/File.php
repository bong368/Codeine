<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Process', function ($Call)
    {
       $Call['Headers']['Content-type'] = 'text/plain';
       $Call['Output'] = $Call['Value'];

       return $Call;
    });
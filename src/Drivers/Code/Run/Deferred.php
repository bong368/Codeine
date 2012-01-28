<?php

    /* Codeine
     * @author BreathLess
     * @description Deferred Run 
     * @package Codeine
     * @version 7.0
     */

     self::setFn('Do', function ($Call)
     {
         $CallID = F::hashCall($Call['Value']);

         return F::Run(
             array(
                 'Send' => 'Deferred',
                 'Scope' => 'Deferred',
                 'Message' =>
                     array(
                         'ID' => $CallID,
                         'Time' => microtime(true),
                         'Call' => $Call['Value']
                     )
             )
         );

     });
<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Do', function ($Call)
    {
        return F::Run(array(
                    '_N'  => 'Engine.Message',
                    '_F'  => 'Send',
                    'To' => 'Event',
                    'Message' => 'Call to unrealized function',
                    'Call' => $Call
                       ));
    });

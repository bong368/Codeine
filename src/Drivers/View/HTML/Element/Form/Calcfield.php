<?php

    /* Codeine
     * @author BreathLess
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 7.x
     */

     setFn('Make', function ($Call)
     {
         return F::Run ('View', 'Load',
                           array(
                                'Scope' => 'Default',
                                'ID'    => 'UI/Form/Calcfield',
                                'Data'  => $Call
                           ));
     });
<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Do', function ($Call)
    {
        $Widgets = array(
            array(
                'Place' => 'Content',
                'Type' => 'Block',
                'Class' => array('Documentation_Description_Block'),
                'Value' => $Call['Description'][$Call['Locale']]
            )
        );

        return $Widgets;
    });
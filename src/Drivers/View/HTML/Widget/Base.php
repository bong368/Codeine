<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */


    setFn('Make', function ($Call)
    {
        if (isset($Call['Attributes']['String']))
            foreach ($Call['Attributes']['String'] as $Attribute => $Value)
            {
                 if (isset($Call[$Attribute]) && $Call[$Attribute])
                     $Attributes[] = strtolower($Attribute).'="'.$Call[$Attribute].'"';
                 else
                     if (!empty($Value))
                         $Attributes[] = strtolower($Attribute).'="'.$Value.'"';
            }

        if (isset($Call['Attributes']['Boolean']))
            foreach ($Call['Attributes']['Boolean'] as $Attribute => $Value)
            {
                 if (isset($Call[$Attribute]) && $Call[$Attribute])
                     $Attributes[] = strtolower($Attribute);
                 else
                     if (!empty($Value) && $Value)
                         $Attributes[] = strtolower($Attribute);
            }
         
        if (isset($Call['Block']) && $Call['Block'])
            $Call['HTML'] = '<'.$Call['Tag'].' '.implode(' ', $Attributes).'>'.$Call['Value'].'</'.$Call['Tag'].'>';
        else
            $Call['HTML'] = '<'.$Call['Tag'].' '.implode(' ', $Attributes).' />';

        return $Call;
    });
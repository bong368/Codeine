<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        $Data = [];

        if (count($Call['Value']) > 0)
            foreach ($Call['Value'] as $K => $V)
                if ($K > 0)
                    $Data[] = [$Call['Name'] => $V];

        $Call['Data'] = json_encode($Data, JSON_UNESCAPED_UNICODE);

        return F::Run('View', 'Load', array('Scope' => 'Default', 'ID' => 'UI/Form/Multifield', 'Data' => $Call));
     });
<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call);

        if (isset($Call['Where']))
            $Call['Where'] = F::Live($Call['Where'], $Call);

        $Call = F::Hook('beforeCount', $Call);

            $Call['Data'] = F::Run('Entity', 'Count', $Call);

        $Call = F::Hook('afterCount', $Call);

        return $Call['Data'];
    });
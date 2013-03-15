<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
       if (isset($Call['Nodes']))
            foreach ($Call['Nodes'] as $Name => $Node)
            {
                if (isset($Node['Hooks']))
                    if (isset($Node['Hooks'][$Call['On']]))
                    {
                        // Multiread
                        if (isset($Call['Purpose']) && ($Call['Purpose'] == 'Read'))
                        {
                            if (isset($Call['Data']))
                                foreach ($Call['Data'] as &$Data)
                                    $Data[$Name] = F::Live($Node['Hooks'][$Call['On']],
                                        [
                                            'Entity' => $Call['Entity'],
                                            'Nodes' => $Call['Nodes'], // FIXME Bullshit.
                                            'Data' => $Data
                                        ]);
                        }
                        else
                        {
                            if (isset($Node['User Override']) and $Node['User Override'] and !empty($Call['Data'][$Name]))
                                F::Log('Node '.$Name.' overriden by user.', LOG_INFO);
                            else
                            {
                                $Call['Data'][$Name] = F::Live($Node['Hooks'][$Call['On']], $Call);
                                F::Log('Node '.$Name.' executed.', LOG_INFO);
                            }
                        }
                    }
            }

        return $Call;
    });
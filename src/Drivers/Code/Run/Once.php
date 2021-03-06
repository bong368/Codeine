<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        $Result = F::Run('IO', 'Read', array
                             (
                                'Storage' => 'Run Once',
                                'Where' => $Call['ID']
                             ));


        if (null !== $Result)
        {
            foreach ($Result[0]['Code'] as $Code)
                $Call = F::Live($Code, $Call);

            F::Run('IO', 'Write', array
                             (
                                'Storage' => 'Run Once',
                                'Where' => $Call['ID'],
                                'Data' => null
                             ));
        }

        return $Call;
    });

    setFn('Prepare', function ($Call)
    {
        $UID = F::Run('Security.UID', 'Get', ['Mode' => 'Secure+']);

        F::Run('IO', 'Write', array
                             (
                                'Storage' => 'Run Once',
                                'Data' =>
                                    [
                                        'ID' => $UID,
                                        'Code' => $Call['Run']
                                    ]
                             ));

        return $UID;
    });
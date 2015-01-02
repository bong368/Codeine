<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        $VKTS = microtime(true);

        $LastVKTS = F::Get('Last VK TS');
        F::Set('Last VK TS', $VKTS);

        if (($LastVKTS === null) or ($VKTS - $LastVKTS > (1/$Call['VKontakte']['Max Frequency'])))
            ;
        else
            usleep($VKTS - $LastVKTS);

        $Result = null;

        $Call = F::Hook('beforeVKontakteRun', $Call);

        $Call['Call']['access_token'] = F::Run(null, 'Access Token', $Call);
        $Call['Call']['param_v'] = $Call['VKontakte']['Version'];

        $Query = '?'.http_build_query($Call['Call']);

        $Result = F::Run('IO', 'Read',
               [
                   'Storage' => 'Web',
                   'IO TTL'  => 86400,
                   'Format'  => 'Formats.JSON',
                   'Where'   => $Call['VKontakte']['Entry Point'].'/'.$Call['Service'].'.'.$Call['Method'].$Query
               ]);

        $Result = array_pop($Result);

        if (isset($Result['response']))
        {
            if (isset($Call['Return Key']) && (F::Dot($Result['response'], $Call['Return Key']) !== null))
                $Result = F::Dot($Result['response'], $Call['Return Key']);
            else
                $Result = $Result['response'];
        }
        else
            F::Log($Result['error'], LOG_ERR);

        F::Hook('afterVKontakteRun', $Call);

        return $Result;
    });

    setFn('Access Token', function ($Call)
    {
        if (isset($Call['Session']['User']['VKontakte']['Auth']) && !empty($Call['Session']['User']['VKontakte']['Auth']))
        {
            F::Log('Used current user VK.Auth', LOG_INFO);
            $Token = $Call['Session']['User']['VKontakte']['Auth'];
        }
        else
        {
            F::Log('Used another user VK.Auth', LOG_INFO);
            $Token =
                F::Run ('Entity', 'Read',
                    [
                        'Entity' => 'User',
                        'Where'  =>
                            [
                                'VKontakte.Active' => true
                            ],
                        'Sort' =>
                            [
                                'Modified' => true
                            ],
                        'One' => true
                    ])['VKontakte']['Auth'];
        }

        return $Token;
    });

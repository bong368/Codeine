<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    setFn('Run', function ($Call)
    {
        if (isset($Call['Public']))
            F::Log('Facebook Public Method '.$Call['Method'].' called ', LOG_INFO);
        else
        {
            if (isset($Call['Call']['access_token']))
                F::Log('Facebook Private Method '.$Call['Method'].' called with implicit token '.$Call['Call']['access_token'], LOG_INFO);
            else
            {
                $Call['Call']['access_token'] = F::Run(null, 'Access Token', $Call);
                F::Log('Facebook Private Method '.$Call['Method'].' called with automatic token '.$Call['Call']['access_token'], LOG_INFO);
            }
        }

        if (!isset($Call['Call']['locale']))
            $Call['Call']['locale'] = $Call['Facebook']['Default Locale'];

        if (isset($Call['Call']))
            $Query = '?'.http_build_query($Call['Call']);
        else
            $Query = '';

        $URL = $Call['Facebook']['Entry Point'].$Call['Method'].$Query;

        $Result = F::Run($Call['Backend']['Service'], $Call['Backend']['Method'], 
                         $Call['Backend']['Options'],
               [
                   'Where'      => $URL
               ]);


        $Result = array_pop($Result);

        if (isset($Call['Return Key']))
        {
            if (F::Dot($Result, $Call['Return Key']))
                $Result = F::Dot($Result, $Call['Return Key']);
            else
                $Result = null;
        }

        return $Result;
    });

    setFn('Access Token', function ($Call)
    {
        $Result = null;

        if (isset($Call['Data']['Facebook']['Auth']))
        {
            F::Log('Using FB Token from Data', LOG_INFO);
            $Result['Auth'] = $Call['Data']['Facebook']['Auth'];
        }
        elseif (isset($Call['Session']['User']['Facebook']['Auth']))
        {
            F::Log('Using FB Token from Session', LOG_INFO);
            $Result['Auth'] = $Call['Session']['User']['Facebook']['Auth'];
        }
        else
        {
            F::Log('Using FB Token from random users', LOG_INFO);

            $Donor =
                F::Run ('Entity', 'Read',
                    [
                        'Entity' => 'User',
                        'Where'  =>
                            [
                                'Facebook.Active' => true
                            ],
                        'Limit' =>
                        [
                            'From' => 0,
                            'To'   => 1
                        ],
                        'Sort' => ['Modified' => false],
                        'One' => true
                    ]);

            $Result = $Donor['Facebook'];

            if (isset($Result['Expire']) && $Result['Expire'] > time())
                ;
            elseif (isset($Result['Auth']))
            {
                $URL = 'https://graph.facebook.com/oauth/access_token';

                $ResultFB = F::Run('IO', 'Read',
                     [
                         'Storage'  => 'Web',
                         'Where'    => $URL,
                         'Data'     =>
                         [
                             'client_id' => $Call['Facebook']['AppID'],
                             'client_secret' => $Call['Facebook']['Secret'],
                             'grant_type' => 'fb_exchange_token',
                             'fb_exchange_token' => $Result['Auth']
                         ]
                     ]);

                if ((array) $ResultFB === $ResultFB)
                {
                    $ResultFB = array_pop($ResultFB);
                    parse_str($ResultFB, $ResultFB);
                }

                if (isset($ResultFB['access_token']))
                {
                    F::Run ('Entity', 'Update',
                    [
                        'Entity' => 'User',
                        'Where'  =>
                        [
                            'Facebook.ID' =>$Result['ID']
                        ],
                        'Data' =>
                        [
                            'Facebook' =>
                            [
                                'Auth' => $ResultFB['access_token'],
                                'Expire' => time()+$ResultFB['expires']
                            ]
                        ],
                        'No'  => ['beforeEntityWrite' => true],
                        'One' => true
                    ]);

                    F::Run ('Code.Run.Delayed', 'Run',
                        [
                            'Delayed Mode' => 'Dirty',
                            'Run' =>
                            [
                                'Service'=> 'Entity',
                                'Method'=> 'Update',
                                'Call'=>
                                [
                                    'Entity'=> 'User',
                                    'Where' => ['Facebook.ID' => $Result['ID']],
                                    'Data' =>  [
                                                'Facebook' =>
                                                [
                                                    'Auth' => $ResultFB['access_token'],
                                                    'Expire' => time()+$ResultFB['expires']
                                                ]
                                            ],
                                    'No'  => ['beforeEntityWrite' => true]
                                ]
                            ]
                        ]);

                    $Result['Auth'] = $ResultFB['access_token'];
                    $Result['Expire'] = $ResultFB['expires'];
                }
                else
                {
                    F::Run('Entity', 'Update',
                        [
                            'Entity' => 'User',
                            'Where' => $Donor['ID'],
                            'Skip Live' => true,
                            'Data' =>
                            [
                                'Facebook' =>
                                [
                                    'Auth' => null,
                                    'Active' => false
                                ]
                            ],
                            'No'  => ['beforeEntityWrite' => true]
                        ]);
                }
            }
            else
            {
                $Result['Auth'] = '';
            }
        }

        return isset($Result['Auth'])? $Result['Auth']: '';
    });

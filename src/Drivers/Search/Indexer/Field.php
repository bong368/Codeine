<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Get', function ($Call)
    {
        $Index = [];

        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Index']) && $Node['Index'])
            {
                if (preg_match_all('/([a-zA-Zа-яА-Я]+)/Ssu', $Call['Data'][$Name], $Pockets))
                {
                    foreach($Pockets[1] as $Pocket)
                        $Index[] = mb_strtolower(strip_tags($Pocket));
                }
            }
        }

        return array_unique($Index); // TODO Relevancy
    });
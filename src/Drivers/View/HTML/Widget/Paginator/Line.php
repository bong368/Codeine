<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        $Call['Value'] = '';
        if ($Call['Page']> 1)
            $Call['Value'].= F::Run('View', 'Load',
                array('Scope' => 'Default',
                      'ID' => 'UI/Paginator/Prev',
                      'Data' =>
                        array(
                            'Num' => $Call['Page']-1,
                            'URL' => $Call['PageURL'],
                            'PageURLPostfix' => $Call['PageURLPostfix'],
                        )));

        for ($ic = 1; $ic <= $Call['PageCount']; $ic++)
            $Call['Value'].= F::Run('View', 'Load',
                array('Scope' => 'Default',
                      'ID' => ($ic == $Call['Page']? 'UI/Paginator/Current': 'UI/Paginator/Page'),
                      'Data' =>
                        array(
                            'Num' => $ic,
                            'URL' => $Call['PageURL'],
                            'PageURLPostfix' => $Call['PageURLPostfix'])));

        if ($Call['Page']< $Call['PageCount'])
            $Call['Value'] .= F::Run('View', 'Load',
                array('Scope' => 'Default',
                      'ID' => 'UI/Paginator/Next',
                      'Data' =>
                        array(
                            'Num' => $Call['Page']+1,
                            'URL' => $Call['PageURL'],
                            'PageURLPostfix' => $Call['PageURLPostfix'])));

        return $Call;
     });
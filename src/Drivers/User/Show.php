<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    setFn('Do', function ($Call)
    {
        $Element = F::Run('Entity', 'Read', ['Entity' => 'User', 'Where' => $Call['ID']]);

        if (empty($Element))
            $Call = F::Hook('NotFound', $Call);
        else
        {
            $Call['Output']['Content'][] = array (
                'Type'  => 'Template',
                'Scope' => 'User',
                'Value' => 'Show.Full',
                'Data'  => $Element[0]
            );

            $Call['Title']       = $Element[0]['Name'].' '.$Element[0]['Surname'];
            $Call['Description'] = $Element[0]['Name'] . ' ' . $Element[0]['Surname'];
            $Call['Keywords']    = preg_split('/\s/', $Element[0]['Name']);

        }

        return $Call;
    });

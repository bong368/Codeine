<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        $Call['Checks'] = '';

        $Call['Name'] .= '[]';
        
        foreach ($Call['Options'] as $Call['Key'] => $Value)
        {
            if (isset($Call['Localized']) && $Call['Localized'])
                $lValue = '<l>'.$Call['Entity'].'.Entity:'.$Call['Key'].'.'.$Value.'</l>';
            else
                $lValue = $Value;

            $Checked = (
                $Call['Key'] == $Call['Value']
                ||
                $Value == $Call['Value']
                || (is_array($Call['Value'])
                && in_array($Call['Key'], $Call['Value'])));

            $Call['Checks'] .= F::Run('View', 'Load',
                [
                    'Scope' => $Call['Widget Set'].'/Widgets',
                    'ID' => 'Form/Checkbox',
                    'Data' =>
                    F::Merge ($Call,
                        [
                            'Value' => $lValue,
                            'Checked' => $Checked? 'checked': ''
                        ])
                ]
            );
        }

        return $Call;
     });
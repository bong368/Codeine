<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Before', function ($Call)
    {
        $Call['Current'] = F::Run('Entity', 'Read', $Call);
        return $Call;
    });

    setFn('Do', function ($Call)
    {
        return F::Hook('afterUpdateDo',
                    F::Run(null, $Call['HTTP']['Method'],
                        F::Hook('beforeUpdateDo', $Call)));
    });

    setFn('GET', function ($Call)
    {
        // Загрузить предопределённые данные и умолчания
        $Call['Data'] = $Call['Current'][0];

        $Call = F::Hook('beforeUpdateGet', $Call);

        $Call['Output']['Content']['Form Widget'] = ['Type' => 'Form', 'Submit' => 'Update'];

        $Call['Tag'] = isset($Call['Scope'])? $Call['Scope']: null;

        $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope'] : $Call['Entity'];

        $Call['Layouts'][] =
            [
                'Scope' => $Call['Entity'],
                'ID' => isset($Call['Custom Layouts']['Update'])?
                        $Call['Custom Layouts']['Update']: 'Update',
                'Context' => $Call['Context']
            ];

        foreach ($Call['Current'] as $IX => $cData)
            $Call = F::Apply('Entity.Form', 'Generate', $Call, ['IX' => $IX, 'Data!' => $cData]);

        // Вывести

        $Call['Output']['Content']['Form Widget']['Action'] = isset($Call['Action'])? $Call['Action']: '';

      //  $Call = F::Hook('afterUpdateGet', $Call);

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call = F::Hook('beforeUpdatePost', $Call);

            if (isset($Call['Request']['Data']))
                $Call['Data'] = $Call['Request']['Data'];

            // Отправляем в Entity.Update
            $Result = F::Run('Entity', 'Update', $Call);

            // Выводим результат
            if (!isset($Result['Errors']) or empty($Result['Errors']))
            {
                $Call['Current'] = $Result;
                $Call = F::Hook('afterUpdatePost', $Call);
            }
            else
            {
                foreach ($Result['Errors'] as $Name =>$Node)
                    foreach ($Node as $Error)
                        $Call['Output']['Message'][] =
                            [
                                'Type' => 'Block',
                                'Class' => 'alert alert-danger',
                                'Value' => '<l>'.$Call['Entity'].'.Error:'.$Name.'.'.$Error.'</l>'
                            ];
            }

            $Call = F::Apply(null, 'GET', $Call);

        return $Call;
    });
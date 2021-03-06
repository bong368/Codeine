<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Merge(F::loadOptions('Entity.'.$Call['Entity']), $Call); // FIXME

        $Call['Layouts'][] = ['Scope' => $Call['Entity'], 'ID' => 'Catalog', 'Context' => $Call['Context']];
        $Call['Fields'] = [$Call['Key']];

        $Call = F::Hook('beforeCatalog', $Call);

            if (isset($Call['No Catalog Entity']))
            {
                $Elements = F::Run('Entity', 'Read', $Call,
                    [
                        'Entity'    => $Call['Entity'],
                        'Fields'    => [$Call['Key']],
                        'Distinct'  => true
                    ]);

                $Elements = F::Extract($Elements, $Call['Key']);
            }
            else
            {
                $Elements = F::Run('Entity', 'Read',
                                   [
                                       'Entity' => $Call['Key'],
                                       'Where'  => []
                                   ]);

                $Elements = F::Extract($Elements, 'ID');
            }

            $Values = [];

            if (count($Elements) > 0)
            {
                foreach ($Elements as $Element)
                {
                    $Value = F::Run('Entity', 'Count',
                    [
                        'Entity' => $Call['Entity'],
                        'Where' =>
                        [
                            $Call['Key'] => $Element
                        ]
                    ]);

                    if ($Value > 0)
                        $Values[$Element] = $Value;
                }

                arsort($Values);

                $Call['Output']['Content'][] =
                    [
                        'Type'    => 'TagCloud',
                        'Value'   => $Values,
                        'Context' => $Call['Context'],
                        'Minimal' => $Call['Minimal'],
                        'Entity'  => $Call['Entity'],
                        'Key'     => $Call['Key']
                    ];
            }

        $Call = F::Hook('afterCatalog', $Call);

        return $Call;
    });
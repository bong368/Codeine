<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Pagination hooks 
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeList', function ($Call)
    {
        if (isset($Call['No Page']) && $Call['No Page'])
            return $Call;

        if (isset($Call['Limit']))
            ;
        else
        {
            if (isset($Call['Count']) && !empty($Call['Count']))
            {
                $Call['Limit']['From']= 0;
                $Call['Limit']['To'] = $Call['Count'];
            }
            else
            {
                if (!isset($Call['Page']) or empty($Call['Page']))
                    $Call['Page'] = 1;
                else
                    if ($Call['Page'] > 100)
                        $Call['Page'] = 100;

                $Call['Count'] = F::Run('Entity', 'Count', $Call);

                if (isset($Call['Sort']))
                {
                    $Call['Limit']['From']= ($Call['Page']-1)*$Call['EPP'];
                    $Call['Limit']['To'] = $Call['EPP'];
                }
                else
                {
/*                    if ((isset($Call['Sequence ID']) and $Call['Sequence ID']) && !isset($Call['Where']))
                    {
                        $Max = F::Run('Entity', 'Count', $Call);
                        $Call['Where']['ID']['$lt'] = $Max - ($Call['Page']-1)*$Call['EPP'] + 1;
                        $Call['Limit']['From']  = 0;
                        $Call['Limit']['To']    = $Call['EPP'];
                        $Call['Sort'] = ['ID' => false];
                    }
                    else*/
                    {
                        $Call['Limit']['From']= ($Call['Page']-1)*$Call['EPP'];
                        $Call['Limit']['To'] = $Call['EPP'];
                    }

                }

                $Call['PageCount'] = ceil($Call['Count']/$Call['EPP']);

                if ($Call['PageCount'] > 100)
                    $Call['PageCount'] = 100;
            }

        }

        return $Call;
    });

    setFn('afterList', function ($Call)
    {
        if (isset($Call['No Page']) && $Call['No Page'])
            return $Call;

        $Call['PageURLPostfix'] = isset($Call['PageURLPostfix'])? $Call['PageURLPostfix']: '';

        $Call['PageURLPostfix'].= isset($Call['HTTP']['URL Query'])? '?'.$Call['HTTP']['URL Query']: '';

        if (!isset($Call['FirstURL']) && isset($Call['HTTP']['URL']))
            $Call['FirstURL'] = preg_replace('@/page(\d+)@', '', $Call['HTTP']['URL']);

        if (isset($Call['PageCount']) && $Call['PageCount']>1)
            $Call['Output']['Pagination'][] =
            [
                'Type'  => 'Paginator',
                'Total' => $Call['Count'],
                'EPP' => $Call['EPP'],
                'Page' => $Call['Page'],
                'FirstURL' => isset($Call['FirstURL'])? $Call['FirstURL']: '',
                'PageURL' => isset($Call['PageURL'])? $Call['PageURL']: '',
                'PageCount' => $Call['PageCount'],
                'PageURLPostfix' => $Call['PageURLPostfix']
           ];

        return $Call;
    });
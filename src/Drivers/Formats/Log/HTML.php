<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        if ($Call['View']['Renderer']['Service'] == 'View.HTML' && empty($Call['Context']))
            ;
        else
            return null;

        if (isset($Call['Channel']))
            ;
        else
            $Call['Channel'] = 'Undefined Channel';

        if (PHP_SAPI == 'cli')
        {
            $UserString = posix_getpwuid(posix_getuid())['name'].' from CLI ';

            if (empty($SSH = shell_exec('echo $SSH_CLIENT')))
                ;
            else
                $UserString.= 'SSH from: '.$SSH;
        }
        else
            $UserString = '*'.$Call['HTTP']['Agent'].'* from *'.$Call['HTTP']['IP'].'*';

        $Header = $Call['Channel'].' Channel ('.count($Call['Value']).')</td></tr><tr><td colspan="3">'.date(DATE_RSS, round(Started)).PHP_EOL.$UserString;

        if (isset($Call['Session']['User']['ID']) && $Call['Session']['User']['ID']>0)
            $Header.= PHP_EOL.'User: '.$Call['Session']['User']['ID'].(isset($Call['Session']['User']['Title'])? '('.$Call['Session']['User']['Title'].')': '');

        if (is_array($Call['Value']))
        {
            $Output = '<table class="console"><tr class="'.$Call['Levels'][11].'"><td colspan="3">'.$Header.'</td></tr>';

            $LastTS = 0;
            foreach ($Call['Value'] as $IX => $Row)
            {
                $Output .= '<tr class="'.$Call['Levels'][$Row[0]].'">
                        <td class="col-md-1">['.$Row[0].'] '.$Row[1].' (+'.round($Row[1]-$LastTS, 3).')</td>
                        <td class="col-md-4">'.($Row[3] == (isset($Call['Value'][$IX-1][3])? $Call['Value'][$IX-1][3]: false)? '': $Row[3]).'</td>
                        <td class="col-md-7">'.stripslashes(htmlentities($Row[2])).'</td>
                        </tr>';
                $LastTS = $Row[1];
            }
            $Output .= '</table>';
        }
        else
            $Output = $Header.PHP_EOL.$Call['Value'];

        $Output = preg_replace('/\*(.*)\*/SsUu', '<strong class="strong">$1</strong>', $Output);

        return $Output;
    });
<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeDo', $Call);

            foreach ($Call['Auth Modes'] as $Mode)
                $Call = F::Apply('Security.Auth.'.$Mode , null, $Call);

        $Call = F::Hook('afterDo', $Call);

        return $Call;
    });

    setFn('Identificate', function ($Call)
    {
        $Call = F::Hook('beforeIdentificate', $Call);

        $Call = F::Apply('Security.Auth.'.$Call['Mode'], null, $Call);

        $Call['Layouts'][] = [
            'Scope' => 'User.Login',
            'ID' => isset($Call['Session']['User']['ID'])? 'Logged': 'Guest'];

        $Call = F::Hook('afterIdentificate', $Call);

        return $Call;
    });

    setFn('Authenticate', function ($Call)
    {
        $Call = F::Hook('beforeAuthenticate', $Call);

        $Call = F::Apply('Security.Auth.'.$Call['Mode'], null, $Call);

        if (!empty($Call['User']))
        {
            if (isset($Call['Request']['Remember']))
                $Call['TTL'] = $Call['TTLs']['Long'];

            $Call = F::Apply('Session', 'Write', $Call, ['Data' => ['User' => $Call['User']['ID']]]);

            if ($Call['Session']['User']['ID'] == $Call['User']['ID'])
            {
                $Call = F::Hook('afterAuthenticate', $Call);
                F::Log('User authorized '.$Call['User']['ID'], LOG_INFO, 'Security');
            }
            else
                F::Log('User is not authorized', LOG_INFO, 'Security');
        }
        else
        {
            $Call = F::Hook('Authenticating.Failed', $Call);
            F::Log('Authentification failed', LOG_INFO, 'Security');
        }

        return $Call;
    });

    setFn('Annulate', function ($Call)
    {
        $Call = F::Hook('beforeAnnulate', $Call);

            $Call = F::Apply('Security.Auth.'.$Call['Mode'], null, $Call);

            $Call['Layouts'][] = [
                'Scope' => 'User.Login',
                'ID' => isset($Call['Session']['User']['ID'])? 'Logged': 'Guest'];

        $Call = F::Hook('afterAnnulate', $Call);

        return $Call;
    });
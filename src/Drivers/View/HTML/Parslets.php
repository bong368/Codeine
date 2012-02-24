<?php

    /* Codeine
     * @author BreathLess
     * @description Apriori Parser 
     * @package Codeine
     * @version 7.2
     */

     self::setFn('Process', function ($Call)
     {
         foreach ($Call['Parslets'] as $Parslet)
         {
             $Tag = strtolower($Parslet);

             while (preg_match_all('@<'.$Tag.'>(.*)<\/'.$Tag.'>@SsUu', $Call[$Call['Var']], $Parsed))
                 $Call[$Call['Var']] = F::Run('View.HTML.Parslets.'.$Parslet, 'Parse', $Call,
                    array(
                        'Parsed' => $Parsed
                    )
                 );
         }

         return $Call;
     });
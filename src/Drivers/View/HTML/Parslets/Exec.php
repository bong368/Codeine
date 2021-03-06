<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet 
     * @package Codeine
     * @version 8.x
     */

     setFn('Parse', function ($Call)
     {
          foreach ($Call['Parsed'][2] as $IX => $Match)
          {
              $Root = simplexml_load_string('<root '.$Call['Parsed'][1][$IX].'></root>');

              if ($Root->attributes()->type !== null)
                  $Type = (string) $Root->attributes()->type;
              else
                  $Type = $Call['Parslet']['Exec']['Type'];

              $Match = F::Run('Formats.'.$Type, 'Read', ['Value' => trim($Call['Parsed'][2][$IX])]);

              if ($Match)
              {
                  F::Log($Match, LOG_INFO);

                  foreach ($Call['Parslet']['Exec']['Inherited'] as $Key)
                      if (isset($Call[$Key]))
                        $Match[$Key] = $Call[$Key];
                      else
                        $Match[$Key] = null;

                  if (isset($Match['Exec TTL']))
                      $RTTL = $Match['Exec TTL'];
                  else
                      $RTTL = 0;

                  $Application = F::Run('Code.Flow.Application', 'Run', ['RTTL' => $RTTL, 'Run' => $Match]);

                  /*if (F::Environment() == 'Development')
                      $Application['Output'] = '<div class="exec-cached">'.$Application['Output'].'</div>';
                  */
                  if (isset($Application['Output']))
                    $Call['Output'] = str_replace(
                            $Call['Parsed'][0][$IX],
                            $Application['Output'],
                            $Call['Output']);
                  else
                    $Call['Output'] = str_replace(
                            $Call['Parsed'][0][$IX],
                            '',
                            $Call['Output']);

              }
              else
                  $Call['Output'] = str_replace($Call['Parsed'][0][$IX], 'Bad Exec.', $Call['Output']);
          }

          return $Call;
     });
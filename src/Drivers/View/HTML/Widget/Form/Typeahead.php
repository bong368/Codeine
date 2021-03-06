<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description HTML Textfield Driver 
     * @package Codeine
     * @version 8.x
     */

     setFn('Make', function ($Call)
     {
         if (isset($Call['Value']))
         {
             if (is_array($Call['Value']))
                 $Call['Value'] = implode(',', $Call['Value']);

             $Call['HValue'] = $Call['Value'];

             $Call['Value'] = '<far>'.$Call['Entity'].':'.$Call['Value'].':Title</far>';
         }
         else
             $Call['Value'] = '';


         return F::Run('View.HTML.Widget.Base', 'Make',
             $Call,
             [
                 'Tag' => 'input',
                 'Type' => (isset($Call['Subtype'])? $Call['Subtype']: 'text')
             ]);
     });
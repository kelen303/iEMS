<?php
/*  ===============================================================================
    -------------------------------dayAheadBids.php--------------------------------
                                         v 1.0                                    
                                                                             
    Coordinates Day Ahead Bid processing.
                                                                               
    Created by:     Marian C. Buford                                                 
                    Conservation Resource Solutions, Inc. (CRS) 
    Created on:     01.18.2011
    License:        Proprietary
    Copyright:      2011 Conservation Resource Solutions, Inc. All rights reserved.                                                                              
    =============================================================================== */
/*  ===============================================================================
    FUNCTION : processDayAheadBids()
    -----------------------------------variables-----------------------------------
    $Loader         object      : MDR's executive assistant
    $User           object      : User Object
    $post           array       : POSTed form variables
    =============================================================================== */
    function processDayAheadBids($Loader,$User,$post)
    {
        if(isset($post['dayAheadBids']) || isset($_POST['dayAheadBidsCorrect']) )
            return submitBidForm($Loader,$User,$post);

        if(isset($post['dayAheadBidsSubmit']))
            return confirmBidForm($Loader,$User,$post);

        if(isset($post['dayAheadBidsConfirm']))
            return dayAheadBidsAccept($Loader,$User,$post);
    } // processDayAheadBids()

/*  ===============================================================================
    FUNCTION : submitBidForm()
    -----------------------------------variables-----------------------------------
    $Loader         object      : MDR's executive assistant
    $User           object      : User Object
    $post           array       : POSTed form variables
    =============================================================================== */
    function submitBidForm($Loader,$User,$post)
    {
        $pcIdentifier = explode(':',$post['dayAheadPoint']);

        if(!$bidList = initBidList($Loader,$User,$pcIdentifier[0],$pcIdentifier[1],true))
            return '<div class="error" style="width: 700px;">We were unable to process your request.  Please try again. If the problem persists, please contact the <a href="http://help.crsolutions.us/">Help Desk</a>.</div>';
        
        if(strlen($bidList) > 1)
            $result = $bidList;

        $dayAheadStart                  = isset($post['dayAheadStart']) ? $post['dayAheadStart'] : null;
        $dayAheadStop                   = isset($post['dayAheadStop']) ? $post['dayAheadStop'] : null;
        $dayAheadMW                     = isset($post['dayAheadMW']) ? $post['dayAheadMW'] : null;
        $dayAheadOfferPrice             = isset($post['dayAheadOfferPrice']) ? $post['dayAheadOfferPrice'] : null;
        $dayAheadCurtailmentPrice       = isset($post['dayAheadCurtailmentPrice']) ? $post['dayAheadCurtailmentPrice'] : null;
        $dayAheadInterruptionDuration   = isset($post['dayAheadInterruptionDuration']) ? $post['dayAheadInterruptionDuration'] : null;

        $result .= '
                    <tr>
                        <td class="dayAheadInputCell"><input name="dayAheadStart" id="dayAheadStart" type="text" value="'.$dayAheadStart.'" /></td>
                        <td class="dayAheadInputCell"><input name="dayAheadStop" id="dayAheadStop" type="text" value="'.$dayAheadStop.'" /></td>
                        <td class="dayAheadInputCell"><input name="dayAheadMW" id="dayAheadMW" type="text" value="'.$dayAheadMW.'" size="10" /></td>
                        <td class="dayAheadInputCell"><input name="dayAheadOfferPrice" id="dayAheadOfferPrice" type="text" value="'.$dayAheadOfferPrice.'" size="10" /></td>
                        <td class="dayAheadInputCell"><input name="dayAheadCurtailmentPrice" id="dayAheadCurtailmentPrice" type="text" value="'.$dayAheadCurtailmentPrice.'" size="10" /></td>
                        <td class="dayAheadInputCell"><input name="dayAheadInterruptionDuration" id="dayAheadInterruptionDuration" type="text" value="'.$dayAheadInterruptionDuration.'" size="10" /></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="dayAheadInputCell">
                            <input name="formUsed" type="hidden" value="dayAheadForm" />
                            <input name="dayAheadPoint" type="hidden"  value="'.$pcIdentifier[0].':'.$pcIdentifier[1].'" />
                            <input name="dayAheadBidsSubmit" type="hidden" value="true" />
                            <input type="button" value="Submit Bid" class="defaultButton" onClick="doSubmit();"/>
                        </td>
                    </tr>
                ';

        $result .= '</form>
                    </table>
                    </div>';
        
        $result .= '
            <script language="javascript">
            /*  ===============================================================================
                FUNCTION : doSubmit()
                =============================================================================== */
                function doSubmit()
                {
                    var errorMessage = "";
                    if (!dojo.byId("dayAheadStart").value) {
                        errorMessage = "Start Day must be set.\n";
                    }
                    
                    if (!dojo.byId("dayAheadStop").value) {
                        errorMessage += "Stop Day must be set.\n";
                    }

                    if (dojo.byId("dayAheadStart").value && dojo.byId("dayAheadStop").value) {
                        console.log("got dates");
                        var start = dojo.byId("dayAheadStart").value.split("-");
                        var stop = dojo.byId("dayAheadStop").value.split("-");    

                        var startDate = new Date(start[2], start[0]-1, start[1], 0, 0, 0, 0);
                        var stopDate = new Date(stop[2], stop[0]-1, stop[1], 0, 0, 0, 0);
                        var now = new Date();
                        if (startDate <= now) {
                            errorMessage += "Start Day must be in the future.\n";
                        } else if ((startDate.getDate() == now.getDate() + 1) &&
                            (startDate.getMonth() == now.getMonth()) &&
                            (startDate.getYear() == now.getYear()) &&
                            (now.getHours() >= 12)) {
                            errorMessage += "Start Day cannot be tommorow on or after noon today.\n";
                        }

                        if (stopDate < startDate) {
                            errorMessage += "Stop Day must be on or after the Start Day.\n";
                        }

                        var dayOfWeek = startDate.getDay();

                        if ((dayOfWeek == 0) || (dayOfWeek == 6)) {
                            errorMessage += "Start Day must be a week day.\n";
                        }

                        var dayOfWeek = stopDate.getDay();
                        
                        if ((dayOfWeek == 0) || (dayOfWeek == 6)) {
                            errorMessage += "Stop Day must be a week day.\n";
                        }
                    }
                    
                    if (!dojo.byId("dayAheadMW").value) {
                        errorMessage += "MW must be set.\n";
                    } else if (dojo.byId("dayAheadMW").value < 0.000) {
                        errorMessage += "MW must be a zero or positive value greater than or equal to 0.100 MW.\n";
                    } else if ( (dojo.byId("dayAheadMW").value > 0.000) && 
                                (dojo.byId("dayAheadMW").value < 0.100)) {
                        errorMessage += "MW must be a zero or positive value greater than or equal to 0.100 MW.\n";
                    }
                    
                    if (dojo.byId("dayAheadMW").value > 0.000) {
            
                        if (!dojo.byId("dayAheadOfferPrice").value) {
                            errorMessage += "Offer Price must be set.\n";
                        } else if ( (dojo.byId("dayAheadOfferPrice").value < 0.00) || 
                                    (dojo.byId("dayAheadOfferPrice").value > 1000.00)) {
                            errorMessage += "Offer Price must be between $0.00 and $1000.00, inclusive.\n";
                        }
                        if (!dojo.byId("dayAheadCurtailmentPrice").value) {
                            errorMessage += "Curtailment Initiation Price must be set.\n";
                        } else if (dojo.byId("dayAheadCurtailmentPrice").value < 0.00) {
                            errorMessage += "Curtailment Initiation Price must be a zero or positive value.\n";
                        }
                        if (!dojo.byId("dayAheadInterruptionDuration").value) {
                            errorMessage += "Minimum Interruption Duration must be set.\n";
                        } else if ( (dojo.byId("dayAheadInterruptionDuration").value < 1) ||
                                    (dojo.byId("dayAheadInterruptionDuration").value > 4)) {
                            errorMessage += "Minimum Interruption Duration must be between 1 hour and 4 hours, inclusive.\n";
                        }
                    } else {
                        dojo.byId("dayAheadOfferPrice").value = 0.000
                        dojo.byId("dayAheadCurtailmentPrice").value = 0.000
                        dojo.byId("dayAheadInterruptionDuration").value = 0 
                    }
            
                    if (errorMessage.length) {
                        alert("A bid cannot be submitted until each of the following conditions is met:\n\n" + errorMessage);
                    } else {
                        document.day_ahead_bids.submit();
                    }
                } // doSubmit()
            </script>
            ';

        return $result;
    } // submitBidForm()

/*  ===============================================================================
    FUNCTION : confirmBidForm()
    -----------------------------------variables-----------------------------------
    $post           array       : POSTed form variables
    =============================================================================== */
    function confirmBidForm($Loader,$User,$post)
    {
        $pcIdentifier = explode(':',$post['dayAheadPoint']);

        if(!$bidList = initBidList($Loader,$User,$pcIdentifier[0],$pcIdentifier[1]))
            return '<div class="error" style="width: 700px;">We were unable to process your request.  Please try again. If the problem persists, please contact the <a href="http://help.crsolutions.us/">Help Desk</a>.</div>';
        
        if(strlen($bidList) > 1)
            $result = $bidList;

        $result .= '<tr>
                        <td class="dayAheadInputCell day_ahead_bid_confirm_values"><input name="dayAheadStart" id="dayAheadStart" type="hidden" value="'.$post['dayAheadStart'].'" />'.$post['dayAheadStart'].'</td>
                        <td class="dayAheadInputCell day_ahead_bid_confirm_values"><input name="dayAheadStop" id="dayAheadStop" type="hidden" value="'.$post['dayAheadStop'].'" />'.$post['dayAheadStop'].'</td>
                        <td class="dayAheadInputCell day_ahead_bid_confirm_values"><input name="dayAheadMW" id="dayAheadMW" type="hidden" value="'.number_format($post['dayAheadMW'],3).'" size="10" />'.number_format($post['dayAheadMW'],3).'</td>
                        <td class="dayAheadInputCell day_ahead_bid_confirm_values"><input name="dayAheadOfferPrice" id="dayAheadOfferPrice" type="hidden" value="'.number_format($post['dayAheadOfferPrice'],3).'" size="10" />'.number_format($post['dayAheadOfferPrice'],3).'</td>
                        <td class="dayAheadInputCell day_ahead_bid_confirm_values"><input name="dayAheadCurtailmentPrice" id="dayAheadCurtailmentPrice" type="hidden" value="'.number_format($post['dayAheadCurtailmentPrice'],3).'" size="10" />'.number_format($post['dayAheadCurtailmentPrice'],3).'</td>
                        <td class="dayAheadInputCell day_ahead_bid_confirm_values"><input name="dayAheadInterruptionDuration" id="dayAheadInterruptionDuration" type="hidden" value="'.$post['dayAheadInterruptionDuration'].'" size="10" />'.$post['dayAheadInterruptionDuration'].'</td>
                    </tr>';

        $result .= '<tr>
                        <td colspan="6" class="dayAheadInputCell">
                            <input name="formUsed" type="hidden" value="dayAheadForm" />
                            <input name="assetIdentifier" type="hidden" value="'.$User->pointChannels()->pointChannel($pcIdentifier[0],$pcIdentifier[1])->assetIdentifier().'" />
                            <input name="dayAheadPoint" type="hidden"  value="'.$pcIdentifier[0].':'.$pcIdentifier[1].'" />
                            <input type="submit" name="dayAheadBidsCorrect" value="Correct Bid" class="defaultButton" />
                            <input type="submit" name="dayAheadBidsConfirm" value="Confirm Bid" class="defaultButton" />
                        </td>
                    </tr>
                ';

        $result .= '</form>
                    </table>
                    </div>';
        
        
        return $result;
    } // confirmBidForm()

/*  ===============================================================================
    FUNCTION : dayAheadBidsAccept()
    -----------------------------------variables-----------------------------------
    $post           array       : POSTed form variables
    =============================================================================== */
    function dayAheadBidsAccept($Loader,$User,$post)
    {
        $Loader->includeIsoneDayAheadBids();
        $Bid = new IsoneDayAheadBid();

        $startDayParts = explode('-',$post['dayAheadStart']);
        $stopDayParts = explode('-',$post['dayAheadStop']);

        $Bid->load( 0,
                    $post['assetIdentifier'],
                    $startDayParts[2].'-'.$startDayParts[0].'-'.$startDayParts[1],
                    $stopDayParts[2].'-'.$stopDayParts[0].'-'.$stopDayParts[1],
                    $post['dayAheadMW'],
                    $post['dayAheadOfferPrice'],
                    $post['dayAheadCurtailmentPrice'],
                    $post['dayAheadInterruptionDuration'],
                    0,
                    $User->id(),
                    0,
                    0,
                    0
                   );

        if($Bid->save())
        {
            $pcIdentifier = explode(':',$post['dayAheadPoint']);

            $result = '<div class="error" style="width: 700px;">The bid was successfully submitted.</div>';

            if(!$bidList = initBidList($Loader,$User,$pcIdentifier[0],$pcIdentifier[1]))
                return '<div class="error" style="width: 700px;">We were unable to process your request.  Please try again. If the problem persists, please contact the <a href="http://help.crsolutions.us/">Help Desk</a>.</div>';
            
            if(strlen($bidList) > 1)
                $result .= $bidList;

            $result .= '</form>
                    </table>
                    </div>';
            return $result;
        }
        else
        {
            return '<div class="error" style="width: 700px;">We were unable to process your request.  Please try again. If the problem persists, please contact the <a href="http://help.crsolutions.us/">Help Desk</a>.</div>';
        }
        
    } // dayAheadBidsAccept()

/*  ===============================================================================
    FUNCTION : initBidList()
    -----------------------------------variables-----------------------------------
    $Loader         object      : MDR's executive assistant
    $User           object      : User Object
    -------------------------------------notes-------------------------------------
    The table gets rendered three times, the only variance is in the last row.
    This function populates the top portion of the table.  The calling function is
    responsible for closing out both the table and the div.
    =============================================================================== */
    function initBidList($Loader,$User,$point,$channel,$start=false)
    {
        $result = true;

        $Loader->includeIsoneDayAheadBids();
        $Bids = new IsoneDayAheadBids();

        $Bids->load($User);

        $bidList = $Bids->bidList();

        if($bidList['error'])
            return false;
        
        if(count($bidList) > 0)
        {
            $result = '
                <div style="width: 700px;">
                    <style>
                        .dayAheadHeader { border-bottom: 2px solid #FFFFFF; font-weight: normal; color: #FF6701; }
                        .dayAheadCell { border: 1px solid #FFFFFF; text-align: right; }
                        .dayAheadInputCell {text-align: right;}
                        .day_ahead_bid_confirm_values {
                            text-align: right;
                            font-weight: bold;
                            color: red;
                            background-color: #CFCFCF;
                            border: 2px  solid #175A87;
                            width: 90px;
                        }
                    </style>
                ';

            if($start)
            {
                $result .= '
                        <script>
                            dojo.addOnLoad(function(){
                                dayAheadCalStart    = new Calendar({ dayAheadStart: "m-d-Y" },{direction: 1,navigation: 1});
                                dayAheadCalStop     = new Calendar({ dayAheadStop: "m-d-Y" },{direction: 1,navigation: 1});
                            });
            
                        </script>
                    ';
            }

            $result .= '
                    <h2 style="margin: 0; padding: 0; font-size: 16px;">Load Response Offers for '.$User->pointChannels()->pointChannel($point,$channel)->channelDescription().'</h2>
                    <form action="'.$_SERVER['PHP_SELF'].'" method="POST" name="day_ahead_bids" dojoType="dijit.form.Form">
                    <table border="0" cellpadding="5" cellspacing="0" align="center">
                        <tr>
                            <th class="dayAheadHeader">Start Day</th>
                            <th class="dayAheadHeader">Stop Day<br />(inclusive)</th>
                            <th class="dayAheadHeader">MW</th>
                            <th class="dayAheadHeader">Price</th>
                            <th class="dayAheadHeader">Curtailment<br />Initiation Price</th>
                            <th class="dayAheadHeader">Minimum<br />Interruption<br />Duration</th>
                        </tr>
                        ';
            foreach($bidList as $inx=>$listItem)
            {
                if($listItem->assetIdentifier() == $User->pointChannels()->pointChannel($point,$channel)->assetIdentifier())
                {
                    $result .= '
                        <tr>
                            <td class="dayAheadCell">'.date('m-d-Y',strtotime($listItem->startDay())).'</td>
                            <td class="dayAheadCell">'.date('m-d-Y',strtotime($listItem->stopDay())).'</td>
                            <td class="dayAheadCell">'.number_format($listItem->mw(),3).'</td>
                            <td class="dayAheadCell">'.$listItem->offerPrice().'</td>
                            <td class="dayAheadCell">'.$listItem->curtailmentInitiationPrice().'</td>
                            <td class="dayAheadCell">'.$listItem->minimumInterruptionDuration().'</td>
                        </tr>
                    ';
                }
            }
        }

        return $result;

    } // initBidList()
   
/*  ===============================================================================
    TROUBLESHOOTING HELPERS
    =============================================================================== */
    //$Loader->preDebugger($mdrUser->id());
    //$Loader->preDebugger($mdrUser->lseDomain()->name());
    //$Loader->preDebugger($mdrUser->lseDomain()->id());
    //$Loader->preDebugger($mdrUser->privileges());
    //$Loader->preDebugger($mdrUser->pointChannels());

/*  -------------------------------dayAheadBids.php-------------------------------- */
?>

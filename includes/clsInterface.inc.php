<?php
/**
 * userInterface
 *
 * @package IEMS 2.0
 * @name Interface
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 *
 * @abstract This is the bridge between the MDR suite of classes for gathering data from the MDR database and the rest of the site. gather() is the core function, the remaining functions primarily format the results from gather into the final output requested.
 *
 */
//Ensuring that the page cannot be called directly. Definition is set in the calling pages.
if(!defined('APPLICATION')){header('HTTP/1.0 404 not found');exit;}

class userInterface
{
	var $message = '';

/*  INDEX----------------------------------------------------------------------------------------------------------------------------------------------
    --------------------------------------------------------------------------------------------------------------------------------------------------- */
    /*  ==========================================================================
        FUNCTION: loginForm()
        ========================================================================== */
    	function loginForm($titleString)
    	{
    		return '
    			<td>
    			<br /><br />
    			<h1>'.$titleString.'</h1>
    			<form action="index.php" method="post" enctype="application/x-www-form-urlencoded" name="logon" target="_self">
    			<table cellpadding="5" cellspacing="0" border="0">
    				<tr>
    					<td><label>User Name: </label></td><td><input type="text" style="width: 12em; border: 2px solid #1B6097; padding-left: 5px;" name="username" value="" /></td>
    				</tr>
    				<tr>
    					<td><label>Password: </label></td><td><input type="password" style="width: 12em; border: 2px solid #1B6097; padding-left: 5px;"  name="password" value="" /></td>
    				</tr>
    				<tr>
    					<td>&nbsp;</td><td><input type="submit" name="login" value="Log On" /></td>
    				</tr>
    			</table>
    			</form>

    			</td>
    		';
    	}

    /*  ==========================================================================
        FUNCTION: ticker()
        ========================================================================== */
    	function ticker($domain,$connection)
    	{
    		$sql = '
    			SELECT
    				TickerMessage,
    				Priority
    			FROM
    				t_tickermessages tm,
    				t_objectxrefs dox,
    				t_objectxrefs tox,
    				t_objects o,
    				t_objecttypes ot,
    				t_objecttypes dot,
    				t_objects do
    			WHERE
    				dox.ChildObjectID = '.$domain.' and
    				tox.ParentObjectID = dox.ParentObjectID and
    				do.ObjectID = tox.ParentObjectID and
    				dot.ObjectTypeID = do.ObjectTypeID and
    				dot.ObjectTypeName = "Domain" and
    				o.ObjectID = tox.ChildObjectID and
    				o.ObjectTypeID = ot.ObjectTypeID and
    				ot.ObjectTypeName = "TickerMessage" and
    				tm.ObjectID = o.ObjectID and
    				Now() between case tm.EffectiveDate when "0000-00-00 00:00:00" then date_sub(now(), INTERVAL 1 DAY) when null then date_sub(now(), INTERVAL 1 DAY)  else tm.EffectiveDate end and
    				       case tm.ExpirationDate when "0000-00-00 00:00:00" then date_add(now(), INTERVAL 1 DAY) when null then date_add(now(), INTERVAL 1 DAY)  else tm.ExpirationDate end
    			ORDER BY
    				Priority
    		';

    		$result = mysql_query($sql, $connection);

    		if(mysql_numrows($result) == 0)
    		{
    			$tickerArray = array('TickerMessage'=>'', 'Priority'=>3);
    		}
    		else
    		{
    			$priorityCheck = array();
                $inx = 0;
                while($tickerRow = mysql_fetch_assoc($result))
                {
                        $priorityCheck[$inx] = $tickerRow['Priority'];
                        $values[$inx] = $tickerRow['TickerMessage'];
                        $inx++;
                }

                $iMinValue = min($priorityCheck);
                $arFlip = array_flip($priorityCheck);
                $iMinPosition = $arFlip[$iMinValue];

                $tickerArray['Priority'] = $priorityCheck[$iMinPosition];
                $tickerArray['TickerMessage'] = $values[$iMinPosition];

    		}
    		return $tickerArray;
    	}

    /*  ==========================================================================
        FUNCTION: usertable()
        ========================================================================== */
    	function usertable($mdrUser, $username, $logoutString, $fullName, $domain, $email, $telephone)
    	{
    		return '
    			<table cellpadding="0" cellspacing="0" border="0">
    				<tr>
    					<td>
    						<div style="padding-left: 30px;">
    						<table width="100%" cellpadding="0" cellspacing="0" border="0">
    							<tr>
    								<td style="padding-bottom: 15px;">You are logged in as '.$username.' . . . </td><td>'.$logoutString.'</td>
    							</tr>
    						</table>
    						</div>
    					</td>
    				</tr>
    				<tr>
    					<td>
    						<div style="padding-left: 30px;">
    						<table cellpadding="3" cellspacing="0" border="0" style="border: 1px solid #4B7596;">
    							<tr>
    								<td style="padding-left: 10px;">User</td><td style="padding-right: 10px;">'.$fullName.'</td>
    							</tr>
    							<tr>
    								<td style="padding-left: 10px;">Email</td><td style="padding-right: 10px;">'.$email.'</td>
    							</tr>
    							<tr>
    								<td style="padding-left: 10px;">Tel</td><td style="padding-right: 10px;">'.$telephone.'</td>
    							</tr>
    							<tr>
    								<td style="padding-right: 15px; padding-left: 10px;">Enrolling Participant</td><td style="padding-right: 10px;">'.$mdrUser->lseDomain()->description().'</td>
    							</tr>
    						</table>
    						</div>
    					</td>
    				</tr>
    				<tr>
    					<td style="text-align: center;"><br /><a href="http://isoexpress.iso-ne.com/" target="_blank" ; style="font-size: x-large">Click Here for Power System Conditions</a>
    					<br />
    					<br />
    					<br />
						</td>
    				</tr>
    			</table>
    		';
    	}


/*  GENERAL CHARTS & TABLES ---------------------------------------------------------------------------------------------------------------------------
    --------------------------------------------------------------------------------------------------------------------------------------------------- */
    /*  ==========================================================================
        FUNCTION: gather()
        ========================================================================== */
        function gather($action,
                        $points,
                        $baseDate,
                        $dateSpan,
                        $presentation,
                        $view,
                        $connection,
                        $formUsed,
                        $mdrUser,
                        $refresh = false)
        {
            //$this->preDebugger(func_get_args());           
            
        	$dstFlag = false;
        	$idString = ''; //tabularPricing needs this
        	$aryUnits = array('KWh'=>'KW', 'kVarh'=>'kVar'); //mcb 2009.05.30

        	if($view == 'exportPricingFiveMinute' || $view == 'exportTabularFiveMinute')
        	{
        		$forceHourlyRollup = false;
        	}
        	elseif($dateSpan > 1)
        	{
        		$forceHourlyRollup = true;
        	}
        	else
        	{
        		$forceHourlyRollup = false;
        	}

        	$longTermFlag = false;

        	switch ($view)
        	{
        		case 'exportTabularHourly':
        			$longTermFlag = true;
        			break;
        		case 'exportTabularFiveMinute':
        			$longTermFlag = true;
        			break;
        		case 'exportPricingHourly':
        			$longTermFlag = true;
        			break;
        		case 'exportPricingFiveMinute':
        			$longTermFlag = true;
        			break;
        	}

        	$baseDateArray = '';

        	//need to work this area here to handle the basedate and basedate objects so that they send to the
        	//comparison interval sets correctly.
         	if($presentation == 'comparison')
        	{
        		$baseDateArray = $baseDate; //need to preserve the array that we receive 'cause the comparison object needs it.

        		$baseDate = $baseDate[0]; //this will be removed when we have good return below.

        	}

        	/* date manipulation in order to ease gathering */
        	$fiveMinutes = 300;
        	$oneDay = 86400;


        	$startDate = gmdate('Y-m-d H:i:s', strtotime($baseDate));
        	$endDate = gmdate('Y-m-d H:i:s', strtotime($baseDate)+($dateSpan * $oneDay)); // add 1 day to get bottom of set

        	$priceStartDate = date('Y-m-d H:i:s', strtotime($baseDate) + $fiveMinutes); // add five minutes to get to the top of the set
        	$priceEndDate = date('Y-m-d H:i:s', strtotime($baseDate)+($dateSpan * $oneDay)); // add 1 day to get bottom of set
        	/* end date manipulation */
        	$oMeterPoint = new MeterPoint();
        	$oBaseDate = new CrsDate($baseDate);

        	$frequency = 1; //we may use this later to enable rolling up in whatever manner is desired -- 15 minute increments, 30 minute, whatever.

            // This coding is special just for the August 2008 events....
            $envelope['message']['isDisplayable'] = false;
            $envelope['message']['text'] = "";

          	foreach($points as $pointToGather=>$state)
        	{
        		$idString .= $pointToGather.',';
        		$ids = explode(':',$pointToGather);
        		$pointID = $ids[0];
        		$channelID = $ids[1];
                $pricingEnvelope = array();

                //$oMeterPoint = new MeterPoint();
                //$oMeterPoint = $mdrUser->pointChannels()->meterPoint($pointID)


                $programName[$pointToGather]['program'] = '';
        		$programName[$pointToGather]['participationType'] = $mdrUser->pointChannels()->meterPoint($pointID)->participationTypeDescription();


                    $oIntervalSet = new IntervalValueSets();

                    //$this->preDebugger($oIntervalSet);
                    /* 
                        [0] => advanced
                        [1] => Array
                            (
                                [3089:2] => on
                                [3092:1] => on
                            )
                    
                        [2] => 2011-10-10
                        [3] => 30
                        [4] => individual
                        [5] => charts
                        [6] => Resource id #42
                        [7] => advPointsForm 
                    */ 

        			if($action != 'event')
                    {
                        // display price id = 5 minute prices, settlement price id = hourly prices

            			if($forceHourlyRollup == true)
            			{
            				$pricingArray = array($mdrUser->pointChannels()->meterPoint($pointID)->settlementPriceId() =>
                                                  $mdrUser->pointChannels()->meterPoint($pointID)->settlementPriceDescription());
            			}
            			else
            			{
            				$pricingArray = array($mdrUser->pointChannels()->meterPoint($pointID)->displayPriceId() =>
                                                  $mdrUser->pointChannels()->meterPoint($pointID)->displayPriceDescription());
            			}

                        //$this->preDebugger($mdrUser->pointChannels()->meterPoint($pointID)->displayPriceId(),'orange');

            			foreach($pricingArray as $id=>$description)
            			{
            				$sql = '
            					SELECT *
            					FROM
            						t_priceintervals
            					WHERE
            						PriceID = '.$id.' and
            						IntervalDate between "'.$priceStartDate.'" and "'.$priceEndDate.'"
            				';

                            //echo "sql='" . $sql . "'<br>\n";
                            //SELECT * FROM t_priceintervals WHERE PriceID = 5 and IntervalDate between "2011-11-21 00:05:00" and "2011-11-28 00:00:00"

            				$result = mysql_query($sql, $connection);
                            //$mdrUser->preDebugger($connection,'yellow'); 
                            //$mdrUser->preDebugger(mysql_num_rows($result)); 

            				if(mysql_numrows($result) != 0)
            				{
            					while ($row = mysql_fetch_array($result,MYSQL_ASSOC))
            					{
            						$pricingEnvelope[$description][$row['IntervalDate']] = $row['IntervalValue'];
            					}
            				}
                            //$mdrUser->preDebugger($pricingEnvelope); 
            			}
                    }

                    //$mdrUser->preDebugger($mdrUser->pointChannels()->meterPoint($pointID));

        			if($action == 'advanced' || (($action == 'modalPrint' || $action == 'modalDisplay') && $presentation == 'comparison'))
        			{
        				if($presentation == 'comparison')
        				{
        					foreach($baseDateArray as $dateInx=>$date)
        					{
        						$dateObjectArray[$dateInx] = new CrsDate($date);
        					}

        					$oIntervalSet->Load_list($mdrUser->pointChannels()->pointChannel($pointID, $channelID), $mdrUser->pointChannels()->meterPoint($pointID), 'intervalset', $forceHourlyRollup, $dateObjectArray);

        				}
        				else
        				{
        					$oIntervalSet->Load($mdrUser->pointChannels()->pointChannel($pointID, $channelID), $mdrUser->pointChannels()->meterPoint($pointID), 'intervalset', $dateSpan, $forceHourlyRollup, $baseDate, $oBaseDate);

        				}
        			}
        			else
        			{
        				$oIntervalSet->Load($mdrUser->pointChannels()->pointChannel($pointID, $channelID), $mdrUser->pointChannels()->meterPoint($pointID), 'intervalset', $dateSpan, $forceHourlyRollup, $baseDate, $oBaseDate);
        				//$this->preDebugger($oIntervalSet);
        			}

                    $envelope[$pointID.':'.$channelID]['assetIdentifier'] = ''; //kludge


                    $envelope[$pointID.':'.$channelID]['isGenerator'] = $mdrUser->pointChannels()->pointChannel($pointID, $channelID)->isGenerator(); //$oMeterPoint->isGenerator();
        			$envelope[$pointID.':'.$channelID]['pointID'] = $pointID;
        			$envelope[$pointID.':'.$channelID]['channelID'] = $channelID;
        			$envelope[$pointID.':'.$channelID]['meterName'] = $mdrUser->pointChannels()->pointChannel($pointID, $channelID)->channelDescription(); //$oPointChannels->channelDescription($iny);
                    $envelope[$pointID.':'.$channelID]['assetIdentifier'] = $mdrUser->pointChannels()->pointChannel($pointID, $channelID)->assetIdentifier();
                    $envelope[$pointID.':'.$channelID]['adjustedBaselineString'] = '';

                    $mdrUser->pointChannels()->pointChannel($pointID, $channelID)->RefreshStats();
                    $envelope[$pointID.':'.$channelID]['firstRead'] = $mdrUser->pointChannels()->meterPoint($pointID)->timeZone()->ToLocalTime($mdrUser->pointChannels()->pointChannel($pointID, $channelID)->firstIntervalDate())->Format("Y-m-d H:i:s");
                    $envelope[$pointID.':'.$channelID]['lastRead'] = $mdrUser->pointChannels()->meterPoint($pointID)->timeZone()->ToLocalTime($mdrUser->pointChannels()->pointChannel($pointID, $channelID)->lastIntervalDate())->Format("Y-m-d H:i:s");

                    $envelope[$pointID.':'.$channelID]['meterName'] = $mdrUser->pointChannels()->pointChannel($pointID, $channelID)->channelDescription(); //$oPointChannels->channelDescription($iny);
                    $envelope[$pointID.':'.$channelID]['registeredProgram'] = $mdrUser->pointChannels()->pointChannel($pointID, $channelID)->participationTypeDescription();
        			$envelope[$pointID.':'.$channelID]['zone'] = $mdrUser->pointChannels()->meterPoint($pointID)->zone();
        			//MCB 2012-05-024 $envelope[$pointID.':'.$channelID]['committedReduction'] = $mdrUser->pointChannels()->meterPoint($pointID)->committedReduction();
                    $envelope[$pointID.':'.$channelID]['committedReduction'] = $mdrUser->pointChannels()->pointChannel($pointID, $channelID)->committedReduction();
        			$envelope[$pointID.':'.$channelID]['maximumValue'] = $oIntervalSet->maximumValue();
        			$envelope[$pointID.':'.$channelID]['maximumValueDate'] = date('Y-m-d H:i:s',$oIntervalSet->maximumValueDateTime()->asDate());
        			$envelope[$pointID.':'.$channelID]['minimumValue'] = $oIntervalSet->minimumValue();
        			$envelope[$pointID.':'.$channelID]['minimumValueDate'] = date('Y-m-d H:i:s',$oIntervalSet->minimumValueDateTime()->asDate());
        			$envelope[$pointID.':'.$channelID]['averageValue'] = $oIntervalSet->averageValue();

        			$valueEnvelope[$pointID.':'.$channelID]['pointID'] = $pointID;
        			$valueEnvelope[$pointID.':'.$channelID]['channelID'] = $channelID;
        			$valueEnvelope[$pointID.':'.$channelID]['unit'] = $aryUnits[$mdrUser->pointChannels()->pointChannel($pointID, $channelID)->units()->unitOfMeasureName()];
                    $valueEnvelope[$pointID.':'.$channelID]['isGenerator'] = $mdrUser->pointChannels()->pointChannel($pointID, $channelID)->isGenerator(); //$oMeterPoint->isGenerator();

        			$valueEnvelope[$pointID.':'.$channelID]['pricing'] = $pricingEnvelope;

                    //$this->preDebugger($valueEnvelope);
                    //$this->preDebugger($envelope);

        			$adjustedBaselineString = '';
                    $eventDateStack = '';
                    //$this->preDebugger($oIntervalSet->values());

        			if(($oIntervalSet->recordsReturned != 0))
        			{
                        if($oMeterPoint->isGenerator() != 1 || $presentation != 'comparison' || $longTermFlag === false)
                        {
                            
        				    $oBaselineSet = new IntervalValueSets();
        					$hasBaseline = true;

                            $oBaselineSet->Load($mdrUser->pointChannels()->pointChannel($pointID, $channelID), $mdrUser->pointChannels()->meterPoint($pointID), 'baselineset', $dateSpan, $forceHourlyRollup, $baseDate, $oBaseDate);
                            
            				if($oBaselineSet->isAdjustedBaseline() == true)
        					{
        						if($formUsed == 'eventsForm')
        						{

        							$envelope[$pointID.':'.$channelID]['adjustedBaselineString'] = 'Your baseline is adjusted by '.number_format($oBaselineSet->adjustmentAmount(),3,'.',',').' '.$aryUnits[$mdrUser->pointChannels()->pointChannel($pointID, $channelID)->units()->unitOfMeasureName()].'.'; //mcb 2009.05.30
        						}
        						else
        						{
                                    $unit = isset($envelope[$pointID.':'.$channelID]['unit']) ? ' ('.$envelope[$pointID.':'.$channelID]['unit'].')' : '';

                                    $envelope[$pointID.':'.$channelID]['adjustedBaselineString'] = '
                                        <tr>
                                            <td style="text-align: left; color: #FF6701;">Baseline Adjusted By'.$unit.'</td><td style="text-align: right; color: #FF6701;">'.number_format($oBaselineSet->adjustmentAmount(),3,'.',',').'</td>
                                        </tr>
                                    ';
        						}

        					}
                        }

                        if($formUsed == 'advPointsForm' && $dateSpan > 1 && $forceHourlyRollup)
                        {
                            // mcb 2010.05.30 When I can think more clearly, we'll figure out why I seem to only be able to get this to work this way.
                            // This fixes the situation where the advanced charts date span greater than 1 is the only interval set being retrieved
                            // with a timezone adjustment in the sql query.
                        }
                        else
                        {
                            date_default_timezone_set(timezone_name_from_abbr($mdrUser->pointChannels()->meterPoint($pointID)->timeZone()->stdAbbreviation()));
                        }

                        $mdrUser->pointChannels()->meterPoint($pointID)->baseDate($oBaseDate);
                        $mdrUser->pointChannels()->meterPoint($pointID)->dateSpan($dateSpan);
                        $mdrUser->pointChannels()->meterPoint($pointID)->RefreshPrices();

                        $envelope[$pointID.':'.$channelID]['realTimePrice'] = $mdrUser->pointChannels()->meterPoint($pointID)->currentDisplayPrice();

                        $envelope[$pointID.':'.$channelID]['peakPrice'] = $mdrUser->pointChannels()->meterPoint($pointID)->maximumDailyDisplayPrice();
            			$envelope[$pointID.':'.$channelID]['peakDate'] = $mdrUser->pointChannels()->meterPoint($pointID)->maximumDailyDisplayPriceDate();
            			$envelope[$pointID.':'.$channelID]['priceSource'] = $mdrUser->pointChannels()->meterPoint($pointID)->displayPriceDescription();

        				$envelope[$pointID.':'.$channelID]['realTimePriceDate'] = date('Y-m-d H:i:s',$mdrUser->pointChannels()->meterPoint($pointID)->currentDisplayPriceDate()->asDate());

        				$tsInx = 0;
        				$lastHour = '';
        				$tcFlag = '';

        				$uxMinute = 60;
        				$uxHour = 60 * 60;

        				if($forceHourlyRollup === true)
        				{
        					$increment = $uxHour;
        				}
        				else
        				{
        					$increment = $oIntervalSet->readInterval() * $uxMinute;
        				}

                        $expectedTime = strtotime($baseDate);

        				$tcCheck = '';

        				$baselinePackage = '';
        				if($presentation != 'comparison')
        				{
        					if($oBaselineSet->values() != '')
        					{
        						$baselinePackage = $oBaselineSet->values();
        					}

        					foreach($oIntervalSet->values() as $utcTimestamp=>$value)
        					{
        						//$this->preDebugger(date('Y-m-d H:i:s',$utcTimestamp).' => '.$value,'green');
                                //$this->preDebugger($value,'orange');
        						$expectedTime += $increment;
        						$timeProperties = localtime($utcTimestamp,true);
        						$thisHour = $timeProperties['tm_hour'];
        						$thisMinute = $timeProperties['tm_min'];

        						if($lastHour == ''){ $lastHour = $thisHour-1; }

        						if($utcTimestamp != $expectedTime)
        						{
                                    /*$this->preDebugger('this hour ==> '.$thisHour);
                                    $this->preDebugger('last hour ==> '.$lastHour);
                                    $this->preDebugger('utc ==> '.$utcTimestamp);
                                    $this->preDebugger('expected ==> '.$expectedTime); */
                                    
        							$numberToFill = ($utcTimestamp - $expectedTime)/$increment;
                                    //$this->preDebugger($numberToFill);

        							for($i = 1; $i <= $numberToFill; $i++)
        							{
        								$timestampArray[$pointID.':'.$channelID]['timestamps'][$tsInx] = date('Y-m-d H:i:s',$expectedTime);
        								$valueEnvelope[$pointID.':'.$channelID]['values'][date('Y-m-d H:i:s',$expectedTime)] = '';

        								if($baselinePackage != '')
        								{
                                            $valueEnvelope[$pointID.':'.$channelID]['valuesBaseline'][date('Y-m-d H:i:s',$expectedTime)] =
                                                array_key_exists($expectedTime,$baselinePackage) ? $baselinePackage[$expectedTime]['value'] : '';
        								}
        								$expectedTime += $increment;
        								$lastHour = $thisHour;
        								$tsInx++;
        							}
        						}
                                
        						if(($thisHour - $lastHour) == 2)
        						{
        							$tcCheck = 'spring';
        							$timestampArray[$pointID.':'.$channelID]['dst']['direction'] = 'spring';
        							$timestampArray[$pointID.':'.$channelID]['dst'][$tsInx] = date('Y-m-d H:i:s',$utcTimestamp);
        							$fillHour = $thisHour-1;

        						   if($fillHour <10)
        							{
        								$fillHourString = '0'.$fillHour;
        							}
        							else
        							{
        								$fillHourString = $fillHour;
        							}

        							if($forceHourlyRollup === true)
        							{
        								$tsInx++;
        								$timestampArray[$pointID.':'.$channelID]['timestamps'][$tsInx] = date('Y-m-d',$utcTimestamp).' '.$fillHourString.':00:00';
        								$valueEnvelope[$pointID.':'.$channelID]['values'][date('Y-m-d',$utcTimestamp).' '.$fillHourString.':00:00'] = '';
        								if($baselinePackage != '')
        								{
        									$valueEnvelope[$pointID.':'.$channelID]['valuesBaseline'][date('Y-m-d',$utcTimestamp).' '.$fillHourString.':00:00'] = '';
        								}

        								$tsInx++;
        							}
        							else
        							{
        								$target = ($uxHour/$increment);
        								$fillMinute = 0;
        								for($fillCount = 0; $fillCount < $target; $fillCount++)
        								{

        									if($fillMinute <10)
        									{
        										$fillMinuteString = '0'.$fillMinute;
        									}
        									else
        									{
        										$fillMinuteString = $fillMinute;
        									}

        									$timestampArray[$pointID.':'.$channelID]['timestamps'][$tsInx] = date('Y-m-d',$utcTimestamp).' '.$fillHourString.':'.$fillMinuteString.':00';
        									$valueEnvelope[$pointID.':'.$channelID]['values'][date('Y-m-d',$utcTimestamp).' '.$fillHourString.':'.$fillMinuteString.':00'] = '';

        									if($baselinePackage != '')
        									{
        										$valueEnvelope[$pointID.':'.$channelID]['valuesBaseline'][date('Y-m-d',$utcTimestamp).' '.$fillHourString.':'.$fillMinuteString.':00'] = '';
        									}


        									$tsInx++;

        									$fillMinute += $increment/60;
        								}
        							}
        						}
        						elseif(($thisHour - $lastHour) == 0 && $thisMinute == 0)
        						{
        							$tcFlag = 'fall';
        							if($dateSpan > 1)
        							{
        								$dstFlag = true;
        							}

        						}

        						if($tcFlag == 'fall')
        						{
        							if($thisHour != $lastHour)
        							{
        								$tcFlag = '';
        								$tcCheck = 'fall';
        							}
        							else
        							{
        								$timestampArray[$pointID.':'.$channelID]['dst']['direction'] = 'fall';
        								$timestampArray[$pointID.':'.$channelID]['dst'][$tsInx] = date('Y-m-d H:i:s',$utcTimestamp);
        							}
        						}

        						$timestampArray[$pointID.':'.$channelID]['timestamps'][$tsInx] = date('Y-m-d H:i:s',$utcTimestamp);

                                //$this->preDebugger($timestampArray[$pointID.':'.$channelID]['timestamps']);
                                //$this->preDebugger(date('Y-m-d H:i:s',$utcTimestamp));
                                //$this->preDebugger($value['value']);
        						$valueEnvelope[$pointID.':'.$channelID]['values'][date('Y-m-d H:i:s',$utcTimestamp)] = $value['value'];

        						if($baselinePackage != '')
        						{
        							if(array_key_exists($utcTimestamp,$baselinePackage))
        							{
        								$valueEnvelope[$pointID.':'.$channelID]['valuesBaseline'][date('Y-m-d H:i:s',$utcTimestamp)] = $baselinePackage[$utcTimestamp]['value'];
        							}
        							else
        							{
        								$valueEnvelope[$pointID.':'.$channelID]['valuesBaseline'][date('Y-m-d H:i:s',$utcTimestamp)] = '';
        							}
        						}
                                else
                                {
                                    $valueEnvelope[$pointID.':'.$channelID]['valuesBaseline'][date('Y-m-d H:i:s',$utcTimestamp)] = '';
                                }

        						$lastHour = $thisHour;

        						$tsInx++;
                                //$this->preDebugger('utc ['.$utcTimestamp.'] '.date('Y-m-d H:i:s',$utcTimestamp),'green');
        					} // end foreach($oIntervalSet->values() as $utcTimestamp=>$value)
        					//$this->preDebugger($valueEnvelope);
        					$tsInx++;

                            //$this->preDebugger($oIntervalSet->expectedLength);

        					if(($tsInx < $oIntervalSet->expectedLength))
        					{
        						$leftOffTime = $utcTimestamp;

        						for ($tsInx; $tsInx <= $oIntervalSet->expectedLength; $tsInx++)
        						{
                                    $leftOffTime += $increment;

        							$timestampArray[$pointID.':'.$channelID]['timestamps'][$tsInx] = date('Y-m-d H:i:s',$leftOffTime);
        							$valueEnvelope[$pointID.':'.$channelID]['values'][date('Y-m-d H:i:s',$leftOffTime)] = '';
        	    					if($baselinePackage != '')
        		    				{
                                        if (array_key_exists($leftOffTime,$baselinePackage))
                                        {
                                            $valueEnvelope[$pointID.':'.$channelID]['valuesBaseline'][date('Y-m-d H:i:s',$leftOffTime)] = $baselinePackage[$leftOffTime]['value'];
                                        }
                                        else
                                        {
                                            $valueEnvelope[$pointID.':'.$channelID]['valuesBaseline'][date('Y-m-d H:i:s',$leftOffTime)] = '';
                                        }
                                    }
                                    else
                                    {
                                        $valueEnvelope[$pointID.':'.$channelID]['valuesBaseline'][date('Y-m-d H:i:s',$leftOffTime)] = '';
                                    }
        						}
        					}
        				}
        				else
        				{
        					$timestampsDone = false;

        					foreach($oIntervalSet->values() as $dsIndex=>$dataset)
        					{

        						$dateIndex = '';
        						foreach($dataset as $utcTimestamp=>$value)
        						{
        							if($dateIndex == '')
        							{
        								$dateIndex = date('Y-m-d',$utcTimestamp);
        							}

        							$expectedTime += $increment;
        							$timeProperties = localtime($utcTimestamp,true);
        							$thisHour = $timeProperties['tm_hour'];
        							$thisMinute = $timeProperties['tm_min'];

        							if($lastHour == '')
        							{
        								$lastHour = $thisHour-1;
        							}

        							if($utcTimestamp != $expectedTime)
        							{
        								$numberToFill = ($utcTimestamp - $expectedTime)/$increment;
        								for($i = 1; $i <= $numberToFill; $i++)
        								{

        									if($timestampsDone === false)
        									{
        										$timestampArray[$pointID.':'.$channelID]['timestamps'][$tsInx] = date('H:i:s',$expectedTime);
        									}
        									$valueEnvelope[$pointID.':'.$channelID]['values'][$dateIndex][date('H:i:s',$expectedTime)] = '';
        									$expectedTime += $increment;
        									$lastHour = $thisHour;
        									$tsInx++;
        								}

        							}

        							if(($thisHour - $lastHour) == 2)
        							{
        								$timestampArray[$pointID.':'.$channelID]['dst']['direction'] = 'spring';
        								$timestampArray[$pointID.':'.$channelID]['dst'][$tsInx] = date('H:i:s',$utcTimestamp);;
        							}
        							elseif(($thisHour == $lastHour) && $thisMinute == 0)
        							{
        								$tcFlag = 'fall';
        							}

        							if($tcFlag == 'fall')
        							{
        								if($thisHour != $lastHour)
        								{
        									$tcFlag = '';
        								}
        								else
        								{
        									$timestampArray[$pointID.':'.$channelID]['dst']['direction'] = 'fall';
        									$timestampArray[$pointID.':'.$channelID]['dst'][$tsInx] = date('H:i:s',$utcTimestamp);
        								}

        							}

        							//we only need one set of timestamps -- this is a kludge.
        							if($timestampsDone === false)
        							{
        								$timestampArray[$pointID.':'.$channelID]['timestamps'][$tsInx] = date('H:i:s',$utcTimestamp);
        							}

        							$valueEnvelope[$pointID.':'.$channelID]['values'][$dateIndex][date('H:i:s',$utcTimestamp)] = $value['value'];

        							$lastHour = $thisHour;
        							$tsInx++;
        						}
        						$timestampsDone = true;
        					}
        				}

        				if($oMeterPoint->isGenerator() != 1 && $presentation != 'comparison' && $longTermFlag == false)
                        {
        					if($oBaselineSet->values() != '') // need to dig into this a bit --- without this check, event performance gets into here on charts that do not have events -- need to flip the logic to check for the event in the first place.
        					{
        						$envelope[$pointID.':'.$channelID]['maximumValueBaseline'] = $oBaselineSet->maximumValue();
                                $envelope[$pointID.':'.$channelID]['maximumValueDateBaseline'] = $oBaselineSet->maximumValueDateTime()->Format('Y-m-d H:i:s');
        						$envelope[$pointID.':'.$channelID]['minimumValueBaseline'] = $oBaselineSet->minimumValue();
        						$envelope[$pointID.':'.$channelID]['minimumValueDateBaseline'] = $oBaselineSet->minimumValueDateTime()->Format('Y-m-d H:i:s');
        						$envelope[$pointID.':'.$channelID]['averageValueBaseline'] = $oBaselineSet->averageValue();
        					}
        		        }
        //$this->preDebugger($valueEnvelope);
/*  ------------------------------EVENT ENVELOPE------------------------------ */

                $oBaseDateMonth = strlen($oBaseDate->month()) < 2 ? '0'.$oBaseDate->month() : $oBaseDate->month();
                $oBaseDateDay = strlen($oBaseDate->day()) < 2 ? '0'.$oBaseDate->day() : $oBaseDate->day();

                $originalEventBaseDate = $oBaseDateMonth.'-'.$oBaseDateDay.'-'.$oBaseDate->year();

                $mdrUser->pointChannels()->meterPoint($pointID)->RefreshEventDates();

                $eventDateStack = '';

                //$mdrUser->preDebugger($mdrUser->pointChannels()->meterPoint($pointID)->eventDates());

                $eventDateStack = $mdrUser->pointChannels()->meterPoint($pointID)->eventDates();

                if(is_array($eventDateStack) && array_key_exists($originalEventBaseDate,$eventDateStack))
                {

                    //$mdrUser->preDebugger($eventDateStack);

                    $startDateParts = explode('-',$eventDateStack[$originalEventBaseDate]['startDate']);
                    $longStartDate = $startDateParts[2].'-'.$startDateParts[0].'-'.$startDateParts[1].' '.$eventDateStack[$originalEventBaseDate]['startTime'];

                    $endDateParts = explode('-',$eventDateStack[$originalEventBaseDate]['endDate']);
                    $longEndDate = $endDateParts[2].'-'.$endDateParts[0].'-'.$endDateParts[1].' '.$eventDateStack[$originalEventBaseDate]['endTime'];

                    $particulars = $mdrUser->pointChannels()->meterPoint($pointID)->fetchEventParticulars(   $pointID,
                                                                            $channelID,
                                                                            $longStartDate,
                                                                            $longEndDate    );

                            //$mdrUser->preDebugger($particulars);
                            $eventEnvelope[$pointID.':'.$channelID]['startDate'] = $particulars ? $particulars['base']->StartDate : '';
                            $eventEnvelope[$pointID.':'.$channelID]['endDate'] = $particulars ? $particulars['base']->EndDate : '';

                            $eventEnvelope[$pointID.':'.$channelID]['restorationTime'] = $particulars ? $particulars['base']->RestorationTime : '';
                            $eventEnvelope[$pointID.':'.$channelID]['performance'] = $particulars['FCA']['performance'];
                            $eventEnvelope[$pointID.':'.$channelID]['pcr'] = $particulars['FCA']['pcr'];

                            $baseDispatch = $particulars ? date('Y-m-d H:',strtotime($particulars['base']->DispatchTime)) : '';
                            $baseMinutes = $particulars ? date('i', strtotime($particulars['base']->DispatchTime)) : '';
                            $minutes = $particulars ? (date('s', strtotime($particulars['base']->DispatchTime)) > 0 ? $baseMinutes + 1 : $baseMinutes) : '';

                            $eventEnvelope[$pointID.':'.$channelID]['dispatchTime'] = $baseDispatch.substr('0'.(ceil($minutes/5)*5),-2,2).':00';


                            $baseEffective = $particulars ? date('Y-m-d H:',strtotime($particulars['base']->EffectiveTime)) : '';
                            $baseMinutes = $particulars ? date('i', strtotime($particulars['base']->EffectiveTime)) : '';
                            $minutes = $particulars ? (date('s', strtotime($particulars['base']->EffectiveTime)) > 0 ? $baseMinutes + 1 : $baseMinutes) : '';

                            $eventEnvelope[$pointID.':'.$channelID]['effectiveTime'] = $baseEffective.substr('0'.(ceil($minutes/5)*5),-2,2).':00';

                            //$User->preDebugger($eventEnvelope);
                }
                else
                {
                    $eventEnvelope[$pointID.':'.$channelID]['startDate'] = '';
                    $eventEnvelope[$pointID.':'.$channelID]['endDate'] = '';
                }

/*  ------------------------------END EVENT ENVELOPE-------------------------- */
                }
        			else
        			{
                        $inz = 0;
        				$valueEnvelope[$pointID.':'.$channelID]['values'] = '';
                        $eventEnvelope[$pointID.':'.$channelID]['startDate'] = '';
        				$eventEnvelope[$pointID.':'.$channelID]['endDate'] = '';
                        $timestampArray[$pointID.':'.$channelID]['timestamps'] = '';
        			}
        		}

                
                //$this->preDebugger($valueEnvelope);

          		if($view != 'charts')
        		{
        			switch ($view)
        			{
        			case 'tabularData':
        				$presentation = $this->renderTabularData($envelope,$valueEnvelope,$timestampArray, $dateSpan, $baseDate, $view,$formUsed,$presentation,$action);
        			    break;
                    case 'tabularDataCSV':
                        $presentation = $this->renderCSVData($envelope,$valueEnvelope,$timestampArray, $dateSpan,$baseDate,$view,$formUsed,$presentation);
        			    break;
        			case 'exportTabularHourly':
        				$presentation = $this->renderCSVData($envelope,$valueEnvelope,$timestampArray, $dateSpan,$baseDate,$view,$formUsed,$presentation);
        				break;
        			case 'exportTabularFiveMinute':
        				$presentation = $this->renderCSVData($envelope,$valueEnvelope,$timestampArray, $dateSpan,$baseDate,$view,$formUsed,$presentation);
        				break;
        			case 'exportPricingHourly':
        				$presentation = $this->renderCSVPrices($envelope, $valueEnvelope, $timestampArray,$dateSpan, $baseDate, $view, rtrim($idString,','),$formUsed);
        			    break;
        			case 'exportPricingFiveMinute':
        				$presentation = $this->renderCSVPrices($envelope, $valueEnvelope, $timestampArray,$dateSpan, $baseDate, $view, rtrim($idString,','),$formUsed);
        			    break;
        			case 'summaryReports':
        			    $presentation = $this->renderSummaryReports($valueEnvelope);
        			    break;
        			}
        		}
        		else
        		{
                    //$this->preDebugger($valueEnvelope);
        			$presentation = $this->renderChart($envelope,$valueEnvelope,$eventEnvelope,$timestampArray,$presentation,$dateSpan,$baseDate,$action,$formUsed,$programName,$dstFlag,$adjustedBaselineString,$refresh);
        		}

              	return $presentation;

        }

    /*  ==========================================================================
        FUNCTION: renderTabularData()
        ========================================================================== */
        function renderTabularData($summaryData,$data,$timestamps,$dateSpan,$baseDate,$view,$formUsed,$presentation,$action)
        {
        	//mcb: for some reason, I decided it would be a good idea to stuff the pre-formatted header values into an array --- questioning whether that is a good idea now, but we need to table refactoring for later.
        	$tableArray = array();
        	$tableString = '';
            $message = '';
            $messageString = '';
        	$ids = '';
        	$price = '';

        	$colString = '';
        	$pricingType = '';
        	$hasTimestamps = false;
        	$numberOfTables = 0;

        	//comparison data does not require as extensive a restacking, so we bypass this first if/then when handling comparison

        	if($presentation != 'aggregate' && $presentation != 'comparison')
        	{
        		foreach($data as $id=>$point)
        		{
        			$hasPrices = false;
        			$hasBaseline = false;
        			$numberOfTables++;
        			$colspan = 1;
        			if($point['values'] == '')
        			{
                        $messageString .= $this->processError('no data', $summaryData[$id]['meterName']);
        			}
        			else
        			{
        			   $ids .= $id.',';

        				if(!empty($point['valuesBaseline']))
        			   {
        					$hasBaseline = true;
        					$colspan++;
        			   }

        			   if(!empty($point['pricing']))
        			   {
        					$hasPrices = true;
        					$colspan++;
        			   }
        			   foreach($timestamps[$id]['timestamps'] as $timestamp)
        				{
        					$tsString = date('m-d-Y H:i:s',strtotime($timestamp));

        					if(array_key_exists($timestamp,$point['values']))
        					{
        						$value = $point['values'][$timestamp];
        					}
        					else
        					{
        						$value = '';
        					}

        					$tableArray[$tsString][$summaryData[$id]['meterName']]['value'] = number_format($value,3,'.',',');

        					if($hasBaseline === true)
        					{
        						if(array_key_exists($timestamp,$point['valuesBaseline']))
        						{
        							$baseline = $point['valuesBaseline'][$timestamp];
        						}
        						else
        						{
        							$baseline = '';
        						}

        						$tableArray[$tsString][$summaryData[$id]['meterName']]['baseline'] = number_format($baseline,3,'.',',');
        					}

        					if($hasPrices === true)
        					{
        						foreach($point['pricing'] as $title=>$tsArray) //mcb this logic is up-side-down; another refactor opportunity; shouldn't have to repeatedly flip through this array
        						{
        							$pricingType = $title;
        							if(array_key_exists($timestamp,$tsArray))
        							{
        								$price = $tsArray[$timestamp];
        							}
        							else
        							{
        								$price = '';
        							}
        						}
        						$tableArray[$tsString][$summaryData[$id]['meterName']]['price'] = number_format($price,2,'.',',');


        					}
        				}

        				if($colspan > 1)
        				{
        					$colString = 'colspan="'.$colspan.'"';
        				}
        				else
        				{
        					$colString = '';
        				}
        				$headerString[$summaryData[$id]['meterName']]['titleCell'] = '<th '.$colString.'>'.$summaryData[$id]['meterName'].'</th>';

        				$headerString[$summaryData[$id]['meterName']]['dataCell'][]= '<th>Readings<br />('.$point['unit'].')</th>'; //mcb 2009.05.30
        				if($hasBaseline === true)
        				{
        					$headerString[$summaryData[$id]['meterName']]['dataCell'][] = '<th>Baseline<br />('.$point['unit'].')</th>'; //mcb 2009.05.30

        				}
        				if($hasPrices === true)
        				{
        					$headerString[$summaryData[$id]['meterName']]['dataCell'][] = '<th style="width: 100px;">'.$pricingType.'<br />($/MWH)</th>';

        				}
        			}

        		}
        	}
        	elseif($presentation != 'comparison')
        	{
        		$meterNames = '';

        		$interimArray = array();
        		foreach($data as $id=>$point)
        		{
        			if($point['values'] == '')
        			{
        				$messageString .= $this->processError('no data', $summaryData[$id]['meterName']);
        			}
        			else
        			{
        			   $ids .= $id.',';

        			   foreach($timestamps[$id]['timestamps'] as $timestamp)
        				{
        					if(array_key_exists($timestamp,$point['values']))
        					{
        						$interimArray[$timestamp][$id] = $point['values'][$timestamp];
        					}

        				}
        			   foreach($interimArray as $intTimestamp=>$valueSet)
        			   {
        				   $value = 0;
        				   foreach($valueSet as $valueData)
        				   {
        					   $value += $valueData;
        				   }
        					$tsString = date('m-d-Y H:i:s',strtotime($intTimestamp));
        				   $tableArray['aggregate'][$tsString]['value'] = number_format($value,3,'.',',');
        			   }
        			   $meterNames .= $summaryData[$id]['meterName'].', ';
        			}
        		}
        		$headerString[] = '<tr><th colspan="2">Aggregate of:<br />'.rtrim($meterNames,', ').'</th></tr>';
        		$headerString[] = '<tr>';
        		$headerString[] = '<th style="width: 145px;">Date / Time</th>';
        		$headerString[]= '<th>Readings<br />(KWH)</th>';
        		$headerString[] = '</tr>';
        	}

        	if($presentation != 'aggregate' && $presentation != 'comparison')
        	{
        		$headerCells = '';
        		$tableString .= '<table align="center" cellpadding="5" cellspacing="0" border="1">';
        		$tableString .= '<tr>';
        		$tableString .= '<th rowspan="2" style="width: 145px;" >Date / Time</th>';

        		foreach($headerString as $headerSet)
        		{
        			$tableString .= $headerSet['titleCell'];

        			foreach($headerSet['dataCell'] as $cellValue)
        			{
        				$headerCells .= $cellValue;
        			}
        		}

        		$tableString .= '</tr>';
        		$tableString .= '<tr>';
        		$tableString .= $headerCells;
        		$tableString .= '</tr>';

        		foreach($tableArray as $timestamp=>$meters)
        		{
        			$tableString .= '<tr><td>'.$timestamp.'</td>';
        			foreach($meters as $meter=>$valueNames)
        			{
        				foreach($valueNames as $valueName=>$valueItem)
        				{
        					$tableString .= '<td style="text-align: right;">'.$valueItem.'</td>';
        				}

        			}
        			$tableString .= '</tr>';
        		}
        		$tableString .= '</table>';
        	}
        	elseif($presentation == 'comparison')
        	{

        		//comparisons rely on having a single point selected, so we can take some liberties with that.
        		foreach($summaryData as $idString=>$summaryStack)
        		{
                    if($idString != 'message')
                    {
                        $ids = $idString.',';
                        $pointID = $summaryStack['pointID'];
                        $meterName = $summaryStack['meterName'];

                    }
        		}

        		$colspan = 1;
        		$dateCells = '';
        		$colString = '';
        		$readingHeadingString = '';
        		foreach($data as $point=>$dataStack)
        		{

        			foreach($dataStack['values'] as $date=>$valueSet)
        			{

        				$dateCells .= '<th>'.$date.'</th>';
        				$readingHeadingString .= '<th>('.$dataStack['unit'].')</th>';//mcb 2009.05.30
        				$colspan++;
        				foreach($valueSet as $time=>$value)
        				{
        					$tableArray[$time][$date] = number_format($value,3,'.',',');
        				}

        			}
        		}

        		$cellString = '';

        		foreach($tableArray as $timestamp=>$values)
        		{
        			$cellString .= '<tr><td>'.$timestamp.'</td>';

        			foreach($values as $value)
        			{

        				$cellString .= '<td style="text-align: right;">'.$value.'</td>';
        			}
        			$cellString .= '</tr>';
        		}

        		if($colspan > 1)
        		{
        			$colString = 'colspan="'.$colspan.'"';
        		}
        		$tableString .= '<table align="center" cellpadding="5" cellspacing="0" border="1">';
        		$tableString .= '<tr>';
        		$tableString .= '<th '.$colString.'>'.$meterName.'</th>';
        		$tableString .= '</tr>';
        		$tableString .= '<tr>';
        		$tableString .= '<th rowspan="2">Time</th>'.$dateCells;
        		$tableString .= '</tr>';
        		$tableString .= '<tr>';
        		$tableString .= $readingHeadingString;
        		$tableString .= '</tr>';
        		$tableString .= $cellString;
        		$tableString .= '</table>';
        	}
        	else
        	{
        		 foreach($tableArray as $dataSet)
        		{
        			$tableString .= '<table align="center" cellpadding="5" cellspacing="0" border="1">';

        			foreach($headerString as $headerSet)
        			{
        					$tableString .= $headerSet;
        			}

        			foreach($dataSet as $ts=>$items)
        			{
        				$tableString .= '<tr>';
        				$tableString .= '<td>'.$ts.'</td>';
        				foreach($items as $value)
        				{
        					$tableString .= '<td style="text-align: right;">'.$value.'</td>';
        				}

        				$tableString .= '</tr>';
        			}

        			$tableString .= '</table><br />';

        		}
        	}

        	if($action != 'modalPrint')
        	{

        		$pointString = rtrim($ids,',');
        		$queryString = '?ID='.$pointString.'&Date='.$baseDate.'&Span='.$dateSpan.'&view='.$view;
        		$url = 'frmMagnify.i.inc.php'.$queryString.'&pres=tabularData&action=modalPrint&formUsed='.$formUsed.'&isTabular=true';

        		$tableString = '
        		<script type="text/javascript" src="mootools/smoothbox.js"></script>
        			<table width="900" align="center" cellpadding="0" cellspacing="0" border="0">
        				<tr>
        					<td>
        					<table align="right" cellpadding="0" cellspacing="0" border="0">
        						<tr>
        							<td class="print" style="text-align: left; "><a id="printTip" onClick="TB_show(\'Full Sized Table for Printing\', \'' . htmlspecialchars($url.'&height=500&width=750') . '\', \'\');" target="_blank"><img src="_template/images/blank.gif" height="31" width="31" border="0"  /></a></td>
        							<td><img src="_template/images/blank.gif" height="31" width="15" border="0" alt="spacer" /></td>
        							<td class="export"><a href="#" id="exportTableTip" onClick="processTabularData(\''.$formUsed.'\',\''.$pointString.'\',true);" ><img src="_template/images/blank.gif" height="31" width="31" border="0" /></a></td>
        						</tr>
        						<tr>
        							<td colspan="3" style="padding: 10px;">
        							 <script type="text/javascript">

        							function returnToChart()
                                    {
                                        var dataReturn_chart = $(\'dataReturn_res\').setStyle(\'display\', \'block\');
                                        var dataReturn_table = $(\'dataReturn_table\').setStyle(\'display\', \'none\');

                                    };
        							</script>
        							<a href="#" onClick="returnToChart();" ><strong style="font-size: 13px;">Return to Chart</strong></a></td>
        						</tr>
        					</table>
        						<script type="text/javascript">

        						</script>
        					</td>
        				</tr>
        				<tr>
        					<td style="text-align: center;">
        						'.$tableString.'
        					</td>
        				</tr>
        			</table>
        		';
        	}

        	if($messageString != '')
        		{
        			$message = '<div class="error" style="align: center; width: 600px; margin-left: 60px;">'.$messageString.'</div>';
        		}

        	return $message.$tableString;

        }


/*  CSV -----------------------------------------------------------------------------------------------------------------------------------------------
    --------------------------------------------------------------------------------------------------------------------------------------------------- */
    /*  ==========================================================================
        FUNCTION: renderCSVData()
        ========================================================================== */
        function renderCSVData($summaryData,$data,$timestamps,$dateSpan,$baseDate,$view,$formUsed,$presentation)
        {
        	//this is a relative duplicate of renderTabularData -- only diff is the formatting of the output (commas and line terminators instead of <td>s and <tr>s

        	$tableArray = array();
        	$tableString = '';
            $message = '';
            $messageString = '';
        	$ids = '';
        	$price = '';

        	$colString = '';
        	$pricingType = '';
        	$hasTimestamps = false;
        	$numberOfTables = 0;

        	$delm = ',';
        	$eol = "\n";

        	//comparison data does not require as extensive a restacking, so we bypass this first if/then when handling comparison

        	if($presentation != 'aggregate' && $presentation != 'comparison')
        	{
        		foreach($data as $id=>$point)
        		{
        			$hasPrices = false;
        			$hasBaseline = false;
        			$numberOfTables++;
        			$colspan = 1;
        			if($point['values'] == '')
        			{
        				$messageString .= $this->processError('no data', $summaryData[$id]['meterName']);
        			}
        			else
        			{
        			   $ids .= $id.',';

        				if(!empty($point['valuesBaseline']))
        			   {
        					$hasBaseline = true;
        					$colspan++;
        			   }

        			   if(!empty($point['pricing']))
        			   {
        					$hasPrices = true;
        					$colspan++;
        			   }
        			   foreach($timestamps[$id]['timestamps'] as $timestamp)
        				{
        					$tsString = date('m-d-Y H:i:s',strtotime($timestamp));

        					if(array_key_exists($timestamp,$point['values']))
        					{
        						$value = $point['values'][$timestamp];
        					}
        					else
        					{
        						$value = '';
        					}

        					$tableArray[$tsString][$summaryData[$id]['meterName']]['value'] = $value;

        					if($hasBaseline === true)
        					{
        						if(array_key_exists($timestamp,$point['valuesBaseline']))
        						{
        							$baseline = $point['valuesBaseline'][$timestamp];
        						}
        						else
        						{
        							$baseline = '';
        						}

        						$tableArray[$tsString][$summaryData[$id]['meterName']]['baseline'] = $baseline;
        					}

        					if($hasPrices === true)
        					{
        						foreach($point['pricing'] as $title=>$tsArray) //mcb this logic is up-side-down; another refactor opportunity; shouldn't have to repeatedly flip through this array
        						{
        							$pricingType = $title;
        							if(array_key_exists($timestamp,$tsArray))
        							{
        								$price = $tsArray[$timestamp];
        							}
        							else
        							{
        								$price = '';
        							}
        						}
        						$tableArray[$tsString][$summaryData[$id]['meterName']]['price'] = $price;


        					}
        				}

        				if($colspan > 1)
        				{
        					$colString = 'colspan="'.$colspan.'"';
        				}
        				else
        				{
        					$colString = '';
        				}
        				$headerString[$summaryData[$id]['meterName']]['titleCell'] = $summaryData[$id]['meterName'];
        				$headerString[$summaryData[$id]['meterName']]['dataCell'][]= 'Readings ('.$point['unit'].')';//mcb 2009.05.30
        				if($hasBaseline === true)
        				{
        					$headerString[$summaryData[$id]['meterName']]['dataCell'][] = 'Baseline ('.$point['unit'].')';//mcb 2009.05.30

        				}
        				if($hasPrices === true)
        				{
        					$headerString[$summaryData[$id]['meterName']]['dataCell'][] = $pricingType.' ($/MWH)';

        				}
        			}

        		}
        	}
        	elseif($presentation != 'comparison')
        	{
        		$meterNames = '';

        		$interimArray = array();
        		foreach($data as $id=>$point)
        		{
        			if($point['values'] == '')
        			{
        				$messageString .= $this->processError('no data', $summaryData[$id]['meterName']);
        			}
        			else
        			{
        			   $ids .= $id.',';

        			   foreach($timestamps[$id]['timestamps'] as $timestamp)
        				{
        					if(array_key_exists($timestamp,$point['values']))
        					{
        						$interimArray[$timestamp][$id] = $point['values'][$timestamp];
        					}

        				}
        			   foreach($interimArray as $intTimestamp=>$valueSet)
        			   {
        				   $value = 0;
        				   foreach($valueSet as $valueData)
        				   {
        					   $value += $valueData;
        				   }
        					$tsString = date('m-d-Y H:i:s',strtotime($intTimestamp));
        				   $tableArray['aggregate'][$tsString]['value'] = $value;
        			   }
        			   $meterNames .= $summaryData[$id]['meterName'].'; ';
        			}
        		}
        		$headerString = 'Aggregate of: '.rtrim($meterNames,'; ').$eol;
        		$headerString .= 'Date / Time'.$delm;
        		$headerString .= 'Readings ('.$point['unit'].')';//mcb 2009.05.30
        	}

        /** now we stitch it all together **/

        	if($presentation != 'aggregate' && $presentation != 'comparison')
        	{
        		$headerCells = '';

        		$tableString = $delm;

        		foreach($headerString as $headerSet)
        		{
        			$tableString .= '"'.$headerSet['titleCell'].'"'.str_repeat($delm,count($headerSet['dataCell']));

        			foreach($headerSet['dataCell'] as $cellValue)
        			{
        				$headerCells .= $cellValue.$delm;
        			}
        		}

        		$tableString .= $eol;
            	$tableString .= 'Date / Time'.$delm;
        		$tableString .= $headerCells;
        		$tableString .= $eol;

        		foreach($tableArray as $timestamp=>$meters)
        		{
        			$cellSet = '';
        			$tableString .= $timestamp.$delm;
        			foreach($meters as $meter=>$valueNames)
        			{
        				foreach($valueNames as $valueName=>$valueItem)
        				{
        					$cellSet .= $valueItem.$delm;
        				}

        			}
        			$tableString .= rtrim($cellSet,$delm);
        			$tableString .= $eol;
        		}
        	}
        	elseif($presentation == 'comparison')
        	{
        		//comparisons rely on having a single point selected, so we can take some liberties with that.
        		foreach($summaryData as $idString=>$summaryStack)
        		{
                    if($idString != 'message'){
                        $pointID = $summaryStack['pointID'];
                        $meterName = $summaryStack['meterName'];
                    }
        		}

        		$colspan = 1;
        		$dateCells = '';
        		$colString = '';
        		$readingHeadingString = 'Time'.$delm;

        		foreach($data as $point=>$dataStack)
        		{
        			foreach($dataStack['values'] as $date=>$valueSet)
        			{
        				$dateCells .= $date.$delm;
        				$readingHeadingString .= '('.$dataStack['unit'].')'.$delm;//mcb 2009.05.30
        				$colspan++;
        				foreach($valueSet as $time=>$value)
        				{
        					$tableArray[$time][$date] = $value;
        				}

        			}
        		}

        		$cellString = '';

        		foreach($tableArray as $timestamp=>$values)
        		{
        			$cellSet = '';
        			$cellString .= $timestamp.$delm;
        			foreach($values as $value)
        			{
        				$cellSet .= $value.$delm;
        			}
        			$cellString .= rtrim($cellSet,$delm);
        			$cellString .= $eol;
        		}

        		if($colspan > 1)
        		{
        			$colString = 'colspan="'.$colspan.'"';
        		}


        		$tableString .= $meterName.$delm;
        		$tableString .= $eol;

        		$tableString .= rtrim($dateCells,$delm);
        		$tableString .= $eol;

        		$tableString .= rtrim($readingHeadingString,$delm);
        		$tableString .= $eol;
        		$tableString .= $cellString;

        	}
        	else
        	{
        		$tableString = $headerString;
        		$tableString .= $eol;
        		 foreach($tableArray as $dataSet)
        		{
        			foreach($dataSet as $ts=>$items)
        			{
        				$cellSet = '';
        				$tableString .= $ts.$delm;
        				foreach($items as $value)
        				{
        					$cellSet .= $value.$delm;
        				}
        				$tableString .= rtrim($cellSet,$delm);
        				$tableString .= $eol;
        			}

        		}
        	}

        	if($messageString != '')
        		{
        			$message = $messageString.$eol;
        		}

        	return $message.$tableString;

        }

    /*  ==========================================================================
        FUNCTION: renderCSVPrices()
        ========================================================================== */
        function renderCSVPrices($summary, $data, $timestamps, $summaryData, $dateSpan, $baseDate, $view, $id)
        {
        	$delm=',';
        	$eol = "\n";
        	$valueSet = array();
        	$labelRow = 'Date / Time';

        	if(count($summary) > 1)
        	{
        		$nameRow = $delm;
        	}
        	else
        	{
        		$nameRow = '';
        	}
        	$output = '';

        foreach($timestamps as $id=>$timestampArray)
        {
        	$priceLabelArray = array_keys($data[$id]['pricing']);

        	$nameRow .= $summary[$id]['meterName'].$delm;
        	$labelRow .= '$/MWH'.$delm;

        	foreach($priceLabelArray as $inx=>$priceLabel)
        	{
        		foreach($timestampArray['timestamps'] as $inx=>$timestamp)
        		{
        			if(array_key_exists($timestamp,$data[$id]['pricing'][$priceLabel]))
        			{
        				$value = $data[$id]['pricing'][$priceLabel][$timestamp];

        				$valueSet[$timestamp][$id] = $value;

        			}
        			else
        			{
        				$valueSet[$timestamp][$id] = '';
        			}

        		}
        	}
        }
        $valueString = '';
        foreach($valueSet as $ts=>$values)
        {
        	$valueString = $ts.$delm;
        	foreach($values as $id=>$value)
        	{
        		$valueString .= $value.$delm;
        	}
        	$output .= rtrim($valueString,$delm).$eol;

        }

        		$nameRow = rtrim($nameRow,$delm).$eol;
        		$labelRow = rtrim($labelRow,$delm).$eol;

        		$output = $nameRow.$labelRow.$output;

        		return $output;

        }

    /*  ==========================================================================
        FUNCTION: renderCSVEventData()
        ========================================================================== */
        function renderCSVEventData($summaryData,$data,$eventDates,$timestamps,$dateSpan,$formUsed,$programName,$adjustedBaselineString,$dstFlag)
        {
            //$this->preDebugger('got renderCSVEventData');
            //$this->preDebugger($data);
            $eventTimeStamps = '';

        	foreach($data as $id=>$point)
        	{
        		$tableArray = '';
        		$etsInx = 0;

        		if($point['values'] == '')
        		{
                    $message = $this->processError('no data', $summaryData[$id]['meterName']);

        			$summary = '';
        			$chart = '';
        		}
        		else
        		{

        			if($eventDates[$id]['startDate'] == '')
        			{
                        $message = $this->processError('no event',$summaryData[$id]['meterName'],false);
        				$summary = '';
        				$chart = '';
        			}
        			else
        			{
        				$pointString = $id;
        				$urlString = '';
        				$graphCounter = 1;
        				$graphValues = '';
        				$graphTimestamps = '';
        				$deltaValues = '';
        				$reductionValues = '';
        				$axisMax = 0;
        				$axisMin = 0;

        				foreach($timestamps[$id]['timestamps'] as $timestamp)
        				{
        					if(($timestamp >= $eventDates[$id]['startDate']) && ($timestamp <= $eventDates[$id]['endDate']))
        					{
        						if($timestamp != $eventDates[$id]['startDate'])
        						{
        							$eventTimeStamps[$etsInx] = $timestamp;
        							$etsInx++;
        						}
        					}
        				}

        				$compiledTimestamps = $this->compileTimestamps($id,$eventTimeStamps,$eventDates,$dateSpan,'event','individual',$formUsed,$dstFlag);

        				$graphTimestamps = $compiledTimestamps['timestamps'];

        				$hasEvent = $compiledTimestamps['eventFlag'];


        				foreach($eventTimeStamps as $timestamp)
        				{
        					if(array_key_exists($timestamp,$point['values']))
        					{
        						$value = $point['values'][$timestamp];
        					}
        					else
        					{
        						$value = 0;
        					}

                            if(array_key_exists($timestamp,$point['percentages']))
        					{
        						$percentValue = $point['percentages'][$timestamp];
        					}
        					else
        					{
        						$percentValue = 0;
        					}

                            $tableArray[$timestamp]['intervalSet'] = $value;
                            $tableArray[$timestamp]['percentageIntervalSet'] = $percentValue;
        				}

                        //$this->preDebugger($tableArray);
        				$output = $this->buildEventSummaryCSV($tableArray,$eventTimeStamps,$eventDates[$id],$summaryData[$id]['meterName'],$summaryData[$id]['committedReduction'],$point['isGenerator'],$programName[$id],$eventDates[$id]['adjustedBaselineString'],$point['unit'],$summaryData[$id]['assetIdentifier']);
        			}
        		}
        	}
        	return $output;
        }



    /*  ==========================================================================
        FUNCTION: renderChart()
        ========================================================================== */
        function renderChart($summaryData,$data,$eventDates,$timestamps,$presentation,$dateSpan,$baseDate,$action, $formUsed,$programName,$dstFlag,$adjustedBaselineString,$refresh = false)
        {
            /*print '<pre>';
            print_r($data);
            print '</pre>';*/
            $message = '';

        	$inxMult = 0;

        	if($dateSpan == 1)
        	{
        		$inxMult = 1;
        	}

        	if($dateSpan > 1 && $dstFlag === true)
        	{
        		$legendMargin = 25;
        	}
        	else
        	{
        		$legendMargin = 15;
        	}

        	$displayString = '';
        	$summary = '';
        	$marginTop = 5;

        	$marginLeft = 50;

        	$marginRight = 20;
        	$flashWidth = 850;

            $baseline = '';
            $baselineSettings = '';
            $baselineValues = '';
            $priceValues = '';
            $priceTitle = '';
            $dateTitle = '';

        	if($dateSpan == 1)
        	{
        		if($formUsed == 'eventsForm')
        		{
        			$labelFrequency = 2;
        			$xAngle = 45;
        		}
        		else
        		{
        			$labelFrequency = 12;
        			$xAngle = 90;
        		}

        	}
        	else
        	{
        		$labelFrequency = 24;
        		$xAngle = 45;
        	}
        	if($action == 'modalPrint' || $action == 'printEvent' || $action == 'modalDisplay')
        	{
        		$plotColor = '#FFFFFF';
        		$fontColor = '#000000';
        	}
        	else
        	{
        		$plotColor = '#CBCCD9';
        		$fontColor = '#FFFFFF';
        	}

            if($dateSpan == 1 && $presentation != 'comparison')
            {
                $dateTitle = date('m-d-Y',strtotime($baseDate));
                //if($baseDate == date('Y-m-d')) $dateTitle .= '<br /><span style="font-size: 12px; font-weight: normal;">'.date('H:i:s').'</span>';
            }
            else
            {
                $uxDay =  60 * 60 * 24;
                $startDate =  strtotime($baseDate);
                $endDate = $startDate + (($dateSpan - 1) * $uxDay);
                $dateTitle = date('m-d-Y',$startDate).' through '.date('m-d-Y',$endDate);
            }

        /**********************************************************************/

        	if($presentation == 'individual')
        	{
        		$marginBottom = 100;

        		$flashHeight = 320 + $marginBottom + $legendMargin;

        		$aryColors = array(1=>'#1B6097',2=>'#7C1787',3=>'#874417',4=>'#228717',5=>'#207DBC',6=>'#BC5E20',7=>'#17877C',8=>'#871722',9=>'#172287',10=>'#87175A',11=>'#877C17',12=>'#178744',13=>'#441787',14=>'#5A8717',15=>'#3B9DDE',16=>'#DE7C3B');

        		if($formUsed == 'eventsForm')
        		{
        			$eventTimeStamps = '';

        			if($action != 'modalDisplay')
        			{
        				$flashWidth = 700;
        			}

        			$legendY = $flashHeight-20;

        			foreach($data as $id=>$point)
        			{
                        $tableArray = '';
                        $etsInx = 0;
        				$deltaValues = ''; //mcb 05.03.08 add this line to production once approved
        				$reductionValues = ''; //mcb 05.03.08 add this line to production once approved

        				if($point['values'] == '')
        				{
        					$messageString .= $this->processError('no data', $summaryData[$id]['meterName'],true);
        					$summary = '';
        					$chart = '';
        				}
        				else
        				{
        					if($eventDates[$id]['startDate'] == '')
        					{
                                $message = $this->processError('no event', $summaryData[$id]['meterName'],true);
        						$summary = '';
        						$chart = '';
        					}
        					else
        					{
        						$message = '';
        						$pointString = $id;
        						$urlString = '';
        						$graphCounter = 1;
        						$graphValues = '';
        						$graphTimestamps = '';
        						$deltaValues = '';
        						$reductionValues = '';
        						$axisMax = 0;
        						$axisMin = 0;
                            	$eventTimeStamps = '';
        						$timeStampsForSummary = '';
        						$etsSummaryInx = 0;

        						$start = date('Y-m-d H:i:s',strtotime($eventDates[$id]['startDate']) - 2100);
        						$end =  date('Y-m-d H:i:s',strtotime($eventDates[$id]['endDate']) + 1800);
        						foreach($timestamps[$id]['timestamps'] as $timestamp)
        						{
                                    if(($timestamp >= $start) && ($timestamp <= $end))
                                    {
                                        if($timestamp != $start)
                                        {
                                            $eventTimeStamps[$etsInx] = $timestamp;
                                            $etsInx++;
                                        }
                                    }
        							if(($timestamp >= $eventDates[$id]['startDate']) && ($timestamp <= $eventDates[$id]['endDate']))
                                    {
                                        if($timestamp != $eventDates[$id]['startDate'])
                                        {
                                            $timeStampsForSummary[$etsSummaryInx] = $timestamp;
                                            $etsSummaryInx++;
                                        }

                                    }
        						}

        						$compiledTimestamps = $this->compileTimestamps($id,$eventTimeStamps,$eventDates,$dateSpan,$action,$presentation,$formUsed,$dstFlag);

        						$graphTimestamps = $compiledTimestamps['timestamps'];

        						$hasEvent = $compiledTimestamps['eventFlag'];

        						$emptySet = true;

        						foreach($eventTimeStamps as $timestamp)
        						{
        							if(array_key_exists($timestamp,$point['values']))
        							{
        								$value = $point['values'][$timestamp];
        								if($value != '')
        								{
        									$emptySet = false;
        								}
        							}
        							else
        							{
        								$value = '';
        							}

                                    //if($point['isGenerator'] == 0)
                                    //{
                                        if(array_key_exists($timestamp,$point['valuesBaseline']))
            							{
            								$valueBaseline = $point['valuesBaseline'][$timestamp];
            							}
            							else
            							{
            								$valueBaseline = 0;
            							}
                                        $tableArray[$timestamp]['baselineSet'] = $valueBaseline;
                                    //}
                                    $tableArray[$timestamp]['intervalSet'] = $value;
        						}

        						if($emptySet === true)
        						{
        							$message = $this->processError('no data', $summaryData[$id]['meterName'],true);
        							$chart = '';
        							$summary = '';
        						}
        						else
        						{
        							$labelCount = 0;
        							$inx = 0;

        							foreach($tableArray as $timestamp=>$valuePair)
        							{
        								if($point['isGenerator'] == 0)
        								{
        									if(is_numeric($valuePair['intervalSet']))
        									{
        										$delta = $valuePair['baselineSet'] - $valuePair['intervalSet'];
        									}
        									else
        									{
        										$delta = '';
        									}

        									$deltaValues .= '<value xid=\"'.$inx.'\">'.$delta.'</value>';
        									$reductionValues .= '<value xid=\"'.$inx.'\">'.($summaryData[$id]['committedReduction']).'</value>';

        									if($axisMax < $delta)
        									{
        										$axisMax = $delta;
        									}
        									if($axisMin > $delta)
        									{
        										$axisMin = $delta;
        									}
        								}
        								else
        								{
        									$delta = $valuePair['intervalSet'];

        									if($delta == 0)
        									{
        										$delta = '';
        									}
        									$deltaValues .= '<value xid=\"'.$inx.'\">'.$delta.'</value>';

        									$reductionValues .= '<value xid=\"'.$inx.'\">'.($summaryData[$id]['committedReduction']).'</value>';
                                            $numbersUsed[] = $delta;
                                            $numbersUsed[] = $summaryData[$id]['committedReduction'];
        									if($axisMax < $delta)
        									{
        										$axisMax = $delta;
        									}

        									if($axisMin > $delta)
        									{
        										$axisMin = $delta;
        									}

                                            if($axisMin > $summaryData[$id]['committedReduction'])
                                            {
                                                $axisMin = $summaryData[$id]['committedReduction'];
                                            }
        								}
        									$inx++;
        									$labelCount++;
        							}

        							if(($summaryData[$id]['committedReduction']) > $axisMax)
        							{
        								$axisMax = ($summaryData[$id]['committedReduction']);
        							}

        							if(($summaryData[$id]['committedReduction']) < $axisMin)
        							{
        								$axisMin = ($summaryData[$id]['committedReduction']);
        							}

        							//adding a percentage for a little visual padding
        							if($axisMax == 0)
        							{
        								$axisMax = $axisMin * .1;
        							}
        							$axisMin *= .995;
        							$axisMax *= 1.05;

                                    
        							$chartID = str_replace(':','',$id);


        							if($labelCount > 36)
        							{
        								$labelFrequency = 6;
        							}
        							$deltaSettings = $this->compileEventGraphSettings(1, $aryColors[1], '', 'left', 10, 100,1);
        							$reductionSettings = $this->compileEventGraphSettings(2, '#980000', '', 'right', 0, 100,2);


        							$deltaGraph = '<graph gid=\"1\" title=\"'.$summaryData[$id]['meterName'].' Performance\" axis=\"left\">'.$deltaValues.'</graph>';
        							$reductionGraph = '<graph gid=\"2\" title=\"Estimated Capability\" axis=\"right\">'.$reductionValues.'</graph>';

        							$urlString = '?ID='.$pointString.'&Date='.$baseDate.'&Span='.$dateSpan.'&pres='.$presentation;

        							$chartSettings = $this->compileEventSettings($deltaSettings.$reductionSettings,$labelFrequency,$xAngle,$marginBottom,$marginTop,$marginLeft,80,$plotColor,$fontColor,$axisMax,$axisMin,$legendY);
        							$chartValues = '<chart><series>'.$graphTimestamps.'</series><graphs>'.$deltaGraph.$reductionGraph.'</graphs></chart>';
        							$chart = $this->compileChart($chartSettings, $chartValues, $flashHeight, $flashWidth, $chartID, $pointString, $urlString, $action, $dateTitle,$formUsed,$point['unit'],count($data),$refresh);
        							$summary = $this->buildEventSummary($tableArray,$timeStampsForSummary,$eventDates[$id],$summaryData[$id]['meterName'],$summaryData[$id]['committedReduction'],$point['isGenerator'],$programName[$id],$summaryData[$id]['adjustedBaselineString'],$point['unit'],$summaryData[$id]['assetIdentifier']);
        						}
        					}
        				}
                        
        				$displayString .= $message.$chart.$summary.'</div><!-- refreshResponse -->'; //this ending div starts in compileChart()

        			}

        		}
        		else
        		{
        			foreach($data as $id=>$point)
        			{
        				$marginRight = 20;
        				if($presentation == 'individual' && ($action == 'basic' || $action == ''))
        				{
        					$action = 'basicIndividual';
        				}
        				$flashWidth = 800;

        				if($point['values'] == '')
        				{
        					$message = $this->processError('no data', $summaryData[$id]['meterName'],true);

        					$summary = '';
        					$chart = '';
        				}
        				else
        				{
        					$pointString = $id;
        					$urlString = '';
        					$graphCounter = 1;
        					if($dateSpan == 1)
        					{
        						$graphValues = '<value xid=\"0\"></value>';
        					}
        					else
        					{
        						$graphValues = '';
        					}


        					$graphTimestamps = '';
        					$deltaValues = '';
                            $baseline = '';


        					$baselineSettings = '';


                            if($dateSpan == 1)
        					{
        						$baselineValues = '<value xid=\"0\"></value>';
        					}
        					else
        					{
        						$baselineValues = '';
        					}
                            $priceSettings = '';
                            $priceGraph = '';
        					$nullFlag = false;

        					$compiledTimestamps = $this->compileTimestamps($id,$timestamps[$id]['timestamps'],$eventDates,$dateSpan, $action,$presentation,$formUsed,$dstFlag);
        					$graphTimestamps = $compiledTimestamps['timestamps'];

        					$inx = 0;
        						 /*
        					print '<pre>';
        					print '<div style="color: orange;">
        					print_r($timestamps[$id]);
        					print '</div>';
        					print '</pre>';
        					*/
        					foreach($timestamps[$id]['timestamps'] as $timestampValue)
        					{
        						if(array_key_exists($timestampValue,$point['values']))
        						{
        							$value = $point['values'][$timestampValue];
        						}
        						else
        						{
        							$value = '';
        						}

        						$graphValues .= '<value xid=\"'.($inx + $inxMult).'\">'.$value.'</value>';
        						$inx++;
        					}

        					$chartID = str_replace(':','',$id);

        					$chartSettings = $this->compileGraphSettings($graphCounter, $aryColors[$graphCounter]);


        						$baselineSettings = $this->compileGraphSettings($graphCounter + 1, '#980000');

            					$baselineValues = '';
            					$inx = 0;

            					foreach($timestamps[$id]['timestamps'] as $timestampValue)
        						{
        							if(array_key_exists($timestampValue,$point['valuesBaseline']))
        							{
        								$baselineValue = $point['valuesBaseline'][$timestampValue];
        							}
        							else
        							{
        								$baselineValue = '';
        							}

        							$baselineValues .= '<value xid=\"'.($inx + $inxMult).'\">'.$baselineValue.'</value>';
        							$inx++;
        						}

                            if($point['pricing'] != '')
                            {

                                foreach($point['pricing'] as $title=>$tsArray)
                                {
                                    $priceTitle = $title;
                                    foreach($timestamps[$id]['timestamps'] as $inx => $timestampValue)
                                    {
                                        if(array_key_exists($timestampValue,$tsArray))
                                        {
                                            $priceValues .= '<value xid=\"'.($inx + $inxMult).'\">'.$tsArray[$timestampValue].'</value>';
                                        }
                                        else
                                        {
                                            $priceValues .= '<value xid=\"'.($inx + $inxMult).'\"></value>';
                                        }
                                        $priceGraph = '<graph gid=\"'.($graphCounter + 2).'\" title=\"'.$priceTitle.'\">'.$priceValues.'</graph>';
                                    }

                                }
        				        $priceSettings = $this->compilePriceGraphSettings($graphCounter + 2, '#339900', 1);
                                $marginRight += 45;

                            }

        					$chartID = str_replace(':','',$id);

        					$flashWidth += $marginRight;
        					$urlString = '?ID='.$pointString.'&Date='.$baseDate.'&Span='.$dateSpan.'&pres='.$presentation;
        					$chartSettings = $this->compileSettings($chartSettings.$baselineSettings.$priceSettings,$labelFrequency,$xAngle,$marginBottom,$marginTop,$marginLeft,$marginRight,$plotColor,$fontColor,$legendMargin,$legendMargin);
        					$chartValues = '<chart><series>'.$graphTimestamps.'</series><graphs><graph gid=\"'.$graphCounter.'\" title=\"'.$summaryData[$id]['meterName'].' '.$point['unit'].'\">'.$graphValues.'</graph><graph gid=\"'.($graphCounter + 1).'\" title=\"'.$summaryData[$id]['meterName'].' Baseline '.$point['unit'].'\">'.$baselineValues.'</graph>'.$priceGraph.'</graphs></chart>';//mcb 2009.05.30
        					$chart = $this->compileChart($chartSettings, $chartValues, $flashHeight, $flashWidth, $chartID, $pointString, $urlString, $action, $dateTitle,$formUsed,$point['unit'],count($data),$refresh);

        					if($action == 'modalDisplay')
        					{
        						$summary = '';
        					}
        					else
        					{
        						$summary = $this->buildSummary($summaryData[$id],$action,$summaryData[$id]['adjustedBaselineString'],$point['unit']);
        					}
        					$message = '';
        				}

        				$displayString .= $message.$chart.$summary;
        			}

        		}


        	}
        /**********************************************************************/
        	if($presentation == 'allInOne')
        	{
        		$pointString = '';
        		$chartSettings = '';
        		$chartValues = '';
        		$graphValueSet = '';
        		$chartSettings = '';
        		$baselineSettings = '';
        		$graphCounter = 1;
        		$urlString = '';

                $alreadyRun = false;

        		$summary = '';

        		$marginBottom = 70 + ((count($data)) * 20);

        		$flashHeight = 320 + $marginBottom + $legendMargin;


        		$aryAllInOneColors = array(1=>'#1B6097',2=>'#2994DB',3=>'#7C1787',4=>'#C929DB',5=>'#874417',6=>'#DB7029',7=>'#228717',8=>'#3BDB29',9=>'#207DBC',10=>'#5EAEE4',11=>'#BC5E20',12=>'#E4935E',13=>'#17877C',14=>'#29DBC9',15=>'#871722',16=>'#DB293B',17=>'#172287',18=>'#293BDB',19=>'#87175A',20=>'#DB2994',21=>'#877C17',22=>'#DBC929',23=>'#178744',24=>'#29DB70',25=>'#441787',26=>'#7029DB',27=>'#5A8717',28=>'#94DB29',29=>'#3B9DDE',30=>'#92C8ED',31=>'#DE7C3B',32=>'#EDB692');

        		foreach($data as $id=>$point)
        		{
        			if($point['values'] != '')
        			{
        				$pointString .= $id.',';
        				$graphValues = '';
        				$baselineValues = '';
                        if($alreadyRun == false)
                        {
                            $compiledTimestamps = $this->compileTimestamps($id,$timestamps[$id]['timestamps'],$eventDates,$dateSpan,$action,$presentation,$formUsed,$dstFlag);
                            $alreadyRun = true;
                        }
        				$graphTimestamps = $compiledTimestamps['timestamps'];
        				$inx = 0;

        				foreach($point['values'] as $value)
        				{
        					$graphValues .= '<value xid=\"'.($inx + $inxMult).'\">'.$value.'</value>';
        					$inx++;
        				}
        /*
                        if($point['isGenerator'] == 0)
                        {
            				$inx = 0;

            				foreach($point['valuesBaseline'] as $value)
            				{
            					$baselineValues .= '<value xid=\"'.($inx + $inxMult).'\">'.$value.'</value>';
            					$inx++;
            				}
                        }
        */
        				if($point['isGenerator'] == 0)
                            {

        						$baselineSettings = $this->compileGraphSettings($graphCounter + 1, '#980000');

            					$baselineValues = '';
            					$inx = 0;

            					foreach($timestamps[$id]['timestamps'] as $timestampValue)
        						{
        							if(array_key_exists($timestampValue,$point['valuesBaseline']))
        							{
        								$baselineValue = $point['valuesBaseline'][$timestampValue];
        							}
        							else
        							{
        								$baselineValue = '';
        							}

        							$baselineValues .= '<value xid=\"'.($inx + $inxMult).'\">'.$baselineValue.'</value>';
        							$inx++;
        						}
                            }

        				$chartSettings .= $this->compileGraphSettings($graphCounter, $aryAllInOneColors[$graphCounter]);
        				$graphValueSet .= '<graph gid=\"'.$graphCounter.'\" title=\"'.$summaryData[$id]['meterName'].' '.$point['unit'].'\">'.$graphValues.'</graph>';//mcb 2009.05.30

        				$graphCounter++;

                        if($point['isGenerator'] == 0)
                        {
            				$chartSettings .= $this->compileGraphSettings($graphCounter, $aryAllInOneColors[$graphCounter]);
            				$graphValueSet .= '<graph gid=\"'.$graphCounter.'\" title=\"'.$summaryData[$id]['meterName'].' Baseline '.$point['unit'].'\">'.$baselineValues.'</graph>';//mcb 2009.05.30

            				$graphCounter++;
        	            }

        			}
        			if($action == 'modalDisplay')
        			{
        				$summary = '';
        			}
        			else
        			{
        				$summary .= $this->buildSummary($summaryData[$id],$action,$summaryData[$id]['adjustedBaselineString'],$point['unit']);
        			}
        		}


        			$pointString = rtrim($pointString,',');

        			$chartID = str_replace(':','',$id);

        			$urlString = '?ID='.$pointString.'&Date='.$baseDate.'&Span='.$dateSpan.'&pres='.$presentation;

        			$chartValues .= '<chart><series>'.$graphTimestamps.'</series><graphs>'.$graphValueSet.'</graphs></chart>';
        			$chartSettings = $this->compileSettings($chartSettings,$labelFrequency,$xAngle,$marginBottom,$marginTop,$marginLeft,$marginRight,$plotColor,$fontColor,$legendMargin);
                    $chart = $this->compileChart($chartSettings, $chartValues, $flashHeight,900, $chartID, $pointString, $urlString, $action, $dateTitle,$formUsed,$point['unit'],count($data),$refresh);


        		$displayString = $message.$chart.$summary;
        	}

        /**********************************************************************/
        	if($presentation == 'aggregate')
        	{

        		$pointString = '';
        		$marginBottom = 90;

        		$flashHeight = 320 + $marginBottom + $legendMargin;
        		$urlString = '';
                $tableArray = array();
                $message = '';

                $inx = 0;


                foreach($eventDates as $iny=>$valueSet)
                {
                    //$this->preDebugger($valueSet);

                    $newEventDates[$inx]['startDate'] = $valueSet['startDate'];
                    $newEventDates[$inx]['endDate'] = $valueSet['endDate'];
                        $inx++;

                }

                //sort($newEventDates);

        		$aryColors = array(1=>'#1B6097',2=>'#7C1787',3=>'#874417',4=>'#228717',5=>'#207DBC',6=>'#BC5E20',7=>'#17877C',8=>'#871722',9=>'#172287',10=>'#87175A',11=>'#877C17',12=>'#178744',13=>'#441787',14=>'#5A8717',15=>'#3B9DDE',16=>'#DE7C3B');

        		$hasError = false;
        		$dataAvailable = false;
        		$messageList = '';

        		foreach($data as $id=>$point)
        		{
        			if(($point['values'] == '') || (max($point['values']) == 0))
        			{
        				$messageList = '<li>'.$summaryData[$id]['meterName'].'</li>';
        				$hasError = true;
        			}
        			else
        			{
        				$dataAvailable = true;
        				$pointString .= $id.',';
        				$graphCounter = 1;
        				$graphValues = '';

        				$compiledTimestamps = $this->compileTimestamps($id,$timestamps[$id]['timestamps'],$newEventDates,$dateSpan,$action,$presentation,$formUsed,$dstFlag);
        				$graphTimestamps = $compiledTimestamps['timestamps'];

        				foreach($timestamps[$id]['timestamps'] as $timestamp)
        				{
        					if(array_key_exists($timestamp,$point['values']))
        					{
        						$value = $point['values'][$timestamp];
        					}
        					else
        					{
        						$value = '';
        					}

        					$tableArray[$timestamp][$id] = $value;
        				}

        				if($action == 'advanced' | $action == 'modalPrint')
        				{
        					$summary .= $this->buildSummary($summaryData[$id],$action,$summaryData[$id]['adjustedBaselineString'],$point['unit']);
        				}


        				if($dateSpan == 1)
        				{
        					$inx = 1;
        				}
        				else
        				{
        					$inx = 0;
        				}


        				foreach($tableArray as $timestamp=>$values)
        				{
        					$valueAggregate = 0;
        					foreach($values as $value)
        					{
        						$valueAggregate += $value;
        					}

        					if($valueAggregate == 0)
        					{
        						$valueAggregate = '';
        					}
        					$graphValues .= '<value xid=\"'.$inx.'\">'.$valueAggregate.'</value>';
        					$inx++;

        				}


                    }

        		}
        		if($hasError === true)
        		{
        			$message = '<hr /><br /><div class="error" style="align: center; width: 600px; margin-left: 60px;">
        				On the selected date, there is no data available for:
        					<ul>
        					'.$messageList.'
        					</ul>
        				</div><br /><hr />';
        		}

        		if($dataAvailable === true)
        		{
        			$chartID = str_replace(':','',$id);
        			$pointString = rtrim($pointString,',');


        			$chartSettings = $this->compileGraphSettings($graphCounter, $aryColors[$graphCounter]);
        			$urlString = '?ID='.$pointString.'&Date='.$baseDate.'&Span='.$dateSpan.'&pres='.$presentation;

        			$chartSettings = $this->compileSettings($chartSettings,$labelFrequency,$xAngle,$marginBottom,$marginTop,$marginLeft,$marginRight,$plotColor,$fontColor,$legendMargin);
        			$chartValues = '<chart><series>'.$graphTimestamps.'</series><graphs><graph gid=\"'.$graphCounter.'\" title=\"Aggregate\">'.$graphValues.'</graph></graphs></chart>';

        			$chart = $this->compileChart($chartSettings, $chartValues, $flashHeight, $flashWidth, $chartID, $pointString, $urlString, $action, $dateTitle,$formUsed,$point['unit'],count($data),$refresh);
                 }

        		$displayString = $message.$chart.$summary;
        	}
        /**********************************************************************/
        	if($presentation == 'comparison')
        	{
        		/** this relies VERY heavily on there being only a single point, for now. No reason we can't present multiple charts, one for each point **/
        		/** that being said, it needs to be refactored to act like it only deals with a single point **/
        		$displayString = '';

        		$pointString = '';
        		$chartSettings = '';
        		$chartValues = '';
        		$graphValueSet = '';
        		$chartSettings = '';
        		$baselineSettings = '';
        		$graphCounter = 1;
        		$urlString = '';
        		$summary = '';
        		$dtInx = 0;

        		$marginBottom = 60 + ((count($data)) * 15);
        		$flashHeight = 428 - $marginBottom + $legendMargin;

        		$aryColors = array(1=>'#1B6097',2=>'#7C1787',3=>'#874417',4=>'#228717',5=>'#207DBC',6=>'#BC5E20',7=>'#17877C',8=>'#871722',9=>'#172287',10=>'#87175A',11=>'#877C17',12=>'#178744',13=>'#441787',14=>'#5A8717',15=>'#3B9DDE',16=>'#DE7C3B');

        		foreach($data as $id=>$point)
        		{
        			if($point['values'] == '')
        			{
        				$message = $this->processError('no data', $summaryData[$id]['meterName'],true);
        				$summary = '';
        				$chart = '';
        			}
        			else
        			{
        				$dateTitle = $summaryData[$id]['meterName'];
        				$pointString .= $id.',';
        				$graphValues = '';
        				$baselineValues = '';

        				$compiledTimestamps = $this->compileTimestamps($id,$timestamps[$id]['timestamps'],$eventDates,$dateSpan,$action,$presentation,$formUsed,$dstFlag);

        				$series = $compiledTimestamps['timestamps'];

        				$graphValues = '<value xid=\"0\"></value>';

        				$dateString = '';
        				foreach($point['values'] as $date=>$dataSet)
        				{
        					$dateString .= $date.',';

        					$chartSettings .= $this->compileGraphSettings($graphCounter, $aryColors[$graphCounter]);
        					$xid = 1;
        					foreach($timestamps[$id]['timestamps'] as $timestampID=>$timestampValue)
        					{

        						if(array_key_exists($timestampValue,$dataSet))
        						{
        							$graphValues .= '<value xid=\"'.$xid.'\">'.$dataSet[$timestampValue].'</value>';
        						}
        						else
        						{
        							$graphValues .= '<value xid=\"'.$xid.'\"></value>';
        						}
        						$xid++;
        					}

        					$graphValueSet .= '<graph gid=\"'.$graphCounter.'\" title=\"'.$date.'\">'.$graphValues.'</graph>';

        					$graphCounter++;

        				}

        				if($action == 'modalDisplay')
        				{
        					$summary = '';
        				}
        				else
        				{
        //					$summary = $this->buildSummary($summaryData[$id],'true');
        				}

        			}

        			$pointString = rtrim($pointString,',');
        			$dateString = rtrim($dateString,',');

        			$chartID = str_replace(':','',$id);

        			$urlString = '?ID='.$pointString.'&Date='.$dateString.'&Span='.$dateSpan.'&pres='.$presentation;

        			$chartValues .= '<chart><series>'.$series.'</series><graphs>'.$graphValueSet.'</graphs></chart>';
        			$chartSettings = $this->compileSettings($chartSettings,$labelFrequency,$xAngle,$marginBottom,$marginTop,$marginLeft,$marginRight,$plotColor,$fontColor,$legendMargin);
        			//$chart = $this->compileChart($chartSettings, $chartValues, $flashHeight,$flashWidth, $chartID.'baseline',$urlString, $action);
                    $chart = $this->compileChart($chartSettings, $chartValues, $flashHeight,$flashWidth, $chartID, $pointString, $urlString, $action, $dateTitle,$formUsed,$point['unit'],count($data),$refresh);

        			$displayString = $message.$chart.$summary;

        		}

        	}

            if ($summaryData['message']['isDisplayable']) $displayString = $summaryData['message']['text'] . $displayString;

        	return $displayString;

        }

    /*  ==========================================================================
        FUNCTION: compileTimestamps()
        ========================================================================== */
        function compileTimestamps($id,$timestamps,$eventDates,$dateSpan,$action,$presentation,$formUsed,$dstFlag)
        {
            //$this->preDebugger('entering');
            //$this->preDebugger($id,'red');
            //$this->preDebugger($eventDates[$id]);

        	$inxMult = 0;

        	if($dateSpan == 1)
        	{
        		$graphTimestamps = '<value xid=\"0\">00</value>';
        		$inxMult = 1;
        	}
        	else
        	{
        		$graphTimestamps = '';
        	}

        	$eventStart = '';
        	$eventEnd = '';
        	$eventStart = '';
        	$eventEnd = '';
            $timestampArray = array();

            if($presentation != 'aggregate' && $presentation != 'comparison')
            {
                if($eventDates[$id]['startDate'] != '')
                {
                    //$this->preDebugger('got true','orange');
                    $hasEvent = true;
                }
                else
                {
                    //$this->preDebugger('got false','orange');
                    $hasEvent = false;
                }
            }
        	elseif($presentation == 'comparison')
        	{
        		$hasEvent = false;
        	}
            else
            {
                if($eventDates[0]['startDate'] != '')
                {
                    $hasEvent = true;
                }
                else
                {
                    $hasEvent = false;
                }
            }

        	if($formUsed == 'eventsForm')
        	{
                $eventAlpha = 15;
                $eventColor = '#FF6701';
        		$graphTimestamps = '';
        		//$this->preDebugger($eventDates,'#980000');
        		foreach($timestamps as $inx=>$value)
        		{
                    //$this->preDebugger($value,'purple');

                    //$this->preDebugger($eventDates[$id]['effectiveTime'],'#980000');
                    $eventString = '';
                    if($value == $eventDates[$id]['effectiveTime'])
                    {
                        $eventString = 'event_start=\"'.$eventDates[$id]['effectiveTime'].'\" event_color=\"'.$eventColor.'\" event_description=\"Event: '.$eventDates[$id]['effectiveTime'].' to '.$eventDates[$id]['restorationTime'].' \" event_alpha=\"'.$eventAlpha.'\"';
                    }

                    
                    if($value == $eventDates[$id]['restorationTime'])
                    {
                        $eventString = 'event_end=\"'.$eventDates[$id]['effectiveTime'].'\"';
                    }

                    
        			$graphTimestamps .= '<value xid=\"'.$inx.'\" '.$eventString.'>'.date('H:i:s',strtotime($value)).'</value>';
        		}
        	}
        	else
        	{
                $eventAlpha = 15;
                $eventColor = '#FF6701';
        		foreach($timestamps as $inx=>$value)
        		{
                    //$this->preDebugger($timestamps);
        			if($presentation != 'comparison')
        			{
        				$dateTimeParts = explode(' ',$value);
        				$dateElement = $dateTimeParts[0];
        				$timeElement = $dateTimeParts[1];
        				$dateParts = explode('-',$dateElement);
        				$dayElement = $dateParts[2];
        				$monthElement = $dateParts[1];
        				$yearElement = substr($dateParts[0],-2);
        				$timeParts = explode(':',$timeElement);
        				$hourElement = $timeParts[0];
        				$minuteElement = $timeParts[1];
        				$secondElement = $timeParts[2];
        			}
        			else
        			{
        				$timeElement = $value;
        				$timeParts = explode(':',$timeElement);
        				$hourElement = $timeParts[0];
        				$minuteElement = $timeParts[1];
        				$secondElement = $timeParts[2];
        			}

                    //$this->preDebugger('heading into decision fork','purple');
        			$eventString = '';
        			if($hasEvent)
        			{
                        if($presentation != 'aggregate')
                        {
                            if(($dateSpan == 1 && $value == $eventDates[$id]['effectiveTime']) || ($dateSpan > 1 && $value == date('Y-m-d H:00:00',strtotime($eventDates[$id]['effectiveTime']))))
                            {
                                $eventString .= 'event_start=\"'.$eventDates[$id]['effectiveTime'].'\" event_color=\"'.$eventColor.'\" event_description=\"Event: '.$eventDates[$id]['effectiveTime'].' to '.$eventDates[$id]['restorationTime'].' \" event_alpha=\"'.$eventAlpha.'\"';
                            }

                            if(($dateSpan == 1 && $value == $eventDates[$id]['restorationTime']) || ($dateSpan > 1 && $value == date('Y-m-d H:00:00',strtotime($eventDates[$id]['restorationTime']))))
                            {
                                $eventString .= 'event_end=\"'.$eventDates[$id]['effectiveTime'].'\"';
                            }
                        }
                        else
                        {
                            foreach($eventDates as $inz=>$eventValue)
                            {
                                if(($dateSpan == 1 && $value == $eventValue['startDate']) || ($dateSpan > 1 && $value == date('Y-m-d H:00:00',strtotime($eventValue['startDate']))))
                				{
                                    $eventString .= 'event_start=\"'.$eventValue['startDate'].'\" event_color=\"'.$eventColor.'\" event_description=\"Event: '.$eventValue['startDate'].' to '.$eventValue['endDate'].' \" event_alpha=\"'.$eventAlpha.'\"';
                				}

                				if(($dateSpan == 1 && $value == $eventValue['endDate']) || ($dateSpan > 1 && $value == date('Y-m-d H:00:00',strtotime($eventValue['endDate']))))
                				{
                					$eventString .= 'event_end=\"'.$eventValue['startDate'].'\"';
                				}
                            }
                        }

                        if($dateSpan == 1)
                        {
                            if(date('i',strtotime($value)) == 0)
                            {
                                $graphTimestamps .= '<value xid=\"'.($inx + $inxMult).'\" '.$eventString.'>'.$hourElement.'</value>';
                            }
                            else
                            {
                                $graphTimestamps .= '<value xid=\"'.($inx + $inxMult).'\" '.$eventString.'>'.$timeElement.'</value>';
                            }
                        }
                        else
                        {
                            if(date('H',strtotime($value)) == 1)
                            {
                                $graphTimestamps .= '<value xid=\"'.($inx + $inxMult).'\" '.$eventString.'>'.($monthElement.'-'.$dayElement.'-'.$yearElement).'</value>';
                            }
                            else
                            {
                                $graphTimestamps .= '<value xid=\"'.($inx + $inxMult).'\" '.$eventString.'>'.$hourElement.'</value>';
                            }
                        }

        			}
                    else
                    {
                        if($dateSpan == 1)
                        {

                            if(date('i',strtotime($value)) == 0)
                            {
                                $graphTimestamps .= '<value xid=\"'.($inx + $inxMult).'\" '.$eventString.'>'.$hourElement.'</value>';
                            }
                            else
                            {
                                $graphTimestamps .= '<value xid=\"'.($inx + $inxMult).'\" '.$eventString.'>'.$timeElement.'</value>';
                            }
                        }
                        else
                        {
        					if($dstFlag === true)
        					{
        						$prettyDate = ($monthElement.'-'.$dayElement.'-'.$yearElement).' ['.$hourElement.']';
        						$prettyTime = ($monthElement.'-'.$dayElement.'-'.$yearElement).' ['.$hourElement.']';
        					}
        					else
        					{
        						$prettyDate = ($monthElement.'-'.$dayElement.'-'.$yearElement);
        						$prettyTime = $hourElement;
        					}
                            if(date('H',strtotime($value)) == 1)
                            {

                                $graphTimestamps .= '<value xid=\"'.($inx + $inxMult).'\" '.$eventString.'>'.$prettyDate.'</value>';
                            }
                            else
                            {
                                $graphTimestamps .= '<value xid=\"'.($inx + $inxMult).'\" '.$eventString.'>'.$prettyTime.'</value>';
                            }
                        }
                    }

        		}
        	}

        	$timestampArray['timestamps'] = $graphTimestamps;
        	$timestampArray['eventFlag'] = $hasEvent;
        	return $timestampArray;
        }

    /*  ==========================================================================
        FUNCTION: compileChart()
        ========================================================================== */
        function compileChart($settings, $data, $flashHeight = 242, $flashWidth = 435, $chartID, $pointString, $queryString, $action, $title,$formUsed,$unit, $pointCount = 1, $refresh = false)
    	{
    		if($action == 'modalPrint' || $action == 'printEvent')
    		{
    			$preloadColor = '#FFFFFF';
    			$flashTrans = '';
    			$flashHeight = $pointCount > 1 ? '450px' : '390px';
    			$flashWidth = $pointCount > 1 ? '700px' : '650px';

    		}
    		elseif($action == 'modalDisplay')
    		{
    			$preloadColor = '#FFFFFF';
    			$flashTrans = '';
    			$flashHeight = '540px';
    			$flashWidth = '860px';
    		}
    		else
    		{
    			$preloadColor = '#CBCCD9';
    			$flashTrans = 'so.addParam("wmode", "transparent");';
    		}
    		 $chartString = '
    			<!-- amline script-->

    				<div id="flashcontent'.$pointString.'" >
    					<strong>If this message displays for more than a couple of minutes,<br /> you may need to upgrade your Flash Player</strong>
    				</div>
    				<script type="text/javascript" src="amline/swfobject.js"></script>

    				<script type="text/javascript">
    					// <![CDATA[
    					var so = new SWFObject("amline/amline.swf", "amline", "'.$flashWidth.'", "'.$flashHeight.'", "8", "'.$preloadColor.'");
    					so.addVariable("path", "amline/");
    					so.addVariable("chart_settings", escape("'.$settings.'"));
    					so.addVariable("chart_data", escape("'.$data.'"));
    					so.addVariable("preloader_color", "'.$preloadColor.'");
    					'.$flashTrans.'
    					so.write("flashcontent'.$pointString.'");
    					// ]]>
    				</script>

    			<!-- end of amline script -->
    		';

             $padding = $flashHeight - 320;

    		if($action == 'event' || $action == 'advanced' || $action == 'basic' || $action == 'basicIndividual' || $action == 'printEvent' || $action == '' || $action == 'modalPrint'  || $action == 'modalDisplay')
    		{
                $priceLabel = '';
                $colSpan = 2;
                if($action == 'basicIndividual' || ($action == 'modalPrint' && $pointCount == 1)  || ($action == 'modalDisplay' && $pointCount == 1))
                {
                    $priceLabel = '
                        <td style="font-size: 12px; vertical-align: middle; width: 30px;">$/MWH<br /><img src="_template/images/blank.gif" height="'.$padding.'" width="1" border="0"  /></td>
                    ';
                    $colSpan = 3;
                }
    			if($action == 'event')
    			{
    				$actionLabel = 'printEvent';
    			}
    			else
    			{
    				$actionLabel = 'modalPrint';
    			}
    			if($action != 'modalPrint' || $action != 'printEvent'  || $action == 'modalDisplay')
    			{
                    $printUNID =  'print_'.uniqid();
                    $magnifyUNID =  'magnify_'.uniqid();
                    $exportUNID =  'export_'.uniqid();
                    $tabularUNID =  'tabular_'.uniqid();

    				if($action == 'event' || $action == 'printEvent' || $action == 'modalPrint')
    				{
    					$tabularLink = '<div id="'.$tabularUNID.'"></div>';
    				}
    				else
    				{
    					$tabularLink = '
    					<td>
    						<img src="_template/images/blank.gif" height="31" width="15" border="0" alt="spacer" />
    					</td>
    						<td class="tabular">
    						<a href="#" id="'.$tabularUNID.'" class="tabularTip" onClick="processTabularData(\''.$formUsed.'\',\''.$pointString.'\',false);" >
                                <img src="_template/images/blank.gif" height="31" width="31" border="0" />
                            </a>
    					</td>';
    				}

/*<a id="'.$printUNID.'" onClick="TB_show(\'Full Sized Table for Printing\', \'frmMagnify.i.inc.php'.$queryString.'&action='.$actionLabel.'&formUsed='.$formUsed.'&TB_iframe=true&height=500&width=750\', \'\');"  style="text-align: center; vertical-align: middle;"  target="_blank"  >*/
/*<a id="'.$magnifyUNID.'" onClick="TB_show(\'Full Sized Table for Viewing\', \'frmMagnify.i.inc.php'.$queryString.'&action=modalDisplay&formUsed='.$formUsed.'&TB_iframe=true&height=500&width=950\', \'\');" target="_blank">*/

                    //print $refresh ? 'true' : 'false';
    				$linkString = $refresh ? null : '
                    <tr>
    					<td style="text-align: center; padding-left: 20px;">
                        <script type="text/javascript" src="mootools/smoothbox.js"></script>
    					<table align="right" cellspacing="0" border="0">
    						<tr>
    							<td class="print">
    								    <a id="'.$printUNID.'" onClick="TB_show(\'Full Sized Table for Printing\', \'frmMagnify.i.inc.php'.$queryString.'&action='.$actionLabel.'&formUsed='.$formUsed.'&TB_iframe=true&height=500&width=750\', \'\');"  style="text-align: center; vertical-align: middle;"  target="_blank"  >
    									<img src="_template/images/blank.gif" height="31" width="31" border="0" alt="print" />
    								</a>
    							</td>
    							<td><img src="_template/images/blank.gif" height="31" width="15" border="0" alt="spacer" /></td>
    							<td class="magnify">
    								<a id="'.$magnifyUNID.'" onClick="TB_show(\'Full Sized Table for Viewing\', \'frmMagnify.i.inc.php'.$queryString.'&action=modalDisplay&formUsed='.$formUsed.'&TB_iframe=true&height=500&width=950\', \'\');" target="_blank">
    									<img src="_template/images/blank.gif" height="31" width="31" border="0"  />
    								</a>

    							</td>
    							<td><img src="_template/images/blank.gif" height="31" width="15" border="0" alt="spacer" /></td>
    							<td class="export">
    								<a href="#" id="'.$exportUNID.'" class="exportTip" onClick="processTabularData(\''.$formUsed.'\',\''.$pointString.'\',true);" >
    									<img src="_template/images/blank.gif" height="31" width="31" border="0" />
    								</a>
    							</td>
    							'.$tabularLink.'
    						</tr>
    					</table>

    					<script type="text/javascript">
                                window.addEvent(\'domready\', function(){
                                    var printChartTip = new Tips($("'.$printUNID.'"));
                                    $("'.$printUNID.'").store("tip:title", "Full Sized Chart for Printing");
                                    $("'.$printUNID.'").store("tip:text", "Upon selection, chart will resize for optimal printing");

                                    var magnifyTip = new Tips($("'.$magnifyUNID.'"));
                                    $("'.$magnifyUNID.'").store("tip:title", "Full Sized Chart for Viewing");
                                    $("'.$magnifyUNID.'").store("tip:text", "Upon selection, chart will resize for optimal viewing.");

                                    var exportTip = new Tips($("'.$exportUNID.'"));
                                    $("'.$exportUNID.'").store("tip:title", "CSV Output");
                                    $("'.$exportUNID.'").store("tip:text", "Upon selection, data is transformed to a CSV file format for saving or opening immediately in MS-Excel.");

    								var tabularTip = new Tips($("'.$tabularUNID.'"));
    								$("'.$tabularUNID.'").store("tip:title", "Tabular Data");
    								$("'.$tabularUNID.'").store("tip:text", "Upon selection, data is transformed to an on-screen tabular data format.");

                                })
                            </script>

    					</td>
    				</tr>
    			   ';

    			}
    			else
    			{
    				$linkString = '';
    			}

    			if($action == 'modalDisplay')
    			{
    				$titleLabel = '';
    			}
    			else
    			{
    				$titleLabel = '
    					<tr>
    						<td colspan="'.$colSpan.'"><div style="text-align: right; font-weight: bold;">'.$title.'</div></td>
                        </tr>
    				';
    			}

    			if($action != 'modalDisplay')
    			{

    			//mcb 2009.05.30

                    if(!$refresh)
                    {
                        //<div id="refreshResponse"> starts here, ends after the original compileChart call
                        return '
                            <table width="700" align="center" cellpadding="0" cellspacing="0" border="0">
                                '.$linkString.'
                            </table>
                            <div id="refreshResponse">
                                <table width="700" align="center" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td><img src="_template/images/blank.gif" height="1" width="1" border="0"  /></td>
                                        <td style="font-size: 12px; text-align: center;">Hours</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px; vertical-align: middle; width: 20px;">'.$unit.'<br /><img src="_template/images/blank.gif" height="'.$padding.'" width="1" border="0"  /></td>
                                        <td>
                                        '.$chartString.'
                                        </td>
                                        '.$priceLabel.'
                                    </tr>
                                    '.$titleLabel.'
                                </table>

                        ';
                    }
                    else
                    {
                        return '
                                <table width="700" align="center" cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td><img src="_template/images/blank.gif" height="1" width="1" border="0"  /></td>
                                        <td style="font-size: 12px; text-align: center;">Hours</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px; vertical-align: middle; width: 20px;">'.$unit.'<br /><img src="_template/images/blank.gif" height="'.$padding.'" width="1" border="0"  /></td>
                                        <td>
                                        '.$chartString.'
                                        </td>
                                        '.$priceLabel.'
                                    </tr>
                                    '.$titleLabel.'
                                </table>
                        ';
                    }
    			}
    			else
    			{
    			//mcb 2009.05.30
    				return '
    					<table align="center" cellpadding="0" cellspacing="0" border="0">
    						<tr>
    							<td>&nbsp;</td>
    							<td style="font-size: 12px; text-align: center;">Hours</td>
    						</tr>
    						<tr>
    							<td style="font-size: 12px; vertical-align: middle; width: 20px;">'.$unit.'<br /><img src="_template/images/blank.gif" height="'.$padding.'" width="1" border="0"  /></td>
    							<td>
    							'.$chartString.'
    							</td>
    							'.$priceLabel.'
    						</tr>
    					</table>
    				';
    			}
    		}
    		else
    		{
    		//mcb 2009.05.30
    			return '
                <table align="center" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td>&nbsp;</td>
                        <td style="font-size: 12px; text-align: center;">Hours</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; vertical-align: middle; width: 20px;"><br /><img src="_template/images/blank.gif" height="'.$padding.'" width="1" border="0"  />'.$unit.'</td>
                        <td style="text-align: left;">
                        '.$chartString.'
                        <div style="text-align: right; font-weight: bold;">'.$title.'</div>
                        </td>
                    </tr>
                </table>
                ';
    		}

    	}

    /*  ==========================================================================
        FUNCTION: buildSummary()
        ========================================================================== */
        function buildSummary($data,$action,$adjustedBaselineString,$unit)
        {
            //$this->preDebugger($data);
            $baselineInfo = '';
        //mcb 2009.05.30
            $currentInfo = '
                    <td width="50%">
                   <table align="center" width="295" cellpadding="3" cellspacing="0" border="0"  style="border: 1px solid;">
        				<tr>
        					<th colspan="2" style="border-bottom: 1px solid; text-align: center;">Current Demand</th>
        				</tr>
        				<tr>
        					<td style="text-align: left;">Max. Demand ('.$unit.') </td><td style="text-align: right;">'.number_format($data['maximumValue'],3,'.',',').'<br /><span style="font-size: 0.75em">'.$data['maximumValueDate'].'</span></td>
        				</tr>
        				<tr>
        					<td style="text-align: left;">Min. Demand ('.$unit.')</td><td style="text-align: right;">'.number_format($data['minimumValue'],3,'.',',').'<br /><span style="font-size: 0.75em">'.$data['minimumValueDate'].'</span></td>
        				</tr>
        				<tr>
        					<td style="text-align: left;">Avg. Demand ('.$unit.')</td><td style="text-align: right;">'.number_format($data['averageValue'],3,'.',',').'</td>
        				</tr>
        			</table>
                    </td>
                ';



            if(array_key_exists('maximumValueBaseline',$data))
            {
        	//mcb 2009.05.30
                $baselineInfo = '
                    <td width="50%">
                    <table align="center" width="295" cellpadding="3" cellspacing="0" border="0" style="border: 1px solid;">
                        <tr>
                            <th colspan="2" style="border-bottom: 1px solid;">Baseline</th>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Max. Baseline Value ('.$unit.')</td><td style="text-align: right;">'.number_format($data['maximumValueBaseline'],3,'.',',').'<br /><span style="font-size: 0.75em">'.$data['maximumValueDateBaseline'].'</span></td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Min. Baseline Value ('.$unit.')</td><td style="text-align: right;">'.number_format($data['minimumValueBaseline'],3,'.',',').'<br /><span style="font-size: 0.75em">'.$data['minimumValueDateBaseline'].'</span></td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Avg. Baseline Value ('.$unit.')</td><td style="text-align: right;">'.number_format($data['averageValueBaseline'],3,'.',',').'</td>
                        </tr>
                        '.$adjustedBaselineString.'
                    </table>
                    </td>
                ';
            }

        	$leftPadding = '0px';

            $realTimePriceDate = isset($data['realTimePriceDate']) ? $data['realTimePriceDate'] : 'N/A';

        	return '
        		<center>
                <div style="padding-top: 15px; padding-left: '.$leftPadding.';">
        		<table align="center" cellpadding="3" cellspacing="0" border="0" style="border: 1px solid;">
        			<tr>
        				<th colspan="4" style="border-bottom: 1px solid #FFFFFF;">'.$data['meterName'].'<br />(Asset: '.$data['assetIdentifier'].')</th>
        			</tr>
        			<tr>
        				<td style="width: 150px; padding-left: 5px; text-align: left;">Real Time Price</td><td style="width: 175px;">'.$data['realTimePrice'].' <span style="font-size: 0.75em">'.$realTimePriceDate.'</span></td>
        				<td style="width: 150px; padding-left: 5px; text-align: left; border-left: 1px solid #FFFFFF;">Price Source</td><td style="width: 175px;">'.$data['priceSource'].'</td>
        			</tr>
        			<tr>
        				<td style="width: 150px; padding-left: 5px; text-align: left;">Peak Price</td><td style="width: 175px;">'.$data['peakPrice'].' <span style="font-size: 0.75em">'.$data['peakDate'].'</span></td>
        				<td style="width: 150px; padding-left: 5px; text-align: left; border-left: 1px solid #FFFFFF;">Estimated Capability</td><td style="width: 175px;">'.$data['committedReduction'].' KW</td>
        			</tr>
        			<tr>
        				<td style="width: 150px; padding-left: 5px; text-align: left;">Registered Program</td><td style="width: 175px;">'.$data['registeredProgram'].'</td>
        				<td style="width: 150px; padding-left: 5px; text-align: left; border-left: 1px solid #FFFFFF;">First Read</td><td style="width: 175px;">'.$data['firstRead'].'</td>
        			</tr>
        			<tr>
        				<td style="width: 150px; padding-left: 5px; text-align: left;">Dispatch Zone</td><td style="width: 175px;">'.$data['zone'].'</td>
        				<td style="width: 150px; padding-left: 5px; text-align: left; border-left: 1px solid #FFFFFF;">Last Read</td><td style="width: 175px;">'.$data['lastRead'].'</td>
        			</tr>
        		</table>

                <br />
        		<table align="center" width="600" cellpadding="0" cellspacing="0" border="0">
                    <tr>'
                        .$currentInfo.$baselineInfo.'
                    </tr>
        		</table>
                </div>
        		</center>
        		<br /><br />
        	';
        }


/*  EVENTS --------------------------------------------------------------------------------------------------------------------------------------------
    --------------------------------------------------------------------------------------------------------------------------------------------------- */
    /*  ==========================================================================
        FUNCTION: gatherEvent()
        ========================================================================== */
        function gatherEvent($action,$points,$eventBaseDate,$dateSpan,$presentation,$view,$connection,$formUsed,$User,$originalEventBaseDate,$refresh = false)
        {
            /*
            $User->preDebugger('ACTION: '.$action);
            $User->preDebugger('POINTS: ');
            $User->preDebugger($points);
            $User->preDebugger('EVENTBASEDATE: '.$eventBaseDate);
            $User->preDebugger('DATESPAN: '.$dateSpan);
            $User->preDebugger('PRESENTATION: '.$presentation);
            $User->preDebugger('VIEW: '.$view);
            $User->preDebugger('FORMUSED: '.$formUsed);
            $User->preDebugger('ORIGINALEVENTBASEDATE: '.$originalEventBaseDate);

            $User->preDebugger('===================================================================');
            */
            $output = '';
            $idString = '';
            $oBaseDate = new CrsDate($eventBaseDate);
        	$dstFlag = false;
        	$aryUnits = array('KWh'=>'KW', 'kVarh'=>'kVar'); //mcb 2009.05.30

            $timestampArray = array();

          	foreach($points as $pointToGather=>$state)
        	{
        		$idString .= $pointToGather.',';
        		$ids = explode(':',$pointToGather);
        		$pointID = $ids[0];
        		$channelID = $ids[1];

                //$this->preDebugger($points);
                //$this->preDebugger($User->pointChannels());
                $oMeterPoint = $User->pointChannels()->meterPoint($pointID);
                //$oMeterPoint->Refresh();            //$User->preDebugger($oMeterPoint);

                $oPointChannel = clone $User->pointChannels()->pointChannel($pointID, $channelID);

                $programName[$pointToGather]['program'] = $oMeterPoint->program();
                $programName[$pointToGather]['participationType'] = $oPointChannel->participationTypeDescription();

                $oIntervalSet = new IntervalValueSets();
                $oIntervalSet->Load(    $User->pointChannels()->pointChannel($pointID, $channelID),
                                        $User->pointChannels()->meterPoint($pointID),
                                        'PerformanceIntervalSet',
                                        $dateSpan,
                                        false,
                                        $eventBaseDate,
                                        $oBaseDate
                                        );      //$User->preDebugger($oIntervalSet);

                $oPercentIntervalSet = new IntervalValueSets();
                $oPercentIntervalSet->Load(    $User->pointChannels()->pointChannel($pointID, $channelID),
                                               $User->pointChannels()->meterPoint($pointID),
                                               'PercentagePerformanceIntervalSet',
                                               $dateSpan,
                                               false,
                                               $eventBaseDate,
                                               $oBaseDate
                                               );   //$User->preDebugger($oPercentIntervalSet);

                date_default_timezone_set(timezone_name_from_abbr($oMeterPoint->timeZone()->stdAbbreviation()));

                $envelope[$pointID.':'.$channelID]['assetIdentifier'] = ''; //kludge

            /*  ---------------------------------ENVELOPE--------------------------------- */

                $envelope[$pointID.':'.$channelID]['isGenerator'] = $oMeterPoint->isGenerator();
                $envelope[$pointID.':'.$channelID]['pointID'] = $pointID;
                $envelope[$pointID.':'.$channelID]['channelID'] = $channelID;
                $envelope[$pointID.':'.$channelID]['meterName'] = $oPointChannel->channelDescription();
                $envelope[$pointID.':'.$channelID]['registeredProgram'] = $oPointChannel->participationTypeDescription();
                $envelope[$pointID.':'.$channelID]['zone'] = $oMeterPoint->zone();
                $envelope[$pointID.':'.$channelID]['committedReduction'] = $oPointChannel->committedReduction();
                $envelope[$pointID.':'.$channelID]['assetIdentifier'] = $oPointChannel->assetIdentifier();


                //$User->preDebugger($envelope);

            /*  ------------------------------EVENT ENVELOPE------------------------------ */

                $eventDateStack = $oMeterPoint->eventDates();

                //$User->preDebugger($eventDateStack);

                if(empty($eventDateStack))
                {
                    $output .= $this->processError("no event",$envelope[$pointID.':'.$channelID]['meterName'],true);
                }
                else
                {
                    if(!array_key_exists($originalEventBaseDate,$eventDateStack))
                    {
                        $output .= $this->processError("no event",$envelope[$pointID.':'.$channelID]['meterName'],true);
                    }
                    else
                    {
                        $startDateParts = explode('-',$eventDateStack[$originalEventBaseDate]['startDate']);
                        $longStartDate = $startDateParts[2].'-'.$startDateParts[0].'-'.$startDateParts[1].' '.$eventDateStack[$originalEventBaseDate]['startTime'];

                        $endDateParts = explode('-',$eventDateStack[$originalEventBaseDate]['endDate']);
                        $longEndDate = $endDateParts[2].'-'.$endDateParts[0].'-'.$endDateParts[1].' '.$eventDateStack[$originalEventBaseDate]['endTime'];

                        $particulars = $oMeterPoint->fetchEventParticulars(   $pointID,
                                                                                $channelID,
                                                                                $longStartDate,
                                                                                $longEndDate    );
                        //$User->preDebugger($particulars);

                        if($particulars)
                        {
                            $eventEnvelope[$pointID.':'.$channelID]['adjustedBaselineString'] = !$particulars['base']->HasAdjustedBaseline ? '' :
                                                                                        'Your baseline is adjusted by ' .
                                                                                        number_format($oIntervalSet->adjustmentAmount(),3,'.',',').' '.
                                                                                        $aryUnits[$oPointChannel->units()->unitOfMeasureName()].'.';
                        }
                        else
                        {
                            $eventEnvelope[$pointID.':'.$channelID]['adjustedBaselineString'] = '';
                        }

                        //ideally, we should just pass particulars along, but we can do that for 3.0 to avoid cart-tipping here.
                        $eventEnvelope[$pointID.':'.$channelID]['startDate'] = $particulars ? $particulars['base']->StartDate : '';
                        $eventEnvelope[$pointID.':'.$channelID]['endDate'] = $particulars ? $particulars['base']->EndDate : '';
                        $eventEnvelope[$pointID.':'.$channelID]['restorationTime'] = $particulars ? $particulars['base']->RestorationTime : '';

                        $eventEnvelope[$pointID.':'.$channelID]['performance'] = $particulars['FCA']['performance'];
                        $eventEnvelope[$pointID.':'.$channelID]['pcr'] = $particulars['FCA']['pcr'];

                        $baseDispatch = $particulars ? date('Y-m-d H:',strtotime($particulars['base']->DispatchTime)) : '';
                        $baseMinutes = $particulars ? date('i', strtotime($particulars['base']->DispatchTime)) : '';
                        $minutes = $particulars ? (date('s', strtotime($particulars['base']->DispatchTime)) > 0 ? $baseMinutes + 1 : $baseMinutes) : '';

                        $eventEnvelope[$pointID.':'.$channelID]['dispatchTime'] = $baseDispatch.(substr('0'.(ceil($minutes/5)*5),-2,2)).':00';

                        $baseEffective = $particulars ? date('Y-m-d H:',strtotime($particulars['base']->EffectiveTime)) : '';
                        $baseMinutes = $particulars ? date('i', strtotime($particulars['base']->EffectiveTime)) : '';
                        $minutes = $particulars ? (date('s', strtotime($particulars['base']->EffectiveTime)) > 0 ? $baseMinutes + 1 : $baseMinutes) : '';

                        $eventEnvelope[$pointID.':'.$channelID]['effectiveTime'] = $baseEffective.substr('0'.(ceil($minutes/5)*5),-2,2).':00';

                        //$User->preDebugger($eventEnvelope);

                    /*  ------------------------------VALUE ENVELOPE------------------------------ */

                        $valueEnvelope[$pointID.':'.$channelID]['pointID'] = $pointID;
                        $valueEnvelope[$pointID.':'.$channelID]['channelID'] = $channelID;
                        $valueEnvelope[$pointID.':'.$channelID]['unit'] = $aryUnits[$oPointChannel->units()->unitOfMeasureName()];
                        $valueEnvelope[$pointID.':'.$channelID]['isGenerator'] = $oMeterPoint->isGenerator();

                        if(($oIntervalSet->recordsReturned != 0))
                        {
                            $percentValues = $oPercentIntervalSet->values();

                            $tsInx = 0;
                            $lastHour = '';
                            $tcFlag = '';

                            $uxMinute = 60;
                            $uxHour = 60 * 60;


                            $increment = $oIntervalSet->readInterval() * $uxMinute;

                            $expectedTime = strtotime($longStartDate);

                            $tcCheck = '';

                            //php helps us when dealing with time, the result is that it automatically adjusts for dst.
                            //for our purposes, we need to display the gap -- *mainly* because of the chart labelfrequency
                            //therefore, we're going to set flags and set up nice timestamps here, filling any data gaps, then
                            //the period during the time change needs to be handled as string, so that php is unaware that we're
                            //dealing with a time element.

                            foreach($oIntervalSet->values() as $utcTimestamp=>$value)
                            {
                                //$this->preDebugger(date('Y-m-d H:i:s',$utcTimestamp).' --> '.$value['value']);
                                $expectedTime += $increment;
                                $timeProperties = localtime($utcTimestamp,true);
                                $thisHour = $timeProperties['tm_hour'];
                                $thisMinute = $timeProperties['tm_min'];


                                if($lastHour == '')
                                    $lastHour = $thisHour-1;

                                if($utcTimestamp != $expectedTime)
                                {
                                    $numberToFill = ($utcTimestamp - $expectedTime)/$increment;
                                    for($i = 1; $i <= $numberToFill; $i++)
                                    {
                                        $timestampArray[$pointID.':'.$channelID]['timestamps'][$tsInx] = date('Y-m-d H:i:s',$expectedTime);
                                        $valueEnvelope[$pointID.':'.$channelID]['values'][date('Y-m-d H:i:s',$expectedTime)] = '';
                                        $valueEnvelope[$pointID.':'.$channelID]['percentages'][date('Y-m-d H:i:s',$expectedTime)] = '';

                                        $expectedTime += $increment;
                                        $lastHour = $thisHour;
                                        $tsInx++;
                                    }
                                }

                                if(($thisHour - $lastHour) == 2)
                                {
                                    $tcCheck = 'spring';
                                    $timestampArray[$pointID.':'.$channelID]['dst']['direction'] = 'spring';
                                    $timestampArray[$pointID.':'.$channelID]['dst'][$tsInx] = date('Y-m-d H:i:s',$utcTimestamp);
                                    $fillHour = $thisHour-1;

                                   if($fillHour <10)
                                    {
                                        $fillHourString = '0'.$fillHour;
                                    }
                                    else
                                    {
                                        $fillHourString = $fillHour;
                                    }

                                    if($forceHourlyRollup === true)
                                    {
                                        $tsInx++;
                                        $timestampArray[$pointID.':'.$channelID]['timestamps'][$tsInx] = date('Y-m-d',$utcTimestamp).' '.$fillHourString.':00:00';
                                        $valueEnvelope[$pointID.':'.$channelID]['values'][date('Y-m-d',$utcTimestamp).' '.$fillHourString.':00:00'] = '';
                                        $valueEnvelope[$pointID.':'.$channelID]['percentages'][date('Y-m-d',$utcTimestamp).' '.$fillHourString.':00:00'] = '';

                                        $tsInx++;
                                    }
                                    else
                                    {
                                        $target = ($uxHour/$increment);
                                        $fillMinute = 0;
                                        for($fillCount = 0; $fillCount < $target; $fillCount++)
                                        {

                                            if($fillMinute <10)
                                            {
                                                $fillMinuteString = '0'.$fillMinute;
                                            }
                                            else
                                            {
                                                $fillMinuteString = $fillMinute;
                                            }

                                            $timestampArray[$pointID.':'.$channelID]['timestamps'][$tsInx] = date('Y-m-d',$utcTimestamp).' '.$fillHourString.':'.$fillMinuteString.':00';
                                            $valueEnvelope[$pointID.':'.$channelID]['values'][date('Y-m-d',$utcTimestamp).' '.$fillHourString.':'.$fillMinuteString.':00'] = '';
                                            $valueEnvelope[$pointID.':'.$channelID]['percentages'][date('Y-m-d',$utcTimestamp).' '.$fillHourString.':'.$fillMinuteString.':00'] = '';


                                            $tsInx++;

                                            $fillMinute += $increment/60;
                                        }
                                    }
                                }
                                elseif(($thisHour - $lastHour) == 0 && $thisMinute == 0)
                                {
                                    $tcFlag = 'fall';
                                    if($dateSpan > 1)
                                    {
                                        $dstFlag = true;
                                    }

                                }

                                if($tcFlag == 'fall')
                                {
                                    if($thisHour != $lastHour)
                                    {
                                        $tcFlag = '';
                                        $tcCheck = 'fall';
                                    }
                                    else
                                    {
                                        $timestampArray[$pointID.':'.$channelID]['dst']['direction'] = 'fall';
                                        $timestampArray[$pointID.':'.$channelID]['dst'][$tsInx] = date('Y-m-d H:i:s',$utcTimestamp);
                                    }

                                }

                                $timestampArray[$pointID.':'.$channelID]['timestamps'][$tsInx] = date('Y-m-d H:i:s',$utcTimestamp);

                                $valueEnvelope[$pointID.':'.$channelID]['values'][date('Y-m-d H:i:s',$utcTimestamp)] = $value['value'];

                                $valueEnvelope[$pointID.':'.$channelID]['percentages'][date('Y-m-d H:i:s',$utcTimestamp)] = $percentValues[$utcTimestamp]['value'];



                                $lastHour = $thisHour;

                                $tsInx++;

                            }
//$User->preDebugger($valueEnvelope);
                            $tsInx++;

                            if(($tsInx < $oIntervalSet->expectedLength))
                            {
                                $leftOffTime = $utcTimestamp;

                                for ($tsInx; $tsInx <= $oIntervalSet->expectedLength; $tsInx++)
                                {
                                    $leftOffTime += $increment;
                                    $timestampArray[$pointID.':'.$channelID]['timestamps'][$tsInx] = date('Y-m-d H:i:s',$leftOffTime);
                                    $valueEnvelope[$pointID.':'.$channelID]['values'][date('Y-m-d H:i:s',$leftOffTime)] = '';
                                    $valueEnvelope[$pointID.':'.$channelID]['percentages'][date('Y-m-d H:i:s',$leftOffTime)] = '';

                                }
                            }

                        }
                        else
                        {
                            //$output .= '<div class="error" style="width: 700px;">There appears to be no event data available for the selected point channel on the selected event date.<br />If you believe this to be in error, please contact the <a href="http://help.crsolutions.us/">CRS Help Desk</a>.</div>';
                            $output .= $this->processError("no data",$envelope[$pointID.':'.$channelID]['meterName'],true);
                        }
                    }
                } // end of got event loop

                //$this->preDebugger($valueEnvelope);
                //$this->preDebugger($valueEnvelope[$pointID.':'.$channelID]['percentages']);


                    if($view != 'charts')
                    {
                        //$this->preDebugger($valueEnvelope);
                        //if($action == 'eventCSV')
                        $presentation = $this->renderCSVEventData($envelope,$valueEnvelope,$eventEnvelope,$timestampArray, $dateSpan,$formUsed,$programName,"",$dstFlag);

                    }

                }


                if($view == 'charts')
                {

                    //this is a kludge 'cause I made a massive change without realizing that this section needed to be *inside* the above loop so I fixed
                    // code *everywhere to deal with this as an individual, not as a stack . . . ::shakes head:: . . . nothing like making a paradigm change midstream

                    //$this->preDebugger($valueEnvelope);

                        $output .= $this->renderEventChart(
                                                            $envelope,
                                                            $valueEnvelope,
                                                            $eventEnvelope,
                                                            $timestampArray,
                                                            $presentation,
                                                            $dateSpan,
                                                            $eventBaseDate,
                                                            $action,
                                                            $formUsed,
                                                            $programName,
                                                            $dstFlag,
                                                            $refresh
                                                            );
                }

            return $output;
        }

    /*  ==========================================================================
        FUNCTION: renderEventChart()
        ========================================================================== */
        function renderEventChart($summaryData,$data,$eventDates,$timestamps,$presentation,$dateSpan,$baseDate,$action, $formUsed,$programName,$dstFlag,$refresh = false)
        {
            $message = '';
            $messageString = '';

        	$inxMult = 0;

        	if($dateSpan == 1)
        	{
        		$inxMult = 1;
        	}

        	if($dateSpan > 1 && $dstFlag === true)
        	{
        		$legendMargin = 25;
        	}
        	else
        	{
        		$legendMargin = 15;
        	}

        	$displayString = '';
        	$summary = '';
        	$marginTop = 5;

        	$marginLeft = 50;

        	$marginRight = 20;
        	$flashWidth = 850;

            $baseline = '';
            $baselineSettings = '';
            $baselineValues = '';
            $priceValues = '';
            $priceTitle = '';
            $dateTitle = '';

            $labelFrequency = 2;
            $xAngle = 45;

        	if($action == 'modalPrint' || $action == 'printEvent' || $action == 'modalDisplay')
        	{
        		$plotColor = '#FFFFFF';
        		$fontColor = '#000000';
        	}
        	else
        	{
        		$plotColor = '#CBCCD9';
        		$fontColor = '#FFFFFF';
        	}

            $dateTitle = date('m-d-Y',strtotime($baseDate));

            $marginBottom = 100;

            $flashHeight = 320 + $marginBottom + $legendMargin;

            $aryColors = array(1=>'#1B6097',2=>'#7C1787',3=>'#874417',4=>'#228717',5=>'#207DBC',6=>'#BC5E20',7=>'#17877C',8=>'#871722',9=>'#172287',10=>'#87175A',11=>'#877C17',12=>'#178744',13=>'#441787',14=>'#5A8717',15=>'#3B9DDE',16=>'#DE7C3B');

            $eventTimeStamps = '';

            if($action != 'modalDisplay')
            {
                $flashWidth = 700;
            }

            $legendY = $flashHeight-20;

            //$this->preDebugger($data);

            foreach($data as $id=>$point)
            {
                $tableArray = '';
                $etsInx = 0;
                $deltaValues = ''; //mcb 05.03.08 add this line to production once approved
                $reductionValues = ''; //mcb 05.03.08 add this line to production once approved

                if(!isset($point['values']) || $point['values'] == '')
                {
                    $messageString .= $this->processError('no data', $summaryData[$id]['meterName'],true);
                    $summary = '';
                    $chart = '';
                }
                else
                {
                    //$this->preDebugger($eventDates);
                    if($eventDates[$id]['startDate'] == '')
                    {
                        $message = $this->processError('no event', $summaryData[$id]['meterName'],true);
                        $summary = '';
                        $chart = '';
                    }
                    else
                    {
                        $message = '';
                        $pointString = $id;
                        $urlString = '';
                        $graphCounter = 1;
                        $graphValues = '';
                        $graphTimestamps = '';
                        $deltaValues = '';
                        $reductionValues = '';
                        //$axisMax = 0;
                        //$axisMin = 0;
                        $eventTimeStamps = '';
                        $timeStampsForSummary = '';
                        $etsSummaryInx = 0;

                        $start = date('Y-m-d H:i:s',strtotime($eventDates[$id]['dispatchTime']));
                        $end =  date('Y-m-d H:i:s',strtotime($eventDates[$id]['restorationTime']) + 1800);
                        foreach($timestamps[$id]['timestamps'] as $timestamp)
                        {
                            if(($timestamp >= $start) && ($timestamp <= $end))
                            {
                                $eventTimeStamps[$etsInx] = $timestamp;
                                    $etsInx++;
                            }

                            if(($timestamp >= $eventDates[$id]['startDate']) && ($timestamp <= $eventDates[$id]['restorationTime']))
                            {
                                if($timestamp != $eventDates[$id]['startDate'])
                                {
                                    $timeStampsForSummary[$etsSummaryInx] = $timestamp;
                                    $etsSummaryInx++;
                                }

                            }
                        }

                        //$this->preDebugger($eventDates,'green');

                        $compiledTimestamps = $this->compileTimestamps($id,$eventTimeStamps,$eventDates,$dateSpan,$action,$presentation,$formUsed,$dstFlag);

                        $graphTimestamps = $compiledTimestamps['timestamps'];

                        $hasEvent = $compiledTimestamps['eventFlag'];

                        $emptySet = true;
                        //$this->preDebugger($point);
                        foreach($eventTimeStamps as $timestamp)
                        {
                            if(array_key_exists($timestamp,$point['values']))
                            {
                                $value = $point['values'][$timestamp];
                                $percentage = $point['percentages'][$timestamp];
                                if($value != '')
                                {
                                    $emptySet = false;
                                }
                            }
                            else
                            {
                                $value = '';
                                $percentage = '';
                            }

                            $tableArray[$timestamp]['intervalSet'] = $value;
                            $tableArray[$timestamp]['percentageIntervalSet'] = $percentage;

                        }

                        if($emptySet === true)
                        {
                            $message = $this->processError('no data', $summaryData[$id]['meterName'],true);
                            $chart = '';
                            $summary = '';
                        }
                        else
                        {


                            $labelCount = 0;
                            $inx = 0;

                            //$this->preDebugger($tableArray);

                            foreach($tableArray as $timestamp=>$valuePair)
                            {
                                if($point['isGenerator'] == 0)
                                {
                                    $deltaValues .= '<value xid=\"'.$inx.'\">'.$valuePair['intervalSet'].'</value>';
                                    $reductionValues .= '<value xid=\"'.$inx.'\">'.($summaryData[$id]['committedReduction']).'</value>';
                                }
                                else
                                {
                                    $delta = $valuePair['intervalSet'];

                                    if($delta == 0)
                                    {
                                        $delta = '';
                                    }
                                    $deltaValues .= '<value xid=\"'.$inx.'\">'.$valuePair['intervalSet'].'</value>';

                                    $reductionValues .= '<value xid=\"'.$inx.'\">'.($summaryData[$id]['committedReduction']).'</value>';

                                }
                                    $inx++;
                                    $labelCount++;
                            }


                            $axisMax = max($point['values']);
                            $axisMin = min(array_filter($point['values'], 'strlen'));

                            if($summaryData[$id]['committedReduction'] > $axisMax)
                                $axisMax = $summaryData[$id]['committedReduction'];
                            if($summaryData[$id]['committedReduction'] < $axisMin)
                                $axisMin = $summaryData[$id]['committedReduction'];

                            //$this->preDebugger($summaryData[$id]['committedReduction']);
                            //$this->preDebugger($axisMax);
                            //$this->preDebugger($axisMin);

                            //adding a percentage for a little visual padding

                            if($axisMax == 0)
                            {
                                $axisMax = $axisMin * .1;
                            }

                            $axisMin *= .995;
                            $axisMax *= 1.05;

                            $chartID = str_replace(':','',$id);

                            //$this->preDebugger($axisMax);
                            //$this->preDebugger($axisMin);

                            if($labelCount > 36)
                            {
                                $labelFrequency = 6;
                            }
                            $deltaSettings = $this->compileEventGraphSettings(1, $aryColors[1], '', 'left', 10, 100,1);
                            $reductionSettings = $this->compileEventGraphSettings(2, '#980000', '', 'right', 0, 100,2);


                            $deltaGraph = '<graph gid=\"1\" title=\"'.$summaryData[$id]['meterName'].' Performance\" axis=\"left\">'.$deltaValues.'</graph>';
                            $reductionGraph = '<graph gid=\"2\" title=\"Estimated Capability\" axis=\"right\">'.$reductionValues.'</graph>';

                            $urlString = '?ID='.$pointString.'&Date='.$baseDate.'&Span='.$dateSpan.'&pres='.$presentation;



                            $chartSettings = $this->compileEventSettings($deltaSettings.$reductionSettings,$labelFrequency,$xAngle,$marginBottom,$marginTop,$marginLeft,80,$plotColor,$fontColor,$axisMax,$axisMin,$legendY);
                            $chartValues = '<chart><series>'.$graphTimestamps.'</series><graphs>'.$deltaGraph.$reductionGraph.'</graphs></chart>';
                            $chart = $this->compileChart($chartSettings, $chartValues, $flashHeight, $flashWidth, $chartID, $pointString, $urlString, $action, $dateTitle,$formUsed,$point['unit'],count($data),$refresh);
                            $summary = $this->buildEventSummary($tableArray,$timeStampsForSummary,$eventDates[$id],$summaryData[$id]['meterName'],$summaryData[$id]['committedReduction'],$point['isGenerator'],$programName[$id],$eventDates[$id]['adjustedBaselineString'],$point['unit'],$summaryData[$id]['assetIdentifier']);
                        }
                    }
                }

                $displayString .= $message.$chart.$summary;

            }

        	return $displayString;

        }

    /*  ==========================================================================
        FUNCTION: buildEventSummary()
        ========================================================================== */
        function buildEventSummary($data,$timestamps,$eventData,$meterName,$commRed,$isGenerator,$programName,$adjustedBaselineString,$unit,$assetId = 'N/A')
        {
            //$this->preDebugger($timestamps,'#980000');
        	$eventStart = false;
        	$eventEnd = false;
        	$peakCandidates = '';
        //mcb 2009.05.30
        	$summaryTable = '
        		<br />
        		<div style="color: #FF6701; text-align: center;">'.$adjustedBaselineString.'</div>
        		<br />
        		<table align="center" cellpadding="5" cellspacing="0" border="0">
        		<tr>
        		<td>
        		<table width="100%" align="center" cellpadding="5" cellspacing="0" border="1">
        		<tr><th colspan="5">'.$meterName.'<br />(Asset: '.$assetId.')<br />'.$programName['program'].'<br />'.$programName['participationType'].'</th></tr>
        		<tr>
        			<th rowspan="2">Date / Time</th>
        			<th colspan="2">Running Avg. Hourly</th>
        		</tr>
        		<tr>
        			<th>Reduction<br />('.$unit.')</th>
        			<th>Reduction<br />(%)</th>
        		</tr>
        	';

        	$lastHour = '';
        	$dValueTotal = 0;
        	$summaryArray = array();
        	$rowCount = 1;
        	$valueCount = 1;
            $totalRowCount = 0;
        	$totalValueCount = 0;
            $totalDValue = 0;
            $totalPPValue = 0;
            $totalBValue = 0;
        	$bValue = 0;
            $totalIValue = 0;
            $blIndex = 0;

        	foreach($timestamps as $inx=>$value)
        	{
        		$timeValue = strtotime($value);

        		$nowValue = strtotime(date('Y-m-d H:i:s'));

        		$timeProperties = localtime($timeValue,true);
        		$thisHour = $timeProperties['tm_hour'];

        		if(($thisHour != $lastHour) && ($lastHour != ''))
        		{
        			$rowCount -= 1;
        			$valueCount -= 1;

        			if($valueCount > 0 && ($timeValue <= $nowValue))
        			{
        				$summaryArray[$lastHour]['total'] = $dValueTotal/($valueCount);
        			}
        			else
        			{
        				$summaryArray[$lastHour]['total'] = 'N/A';
        			}

        			if(($commRed > 0 && $valueCount > 0) && ($timeValue <= $nowValue))
        			{
                       // $summaryArray[$lastHour]['avgPercent'] =
        				$summaryArray[$lastHour]['avgPercent'] = $dValueTotal/($commRed * ($valueCount));
        			}
        			else
        			{
        				$summaryArray[$lastHour]['avgPercent'] = 'N/A';
        			}

        			$summaryArray[$lastHour]['rowCount'] = $rowCount;

        			$rowCount = 1;
        			$valueCount = 1;
        			$dValueTotal = 0;
        		}



        		if(is_numeric($data[$value]['intervalSet']))
        		{

                    $lewv = $value; //getting our date for the last line with something to report
        			$dValue = $data[$value]['intervalSet'];

        			if($commRed > 0)
        			{
        				$ppValue = ($dValue/$commRed) * 100;

                        //$ppValue = $data[$value]['percentageIntervalSet'];

        			}
        			else
        			{
        				$ppValue = 'N/A';
        			}

        			$dValueTotal += $dValue;

        			$peakCandidates[$value] = $dValue;

        		}
        		else
        		{
        			$dValue = 'N/A';
                    $ppValue = 'N/A';
        		}


        		$summaryArray[$thisHour]['lines'][$value]['dValue'] = $dValue;
        		$summaryArray[$thisHour]['lines'][$value]['ppValue'] = $ppValue;


        		$rowCount++;
                $totalRowCount++;
        		if(is_numeric($dValue))
        		{
        			$valueCount++;
        			$totalValueCount++;
        			$totalDValue += $dValue;
        		}

        		if(is_numeric($ppValue))
        		{
        			$totalPPValue += $ppValue;
        		}

        		$lastHour = $thisHour;
        	}

        	if($valueCount > 1)
        	{
        		$summaryArray[$lastHour]['total'] = $dValueTotal/($rowCount - 1); //append the last value
        	}
        	else
        	{
        		$summaryArray[$lastHour]['total'] = 'N/A'; //append the last value
        	}

        	if($commRed > 0 && $valueCount > 1)
        	{
        		$summaryArray[$lastHour]['avgPercent'] = $dValueTotal/($commRed * ($rowCount - 1)); //append the last value
        	}
        	else
        	{
        		$summaryArray[$lastHour]['avgPercent'] = 'N/A';
        	}

        	$summaryArray[$lastHour]['rowCount'] = $rowCount - 1; //append the last value

        	$firstLine = '';
        	$lines = '';

            foreach($summaryArray as $hour=>$items)
        	{
        	    foreach($items['lines'] as $ts=>$itemStack)
        		{
                    if(is_numeric($itemStack['dValue']))
                    {
                        $dValueString = number_format($itemStack['dValue'],3,'.',',');
                    }
                    else
                    {
                        $dValueString = $itemStack['dValue'];
                    }

                    if(is_numeric($itemStack['ppValue']))
                    {
                        $ppValueString = number_format($itemStack['ppValue'],2,'.',',');
                    }
                    else
                    {
                        $ppValueString = $itemStack['ppValue'];
                    }

                    $blIndex++;

                    if($firstLine == '')
        			{

        				if(is_numeric($items['avgPercent']))

        				{
        					$percentString = number_format(($items['avgPercent'] * 100),2,'.',',');
        				}
        				else
        				{

        					$percentString = $items['avgPercent'];
        				}
        				if(is_numeric($items['total']))

        				{
        					$avgString = number_format($items['total'],2,'.',',');
        				}
        				else
        				{

        					$avgString = $items['total'];
        				}


        				$firstLine = '
        					<tr>
        						<td>'.$ts.'</td>
        						<td style="text-align: right">'.$dValueString.'</td>
        						<td style="text-align: right">'.$ppValueString.'</td>
        					</tr>
        				';
        				$lines .= $firstLine;
        			}
        			else
        			{
        				$lines .= '
        				<tr>
        					<td>'.$ts.'</td>
        					<td style="text-align: right">'.$dValueString.'</td>
        					<td style="text-align: right">'.$ppValueString.'</td>
        				</tr>
        			';
        			}

        		}

        		$firstLine = '';
        	}

            //$lewv = end($timestamps); //latest event window value
/*
            if(date('Y-m-d H:i:s') < $lewv)
            {
                $round_numerator = 60 * 15;
                $lewv = date('Y-m-d H:i:s',( floor ( time() / $round_numerator ) * $round_numerator ));

                //$lewv = date('Y-m-d H:i:s',strtotime('now'));
            }
  */          
            $AHPFinal = number_format(round($data[$lewv]['intervalSet'],3),3,'.',',');
            $PCRFinal = number_format(round($data[$lewv]['percentageIntervalSet'],3),3,'.',',');
            
            $peakValues = '
                <tr>
                    <td>'.$lewv.'</td>
                    <td style="text-align: center">'.$AHPFinal.'</td>
                    <td style="text-align: center">'.$PCRFinal.'</td>
                </tr>
            ';

        	$peakValues = '
        		<tr>
            		<td colspan="7">
        	    	<table width="100%" align="center" cellpadding="5" cellspacing="0" border="1">
        				 <tr>
        					<th>Date / Time</th>
        					<th>Average Hourly Performance<br />('.$unit.')</th>
        					<th>% Committed<br />Reduction</th>
        				</tr>
        				'.$peakValues.
            		'</table>
            		</td>
        		</tr>'
        	;
            //=================================================================

        	$summaryTable .=
        		$lines.
        		'</table></td></tr>'.
        		$peakValues.'
        		</table>
        		<br />
        	';

        	return $summaryTable;

        }

    /*  ==========================================================================
        FUNCTION: buildEventSummaryCSV()
        ========================================================================== */
        function buildEventSummaryCSV(
            $data,
            $timestamps,
            $eventData,
            $meterName,
            $commRed,
            $isGenerator,
            $programName,
            $adjustedBaselineString,
            $unit,
            $assetId = 'N/A')
        {
            $eol = "\n";
            $delm = ",";

            //$this->preDebugger($data,'#980000');

        	$summaryTable = $adjustedBaselineString.$eol;
        	$summaryTable .= $meterName.$eol;
            $summaryTable .= "(Asset: ".$assetId.")".$eol;
            $summaryTable .= $programName['participationType'].$eol;
            $summaryTable .= $delm."Running Avg. Hourly".$eol;
            $summaryTable .= "Date / Time".$delm."Reduction (".$unit.")".$delm."Reduction (%)".$eol;

            foreach($data as $timestamp=>$values)
            {
                $summaryTable .= $timestamp.$delm.$values['intervalSet'].$delm.$values['percentageIntervalSet'].$eol;

            }

            $summaryTable .= $eol;

            $endInterval = $data[$eventData['endDate']]['intervalSet'] ? number_format(round($data[$eventData['endDate']]['intervalSet'],3),3,'.',',') : 'N/A';
            $endPercentInterval = $data[$eventData['endDate']]['percentageIntervalSet'] ? number_format(round($data[$eventData['endDate']]['percentageIntervalSet'],3),3,'.',',') : 'N/A';
        	$summaryTable .= "Date / Time".$delm."Average Hourly Performance (".$unit.")".$delm."% Estimated Capability".$eol;
            $summaryTable .= $eventData['endDate'].$delm.
                            $endInterval.$delm.
                            $endPercentInterval.$eol;


        	print $summaryTable;

        }


    /*  ==========================================================================
        FUNCTION: compileEventSettings()
        ========================================================================== */
        function compileEventSettings($graphSettings,$labelFrequency,$xAngle,$marginBottom = 30,$marginTop = 15,$marginLeft = 50,$marginRight = 15,$plotColor,$fontColor,$axisMax,$axisMin,$legendY)
        {
        	$settingsString = '<settings>';
        	$settingsString .= '<type>stacked</type>';
        	$settingsString .= '<export_image_file>amline/export.php</export_image_file>';
        	$settingsString .= '<decimals_separator>.</decimals_separator>';
        	$settingsString .= '<thousands_separator>,</thousands_separator>';
        	$settingsString .= '<connect>false</connect>';
        	//plot area
        	$settingsString .= '<plot_area>';
        	$settingsString .= '<color>'.$plotColor.'</color>';
        	$settingsString .= '<alpha>100</alpha>';
            $settingsString .= '<margins>';
        	$settingsString .= '<left>'.$marginLeft.'</left>';
        	$settingsString .= '<top>'.$marginTop.'</top>';
        	$settingsString .= '<right>'.$marginRight.'</right>';
        	$settingsString .= '<bottom>'.$marginBottom.'</bottom>';
            $settingsString .= '</margins>';
        	$settingsString .= '</plot_area>';

        	//grid
        	$settingsString .= '<grid>';
        	$settingsString .= '<x>';
        	$settingsString .= '<approx_count>288</approx_count>';
        	$settingsString .= '</x>';
        	$settingsString .= '</grid>';
        	//values
        	$settingsString .= '<values>';
        	$settingsString .= '<x>';
        	$settingsString .= '<rotate>'.$xAngle.'</rotate>';
        	$settingsString .= '<frequency>'.$labelFrequency.'</frequency>';
        	$settingsString .= '<color>'.$fontColor.'</color>';
        	$settingsString .= '<text_size>10</text_size>';
        	$settingsString .= '<skip_last>false</skip_last>';
        	$settingsString .= '</x>';
        	$settingsString .= '<y_left>';
        	$settingsString .= '<color>'.$fontColor.'</color>';
        	$settingsString .= '<text_size>10</text_size>';
        	$settingsString .= '<min>'.$axisMin.'</min>';
        	$settingsString .= '<max>'.$axisMax.'</max>';
            $settingsString .= '<strict_min_max>true</strict_min_max>';
        	$settingsString .= '</y_left>';
        	$settingsString .= '<y_right>';
        	$settingsString .= '<color>'.$fontColor.'</color>';
        	$settingsString .= '<text_size>10</text_size>';
        	$settingsString .= '<min>'.$axisMin.'</min>';
        	$settingsString .= '<max>'.$axisMax.'</max>';
            $settingsString .= '<strict_min_max>true</strict_min_max>';
        	$settingsString .= '</y_right>';
        	$settingsString .= '</values>';

        	//indicator
        	$settingsString .= '<indicator>';
        	$settingsString .= '<color>#1B6097</color>';
        	$settingsString .= '<selection_color>#FF6701</selection_color>';
        	$settingsString .= '<x_balloon_text_color>#FFFFFF</x_balloon_text_color>';
        	$settingsString .= '<one_y_balloon>false</one_y_balloon>';
        	$settingsString .= '</indicator>';
          	//axes
        	$settingsString .= '<axes>.';
        	$settingsString .= '<x>';
        	$settingsString .= '<color>#CBCCD9</color>';
            $settingsString .= '</x>';
        	$settingsString .= '<y_left>';
        	$settingsString .= '<color>#CBCCD9</color>';
            $settingsString .= '</y_left>';
           	$settingsString .= '<y_right>';
        	$settingsString .= '<color>#CBCCD9</color>';
            $settingsString .= '</y_right>';
            $settingsString .= '</axes>.';
        	//legend
        	$settingsString .= '<legend>';
        	$settingsString .= '<y>'.$legendY.'</y>';
        	$settingsString .= '<text_color>'.$fontColor.'</text_color>';
        	$settingsString .= '<text_color_hover>#FF6701</text_color_hover>';
        	$settingsString .= '<text_size>9</text_size>';
        	$settingsString .= '<spacing>0</spacing>';
        	$settingsString .= '<margins>0</margins>';
        	$settingsString .= '<key>';
        	$settingsString .= '<size>10</size>';
        	$settingsString .= '</key>';
        	$settingsString .= '<values>';
        	$settingsString .= '<enabled>false</enabled>';
        	$settingsString .= '</values>';
        	$settingsString .= '</legend>';
        	//strings
        	$settingsString .= '<strings>';
        	$settingsString .= '<collecting_data>Collecting Data . . . </collecting_data>';
        	$settingsString .= '</strings>';
        	//graphs
        	$settingsString .= '<graphs>';
        	$settingsString .= $graphSettings;
        	$settingsString .= '</graphs>';
        	$settingsString .= '</settings>';

        	return $settingsString;
        }


/*  GENERAL GRAPH -------------------------------------------------------------------------------------------------------------------------------------
    --------------------------------------------------------------------------------------------------------------------------------------------------- */
    /*  ==========================================================================
        FUNCTION: compileGraphSettings()
        ========================================================================== */
        function compileGraphSettings($graphID, $colorString)
        {
        	$settingsString = '<graph gid=\"'.$graphID.'\">';
        	$settingsString .= '<axis>left</axis>';
        	$settingsString .= '<title></title>';
        	$settingsString .= '<color>'.$colorString.'</color>';
        	$settingsString .= '<color_hover>#FF6701</color_hover>';
        	$settingsString .= '<line_alpha></line_alpha>';
        	$settingsString .= '<line_width></line_width>';
        	$settingsString .= '<fill_alpha>0</fill_alpha>';
        	$settingsString .= '<fill_color></fill_color>';
        	$settingsString .= '<balloon_color></balloon_color>';
        	$settingsString .= '<balloon_alpha></balloon_alpha>';
        	$settingsString .= '<balloon_text_color></balloon_text_color>';
        	$settingsString .= '<bullet></bullet>';
        	$settingsString .= '<bullet_size></bullet_size>';
        	$settingsString .= '<bullet_color></bullet_color>';
        	$settingsString .= '<bullet_alpha></bullet_alpha>';
        	$settingsString .= '<hidden></hidden>';
        	$settingsString .= '<selected></selected>';
        	$settingsString .= '<balloon_text>';
        	$settingsString .= '<![CDATA[]]>';
        	$settingsString .= '</balloon_text>';
        	$settingsString .= '<vertical_lines></vertical_lines>';
        	$settingsString .= '</graph>';

        	return $settingsString;
        }

    /*  ==========================================================================
        FUNCTION: compileSettings()
        ========================================================================== */
        function compileSettings($graphSettings,$labelFrequency,$xAngle,$marginBottom = 30,$marginTop = 15,$marginLeft = 50,$marginRight = 15,$plotColor,$fontColor,$legendMargin)
        {
        	$settingsString = '<settings>';
        	$settingsString .= '<export_image_file>amline/export.php</export_image_file>';
        	$settingsString .= '<decimals_separator>.</decimals_separator>';
        	$settingsString .= '<thousands_separator>,</thousands_separator>';
        	$settingsString .= '<connect>false</connect>';
        	//plot area
        	$settingsString .= '<plot_area>';
        	$settingsString .= '<color>'.$plotColor.'</color>';
        	$settingsString .= '<alpha>100</alpha>';
            $settingsString .= '<margins>';
        	$settingsString .= '<left>'.$marginLeft.'</left>';
        	$settingsString .= '<top>'.$marginTop.'</top>';
        	$settingsString .= '<right>'.$marginRight.'</right>';
        	$settingsString .= '<bottom>'.$marginBottom.'</bottom>';
            $settingsString .= '</margins>';
        	$settingsString .= '</plot_area>';

        	//grid
        	$settingsString .= '<grid>';
        	$settingsString .= '<x>';
        	$settingsString .= '<approx_count>288</approx_count>';
        	$settingsString .= '</x>';
            $settingsString .= '<y_right>';
            $settingsString .= '<enabled>False</enabled>';
            $settingsString .= '</y_right>';
        	$settingsString .= '</grid>';
        	//values
        	$settingsString .= '<values>';
        	$settingsString .= '<x>';
        	$settingsString .= '<rotate>'.$xAngle.'</rotate>';
        	$settingsString .= '<frequency>'.$labelFrequency.'</frequency>';
        	$settingsString .= '<color>'.$fontColor.'</color>';
        	$settingsString .= '<text_size>10</text_size>';
        	$settingsString .= '</x>';
        	$settingsString .= '<y_left>';
        	$settingsString .= '<color>'.$fontColor.'</color>';
        	$settingsString .= '<text_size>10</text_size>';
        	$settingsString .= '</y_left>';
            $settingsString .= '<y_right>';
        	$settingsString .= '<color>'.$fontColor.'</color>';
        	$settingsString .= '<text_size>10</text_size>';
        	$settingsString .= '</y_right>';
        	$settingsString .= '</values>';
        	//indicator
        	$settingsString .= '<indicator>';
        	$settingsString .= '<color>#1B6097</color>';
        	$settingsString .= '<selection_color>#FF6701</selection_color>';
        	$settingsString .= '<x_balloon_text_color>#FFFFFF</x_balloon_text_color>';
        	$settingsString .= '<one_y_balloon>true</one_y_balloon>';
        	$settingsString .= '</indicator>';
          	//axes
        	$settingsString .= '<axes>.';
        	$settingsString .= '<x>';
        	$settingsString .= '<color>#CBCCD9</color>';
            $settingsString .= '</x>';
        	$settingsString .= '<y_left>';
        	$settingsString .= '<color>#CBCCD9</color>';
            $settingsString .= '</y_left>';
           	$settingsString .= '<y_right>';
        	$settingsString .= '<color>#CBCCD9</color>';
            $settingsString .= '</y_right>';
            $settingsString .= '</axes>.';
        	//legend
        	$settingsString .= '<legend>';
        	$settingsString .= '<text_color>'.$fontColor.'</text_color>';
        	$settingsString .= '<text_color_hover>#FF6701</text_color_hover>';
        	$settingsString .= '<text_size>9</text_size>';
        	$settingsString .= '<spacing>5</spacing>';
        	$settingsString .= '<margins>'.$legendMargin.'</margins>';
        	$settingsString .= '<key>';
        	$settingsString .= '<size>10</size>';
        	$settingsString .= '</key>';
        	$settingsString .= '<values>';
        	$settingsString .= '<enabled>false</enabled>';
        	$settingsString .= '</values>';
        	$settingsString .= '</legend>';
        	//strings
        	$settingsString .= '<strings>';
        	$settingsString .= '<collecting_data>Collecting Data . . . </collecting_data>';
        	$settingsString .= '</strings>';
        	//graphs
        	$settingsString .= '<graphs>';
        	$settingsString .= $graphSettings;
        	$settingsString .= '</graphs>';
        	$settingsString .= '</settings>';

        	return $settingsString;
        }

    /*  ==========================================================================
        FUNCTION: compileEventGraphSettings()
        ========================================================================== */
        function compileEventGraphSettings($graphID, $colorString, $balloonString, $axis, $fillAlpha, $balloonAlpha,$lineWidth)
        {
        	$settingsString = '<graph gid=\"'.$graphID.'\">';
        	$settingsString .= '<axis>'.$axis.'</axis>';
        	$settingsString .= '<title></title>';
        	$settingsString .= '<color>'.$colorString.'</color>';
        	$settingsString .= '<color_hover>#FF6701</color_hover>';
        	$settingsString .= '<line_alpha></line_alpha>';
        	$settingsString .= '<line_width>'.$lineWidth.'</line_width>';
        	$settingsString .= '<fill_alpha>'.$fillAlpha.'</fill_alpha>';
        	$settingsString .= '<fill_color></fill_color>';
        	$settingsString .= '<balloon_color></balloon_color>';
        	$settingsString .= '<balloon_alpha>'.$balloonAlpha.'</balloon_alpha>';
        	$settingsString .= '<balloon_text_color></balloon_text_color>';
        	$settingsString .= '<bullet></bullet>';
        	$settingsString .= '<bullet_size></bullet_size>';
        	$settingsString .= '<bullet_color></bullet_color>';
        	$settingsString .= '<bullet_alpha></bullet_alpha>';
        	$settingsString .= '<hidden></hidden>';
        	$settingsString .= '<selected></selected>';
        	$settingsString .= '<balloon_text>';
        	$settingsString .= '<![CDATA['.$balloonString.']]>';
        	$settingsString .= '</balloon_text>';
        	$settingsString .= '<vertical_lines></vertical_lines>';
        	$settingsString .= '</graph>';

        	return $settingsString;
        }

    /*  ==========================================================================
        FUNCTION: compilePriceGraphSettings()
        ========================================================================== */
        function compilePriceGraphSettings($graphID, $colorString, $lineWidth)
        {
        	$settingsString = '<graph gid=\"'.$graphID.'\">';
        	$settingsString .= '<axis>right</axis>';
        	$settingsString .= '<title></title>';
        	$settingsString .= '<color>'.$colorString.'</color>';
        	$settingsString .= '<color_hover>#FF6701</color_hover>';
        	$settingsString .= '<line_alpha></line_alpha>';
        	$settingsString .= '<line_width>'.$lineWidth.'</line_width>';
        	$settingsString .= '<fill_color></fill_color>';
        	$settingsString .= '<balloon_color></balloon_color>';
        	$settingsString .= '<balloon_text_color></balloon_text_color>';
        	$settingsString .= '<bullet></bullet>';
        	$settingsString .= '<bullet_size></bullet_size>';
        	$settingsString .= '<bullet_color></bullet_color>';
        	$settingsString .= '<bullet_alpha></bullet_alpha>';
        	$settingsString .= '<hidden></hidden>';
        	$settingsString .= '<selected></selected>';
        	$settingsString .= '<balloon_text>';
        	$settingsString .= '</balloon_text>';
        	$settingsString .= '<vertical_lines>False</vertical_lines>';
        	$settingsString .= '</graph>';

        	return $settingsString;
        }


/*  UTILITIES -----------------------------------------------------------------------------------------------------------------------------------------
    --------------------------------------------------------------------------------------------------------------------------------------------------- */
    /*  ==========================================================================
        FUNCTION: preDebugger()
        ========================================================================== */
        function preDebugger($data,$color = 'blue')
        {
            print '<pre style="color: '.$color.'">';
            print_r($data);
            print '</pre>';
        }

    /*  ==========================================================================
        FUNCTION: processError()
        ========================================================================== */
        function processError($type,$meter,$styled = false)
        {
            switch($type)
            {
                case 'no data':
                    $string = 'There is no data available for the <strong>'.$meter.'</strong> meter on the selected date.<br />If you believe this to be in error, please try refreshing the page and submitting your request again.  If the problem persists, please contact the <a href="http://help.crsolutions.us/">CRS Help Desk</a>.
                        <div id="printChartTip"></div>
                        <div id="exportTip"></div>
                        <div id="magnifyTip"></div>
                        <div id="tabularTip"></div>';
                    break;
                 case 'no event':
                    $string = 'The <strong>'.$meter.'</strong> meter did not participate in an event on the selected date.<br />If you believe this to be in error, please try refreshing the page and submitting your request again.  If the problem persists, please contact the <a href="http://help.crsolutions.us/">CRS Help Desk</a>.
                        <div id="printChartTip"></div>
                        <div id="exportTip"></div>
                        <div id="magnifyTip"></div>
                        <div id="tabularTip"></div>';
                    break;
            }

            if($styled)
            {
                return '<div class="error" style="align: center; width: 600px;">'.$string.'</div><br /><br />';
            }
            else
            {
                return $string;
            }
        }
}
?>

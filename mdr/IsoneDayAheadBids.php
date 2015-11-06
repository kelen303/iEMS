<?php
/*  ===============================================================================
    -----------------------------IsoneDayAheadBids.php-----------------------------
                                         v 2.0
                                                                               
    Created by:     Marian C. Buford                                                 
                    Conservation Resource Solutions, Inc. (CRS)
    Adapted from:   IsoneDayAheadBid.cls v 1.0 by Kevin L. Keegan, CRS
    Created on:     01.26.2011
    License:        Proprietary
    Copyright:      2011 Conservation Resource Solutions, Inc. All rights reserved.
    =============================================================================== */      
class IsoneDayAheadBids extends CAO
{
    private $p_size;
    private $p_bidList;
    private $p_oUser;

/*  ===============================================================================
    FUNCTION : size()
    =============================================================================== */
    function size()
    {
        return $this->p_size;
    } // size()

/*  ===============================================================================
    FUNCTION : bidList()
    =============================================================================== */
    function bidList()
    {
        return $this->p_bidList;
    } // bidList()

/*  ===============================================================================
    CONSTRUCTOR
    =============================================================================== */
    function __construct()
    {
        parent::__construct();

        //$this->refresh();
        $this->clear();

    } // CONSTRUCTOR

/*  ===============================================================================
    FUNCTION : clear()
    =============================================================================== */
    function clear()
    {
        $this->p_size =   '';
        $this->p_bidList = array();

    } // clear()

/*  ===============================================================================
    FUNCTION : load()
    -----------------------------------variables-----------------------------------
    $oUser          object      : The authenticated user's object
    =============================================================================== */
    function load($oUser)
    {
        $this->p_oUser = $oUser;
        $this->refresh();
    } // load()

/*  ===============================================================================
    FUNCTION : refresh()
    =============================================================================== */
    function refresh()
    {
        $p_maximumValue = 0;
    
        $sql = '
            SELECT 
               IsoneDayAheadBidID,
               i.AssetIdentifier,
               ObjectName,
               StartDay,
               StopDay,
               MW,
               OfferPrice,
               CurtailmentInitiationPrice,
               MinimumInterruptionDuration,
               IsSent,
               i.CreatedBy,
               i.CreatedDate,
               i.UpdatedBy,
               i.UpdatedDate
            FROM 
               t_isonedayaheadbids i,
               t_pointchannels pc,
               t_objects o 
            WHERE
               pc.AssetIdentifier = i.AssetIdentifier and 
               o.ObjectID = pc.ObjectID 
            order by 
               StartDay,
               StopDay
            ';
    
        $result = $this->processQuery($sql,$this->sqlConnection(),'select');
        //$this->preDebugger($result);

        if($result['error'] != null)
        {
            $this->p_bidList['error'] = true;
        }
        else
        {
            if($result['records'] > 0)
            {
                $this->p_size = 0;
    
                foreach($result['items'] as $inx=>$bidItem)
                {
                    if($this->p_oUser->HasPrivilege("Read.".$bidItem->ObjectName))
                    {
                        $updatedBy = $bidItem->UpdatedBy == null ? 0 : $bidItem->UpdatedBy;
                        $updateDate = $bidItem->UpdatedDate == null ? 0 : $bidItem->UpdatedDate;
        
                        $oBid = new IsoneDayAheadBid();
                        $oBid->load(
                                $bidItem->IsoneDayAheadBidID,
                                $bidItem->AssetIdentifier,
                                $bidItem->StartDay,
                                $bidItem->StopDay,
                                $bidItem->MW,
                                $bidItem->OfferPrice,
                                $bidItem->CurtailmentInitiationPrice,
                                $bidItem->MinimumInterruptionDuration,
                                $bidItem->IsSent,
                                $bidItem->CreatedBy,
                                $bidItem->CreatedDate,
                                $updatedBy,
                                $updatedDate
                                );
        
                        $this->p_bidList[$this->p_size] = $oBid;
                        $this->p_size++;
                    }
                }
            }
        }
    } // refresh()

/*  ===============================================================================
    DESTRUCTOR
    =============================================================================== */
    function __destruct()
    {
        parent::__destruct();
    } // DESTRUCTOR

}

?>
<?php
/*  ===============================================================================
    -----------------------------IsoneDayAheadBid.php------------------------------
                                         v 2.0
                                                                               
    Created by:     Marian C. Buford                                                 
                    Conservation Resource Solutions, Inc. (CRS)
    Adapted from:   IsoneDayAheadBid.cls v 1.0 by Kevin L. Keegan, CRS
    Created on:     01.26.2011
    License:        Proprietary
    Copyright:      2011 Conservation Resource Solutions, Inc. All rights reserved.
    =============================================================================== */      
class IsoneDayAheadBid extends CAO
{
    private $p_id;
    private $p_assetIdentifier;
    private $p_startDay;
    private $p_stopDay;
    private $p_mw;
    private $p_offerPrice;
    private $p_curtailmentInitiationPrice;
    private $p_minimumInterruptionDuration;
    private $p_isSent;
    private $p_createdBy;
    private $p_createdDate;
    private $p_updatedBy;
    private $p_updatedDate;

/*  ===============================================================================
    FUNCTION : id()
    =============================================================================== */
    function id($id)
    {
        return $this->p_id;
    } // id()

/*  ===============================================================================
    FUNCTION : assetIdentifier()
    =============================================================================== */
    function assetIdentifier()
    {
        return $this->p_assetIdentifier;
    } // assetIdentifier()

/*  ===============================================================================
    FUNCTION : startDay()
    =============================================================================== */
    function startDay()
    {
        return $this->p_startDay;
    } // startDay()

/*  ===============================================================================
    FUNCTION : stopDay()
    =============================================================================== */
    function stopDay()
    {
        return $this->p_stopDay;
    } // stopDay()

/*  ===============================================================================
    FUNCTION : mw()
    =============================================================================== */
    function mw()
    {
        return $this->p_mw;
    } // mw()

/*  ===============================================================================
    FUNCTION : offerPrice()
    =============================================================================== */
    function offerPrice()
    {
        return $this->p_offerPrice;
    } // offerPrice()

/*  ===============================================================================
    FUNCTION : curtailmentInitiationPrice()
    =============================================================================== */
    function curtailmentInitiationPrice()
    {
        return $this->p_curtailmentInitiationPrice;
    } // curtailmentInitiationPrice()

/*  ===============================================================================
    FUNCTION : minimumInterruptionDuration()
    =============================================================================== */
    function minimumInterruptionDuration()
    {
        return $this->p_minimumInterruptionDuration;
    } // minimumInterruptionDuration()

/*  ===============================================================================
    FUNCTION : isSent()
    =============================================================================== */
    function isSent()
    {
        return $this->p_isSent;
    } // isSent()

/*  ===============================================================================
    FUNCTION : createdBy()
    =============================================================================== */
    function createdBy()
    {
        return $this->p_createdBy;
    } // createdBy()

/*  ===============================================================================
    FUNCTION : createdDate()
    =============================================================================== */
    function createdDate()
    {
        return $this->p_createdDate;
    } // createdDate()

/*  ===============================================================================
    FUNCTION : updatedBy()
    =============================================================================== */
    function updatedBy()
    {
        return $this->p_updatedBy;
    } // updatedBy()

/*  ===============================================================================
    FUNCTION : updatedDate()
    =============================================================================== */
    function updatedDate()
    {
        return $this->p_updatedDate;
    } // updatedDate()

/*  ===============================================================================
    CONSTRUCTOR
    =============================================================================== */
    function __construct()
    {
        parent::__construct();

        $this->clear();

    } // CONSTRUCTOR

/*  ===============================================================================
    FUNCTION : clear()
    =============================================================================== */
    function clear()
    {
        $this->p_id =                           0;
        $this->p_assetIdentifier =              '';
        $this->p_startDay =                     0;
        $this->p_stopDay =                      0;
        $this->p_mw =                           0;
        $this->p_offerPrice =                   0;
        $this->p_curtailmentInitiationPrice =   0;
        $this->p_minimumInterruptionDuration =  0;
        $this->p_isSent =                       false;
        $this->p_createdBy =                    0;
        $this->p_createdDate =                  0;
        $this->p_updatedBy =                    0;
        $this->p_updatedDate =                  0;

    } // clear()

/*  ===============================================================================
    FUNCTION : load()
    -----------------------------------variables-----------------------------------
    $id             integer     : Bid id
    $assetId        integer     : Asset Identifier
    $startDay       string      : Date to start, YYYY-mm-dd
    $stopDay        string      : Date to end, YYYY-mm-dd
    $mw             double      : Megawatts
    $offerPrice     decimal     : Offer Price
    $curtailment    decimal     : Curtailment Initiation Price
    $interruption   integer     : Minimum Interruption Duration
    $issent         integer     : Flag indicating whether bid has been sent
    $createdBy      integer     : Object ID of authenticated user
    $createdDate    string      : Date that the bid item was created
    $updatedBy      integer     : Object ID of authenticated user
    $updatedDate    string      : Date that the bid item was updated
    =============================================================================== */
    function load(
        $id,
        $assetId,
        $startDay,
        $stopDay,
        $mw,
        $offerPrice,
        $curtailment,
        $interruption,
        $issent,
        $createdBy,
        $createdDate,
        $updatedBy,
        $updatedDate
        )
    {
        $this->p_id =                           $id;
        $this->p_assetIdentifier =              $assetId;
        $this->p_startDay =                     $startDay;
        $this->p_stopDay =                      $stopDay;
        $this->p_mw =                           $mw;
        $this->p_offerPrice =                   $offerPrice;
        $this->p_curtailmentInitiationPrice =   $curtailment;
        $this->p_minimumInterruptionDuration =  $interruption;
        $this->p_isSent =                       $issent;
        $this->p_createdBy =                    $createdBy;
        $this->p_createdDate =                  $createdDate;
        $this->p_updatedBy =                    $updatedBy;
        $this->p_updatedDate =                  $updatedDate;

    } // load()

/*  ===============================================================================
    FUNCTION : save()
    =============================================================================== */
    function save()
    {
        if($this->p_createdDate == 0)
        {
            $p_createdDate = date('Y-m-d h:i:s');
            $sql = ' REPLACE INTO 
                        t_isonedayaheadbids 
                        (
                            AssetIdentifier, 
                            StartDay, 
                            StopDay, 
                            MW, 
                            OfferPrice, 
                            CurtailmentInitiationPrice, 
                            MinimumInterruptionDuration, 
                            IsSent, 
                            CreatedBy, 
                            CreatedDate 
                        ) 
                    VALUES 
                        (
                            '.$this->p_assetIdentifier.', 
                             "'.$this->p_startDay.'", 
                             "'.$this->p_stopDay.'", 
                             '.$this->p_mw.', 
                             '.$this->p_offerPrice.', 
                             '.$this->p_curtailmentInitiationPrice.', 
                             '.$this->p_minimumInterruptionDuration.', 
                             '.($this->p_isSent ? 1 : 0).', 
                             '.$this->p_createdBy.', 
                             Now()
                        )';
            $result = $this->processQuery($sql,$this->sqlConnection(),'insert');
            //$this->preDebugger($result);
        }
        else
        {
            $p_updatedDate = date('Y-m-d h:i:s');
            $sql = ' UPDATE
                        t_isonedayaheadbids
                    SET
                        AssetIdentifier = '.$this->p_assetIdentifier.',
                        StartDay = "'.$this->p_startDay.'",
                        StopDay = "'.$this->p_stopDay.'",
                        MW = '.$this->p_mw.',
                        OfferPrice = '.$this->p_offerPrice.',
                        CurtailmentInitiationPrice = '.$this->p_curtailmentInitiationPrice.',
                        MinimumInterruptionDuration = '.$this->p_minimumInterruptionDuration.',
                        '.($this->p_isSent ? 1 : 0).',
                        UpdatedBy = '.$this->p_updatedBy.',
                        UpdatedDate = "'.$this->p_updatedDate.'"
                  WHERE
                     IsoneDayAheadBidID = '.$this->p_id
                ;
            $result = $this->processQuery($sql,$this->sqlConnection(),'update');
            $this->preDebugger($result);
        }

        if(!$result['error'])
        {
            return $result['items'];
        }
        else
        {
            return false;
        }

    } // save()


/*  ===============================================================================
    DESTRUCTOR
    =============================================================================== */
    function __destruct()
    {
        parent::__destruct();
    } // DESTRUCTOR
}

/*  -----------------------------IsoneDayAheadBid.php------------------------------ */
?>

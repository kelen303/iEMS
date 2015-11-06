<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
class EventPerformanceSummaryLineItem {

    private $p_calledProgram;
    private $p_resourceOID;
    private $p_resourceCID;
    private $p_resourceCR;
    private $p_resource;
    private $p_zone;
    private $p_asset;
    private $p_assetID;
    private $p_isReportingAsset;
    private $p_committedReduction;
    private $p_adjustment;
    private $p_peakDelta;
    private $p_peakPCR;
    private $p_fcmBaseline;
    private $p_fcmLoad;
    private $p_fcmDelta;
    private $p_fcmPCR;
	private $p_createdDate;
	private $p_updatedDate;
    private $p_wasUpdated;

    private $p_DoNotDispatch;
    private $p_DispatchTime;
    private $p_EffectiveTime;
    private $p_RestorationTime;
    private $p_IsDispatched;
    private $p_IsRestore;

    function calledProgram()
    {
        return $this->p_calledProgram;
    }

    function resourceOID()
    {
        return $this->p_resourceOID;
    }

    function resourceCID()
    {
        return $this->p_resourceCID;
    }

    function resourceCR()
    {
        return $this->p_resourceCR;
    }

    function resource()
    {
        return $this->p_resource;
    }

    function zone()
    {
        return $this->p_zone;
    }

    function asset()
    {
        return $this->p_asset;
    }

    function assetID()
    {
        return $this->p_assetID;
    }

    function isReportingAsset()
    {
        return $this->p_isReportingAsset;
    }

    function createdDate()
    {
        return $this->p_createdDate;
    }

    function updatedDate()
    {
        return $this->p_updatedDate;
    }

    function wasUpdated()
    {
        return $this->p_wasUpdated;
    }


    function doNotDispatch()
    {
        return $this->p_doNotDispatch;
    }
    function dispatchTime()
    {
        return $this->p_dispatchTime;
    }
    function effectiveTime()
    {
        return $this->p_effectiveTime;
    }
    function restorationTime()
    {
        return $this->p_restorationTime;
    }
    function isDispatched()
    {
        return $this->p_isDispatched;
    }
    function isRestored()
    {
        return $this->p_isRestored;
    }


    function committedReduction($committedReduction = null)
    {
        if (isset($committedReduction)) {
            $this->p_committedReduction = $committedReduction;
        } else {
            if (is_numeric($this->p_committedReduction)) {
                return number_format($this->p_committedReduction, 3, ".", ",");
            } else {
                return $this->p_committedReduction;
            }
        }
    }

    function adjustment()
    {
        return number_format($this->p_adjustment, 3, ".", ",");
    }

    function peakDelta($peakDelta = null)
    {
        if (isset($peakDelta)) {
            $this->p_peakDelta = $peakDelta;
        } else {
            return number_format($this->p_peakDelta, 3, ".", ",");
        }
    }

    function peakPCR($peakPCR = null)
    {
        if (isset($peakPCR)) {
            $this->p_peakPCR = $peakPCR;
        } else {
            if (is_numeric($this->p_peakPCR)) {
                return number_format($this->p_peakPCR, 2, ".", ",");
            } else {
                return $this->p_peakPCR;
            }
        }
    }

    function fcmBaseline($fcmBaseline = null)
    {
        if (isset($fcmBaseline)) {
            $this->p_fcmBaseline = $fcmBaseline;
        } else {
            return number_format($this->p_fcmBaseline, 3, ".", ",");
        }
    }

    function fcmLoad($fcmLoad = null)
    {
        if (isset($fcmLoad)) {
            $this->p_fcmLoad = $fcmLoad;
        } else {
            return number_format($this->p_fcmLoad, 3, ".", ",");
        }
    }

    function fcmDelta($fcmDelta = null)
    {
        if (isset($fcmDelta)) {
            $this->p_fcmDelta = $fcmDelta;
        } else {
            return number_format($this->p_fcmDelta, 3, ".", ",");
        }
    }

    function fcmPCR($fcmPCR = null)
    {
        if (isset($fcmPCR)) {
            $this->p_fcmPCR = $fcmPCR;
        } else {
            if (is_numeric($this->p_fcmPCR)) {
                return number_format($this->p_fcmPCR, 2, ".", ",");
            } else {
                return $this->p_fcmPCR;
            }
        }
    }

    function __construct($calledProgram,
                         $resourceOID,
                         $resourceCID,
                         $resourceCR,
                         $resource,
                         $zone,
                         $asset,
                         $assetID,
                         $isReportingAsset,
                         $committedReduction,
                         $adjustment,
                         $fcmDelta,
                         $fcmPCR,
						 $createdDate,
						 $updatedDate,
                         $doNotDispatch,
                         $dispatchTime,
                         $effectiveTime,
                         $restorationTime,
                         $isDispatched,
                         $isRestored) {


        //array of objects:
        
        $this->p_calledProgram      = $calledProgram;
        $this->p_resourceOID        = $resourceOID;
        $this->p_resourceCID        = $resourceCID;
        $this->p_resourceCR         = $resourceCR;
        $this->p_resource           = $resource;
        $this->p_zone               = $zone;
        $this->p_asset              = $asset;
        $this->p_assetID            = $assetID;
        $this->p_isReportingAsset   = $isReportingAsset;
        $this->p_committedReduction = $committedReduction;
        $this->p_adjustment         = $adjustment;
        
        $this->p_fcmDelta           = $fcmDelta;
        $this->p_fcmPCR             = $fcmPCR;

        $this->p_createdDate        = $createdDate != null ? date('m-d-Y',strtotime($createdDate)) : null;
        $this->p_updatedDate        = $updatedDate != null ? date('m-d-Y',strtotime($updatedDate)) : null;

        $this->p_doNotDispatch      = $doNotDispatch;
        $this->p_dispatchTime       = $dispatchTime;
        $this->p_effectiveTime      = $effectiveTime;
        $this->p_restorationTime    = $restorationTime;
        $this->p_isDispatched       = $isDispatched;
        $this->p_isRestored         = $isRestored;

        $this->p_wasUpdated = $this->p_createdDate != $this->p_updatedDate ? true : false;
    }

    function Accumulate($committedReduction,
                        $peakDelta,
                        $fcmBaseline,
                        $fcmLoad,
                        $fcmDelta)
    {
        $this->p_committedReduction += str_replace(",", "", $committedReduction);
        $this->p_peakDelta += str_replace(",", "", $peakDelta);
        $this->p_peakPCR = ($this->p_peakDelta/$this->p_committedReduction) * 100.0;
        $this->p_fcmBaseline += str_replace(",", "", $fcmBaseline);
        $this->p_fcmLoad += str_replace(",", "", $fcmLoad);
        $this->p_fcmDelta += str_replace(",", "", $fcmDelta);
        $this->p_fcmPCR = ($this->p_fcmDelta/$this->p_committedReduction) * 100.0;
    }
}
?>

<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
class ContactReportLineItem extends CAO {

    private $p_profile;
    private $p_name;
    private $p_use;
    private $p_type;
    private $p_location;
    private $p_priorityLevel;
    private $p_contactValue;
    private $p_status;

    function profile()
    {
        return $this->p_profile;
    }

    function name()
    {
        return $this->p_name;
    }

    function contactUse()
    {
        return $this->p_use;
    }

    function type()
    {
        return $this->p_type;
    }

    function location()
    {
        return $this->p_location;
    }

    function priorityLevel()
    {
        return $this->p_priorityLevel;
    }

    function contactValue()
    {
        return $this->p_contactValue;
    }

    function status()
    {
        return $this->p_status;
    }

    function __construct($profile, $name, $use, $type, $location, $priorityLevel, $contactValue, $status) 
    {
        $this->p_profile = $profile;
        $this->p_name = $name;
        $this->p_use = $use;
        $this->p_type = $type;
        $this->p_location = $location;
        $this->p_priorityLevel = $priorityLevel;
        $this->p_contactValue = $contactValue;
        $this->p_status = $status;
    }
}
?>

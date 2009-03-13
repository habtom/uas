<?php

class User extends BaseUser
{

     public function __construct()
	{
		parent::__construct();                
                $this->setExpiresAt(time() + 360*86400);
	}

    public function __toString()
    {
        return $this->getName(). ' '. $this->getFathersName(). ' ('. $this->getEmailAddress() . ')';
    }

    public function getFullName()
    {
        return $this->getName() . " " . $this->getFathersName() . " " . $this->getGrandFathersName();
    }
    
    public function getEmailAddress()
    {
        return $this->getEmailLocalPart(). '@' . $this->getDomainName();
    }    
    
    public function ToggleStatus()
    {
        if($this->getStatus()=='activated')
        {
            $this->setStatus('disactivated');
        }
        elseif($this->getStatus()=='preregistered')
        {
            $this->setStatus('activated');
        }
        else
        {
            $this->setStatus('activated');
        }
        $this->save();
    }
    public function displayExtendExpiresAt()
    {
        $extend = time() + 360*86400;
        $this->setExpiresAt($extend);        
        echo $extend;
        //return true;
    }
}

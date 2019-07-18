<?php

class countryBlock {

  // Store Country
  private $countries = array("CA","US","CG");
  //http://api.ipinfodb.com/v3/ip-country/?key=
  // Store Visitors IP Address
  private $ip_address = null;
  
  // Store ipInfo object
  private $ipInfo = null;
  
  public function __construct($countries, $api_key, $path_to_script = '')
  {
    // Include ipInfo.inc.php with or without path
    include($path_to_script.'ipInfo.inc.php');
    
    // new ipInfo class with api_key from parameters
    $this->ipInfo = new ipInfo($api_key);
  
    // Save Countries to $countries
    $this->countries = $countries;
    
    // Save IP Address $ip_address
    $this->ip_address = $this->ipInfo->getIPAddress();
    
    // Check if cookie exists
    if($this->cookieCheck())
    {
      // returned true... cookie does not exist
      foreach($this->countries as $country)
      {
        // return true or false
        $blockable = $this->countryCheck($country);
        
        // check
        if($blockable)
        {
          // block
          $this->setCookie();
          return true;
        }
      }
      return false;
    }
    else
    {
      // don't block.. Already blocked...
      return true;
    }
  }
  
  /**
    * getCountry()
    *
    * Returns true or false based off if the country from the ip is in the $countries array
    *
    * @param string $country - country code of one country!
    *
    * @return true/false - true if same, false if not
    *
    */
  protected function countryCheck($country)
  {
    // Get Country from ipInfo API
    $userCountry = $this->ipInfo->getCountry($this->ip_address);
    
    // Compare...
    if($country == $userCountry['countryCode'])
    {
      // Should block
      return true;
    }
    else
    {
      // Shouldn't block
      return false;
    }
  }
  
  /**
    * cookieCheck()
    *
    * Returns true or false depending if the cookie ('ip_not_allowed') is set or not
    *
    * @return true/false - true if not set, false if is set
    *
    */
  protected function cookieCheck()
  {    
    if(!isset($_COOKIE['ip_not_allowed'])))
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  
  /**
    * setCookie()
    *
    * Sets cookie and returns true
    *
    * @return true
    *
    */
  protected function setCookie()
  {
    setcookie('ip_not_allowed', 'true');
    $_COOKIE['ip_not_allowed'] = 'true';
    return true;
  }

}

<?php

require_once dirname(__FILE__).'/../lib/userGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/userGeneratorHelper.class.php';

/**
 * user actions.
 *
 * @package    uas
 * @subpackage user
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class userActions extends autoUserActions
{
    public function executeListToggleStatus(sfWebRequest $request)
    {
        //$user = UserPeer::retrieveByPk($request->getParameter('id'));
       // $user = $this->getRoute()->getObject();
        $id = $request->getParameter('id'); 
        $user = UserPeer::retrieveByPk($id);
       
        $user->ToggleStatus();
        $this->getUser()->setFlash('notice', 'Status is changed successfully.');
        $this->redirect('@user');  
    } 
    public function executeBatchToggle_status(sfWebRequest $request)
    {
        $ids = $request->getParameter('ids'); 
        $users = UserPeer::retrieveByPks($ids);
        foreach ($users as $user)
        {
            $user->ToggleStatus();        
        }
       $this->getUser()->setFlash('notice', 'Status is changed successfully.');
       $this->redirect('@user');
    }
    public function executeListShow(sfWebRequest $request)
{
       if (!$request->getParameter('sf_culture'))
        {
        if ($this->getUser()->isFirstRequest())
        {
            $culture = $request->getPreferredCulture(array('en', 'ti'));
            $this->getUser()->setCulture($culture);
            $this->getUser()->isFirstRequest(false);
        }
        else
        {
            $culture = $this->getUser()->getCulture();
        }
        $this->redirect('@localized_homepage');
        }
        
        $this->user = $this->getRoute()->getObject();
        $this->getUser()->addUserToHistory($this->user); 

        $ftp = $this->user->getFtpAccounts();
        $this->ftp_account = array_pop($ftp);

        $samba = $this->user->getSambaAccounts();
        $this->samba_account = array_pop($samba);

        $unix = $this->user->getUnixAccounts();
        $this->unix_account = array_pop($unix);
    
    }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->forward404Unless($user = UserPeer::retrieveByPk($request->getParameter('id')), sprintf('Object  does not exist (%s).', $request->getParameter('id')));
    $user->delete();

    $this->redirect('user/index');
  }

    
}



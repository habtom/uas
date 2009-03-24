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
        $this->ftp_account = array_shift($ftp);
        $this->ftp_form = new FtpAccountForm();

        $samba = $this->user->getSambaAccounts();
        $this->samba_account = array_shift($samba);

        $unix = $this->user->getUnixAccounts();
        $this->unix_account = array_shift($unix);
    
    }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    $this->forward404Unless($user = UserPeer::retrieveByPk($request->getParameter('id')), sprintf('Object  does not exist (%s).', $request->getParameter('id')));
    $user->delete();

    $this->redirect('user/index');
  }

  public function executeResetpassword(sfWebRequest $request)
  {
    //Get auto Generated password, and the password will be passed to the view
    $password = new Password();
    $generated_pass = $password->getPassword();
    // Put the generated password on a session variable
    $this->getUser()->setFlash('generated_pass',$generated_pass);

    // Get the current ID of the user
    $current_id = $request->getParameter('id');
                 
    // create a criteria, encrypt, and save
    $criteria = new Criteria();
    $criteria->add(UserPeer::ID, $current_id);
    $criteria->add(UserPeer::CRYPT_PASSWORD, $password->getCryptHash());
    $criteria->add(UserPeer::UNIX_PASSWORD, $password->getUnixHash());
    $criteria->add(UserPeer::LM_PASSWORD, $password->getLmHash());
    $criteria->add(UserPeer::NT_PASSWORD, $password->getNtHash());
    UserPeer::doUpdate($criteria);

    // Flash message
    $this->getUser()->setFlash('notice', "User password has been reset");

    // Redirect the user back to the user's page
    $this->redirect('user/ListShow?id='.$current_id);
  }
  
}



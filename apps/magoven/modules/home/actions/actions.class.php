<?php

/**
 * home actions.
 *
 * @package    MagOven
 * @subpackage home
 * @author     MrKistic
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class homeActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $c = new Criteria();
    $c->addDescendingOrderByColumn(PublicationPeer::UPDATED_AT);
    $this->pubs = PublicationPeer::doSelect($c);

    $this->pubCount = count($this->pubs);
    $this->issueCount = IssuePeer::doCount(new Criteria());
  }
}

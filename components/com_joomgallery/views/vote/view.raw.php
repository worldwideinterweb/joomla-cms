<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/views/vote/view.raw.php $
// $Id: view.raw.php 3451 2011-10-21 13:57:03Z erftralle $
/****************************************************************************************\
**   JoomGallery 2                                                                      **
**   By: JoomGallery::ProjectTeam                                                       **
**   Copyright (C) 2008 - 2011  JoomGallery::ProjectTeam                                **
**   Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam                            **
**   Released under GNU GPL Public License                                              **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look                       **
**   at administrator/components/com_joomgallery/LICENSE.TXT                            **
\****************************************************************************************/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
 * Raw View class for the vote view
 *
 * @package JoomGallery
 * @since   1.5.6
 */
class JoomGalleryViewVote extends JoomGalleryView
{
  /**
   * Raw view display method
   *
   * @access  public
   * @return  void
   * @since   1.5.6
   */
  function display()
  {
    $db           = & JFactory::getDBO();
    $errorflag    = 0;
    $message      = '';
    $ratingHTML   = '';
    $tooltipclass = '';

    $model = &$this->getModel();

    if(!$model->vote($message, true))
    {
      $errorflag = 1;
      if($message == '')
      {
        $message = JText::_('COM_JOOMGALLERY_DETAIL_RATINGS_MSG_YOUR_VOTE_NOT_COUNTED');
      }
    }
    else
    {
      $message = JText::_('COM_JOOMGALLERY_DETAIL_RATINGS_MSG_YOUR_VOTE_COUNTED');

      // Get new rating for the image voted to refresh detail view
      $db->setQuery('SELECT
                       imgvotes,
                       imgvotesum,
                       '.JoomHelper::getSQLRatingClause().' AS rating
                     FROM
                       '._JOOM_TABLE_IMAGES.'
                     WHERE
                       id = '.$model->getId()
                   );

      $image = $db->loadObject();
      if($image)
      {
        $ratingHTML = JHTML::_('joomgallery.rating', $image, true, 'jg_starrating_detail', 'hasHintAjaxVote');
      }

      // Set CSS tooltip class in case of star rating
      if($this->_config->get('jg_ratingdisplaytype') == 1)
      {
        if($this->_config->get('jg_tooltips') == 2)
        {
          $tooltipclass = 'jg-tooltip-wrap';
        }
        else
        {
          if($this->_config->get('jg_tooltips') == 1)
          {
            $tooltipclass = 'default';
          }
        }
      }
    }

    // Set mime encoding
    $this->_doc->setMimeEncoding('text/plain');

    $json = '{"error":"'.$errorflag.'","message":"'.$message.'","rating":"'.str_replace('"', '\"', $ratingHTML).'"';
    if($tooltipclass)
    {
      $json .= ',"tooltipclass":"'.$tooltipclass.'"';
    }
    $json .= '}';

    echo $json;
  }
}
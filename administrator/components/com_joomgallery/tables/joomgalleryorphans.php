<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/tables/joomgalleryorphans.php $
// $Id: joomgalleryorphans.php 3378 2011-10-07 18:37:56Z aha $
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
 * JoomGallery maintenance table class
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class TableJoomgalleryOrphans extends JTable
{
  /** @var int Primary key */
  var $id           = null;
  /** @var string */
  var $fullpath     = null;
  /** @var string */
  var $type         = null;
  /** @var int */
  var $refid        = null;
  /** @var string */
  var $title        = null;
  
  function TableJoomgalleryOrphans(&$db)
  {
    parent::__construct(_JOOM_TABLE_ORPHANS, 'id', $db);
  }

  /**
   * Overloaded check function
   *
   * @access  public
   * @return  boolean true on success
   * @since   1.5.5
   */
  /*function check()
  {
    /*
    TODO: This filter is too rigorous,need to implement more configurable solution
    // specific filters
    $filter = & JFilterInput::getInstance( null, null, 1, 1 );
    $this->introtext = trim( $filter->clean( $this->introtext ) );
    $this->fulltext =  trim( $filter->clean( $this->fulltext ) );
    */

    /*if(empty($this->imgtitle))
    {
      $this->setError(JText::_('COM_JOOMGALLERY_COMMON_ALERT_IMAGE_MUST_HAVE_TITLE'));
      return false;
    }

    // clean up keywords -- eliminate extra spaces between phrases
    // and cr (\r) and lf (\n) characters from string
    if(!empty($this->metakey))
    {
      // array of characters to remove
      $bad_characters = array("\n", "\r", "\"", '<', '>');
      // remove bad characters
      $after_clean = JString::str_ireplace($bad_characters, '', $this->metakey);
      // create array using commas as delimiter
      $keys = explode(',', $after_clean);
      $clean_keys = array(); 
      foreach($keys as $key)
      {
        // ignore blank keywords
        if(trim($key))
        {  
          $clean_keys[] = trim($key);
        }
      }
      // put array back together delimited by ', '
      $this->metakey = implode(', ', $clean_keys);
    }
    
    // clean up description -- eliminate quotes and <> brackets
    if(!empty($this->metadesc))
    {
      $bad_characters = array("\"", '<', '>');
      $this->metadesc = JString::str_ireplace($bad_characters, '', $this->metadesc);
    }

    if(empty($this->alias))
    {
      if(!$this->store())
      {
        return false;
      }
      $this->alias = $this->imgtitle.'-'.$this->id;
    }
    $this->alias = JFilterOutput::stringURLSafe($this->alias);

    if(trim(str_replace('-', '', $this->alias)) == '')
    {
      $datenow      = & JFactory::getDate();
      $this->alias  = $datenow->toFormat('%Y-%m-%d-%H-%M-%S');
    }

    return true;
  }*/

  function reorderAll()
  {
    $query = 'SELECT DISTINCT catid
                FROM '.$this->_db->nameQuote($this->_tbl);
    $this->_db->setQuery($query);
    $catids = $this->_db->loadResultArray();

    foreach($catids as $catid)
    {
      $this->reorder('catid = '.$catid);
    }
  }

  /**
   * Returns the ordering value to place a new item first in its group
   *
   * @access  public
   * @param   string query WHERE clause for selecting MAX(ordering).
   * @return  int    the ordring number
   */
  function getPreviousOrder($where = '')
  {
    if(!in_array('ordering', array_keys($this->getProperties())))
    {
      $this->setError(get_class($this).' does not support ordering');
      return false;
    }

    $query = 'SELECT MIN(ordering)' .
        ' FROM ' . $this->_tbl .
        ($where ? ' WHERE '.$where : '');

    $this->_db->setQuery($query);
    $maxord = $this->_db->loadResult();

    if($this->_db->getErrorNum())
    {
      $this->setError($this->_db->getErrorMsg());
      return false;
    }

    return $maxord - 1;
  }
}
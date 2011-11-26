<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/models/fields/owner.php $
// $Id: owner.php 3379 2011-10-07 18:47:35Z aha $
/****************************************************************************************\
**   JoomGallery 2                                                                      **
**   By: JoomGallery::ProjectTeam                                                       **
**   Copyright (C) 2008 - 2011  JoomGallery::ProjectTeam                                **
**   Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam                            **
**   Released under GNU GPL Public License                                              **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look                       **
**   at administrator/components/com_joomgallery/LICENSE.TXT                            **
\****************************************************************************************/

defined('_JEXEC') or die( 'Restricted access' );

/**
 * Renders an owner select box form field
 *
 * @package JoomGallery
 * @since   2.0
 */
class JFormFieldOwner extends JFormField
{
  /**
   * The form field type.
   *
   * @access  protected
   * @var     string
   * @since   2.0
   */
  var  $type = 'Owner';

  /**
   * Returns the HTML for an owner select box form field.
   *
   * @access  protected
   * @return  object    The owner select box form field.
   * @since   2.0
   */
  function getInput()
  {
    return JHTML::_('joomselect.users', $this->name, $this->value, true, array(), null, false, 0, $this->id);
  }
}
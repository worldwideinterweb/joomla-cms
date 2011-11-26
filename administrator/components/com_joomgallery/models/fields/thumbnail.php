<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/models/fields/thumbnail.php $
// $Id: thumbnail.php 3386 2011-10-09 16:35:01Z erftralle $
/****************************************************************************************\
**   JoomGallery  2                                                                     **
**   By: JoomGallery::ProjectTeam                                                       **
**   Copyright (C) 2008 - 2011  JoomGallery::ProjectTeam                                **
**   Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam                            **
**   Released under GNU GPL Public License                                              **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look                       **
**   at administrator/components/com_joomgallery/LICENSE.TXT                            **
\****************************************************************************************/

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/**
 * Renders a thumbnail selection form field
 *
 * @package JoomGallery
 * @since   2.0
 */
class JFormFieldThumbnail extends JFormField
{
  /**
   * The form field type.
   *
   * @access  protected
   * @var     string
   * @since   2.0
   */
  var $type = 'Thumbnail';

  /**
   * Returns the HTML for a thumbnail selection form field.
   *
   * @access  protected
   * @return  object    The thumbnail selection form field.
   * @since   2.0
   */
  function getInput()
  {
    $db         = & JFactory::getDBO();
    $doc        = & JFactory::getDocument();
    $ambit      = & JoomAmbit::getInstance();

    $imagelib_id = $this->element['imagelib_id'] ? $this->element['imagelib_id'] : 'imagelib';

    $img =& JTable::getInstance('joomgalleryimages', 'Table');
    if($this->value)
    {
      $img->load($this->value);
    }
    else
    {
      $img->imgfilename = JText::_('COM_JOOMGALLERY_CATMAN_SELECT_THUMBNAIL_TIP');
    }

    $cids     = JRequest::getVar('cid', array(), '', 'array');

    $catid    = 0;
    if(isset($cids[0]))
    {
      $catid    = intval($cids[0]);
    }

    $path = JRoute::_('index.php?option='._JOOM_OPTION.'&controller=images&view=image&format=raw&type=thumb', false).'&cid=';

    $js = "
    function joom_selectimage(id, title, object, filename) {
      document.getElementById(object + '_id').value = id;
      document.getElementById(object + '_name').value = title;
      $('remove_button').removeClass('jg_displaynone');
      if(id != '') {
        document.getElementById('".$imagelib_id."').src = '".$path."' + id
      } else {
        document.getElementById('".$imagelib_id."').src = '../media/system/images/blank.png';
      }
      window.parent.SqueezeBox.close();
    }
    function joom_clearthumb() {
      $('remove_button').addClass('jg_displaynone');
      document.getElementById('".$this->id."_id').value = 0;
      document.getElementById('".$imagelib_id."').src = '../media/system/images/blank.png';
    }";
    $doc->addScriptDeclaration($js);

    $link = 'index.php?option=com_joomgallery&view=mini&extended=0&tmpl=component&object='.$this->id.'&type=category&catid='.$catid;

    JHTML::_('behavior.modal', 'a.modal');
    $html = '
    <div style="float: left;">
      <input style="background: #ffffff;" type="hidden" id="'.$this->id.'_name" value="'.htmlspecialchars($img->imgtitle, ENT_QUOTES, 'UTF-8').'" disabled="disabled" />
    </div>
    <div id="select_button" class="button2-left">
      <div class="blank">
        <a class="modal" title="'.JText::_('COM_JOOMGALLERY_CATMAN_SELECT_THUMBNAIL_TIP').'" href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 480}}">'.JText::_('COM_JOOMGALLERY_CATMAN_SELECT_THUMBNAIL').'</a>
      </div>
    </div>
    <input type="hidden" id="'.$this->id.'_id" name="'.$this->name.'" value="'.$this->value.'" />
    <a id="remove_button" '.(!$this->value ? 'class="jg_displaynone" ' : '').'title="'.JText::_('COM_JOOMGALLERY_CATMAN_REMOVE_CATTHUMB_TIP').'" href="javascript:joom_clearthumb();"><img src="../media/media/images/remove.png" alt="Remove" /></a>';

    return $html;
  }
}
<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/Plugins/JoomEditorBu/trunk/joombu.php $
// $Id: joombu.php 1989 2010-03-24 15:04:44Z chraneco $
/******************************************************************************\
**   JoomGallery Editor Button Plugin 'JoomBu' 2.0 BETA                       **
**   By: JoomGallery::ProjectTeam                                             **
**   Copyright (C) 2011  Patrick aka Chraneco                                 **
**   Released under GNU GPL Public License                                    **
**   License: http://www.gnu.org/copyleft/gpl.html                            **
\******************************************************************************/
/** ### Original Copyright: ###
 * @copyright Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license   GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

/**
 * Editor Image buton
 *
 * @package Editors-xtd
 * @since 1.5
 */
class plgButtonJoomBu extends JPlugin
{
  /**
   * Constructor
   *
   * For php4 compatability we must not use the __constructor as a constructor for plugins
   * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
   * This causes problems with cross-referencing necessary for the observer design pattern.
   *
   * @access  protected
   * @param   object    $subject The object to observe
   * @param   object    $params  The object that holds the plugin parameters
   * @return  void
   * @since   1.5.0
   */
  function plgButtonJoomBu(&$subject, $config)
  {
    parent::__construct($subject, $config);

    // Load the language file
    $this->loadLanguage('', JPATH_ADMINISTRATOR);
  }

  /**
   * Displays the button
   *
   * @access  public
   * @param   string  $name The name of the element
   * @return  object  A button object
   * @since   1.5.0
   */
  function onDisplay($name)
  {
    // Create a new button object
    $button = new JObject();
    $button->set('text', JText::_('PLG_JOOMBU_LABEL'));
    $button->set('name', 'image');

    // Task of the button
    if($this->params->get('extended', 1))
    {
      $link = 'index.php?option=com_joomgallery&amp;view=mini&amp;tmpl=component&amp;e_name='.$name.'&amp;catid=0&amp;extended=1';
      $button->set('link', $link);

      JHTML::_('behavior.modal');
      $button->set('modal', true);
      $button->set('options', "{handler: 'iframe', size: {x: 620, y: 470}}");// width:620

    }
    else
    {
      $doc = & JFactory::getDocument();

      $script = "
      function insertJoomPlu(editor) {
        jInsertEditorText('{joomplu:}', editor);
      }
      ";
      $doc->addScriptDeclaration($script);
      $button->set('onclick', 'insertJoomPlu(\''.$name.'\');return false;');
      $button->set('link', '#');
    }

    return $button;
  }
}
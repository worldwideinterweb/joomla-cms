<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/views/mini/view.html.php $
// $Id: view.html.php 3500 2011-11-08 18:09:36Z chraneco $
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
 * HTML View class for the Mini Joom view
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryViewMini extends JoomGalleryView
{
  /**
   * HTML view display method
   *
   * @access  public
   * @param   string  $tpl  The name of the template file to parse
   * @return  void
   * @since   1.5.5
   */
  function display($tpl = null)
  {
    $this->_doc->addStyleSheet(JURI::root().'/templates/system/css/system.css');
    $this->_doc->addStyleSheet($this->_ambit->getStyleSheet('mini.css'));

    // JavaScript for inserting the tag
    $this->_doc->addScript($this->_ambit->getScript('mini.js'));

    $e_name = $this->_mainframe->getUserStateFromRequest('joom.mini.e_name', 'e_name', 'text', 'cmd');

    $lists = array();

    $catid = $this->_mainframe->getUserStateFromRequest('joom.mini.catid', 'catid', 0, 'int');
    $url   = JRoute::_('index.php?view=mini&format=json', false);
    $lists['image_categories']         = JHTML::_('joomselect.categorylist', $catid, 'catid', 'onchange="javascript:ajaxRequest(\''.$url.'\', 0, \'catid=\' + this[this.selectedIndex].value)"', null, '- ', 'filter');
    $this->assignRef('catid', $catid);

    $extended     = $this->_mainframe->getUserStateFromRequest('joom.mini.extended', 'extended', 1, 'int');
    $pane_options = array();
    if($extended > 0)
    {
      $plugin = & JPluginHelper::getPlugin('content', 'joomplu');
      if(!count($plugin))
      {
        JError::raiseNotice(100, JText::_('COM_JOOMGALLERY_MINI_MSG_NOT_INSTALLED_OR_ACTIVATED'));
        $parameters = '';
      }
      else
      {
        $parameters = $plugin->params;
      }

      // Load plugin parameters
      $params = new JRegistry();
      $params->loadString($parameters);

      $options  = array();
      $arr      = array();
      $arr[]                  = JHTML::_('select.option', 'thumb', JText::_('COM_JOOMGALLERY_COMMON_THUMBNAIL'));
      $arr[]                  = JHTML::_('select.option', 'img', JText::_('COM_JOOMGALLERY_MINI_DETAIL'));
      $arr[]                  = JHTML::_('select.option', 'orig', JText::_('COM_JOOMGALLERY_MINI_ORIGINAL'));
      $options['type']        = JHTML::_('select.radiolist', $arr, 'jg_bu_type', null, 'value', 'text', $params->get('default_type', 'thumb'));
      $options['position']    = JHTML::_('list.positions', 'jg_bu_position', $params->get('default_position', ''));
      $arr      = array();
      $arr[]                  = JHTML::_('select.option', '0', JText::_('JNO'));
      $arr[]                  = JHTML::_('select.option', '1', JText::_('COM_JOOMGALLERY_MINI_DETAIL_VIEW'));
      $arr[]                  = JHTML::_('select.option', '2', JText::_('COM_JOOMGALLERY_MINI_CATEGORY_VIEW'));
      $options['linked']      = JHTML::_('select.radiolist', $arr, 'jg_bu_linked', null, 'value', 'text', $params->get('default_linked', 1));
      $arr      = array();
      $arr[]                  = JHTML::_('select.option', 'img', JText::_('COM_JOOMGALLERY_MINI_DETAIL'));
      $arr[]                  = JHTML::_('select.option', 'orig', JText::_('COM_JOOMGALLERY_MINI_ORIGINAL'));
      $options['linked_type'] = JHTML::_('select.radiolist', $arr, 'jg_bu_linked_type', null, 'value', 'text', $params->get('default_linked_type', 'img'));
      $arr      = array();
      $arr[]                  = JHTML::_('select.option', '0', JText::_('COM_JOOMGALLERY_MINI_CATEGORY_MODE_THUMBNAILS'));
      $arr[]                  = JHTML::_('select.option', '1', JText::_('COM_JOOMGALLERY_MINI_CATEGORY_MODE_TEXTLINK'));
      $options['category']    = JHTML::_('select.radiolist', $arr, 'jg_bu_category', null, 'value', 'text', $params->get('default_category_mode', 0));
      $arr      = array();
      $arr[]                  = JHTML::_('select.option', '0', JText::_('COM_JOOMGALLERY_MINI_CATEGORY_ORDERING_ORDERING'));
      $arr[]                  = JHTML::_('select.option', '1', JText::_('COM_JOOMGALLERY_MINI_CATEGORY_ORDERING_RANDOM'));
      $options['ordering']    = JHTML::_('select.genericlist', $arr, 'jg_bu_thumbnail_ordering', null, 'value', 'text', $params->get('default_category_ordering', 0));
      $options['class']       = '';
      $this->assignRef('options', $options);
      $this->assignRef('params',  $params);
      $pane_options  = array('allowAllClose' => 1 /*, 'startOffset' => 1, 'startTransition' => 1*/);

      $lists['category_categories'] = JHTML::_('joomselect.categorylist', $catid, 'category_catid', 'onchange="insertCategory(this.value, \''.$e_name.'\');" id="category_catid"');

      // Hidden images
      $this->_mainframe->setUserState('joom.mini.showhidden', $params->get('showhidden'));

      // Upload
      if($params->get('upload_enabled'))
      {
        $catids = explode(',', $params->get('upload_catids'));
        if($params->get('upload_catids') && count($catids))
        {
          $uploadcategories = $this->get('uploadCategories');
          $categories = array();
          foreach($uploadcategories as $category)
          {
            if(in_array($category->cid, $catids))
            {
              $categories[] = JHTML::_('select.option', $category->cid, JHTML::_('joomgallery.categorypath', $category->cid, ' - ', false, false, true));
            }
          }

          if(!count($categories))
          {
            $params->set('upload_enabled', 0);
            $lists['upload_categories'] = '';
          }
          else
          {
            $lists['upload_categories'] = JHTML::_('select.genericlist', $categories, 'catid');
          }
        }
        else
        {
          $lists['upload_categories'] = JHTML::_('joomselect.categorylist', 0, 'catid', null, null, '- ', null, 'joom.upload');
        }

        if($params->get('upload_enabled'))
        {
          $this->_doc->addScript($this->_ambit->getScript('miniupload.js'));
          $this->_doc->addScriptDeclaration('    var jg_filenamewithjs = '.$this->_config->jg_filenamewithjs.';
          var jg_ffwrong = \''.$this->_config->get('jg_wrongvaluecolor').'\';
          var jg_inputcounter = 1;');
          JText::script('COM_JOOMGALLERY_COMMON_ALERT_YOU_MUST_SELECT_CATEGORY');
          JText::script('COM_JOOMGALLERY_COMMON_PLEASE_SELECT_IMAGE');
          JText::script('COM_JOOMGALLERY_UPLOAD_ALERT_YOU_MUST_SELECT_ONE_FILE');
          JText::script('COM_JOOMGALLERY_COMMON_ALERT_IMAGE_MUST_HAVE_TITLE');
          JText::script('COM_JOOMGALLERY_UPLOAD_ALERT_FILENAME_DOUBLE_ONE');
          JText::script('COM_JOOMGALLERY_UPLOAD_ALERT_FILENAME_DOUBLE_TWO');
          JText::script('COM_JOOMGALLERY_UPLOAD_ALERT_WRONG_FILENAME');
          JText::script('COM_JOOMGALLERY_UPLOAD_ALERT_WRONG_EXTENSION');
          JText::script('COM_JOOMGALLERY_UPLOAD_ALERT_WRONG_VALUE');
        }
      }

      // Create Category
      if($params->get('create_category'))
      {
        $catids = explode(',', $params->get('parent_catids'));
        if($params->get('parent_catids') && count($catids))
        {
          $parentcategories = $this->get('parentCategories');
          $categories = array();
          foreach($parentcategories as $category)
          {
            if(in_array($category->cid, $catids))
            {
              $categories[] = JHTML::_('select.option', $category->cid, JHTML::_('joomgallery.categorypath', $category->cid, ' - ', false, false, true));
            }
          }

          if(!count($categories))
          {
            $params->set('create_category', 0);
            $lists['parent_categories'] = '';
          }
          else
          {
            $lists['parent_categories'] = JHTML::_('select.genericlist', $categories, 'parent_id');
          }
        }
        else
        {
          $lists['parent_categories'] = JHTML::_('joomselect.categorylist', 0, 'parent_id');
        }

        if($params->get('create_category'))
        {
          if(!$params->get('upload_enabled'))
          {
            $this->_doc->addScript($this->_ambit->getScript('miniupload.js'));
            $this->_doc->addScriptDeclaration('
        var jg_ffwrong = \''.$this->_config->get('jg_wrongvaluecolor').'\';');
          }
          JText::script('COM_JOOMGALLERY_COMMON_ALERT_CATEGORY_MUST_HAVE_TITLE');
        }
      }
    }

    JHTML::_('behavior.tooltip');
    JHTML::_('behavior.tooltip', '.hasMiniTip');

    jimport('joomla.html.pane');
    $tabs     = & JPane::getInstance('tabs');
    $sliders  = & JPane::getInstance('sliders', $pane_options);

    // Pagination
    $total    = &$this->get('TotalImages');

    // Calculation of the number of total pages
    $limit    = $this->_mainframe->getUserStateFromRequest('joom.mini.limit', 'limit', 30, 'int');
    if(!$limit)
    {
      $totalpages = 1;
    }
    else
    {
      $totalpages = floor($total / $limit);
      $offcut     = $total % $limit;
      if($offcut > 0)
      {
        $totalpages++;
      }
    }

    $total = number_format($total, 0, ',', '.');

    // Get the current page
    $page = JRequest::getInt('page', 0);
    if($page > $totalpages)
    {
      $page = $totalpages;
    }
    if($page < 1)
    {
      $page = 1;
    }

    // Limitstart
    $limitstart = ($page - 1) * $limit;
    JRequest::setVar('limitstart', $limitstart);

    if($total <= $limit)
    {
      $limitstart = 0;
      JRequest::setVar('limitstart', $limitstart);
    }

    JRequest::setVar('limit', $limit);

    $images = &$this->get('Images');

    foreach($images as $key => $image)
    {
      $image->thumb_src = null;
      $thumb = $this->_ambit->getImg('thumb_path', $image);
      if($image->imgthumbname && is_file($thumb))
      {
        $imginfo              = getimagesize($thumb);
        $image->thumb_src     = $this->_ambit->getImg('thumb_url', $image);
        $image->thumb_width   = $imginfo[0];
        $image->thumb_height  = $imginfo[1];
        $this->image          = $image;
        $overlib              = $this->loadTemplate('overlib');
        $image->overlib       = str_replace(array("\r\n", "\r", "\n"), '', htmlspecialchars($overlib, ENT_QUOTES, 'UTF-8'));
      }

      $images[$key]           = $image;
    }

    // Limit Box
    $limits = array ();

    // Create the option list
    for($i = 5; $i <= 30; $i += 5)
    {
      $limits[] = JHTML::_('select.option', $i);
    }
    $limits[] = JHTML::_('select.option', '50');
    $limits[] = JHTML::_('select.option', '100');
    $limits[] = JHTML::_('select.option', '0', JText::_('ALL'));

    $url      = JRoute::_('index.php?view=mini&format=json', false);
    $lists['limit'] = JHTML::_('select.genericlist',  $limits, 'limit', 'class="inputbox" size="1" onchange="javascript:ajaxRequest(\''.$url.'\', 0, \'limit=\' + this[this.selectedIndex].value)"', 'value', 'text', $limit);

    $this->_doc->addScriptDeclaration('
    var jg_minis_page = '.$page.';');

    JText::script('COM_JOOMGALLERY_MINI_PLEASE_ENTER_TEXT');

    $object = $this->_mainframe->getUserStateFromRequest('joom.mini.object', 'object', '', 'cmd');
    $search = $this->_mainframe->getUserStateFromRequest('joom.mini.search', 'search', '', 'string');

    $this->assignRef('images',      $images);
    $this->assignRef('lists',       $lists);
    $this->assignRef('extended',    $extended);
    $this->assignRef('tabs',        $tabs);
    $this->assignRef('sliders',     $sliders);
    $this->assignRef('total',       $total);
    $this->assignRef('totalpages',  $totalpages);
    $this->assignRef('page',        $page);
    $this->assignRef('object',      $object);
    $this->assignRef('search',      $search);
    $this->assignRef('e_name',      $e_name);

    parent::display($tpl);
  }
}
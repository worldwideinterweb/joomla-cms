<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/views/userpanel/view.html.php $
// $Id: view.html.php 3401 2011-10-14 06:39:05Z chraneco $
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
 * HTML View class for the user panel view
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryViewUserpanel extends JoomGalleryView
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
    if(!$this->_config->get('jg_userspace'))
    {
      $msg = JText::_('ALERTNOTAUTH');

      $this->_mainframe->redirect(JRoute::_('index.php?view=gallery', false), $msg, 'notice');
    }

    // TODO: This check may be removed later on
    if(!$this->_user->get('id'))
    {
      $this->_mainframe->redirect(JRoute::_('index.php?view=gallery', false), JText::_('COM_JOOMGALLERY_COMMON_MSG_YOU_ARE_NOT_LOGGED'), 'notice');
    }

    $params = &$this->_mainframe->getParams();

    // Breadcrumbs
    if($this->_config->get('jg_completebreadcrumbs'))
    {
      $breadcrumbs  = &$this->_mainframe->getPathway();
      $breadcrumbs->addItem(JText::_('COM_JOOMGALLERY_COMMON_USER_PANEL'));
    }

    // Header and footer
    JoomHelper::prepareParams($params);

    $pathway  = JText::_('COM_JOOMGALLERY_COMMON_USER_PANEL');

    $backtarget = JRoute::_('index.php?view=gallery');
    $backtext   = JText::_('COM_JOOMGALLERY_COMMON_BACK_TO_GALLERY');

    // Get number of images and hits in gallery
    $numbers  = JoomHelper::getNumberOfImgHits();

    // Load modules at position 'top'
    $modules['top'] = JoomHelper::getRenderedModules('top');
    if(count($modules['top']))
    {
      $params->set('show_top_modules', 1);
    }
    // Load modules at position 'btm'
    $modules['btm'] = JoomHelper::getRenderedModules('btm');
    if(count($modules['btm']))
    {
      $params->set('show_btm_modules', 1);
    }

    // Display button 'Upload' only if there is at least
    // one category into which the user is allowed to upload
    if(count(JoomHelper::getAuthorisedCategories('joom.upload')))
    {
      $params->set('show_upload_button', 1);
    }
    else
    {
      $params->set('show_upload_button', 0);
    }

    // Display button 'Categories' if the current user is allowed
    // to create categories or if there are categories owned by him
    if(   $this->_user->authorise('core.create', _JOOM_OPTION)
      ||  count(JoomHelper::getAuthorisedCategories('core.create'))
      ||  count($this->get('Categories'))
      )
    {
      $params->set('show_categories_button', 1);
    }
    else
    {
      $params->set('show_categories_button', 0);
    }

    // Prepare pagelimit choices
    $default_limit  = $this->_mainframe->getCfg('list_limit');
    $limit          = $this->_mainframe->getUserStateFromRequest('joom.userpanel.limit', 'limit', $default_limit, 'int');
    $limitstart     = JRequest::getInt('limitstart', 0);

    // In case limit has been changed, adjust limitstart accordingly
    $limitstart = ( $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0 );

    // Check if filter has changed
    $filter       = JRequest::getInt('filter', null);
    $filter_state = $this->_mainframe->getUserState('joom.userpanel.filter');
    if(is_null($filter))
    {
      $filter = $filter_state;
      if(is_null($filter))
      {
        $filter = 0;
      }
    }
    else
    {
      $this->_mainframe->setUserState('joom.userpanel.filter', $filter);
      if($filter != $filter_state)
      {
        // Number of images may change now, so go to first page
        $limitstart = 0;
      }
    }
    JRequest::setVar('filter', $filter);

    // Check if ordering has changed
    $ordering       = JRequest::getInt('ordering', null);
    $ordering_state = $this->_mainframe->getUserState('joom.userpanel.ordering');
    if(is_null($ordering))
    {
      $ordering = $ordering_state;
      if(is_null($ordering))
      {
        $ordering = 0;
      }
    }
    else
    {
      $this->_mainframe->setUserState('joom.userpanel.ordering', $ordering);
      if($ordering != $ordering_state)
      {
        // Ordering has changed, so go to first page
        $limitstart = 0;
      }
    }
    JRequest::setVar('ordering', (int) $ordering);

    // Check if category filter has changed
    $catfilter       = JRequest::getInt('catid', null);
    $catfilter_state = $this->_mainframe->getUserState('joom.userpanel.catfilter');
    if(is_null($catfilter))
    {
      $catfilter = $catfilter_state;
      if(is_null($catfilter))
      {
        $catfilter = 0;
      }
    }
    else
    {
      $this->_mainframe->setUserState('joom.userpanel.catfilter', $catfilter);
      if($catfilter != $catfilter_state)
      {
        // Category filter has changed, so go to first page
        $limitstart = 0;
      }
    }
    JRequest::setVar('catid', (int) $catfilter);

    // Check if search filter has changed
    $search       = JRequest::getVar('search', null);
    $search_state = $this->_mainframe->getUserState('joom.userpanel.search');
    if(is_null($search))
    {
      $search = $search_state;
      if(is_null($search))
      {
        $search = '';
      }
    }
    else
    {
      $this->_mainframe->setUserState('joom.userpanel.search', $search);
      if($search !== $search_state)
      {
        // Search pattern has changed, so go to first page
        $limitstart = 0;
      }
    }
    JRequest::setVar('search', $search);

    $lists = array();

    // Image sorting
    $o_options[] = JHTML::_('select.option', 0, JText::_('COM_JOOMGALLERY_COMMON_OPTION_ORDERBY_DATE_ASC'));
    $o_options[] = JHTML::_('select.option', 1, JText::_('COM_JOOMGALLERY_COMMON_OPTION_ORDERBY_DATE_DESC'));
    $o_options[] = JHTML::_('select.option', 2, JText::_('COM_JOOMGALLERY_COMMON_OPTION_ORDERBY_TITLE_ASC'));
    $o_options[] = JHTML::_('select.option', 3, JText::_('COM_JOOMGALLERY_COMMON_OPTION_ORDERBY_TITLE_DESC'));
    $o_options[] = JHTML::_('select.option', 4, JText::_('COM_JOOMGALLERY_COMMON_OPTION_ORDERBY_HITS_ASC'));
    $o_options[] = JHTML::_('select.option', 5, JText::_('COM_JOOMGALLERY_COMMON_OPTION_ORDERBY_HITS_DESC'));
    $o_options[] = JHTML::_('select.option', 6, JText::_('COM_JOOMGALLERY_COMMON_OPTION_ORDERBY_CATNAME_ASC') .' - '. JText::_('COM_JOOMGALLERY_COMMON_OPTION_ORDERBY_TITLE_ASC'));
    $o_options[] = JHTML::_('select.option', 7, JText::_('COM_JOOMGALLERY_COMMON_OPTION_ORDERBY_CATNAME_DESC') .' - '. JText::_('COM_JOOMGALLERY_COMMON_OPTION_ORDERBY_TITLE_DESC'));

    $lists['ordering'] = JHTML::_('select.genericlist', $o_options, 'ordering',
            'class="inputbox" size="1" onchange="form.submit();"',
            'value', 'text', $ordering);

    // Filter by type
    $s_options[] = JHTML::_('select.option', 0, JText::_('COM_JOOMGALLERY_COMMON_ALL'));
    $s_options[] = JHTML::_('select.option', 1, JText::_('COM_JOOMGALLERY_COMMON_OPTION_APPROVED_ONLY'));
    $s_options[] = JHTML::_('select.option', 2, JText::_('COM_JOOMGALLERY_COMMON_OPTION_NOT_APPROVED_ONLY'));
    $s_options[] = JHTML::_('select.option', 3, JText::_('COM_JOOMGALLERY_COMMON_OPTION_PUBLISHED_ONLY'));
    $s_options[] = JHTML::_('select.option', 4, JText::_('COM_JOOMGALLERY_COMMON_OPTION_NOT_PUBLISHED_ONLY'));

    $lists['filter'] = JHTML::_('select.genericlist', $s_options, 'filter',
            'class="inputbox" size="1" onchange="form.submit();"',
            'value', 'text', $filter);

    // Category filter
    $lists['cats'] = JHTML::_('joomselect.categorylist', $catfilter, 'catid', 'onchange="form.submit();"', null, '- ', 'filter');

    // Check if an option resp category is selected
    if(strpos($lists['cats'], "selected=\"selected\"") === false)
    {
      // This may happen if a category has been deleted, ugly hack but operative
      str_replace("value=\"0\"", "value=\"0\"  selected=\"selected\"", $lists['cats']);
      JRequest::setVar('catid', 0);
    }

    // Get data from the model
    $total  = &$this->get('Total');
    if($limitstart >= $total)
    {
      // This may happen for instance when an image has been deleted on a page with just one entry
      $limitstart = ($total > 0 && $total > $limit) ? (floor(($total - 1) / $limit) * $limit) : 0;
    }
    JRequest::setVar('limit',     (int) $limit);
    JRequest::setVar('limitstart', $limitstart);
    $slimitstart = ($limitstart > 0 ? '&limitstart='.$limitstart : '');

    $rows   = &$this->get('Images');

    foreach($rows as $key => &$row)
    {
      // Set the title attribute in a tag with title and/or description of image
      // if a box is activated
      if($this->_config->get('jg_detailpic_open') > 1)
      {
        $row->atagtitle = JHTML::_('joomgallery.getTitleforATag', $row);
      }
      else
      {
        // Set the imgtitle by default
        $row->atagtitle = 'title="'.$row->imgtitle.'"';
      }

      // Show editor links for that image
      $rows[$key]->show_edit_icon   = false;
      $rows[$key]->show_delete_icon = false;
      if( (   $this->_user->authorise('core.edit', _JOOM_OPTION.'.image.'.$rows[$key]->id)
          ||  (   $this->_user->authorise('core.edit.own', _JOOM_OPTION.'.image.'.$rows[$key]->id)
              &&  $rows[$key]->owner
              &&  $rows[$key]->owner == $this->_user->get('id')
              )
          )
      )
      {
        $rows[$key]->show_edit_icon = true;
      }

      if($this->_user->authorise('core.delete', _JOOM_OPTION.'.image.'.$rows[$key]->id))
      {
        $rows[$key]->show_delete_icon = true;
      }
    }

    // Create the navigation, only if images exist
    $pagination = null;
    if($total)
    {
      jimport('joomla.html.pagination');
      $pagination = new JPagination($total, $limitstart, $limit);
    }

    $this->assignRef('params',          $params);
    $this->assignRef('rows',            $rows);
    $this->assignRef('pagination',      $pagination);
    $this->assignRef('slimitstart',     $slimitstart);
    $this->assignRef('lists',           $lists);
    $this->assignRef('pathway',         $pathway);
    $this->assignRef('modules',         $modules);
    $this->assignRef('backtarget',      $backtarget);
    $this->assignRef('backtext',        $backtext);
    $this->assignRef('numberofpics',    $numbers[0]);
    $this->assignRef('numberofhits',    $numbers[1]);
    $this->assignRef('search',          $search);

    parent::display($tpl);
  }
}
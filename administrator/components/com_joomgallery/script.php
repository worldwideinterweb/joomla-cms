<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/script.php $
// $Id: script.php 3491 2011-10-31 20:12:30Z chraneco $
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
 * Install method
 * is called by the installer of Joomla!
 *
 * @access  protected
 * @return  void
 * @since   2.0
 */
class Com_JoomGalleryInstallerScript
{
  /**
   * Version string of the current version
   *
   * @var string
   */
  private $version = '2.0.0 BETA3';

  /**
   * Install method
   *
   * @param
   * @param
   * @return  boolean True on success, false otherwise
   * @since   2.0
   */
  public function install()
  {
    jimport('joomla.filesystem.file');
    $this->_addStyleDeclarations();

    // Create image directories
    require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomgallery'.DS.'helpers'.DS.'file.php';
    $thumbpath  = JPATH_ROOT.DS.'images'.DS.'joomgallery'.DS.'thumbnails';
    $imgpath    = JPATH_ROOT.DS.'images'.DS.'joomgallery'.DS.'details';
    $origpath   = JPATH_ROOT.DS.'images'.DS.'joomgallery'.DS.'originals';
    $result     = array();
    $result[]   = JFolder::create($thumbpath);
    $result[]   = JoomFile::copyIndexHtml($thumbpath);
    $result[]   = JFolder::create($imgpath);
    $result[]   = JoomFile::copyIndexHtml($imgpath);
    $result[]   = JFolder::create($origpath);
    $result[]   = JoomFile::copyIndexHtml($origpath);
    $result[]   = JoomFile::copyIndexHtml(JPATH_ROOT.DS.'images'.DS.'joomgallery');

    if(in_array(false, $result))
    {
      JError::raiseWarning(500, JText::_('Unable to create image directories!'));

      return false;
    }

    // Create news feed module
    $subdomain = '';
    $language = & JFactory::getLanguage();
    if(strpos($language->getTag(), 'de-') === false)
    {
      $subdomain = 'en.';
    }

    $row = JTable::getInstance('module');
    $row->title     = 'JoomGallery News';
    $row->ordering  = 1;
    $row->position  = 'joom_cpanel';
    $row->published = 1;
    $row->module    = 'mod_feed';
    $row->access    = 1;  // TODO: '1' does not have to be a valid access level
    $row->showtitle = 1;
    $row->params    = 'cache=1
    cache_time=15
    moduleclass_sfx=
    rssurl=http://www.'.$subdomain.'joomgallery.net/feed/rss.html
    rssrtl=0
    rsstitle=1
    rssdesc=0
    rssimage=1
    rssitems=3
    rssitemdesc=1
    word_count=30';
    $row->client_id = 1;
    $row->language  = '*';
    if(!$row->store())
    {
      JError::raiseWarning(500, JText::_('Unable to insert feed module data!'));
    }

    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->insert('#__modules_menu');
    $query->set('moduleid = '.$row->id);
    $query->set('menuid = 0');
    $db->setQuery($query);
    if(!$db->query())
    {
      JError::raiseNotice(500, JText::_('Unable to assign feed module!'));
    }

    // joom_settings.css
    $temp = JPATH_ROOT.DS.'media'.DS.'joomgallery'.DS.'css'.DS.'joom_settings.temp.css';
    $dest = JPATH_ROOT.DS.'media'.DS.'joomgallery'.DS.'css'.DS.'joom_settings.css';

    if(!JFile::move($temp, $dest))
    {
      JError::raiseWarning(500, JText::_('Unable to copy joom_settings.css!'));

      return false;
    }
?>
    <div style="margin:0px auto; text-align:center; width:360px;">
      <img src="../media/joomgallery/images/joom_logo.png" alt="JoomGallery Logo" />
      <h3 class="headline oktext">JoomGallery <?php echo $this->version; ?> was installed successfully.</h3>
      <p>You may now start using JoomGallery or download specific language files afore:</p>
      <div class="button2-left" style="margin-left:70px;">
        <div class="blank">
          <a title="Start" onclick="location.href='index.php?option=com_joomgallery';" href="#">Start now!</a>
        </div>
      </div>
      <div class="button2-left jg_floatright" style="margin-right:70px;">
        <div class="blank">
          <a title="Languages" onclick="location.href='index.php?option=com_joomgallery&controller=help';" href="#">Languages</a>
        </div>
      </div>
      <div style="clear:both;"></div>
    </div>
  <?php
  }

  /**
   * Update method
   *
   * @param
   * @param
   * @return  boolean True on success, false otherwise
   * @since   2.0
   */
  function update()
  {
    jimport('joomla.filesystem.file');
    $this->_addStyleDeclarations(); ?>
    <div style="margin:0px auto; text-align:center; width:360px;">
      <img src="../media/joomgallery/images/joom_logo.png" alt="JoomGallery Logo" />
    </div>
    <?php

    $error = false;

    // Delete temporary joom_settings.temp.css
    if(JFile::exists(JPATH_ROOT.DS.'media'.DS.'joomgallery'.DS.'css'.DS.'joom_settings.temp.css'))
    {
      if(!JFile::delete(JPATH_ROOT.DS.'media'.DS.'joomgallery'.DS.'css'.DS.'joom_settings.temp.css'))
      {
        JError::raiseWarning(500, JText::_('Unable to delete temporary joom_settings.temp.css!'));

        $error = true;
      }
    }

    // - Start Update Info - //
    echo '<div class="infobox headline">';
    echo '  Update JoomGallery to version: '.$this->version;
    echo '</div>';

    //******************* Delete folders/files ************************************
    echo '<div class="infobox">';
    echo '<p class="headline2">File system</p>';

    $delete_folders = array();

    echo '<p>';
    echo 'Looking for orphaned files and folders from the old installation<br />';

    // Unzipped folder of latest auto update with cURL
    $temp_dir = false;
    $query = "SELECT jg_pathtemp FROM #__joomgallery_config";
    $database = JFactory::getDBO();
    $database->setQuery($query);
    $temp_dir = $database->loadResult();
    if($temp_dir)
    {
      //$delete_folders[] = JPATH_SITE.DS.$temp_dir.'update';

      for($i = 0; $i <= 100; $i++)
      {
        $update_folder = JPATH_SITE.DS.$temp_dir.'update'.$i;
        if(JFolder::exists($update_folder))
        {
          $delete_folders[] = $update_folder;
        }
      }
    }

    $deleted = false;

    $jg_delete_error = false;
    foreach($delete_folders as $delete_folder)
    {
      if(JFolder::exists($delete_folder))
      {
        echo 'delete folder: '.$delete_folder.' : ';
        $result = JFolder::delete($delete_folder);
        if($result == true)
        {
          $deleted  = true;
          echo '<span class="oktext">ok</span>';
        }
        else
        {
          $jg_delete_error = true;
          echo '<span class="notoktext">not ok</span>';
        }
        echo '<br />';
      }
    }

    //Files
    $delete_files = array();

    // Cache file of the newsfeed for the update checker
    $delete_files[] = JPATH_ADMINISTRATOR.DS.'cache'.DS.md5('http://www.joomgallery.net/components/com_newversion/rss/extensions2.rss').'.spc';
    $delete_files[] = JPATH_ADMINISTRATOR.DS.'cache'.DS.md5('http://www.en.joomgallery.net/components/com_newversion/rss/extensions2.rss').'.spc';

    // Zip file of latest auto update with cURL
    $delete_files[] = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomgallery'.DS.'temp'.DS.'update.zip';

    foreach($delete_files as $delete_file)
    {
      if(JFile::exists($delete_file))
      {
        echo 'delete file: '.$delete_file.' : ';
        $result = JFile::delete($delete_file);
        if($result == true)
        {
          $deleted  = true;
          echo '<span class="oktext">ok</span>';
        }
        else
        {
          $jg_delete_error = true;
          echo '<span class="notoktext">not ok</span>';
        }
        echo '<br />';
      }
    }
   //******************* END delete folders/files ************************************

    if($deleted)
    {
      if($jg_delete_error)
      {
        echo '<span class="notoktext">problems in deletion of files/folders</span>';
        $error = true;
      }
      else
      {
        echo '<span class="oktext">files/folders sucessfully deleted</span>';
      }
    }
    else
    {
      echo '<span class="oktext">nothing to delete</span>';
    }

    echo '</p>';
    echo '</div>';

    //******************* Write joom_settings.css ************************************
    /*echo '<div class="infobox">';
    echo '<p class="headline2">CSS</p>';
    echo '<p>';
    echo 'Update configuration dependent CSS settings: ';

    require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomgallery'.DS.'includes'.DS.'defines.php';
    JLoader::register('JoomConfig', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomgallery'.DS.'helpers'.DS.'config.php');
    JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomgallery'.DS.'tables');

    $config = JoomConfig::getInstance('admin');
    if(!$config->save())
    {
      $error = true;
      echo '<span class="notoktext">not ok</span>';
    }
    else
    {
      echo '<span class="oktext">ok</span>';
    }

    echo '</p>';
    echo '</div>';*/
    //******************* End write joom_settings.css ************************************

    if($error)
    {
      echo '<h3 class="headline notoktext">Problem with the update to JoomGallery version '.$this->version.'<br />Please read the update infos above</h3>';
      JError::raiseWarning(500, JText::_('Problem with the update to JoomGallery version '.$this->version.'. Please read the update infos below'));
    }
    else
    { ?>
    <div style="margin:0px auto; text-align:center; width:360px;">
      <img src="../media/joomgallery/images/joom_logo.png" alt="JoomGallery Logo" />
      <h3 class="headline oktext">JoomGallery was updated to version <?php echo $this->version; ?> successfully.</h3>
      <p>You may now go on using JoomGallery or update your language files for JoomGallery:</p>
      <div class="button2-left" style="margin-left:70px;">
        <div class="blank">
          <a title="Start" onclick="location.href='index.php?option=com_joomgallery';" href="#">Go on!</a>
        </div>
      </div>
      <div class="button2-left jg_floatright" style="margin-right:70px;">
        <div class="blank">
          <a title="Languages" onclick="location.href='index.php?option=com_joomgallery&controller=help';" href="#">Languages</a>
        </div>
      </div>
      <div style="clear:both;"></div>
    </div>
<?php
    }

    return !$error;
  }

  /**
   * Uninstall method
   *
   * @param
   * @param
   * @return  boolean True on success, false otherwise
   * @since   2.0
   */
  function uninstall()
  {
    $path = JPATH_ROOT.DS.'images'.DS.'joomgallery';
    if(JFolder::exists($path))
    {
      JFolder::delete($path);
    }
    echo 'JoomGallery was uninstalled successfully!<br />
          Please remember to remove your images folders manually
          if you didn\'t use JoomGallery\'s default directories.';

    return true;
  }

  /**
   * Adds the style declaration for the install or update output to the document
   *
   * @return  void
   * @since   2.0
   */
  private function _addStyleDeclarations()
  {
    // CCS Styles
    $cssfile  = '
    <style type="text/css">
  p {
    margin:0.3em 0;
    padding:0.2em 0;
  }
  .infobox {
    margin:0.5em 0;
    padding:0.4em 0 0.4em 1em;
    background-color:#f0f0f0;
    border:2px solid #000;
  }
  .headline {
    font-size:1.5em;
    text-align:center;
    font-weight:bold;
  }
  .headline2 {
    font-size:1.3em;
    text-align:center;
    font-weight:bold;
  }
  .headline3 {
    text-align:center;
    font-weight:bold;
  }
  .oktext {
    color:#2d2;
    font-weight:bold;
  }
  .notoktext {
    color:#d22;
    font-weight:bold;
  }
  .noticetext {
    color:#f38201;
    font-weight:bold;
  }
  .button2-left{
    margin-top:10px;
    margin-bottom:30px;
  }
  .jg_floatright{
    float:right;
  }
  </style>';

    echo $cssfile;
  }
}
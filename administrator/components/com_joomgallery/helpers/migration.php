<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/helpers/migration.php $
// $Id: migration.php 3489 2011-10-30 09:00:04Z chraneco $
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
 * Helper class for migration procedures
 *
 * @package JoomGallery
 * @since   1.5.5
 */
abstract class JoomMigration
{
  /**
   * The name of the log file
   *
   * @var string
   */
  protected $logfilename;

  /**
   * JDatabase object
   *
   * @var object
   */
  protected $_db;

  /**
   * JDatabase object for external database
   *
   * @var object
   */
  protected $_db2;

  /**
   * JApplication object
   *
   * @var object
   */
  protected $_mainframe;

  /**
   * JoomConfig object
   *
   * @var object
   */
  protected $_config;

  /**
   * JoomAmbit object
   *
   * @var object
   */
  protected $_ambit;

  /**
   * The name of the migration
   * (should be unique)
   *
   * @var string
   */
  protected $migration;

  /**
   * The new ID of a category
   * which original ID was 1
   *
   * @var int
   */
  protected $new_catid;

  /**
   * Determines whether the gallery
   * from which we are migrating uses
   * another database
   *
   * @var boolean
   */
  protected $other_database = false;

  /**
   * Constructor
   *
   * @return  void
   * @since   1.5.0
   */
  public function __construct()
  {
    $this->_mainframe = & JFactory::getApplication('administrator');
    $this->_db        = & JFactory::getDBO();
    $this->_config    = & JoomConfig::getInstance();
    $this->_ambit     = & JoomAmbit::getInstance();

    $this->logfilename = 'migration.'.$this->migration.'.php';

    require_once JPATH_COMPONENT.DS.'helpers'.DS.'refresher.php';

    $this->refresher = new JoomRefresher(array('task' => 'migrate&migration='.$this->migration));

    JLog::addLogger(array('text_file' => $this->logfilename, 'text_entry_format' => '{DATETIME}	{PRIORITY}	{MESSAGE}'), JLog::ALL, array('migration'.$this->migration));

    $this->new_catid = $this->_mainframe->getUserState('joom.migration.data.new_catid', 1);
  }

  /**
   * Opens the logfile and puts first comments into it.
   *
   * @return  void
   * @since   1.5.0
   */
  public function start()
  {
    $this->_mainframe->setUserState('joom.migration.data', null);
    $this->writeLogfile('Migration Step started');
    $this->writeLogfile('max. execution time: '.@ini_get('max_execution_time').' seconds');
    $this->writeLogfile('calculated refresh time: '.(@ini_get('max_execution_time') * 0.8).' seconds');
    $this->writeLogfile('*****************************');
    $this->doMigration();
    $this->end();
  }

  /**
   * Continues the migration
   *
   * @return  void
   * since    2.0
   */
  public function migrate()
  {
    $this->writeLogfile('*****************************');
    $this->writeLogfile('Migration Step started');
    $this->doMigration();
    $this->end();
  }

  /**
   * Checks the remaining time of the current migration step
   *
   * @return  boolean True: Time remaining for migration, false: No more time left
   * @since   1.5.0
   */
  protected function checkTime()
  {
    return $this->refresher->check();
  }

  /**
   * Make a redirect to continue/end migration
   *
   * @param   string  $action Redirect to continue or end
   * @return  void
   * @since   1.5.0
   */
  protected function refresh($action = '')
  {
    $msg      = '';
    $msgType  = '';
    if($action != 'exit')
    {
      $this->writeLogfile('Refresh to continue the migration');
      $this->refresher->refresh();
    }
    else
    {
      $errors = $this->_mainframe->getUserState('joom.migration.data.errors');
      if($errors)
      {
        $this->writeLogfile('Errors recognized: '.$errors);
        $msg      = 'There were '.$errors.' error(s) during migration. Please have a look at the logfile.';
        $msgType  = 'error';
      }
      else
      {
        $msg      = 'Migration successfully ended';
      }

      $this->writeLogfile('Migration ended');
    }

    $this->refresher->refresh(null, 'display', $msg, $msgType);
  }

  /**
   * Puts last comments into the logfile,
   * closes it and sets redirect with report of success.
   *
   * @return  void
   * @since   1.5.0
   */
  protected function end()
  {
    $this->writeLogfile('end of migration - exiting');
    $this->writeLogfile('*****************************');
    $this->refresh('exit');
  }

  /**
   * Writes a line into the logfile
   *
   * @param   string  $line     The line to write into the logfile
   * @param   int     $priority Determines whether the line is an info or an error message
   * @return  void
   * @since   1.5.0
   */
  protected function writeLogfile($line, $priority = JLog::INFO)
  {
    JLog::add($line, $priority, 'migration'.$this->migration);
  }

  /**
   * Increases the error counter and optionally appends an error message
   *
   * @param   string  $msg  An optional error message to write into the logfile
   * @param   boolean $db   True, if a DB-Error occured
   * @return  void
   * @since   1.5.0
   */
  protected function setError($msg = null, $db = false)
  {
    $error_counter = $this->_mainframe->getUserState('joom.migration.data.errors');
    if(is_null($error_counter))
    {
      $error_counter = 1;
    }
    else
    {
      $error_counter++;
    }

    $this->_mainframe->setUserState('joom.migration.data.errors', $error_counter);

    if(!is_null($msg))
    {
      if(!$db)
      {
        $this->writeLogfile('Error: '.$msg, JLog::ERROR);
      }
      else
      {
        $replace = array("\r\n", "\r", "\n", '              ');
        $msg = str_replace($replace, ' ', $msg);
        $this->writeLogfile('DB error: '.$msg, JLog::ERROR);
      }
    }
  }

  /**
   * Checks general requirements for migration
   *
   * @param   string  $xml_dile     Path to the XML-File of the required extension
   * @param   string  $min_version  minimal required version, false if no check shall be performed
   * @param   string  $min_version  maximum possible version, false if no check shall be performed
   * @return  string  Message about state or boolean true or false.
   * @since   1.5.0
   */
  protected function checkGeneral($xml_file = null, $min_version = false, $max_version = false)
  {
    // Check extension
    if($xml_file)
    {
      if(!file_exists($xml_file))
      {
        return JText::_('COM_JOOMGALLERY_MIGMAN_EXTENSION_NOT_INSTALLED');
      }
      else
      {
        if($min_version || $max_version)
        {
          $xml = JFactory::getXMLParser('simple');
          $xml->loadFile($xml_file);

          $version_tag  = $xml->document->getElementByPath('version');
          $version      = $version_tag->data();
          if($min_version)
          {
            $comparision_min = version_compare($version, $min_version, '>=');
          }
          else
          {
            $comparision_min = true;
          }
          if($max_version)
          {
            $comparision_max = version_compare($version, $max_version, '<=');
          }
          else
          {
            $comparision_max = true;
          }
          if(!$comparision_min || !$comparision_max)
          {
            return JText::_('COM_JOOMGALLERY_MIGMAN_WRONG_VERSION');
          }
        }
      }
    }

    // Check whether site is offline
    $sitestatus = $this->_mainframe->getCfg('offline');
?>
<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
  <tr>
    <th colspan="3" align="center">
      <?php echo JText::_('COM_JOOMGALLERY_MIGMAN_RESULTS'); ?>
    </th>
  </tr>
  <tr>
    <td colspan="3">
      <h4><?php echo JText::_('COM_JOOMGALLERY_MIGMAN_SITESTATUS'); ?></h4>
    </td>
  </tr>
  <tr>
    <td width="80%"><?php echo JText::_('COM_JOOMGALLERY_MIGMAN_SITE_OFFLINE'); ?></td>
    <td width="10%" class="center">
      <?php echo $sitestatus ? JHTML::_('jgrid.published', true, 0, '', false) : '&nbsp;'; ?>
    </td>
    <td class="center">
      <?php echo !$sitestatus ? JHTML::_('jgrid.published', false, 0, '', false) : '&nbsp;'; ?>
    </td>
  </tr>
<?php
    return $sitestatus == 1;
  }

  /**
   * Checks required directories for migration
   *
   * @param   array   $dirs Array of directories to search for
   * @return  boolean True if all directories are existent, false otherwise
   * @since   1.5.0
   */
  protected function checkDirectories($dirs = array())
  {
    // Add JoomGallery directories
    $joom_dirs  = array($this->_ambit->get('img_path'),
                        $this->_ambit->get('orig_path'),
                        $this->_ambit->get('thumb_path'));
    $dirs = array_merge($dirs, $joom_dirs);
?>
  <tr>
    <td colspan="3">
      <h4><?php echo JText::_('COM_JOOMGALLERY_MIGMAN_DIRECTORIES'); ?></h4>
    </td>
  </tr>
<?php $ready = true;
      foreach($dirs as $dir): ?>
  <tr>
    <td><?php echo $dir; ?></td>
    <td class="center">
<?php   if(is_dir($dir)): ?>
      <?php echo JHTML::_('jgrid.published', true, 0, '', false); ?>
    </td>
    <td>
<?php   else: ?>
    </td>
    <td class="center">
      <?php echo JHTML::_('jgrid.published', false, 0, '', false);
          $ready = false; ?>
<?php   endif; ?>
    </td>
  </tr>
<?php endforeach;

    return $ready;
  }

  /**
   * Checks required database tables for migration
   *
   * @param   array   $tables Array of database tables to search for
   * @return  boolean True if all tables are existent, false otherwise
   * @since   1.5.0
   */
  protected function checkTables($tables = array())
  { ?>
  <tr>
    <td colspan="3">
      <h4><?php echo JText::_('COM_JOOMGALLERY_MIGMAN_DATABASETABLES'); ?></h4>
    </td>
  </tr>
<?php
    $ready = false;

    if(!$this->other_database || is_null($this->_db2))
    {
      $db = $this->_db;
    }
    else
    {
      $db = $this->_db2;
    }

    foreach($tables as $table)
    {
      $query = 'SELECT COUNT(*) FROM ' . $table;
      $db->setQuery($query);
      $count = $db->loadResult();
      if(!is_null($count))
      {
        if($count == 0)
        { ?>
        <tr>
          <td>
            <?php echo $table; ?>: <span style="color:#080; font-size:12px; font-weight:bold;"><?php echo JText::_('COM_JOOMGALLERY_MIGMAN_EMPTY'); ?></span>
          </td>
          <td class="center">
            <?php echo JHTML::_('jgrid.published', true, 0, '', false); ?>
          </td>
          <td>
            &nbsp;
          </td>
        </tr>
<?php   }
        else
        {
          $ready = true; ?>
        <tr>
          <td>
            <?php echo $table; ?>: <span style="color:#080; font-weight:bold;"><?php echo $count .' '.JText::_('COM_JOOMGALLERY_MIGMAN_ROWS'); ?></span>
          </td>
          <td class="center">
            <?php echo JHTML::_('jgrid.published', true, 0, '', false); ?>
          </td>
          <td>
            &nbsp;
          </td>
        </tr>
<?php   }
      }
      else
      { ?>
      <tr>
        <td>
          <?php echo $table; ?>: <span style="color:#f30; font-weight:bold;"><?php echo $db->getErrorMsg(); ?></span>
        </td>
        <td>
          &nbsp;
        </td>
        <td class="center">
          <?php echo JHTML::_('jgrid.published', false, 0, '', false); ?>
        </td>
      </tr>
<?php }
    }

    // Check JoomGallery tables
    $tables = array(_JOOM_TABLE_IMAGES,
                    _JOOM_TABLE_CATEGORIES,
                    _JOOM_TABLE_COMMENTS,
                    _JOOM_TABLE_NAMESHIELDS,
                    _JOOM_TABLE_USERS,
                    _JOOM_TABLE_VOTES);
    foreach($tables as $table)
    {
      if($table != _JOOM_TABLE_CATEGORIES)
      {
        $query = 'SELECT COUNT(*) FROM '.$table;
      }
      else
      {
        $query = 'SELECT COUNT(*) FROM '._JOOM_TABLE_CATEGORIES.' WHERE cid != 1';
      }
      $this->_db->setQuery($query);
      $count = $this->_db->loadResult();
      if(!is_null($count) && $count == 0 )
      { ?>
      <tr>
        <td>
          <?php echo $table; ?>: <span style="color:#080; font-size:12px; font-weight:bold;"><?php echo JText::_('COM_JOOMGALLERY_MIGMAN_EMPTY'); ?></span>
        </td>
        <td class="center">
          <?php echo JHTML::_('jgrid.published', true, 0, '', false); ?>
        </td>
        <td>
          &nbsp;
        </td>
      </tr>
<?php }
      else
      {
        $ready = false; ?>
      <tr>
        <td>
          <?php echo $table; ?>: <span style="color:#f30; font-weight:bold;"><?php echo $count .' '.JText::_('COM_JOOMGALLERY_MIGMAN_ROWS'); ?></span>
        </td>
        <td>
          &nbsp;
        </td>
        <td class="center">
          <?php echo JHTML::_('jgrid.published', false, 0, '', false); ?>
        </td>
      </tr>
<?php }
    }

    return $ready;
  }

  /**
   * Displays message whether migration can be started or not.
   * If yes, the button which starts the migration will be displayed, too.
   *
   * @param   boolean $ready  True, if the migration may be started
   * @return  void
   * @since   1.5.0
   */
  protected function endCheck($ready = false)
  { ?>
  <tr>
    <td colspan="3">
      <hr />
<?php
    if($ready)
    { ?>
      <div style="text-align:center;color:#080;padding:1em 0;font:bold 1.2em Verdana;">
        <?php echo JText::_('COM_JOOMGALLERY_MIGMAN_TRUE'); ?></div>
      <div style="text-align:center;"><?php echo JText::_('COM_JOOMGALLERY_MIGMAN_TRUE_LONG'); ?></div>
<?php
    }
    else
    { ?>
      <div style="text-align:center;color:#f30;padding:1em 0;font:bold 1.2em Verdana;">
        <?php echo JText::_('COM_JOOMGALLERY_MIGMAN_FALSE'); ?></div>
      <div style="text-align:center;"><?php echo JText::_('COM_JOOMGALLERY_MIGMAN_FALSE_LONG'); ?></div>
<?php
    } ?>
      <hr />
    </td>
  </tr>
  <tr>
<?php
  if($ready)
  { ?>
    <th colspan="3" style="text-align:center;">
      <form action="index.php?option=<?php echo _JOOM_OPTION; ?>&amp;controller=migration" method="post">
        <div>
          <input type="hidden" name="migration" value="<?php echo $this->migration; ?>">
          <input type="hidden" name="task" value="start">
          <button style="width:100px;"><?php echo JText::_('COM_JOOMGALLERY_MIGMAN_START'); ?></button>
        </div>
      </form>
      <hr />
    </th>
  <?php
    } ?>
  </tr>
</table>
<?php
  }

  /**
   * Starts all default migration checks.
   *
   * If you want to add additional migration checks
   * you will have to call all check functions above manually.
   * Please don't forget to check whether they return 'true'.
   *
   * @param   array   $dirs         Array of directories to search for
   * @param   array   $tables       Array of database tables to search for
   * @param   string  $xml          Path to the XML-File of the required extension
   * @param   string  $min_version  minimal required version, false if no check shall be performed
   * @param   string  $min_version  maximum possible version, false if no check shall be performed
   * @return  boolean True if migration may be started
   * @since   1.5.0
   */
  public function check($dirs = array(), $tables = array(), $xml = false, $min_version = false, $max_version = false)
  {
    $ready    = array();
    $ready[]  = $this->checkGeneral($xml, $min_version, $max_version);
    if($ready[0] !== true && $ready[0] !== false)
    {
      $this->_mainframe->redirect('index.php?option='._JOOM_OPTION.'&controller=migration', $ready[0], 'notice');
    }
    $ready[]  = $this->checkDirectories($dirs);
    $ready[]  = $this->checkTables($tables);
    $this->endCheck(!in_array(false, $ready));
  }

  /**
   * Main migration function
   *
   * @return  void
   * @since   1.5.0
   */
  abstract protected function doMigration();

  /**
   * Returns the maximum category ID of the extension to migrate from
   * This is necessary because in JoomGallery there can't be category
   * with ID 1, so we have to look for a new one.
   *
   * @return  int   The maximum category ID of the extension to migrate from
   * @since   2.0
   */
  abstract protected function getMaxCategoryId();

  /**
   * Creates directories and the database entry for a category
   *
   * @param   object  $cat          Holds information about the new category
   * @param   boolean $check_owner  Determines whether the owner ID shall be checked against the existing users
   * @return  boolean True on success, false otherwise
   * @since   1.5.0
   */
  public function createCategory($cat, $check_owner = false)
  {
    jimport('joomla.filesystem.file');

    // Some checks
    if(!isset($cat->cid))
    {
      $this->setError('Invalid category ID');

      return false;
    }
    if(!isset($cat->name))
    {
      $cat->name = 'no cat name';
    }
    if(!isset($cat->alias))
    {
      // Will be created later on
      $cat->alias = '';
    }
    if(!isset($cat->parent_id) || $cat->parent_id < 0)
    {
      $cat->parent_id = 1;
    }
    else
    {
      // If category with parent category ID 1 comes in we have
      // to set the parent ID to the newly created one because
      // category with ID 1 is the ROOT category in JoomGallery
      if($cat->parent_id == 1)
      {
        $cat->parent_id = $this->new_catid;
      }

      // Main categories are children of the ROOT category
      if($cat->parent_id == 0)
      {
        $cat->parent_id = 1;
      }
    }
    if(!isset($cat->description))
    {
      $cat->description = '';
    }
    if(!isset($cat->ordering))
    {
      $cat->ordering = 0;
    }
    if(!isset($cat->access))
    {
      $cat->access = $this->_mainframe->getCfg('access');
    }
    if(!isset($cat->published))
    {
      $cat->published = 0;
    }
    if(!isset($cat->hidden))
    {
      $cat->hidden = 0;
    }
    if(!isset($cat->in_hidden))
    {
      $cat->in_hidden = 0;
    }
    if(     !isset($cat->owner)
        ||  !is_numeric($cat->owner)
        ||  $cat->owner < 1
        ||  ($check_owner && !JUser::getTable()->load($cat->owner))
      )
    {
      $cat->owner = 0;
    }
    if(!isset($cat->thumbnail))
    {
      $cat->thumbnail = 0;
    }
    if(!isset($cat->img_position))
    {
      $cat->img_position = 0;
    }
    if(!isset($cat->params))
    {
      $cat->params = '';
    }
    if(!isset($cat->metakey))
    {
      $cat->metakey = '';
    }
    if(!isset($cat->metadesc))
    {
      $cat->metadesc = '';
    }

    $catid_changed = false;
    if($cat->cid == 1)
    {
      // Special handling for categories with ID 1 because that's the ROOT category in JoomGallery
      $cat->cid = $this->getMaxCategoryId() + 1;

      // Store the new category ID because we have to use it for the images and categories in this category
      $this->new_catid = $cat->cid;
      $this->_mainframe->setUserState('joom.migration.data.new_catid', $this->new_catid);

      $this->writeLogfile('New ID '.$cat->cid.' assigned to category '.$cat->name);

      $catid_changed = true;
    }

    // Make the category name safe
    JFilterOutput::objectHTMLSafe($cat->name);

    // If the new category should be assigned as subcategory...
    if($cat->parent_id > 1)
    {
      // Save the category path of parent category in a variable
      $parentpath = JoomHelper::getCatPath($cat->parent_id);
    }
    else
    {
      // Otherwise leave it empty
      $parentpath = '';
    }

    // Creation of category path
    // Cleaning of category title with function JoomFile::fixFilename
    // so special chars are converted and underscore removed
    // affects only the category path
    $newcatname = JoomFile::fixFilename($cat->name);
    // Add an underscore and the category ID
    // affects only the category path
    $newcatname = $newcatname.'_'.$cat->cid;
    // Prepend - if exists - the parent category path
    $cat->catpath = $parentpath.$newcatname;

    // Create the paths of category for originals, pictures, thumbnails
    $cat_originalpath  = JPath::clean($this->_ambit->get('orig_path').$cat->catpath);
    $cat_detailpath    = JPath::clean($this->_ambit->get('img_path').$cat->catpath);
    $cat_thumbnailpath = JPath::clean($this->_ambit->get('thumb_path').$cat->catpath);

    $result   = array();
    if(!JFolder::exists($cat_originalpath))
    {
      $result[] = JFolder::create($cat_originalpath);
      $result[] = JoomFile::copyIndexHtml($cat_originalpath);
    }
    if(!JFolder::exists($cat_detailpath))
    {
      $result[] = JFolder::create($cat_detailpath);
      $result[] = JoomFile::copyIndexHtml($cat_detailpath);
    }
    if(!JFolder::exists($cat_thumbnailpath))
    {
      $result[] = JFolder::create($cat_thumbnailpath);
      $result[] = JoomFile::copyIndexHtml($cat_thumbnailpath);
    }

    // Create database entry
    $query = $this->_db->getQuery(true)
          ->insert(_JOOM_TABLE_CATEGORIES)
          ->columns('cid, name, alias, parent_id, description, ordering, access, published, hidden, in_hidden, owner, thumbnail, img_position, catpath, params, metakey, metadesc')
          ->values( (int) $cat->cid.','.
                    $this->_db->quote($cat->name).','.
                    $this->_db->quote($cat->alias).','.
                    (int) $cat->parent_id.','.
                    $this->_db->quote($cat->description).','.
                    (int) $cat->ordering.','.
                    (int) $cat->access.','.
                    (int) $cat->published.','.
                    (int) $cat->hidden.','.
                    (int) $cat->in_hidden.','.
                    (int) $cat->owner.','.
                    (int) $cat->thumbnail.','.
                    (int) $cat->img_position.','.
                    $this->_db->quote($cat->catpath).','.
                    $this->_db->quote($cat->params).','.
                    $this->_db->quote($cat->metakey).','.
                    $this->_db->quote($cat->metadesc)
                  );

    $this->_db->setQuery($query);
    $result[] = $this->runQuery();

    // Create asset and alias
    $table = JTable::getInstance('joomgallerycategories', 'Table');
    $table->load($cat->cid);
    if($table->check())
    {
      $result['db'] = $table->store();
      if(!$result['db'])
      {
        $this->setError($table->getError(), true);
      }
    }

    if($catid_changed)
    {
      $cat->cid = 1;
    }

    if(!in_array(false, $result))
    {
      $this->writeLogfile('Category '.($catid_changed ? $this->new_catid : $cat->cid).' created: '.$cat->name);

      return true;
    }
    else
    {
      $this->writeLogfile(' -> Error creating category '.($catid_changed ? $this->new_catid : $cat->cid).': '.$cat->name);

      return false;
    }
  }

  /**
   * Runs a query set afore and handles the errors
   *
   * @param   string  The database method to use
   * @return  mixed   The result of the query
   * @since   2.0
   */
  protected function runQuery($method = '', $db = null)
  {
    if(!$method)
    {
      $method = 'query';
    }

    if(is_null($db))
    {
      $db = $this->_db;
    }

    try
    {
      $result = $db->$method();
    }
    catch(DatabaseException $e)
    {
      $this->setError($e->getMessage(), true);

      $result = null;
    }

    if($db->getErrorMsg())
    {
      $this->setError($db->getErrorMsg(), true);
    }

    return $result;
  }

  /**
   * Prepares a table for being able to iterate through its data sets
   *
   * For categories it is important that always the parent categories have to be migrated
   * afore their sub-categories. So please pass the parameters $table and $parent_name
   * if you are migrating categories.
   *
   * @param   object  $query          A query object holding the query to use
   * @param   string  $table          Name of the database table to prepare
   * @param   string  $parent_name    Name of the column which holds the parent id of each category
   * @param   array   $first_parents  Array of category IDs which can be migrated first (main categories)
   * @return  void
   * @since   2.0
   */
  protected function prepareTable($query, $table = null, $parent_name = null, $first_parents = array(0))
  {
    $this->parent_name = $parent_name;

    if(is_null($this->_mainframe->getUserState('joom.migration.data.counter')))
    {
      $this->_mainframe->setUserState('joom.migration.data.counter', 0);
    }

    if($table && $parent_name)
    {
      $parent_cats = $this->_mainframe->getUserState('joom.migration.data.parent_cats');
      if(is_null($parent_cats))
      {
        if(!$this->other_database || is_null($this->_db2))
        {
          $db = $this->_db;
        }
        else
        {
          $db = $this->_db2;
        }

        $db->setQuery('ALTER TABLE '.$table.' ADD '.$db->qn('joom_migrated').' INT(1) NOT NULL default 0');
        $this->runQuery('', $db);

        $this->_mainframe->setUserState('joom.migration.data.parent_cats', $first_parents);
      }
    }

    $this->query = $query;
  }

  /**
   * Returns the next data object of the query specified with method 'prepareTable'.
   *
   * 'prepareTable' has to be called first once.
   *
   * @return  object  The next data object
   * @since   2.0
   */
  protected function getNextObject()
  {
    if(!$this->other_database || is_null($this->_db2))
    {
      $db = $this->_db;
    }
    else
    {
      $db = $this->_db2;
    }

    if($this->parent_name)
    {
      $parent_cats = $this->_mainframe->getUserState('joom.migration.data.parent_cats');
      $this->query->clear('where')
                  ->where($this->parent_name.' IN ('.implode(',', $parent_cats).')')
                  ->where('joom_migrated = 0');
    }

    $counter = $this->_mainframe->getUserState('joom.migration.data.counter');

    $db->setQuery($this->query, $counter, 1);

    if(!$this->parent_name)
    {
      $counter++;
      $this->_mainframe->setUserState('joom.migration.data.counter', $counter);
    }

    return $this->runQuery('loadObject', $db);
  }

  /**
   * Marks a table row as migrated.
   *
   * This is important for migrating categories
   * @see method 'prepareTable'
   *
   * @param   int     $catid  ID of the data set which has been migrated
   * @param   string  $key    Primary key name of the table $table
   * @param   string  $table  Name of the database table the row is in
   * @return  void
   * @since   2.0
   */
  protected function markAsMigrated($catid, $key, $table)
  {
    if(!$this->other_database || is_null($this->_db2))
    {
      $db = $this->_db;
    }
    else
    {
      $db = $this->_db2;
    }

    $parent_cats = $this->_mainframe->getUserState('joom.migration.data.parent_cats');
    $parent_cats[] = (int) $catid;
    $parent_cats = array_unique($parent_cats);
    $this->_mainframe->setUserState('joom.migration.data.parent_cats', $parent_cats);

    $query = $db->getQuery(true)
          ->update($table)
          ->set('joom_migrated = 1')
          ->where($key.' = '.(int) $catid);
    $db->setQuery($query);
    $this->runQuery('', $db);
  }

  /**
   * Resets a table which was prepared for iteration with method 'prepareTable'
   *
   * This method must be called after the iteration has been finished
   *
   * @see method 'prepareTable'
   *
   * @param   string  $table  Name of the table to reset
   * @return  void
   * @since   2.0
   */
  protected function resetTable($table = '')
  {
    if(!$this->other_database || is_null($this->_db2))
    {
      $db = $this->_db;
    }
    else
    {
      $db = $this->_db2;
    }

    if($this->parent_name && $table)
    {
      $db->setQuery('ALTER TABLE '.$table.' DROP '.$db->qn('joom_migrated'));
      $this->runQuery('', $db);
    }

    $this->query = null;
    $this->parent_name = null;
    $this->_mainframe->setUserState('joom.migration.data.parent_cats', null);
    $this->_mainframe->setUserState('joom.migration.data.counter', null);
  }

  /**
   * Rebuilds the nested set tree
   *
   * This function has to be called after migrating all categories
   * unless during migration the nested set tree wasn't already created
   *
   * @return  void
   * @since   2.0
   */
  protected function rebuild()
  {
    $table = JTable::getInstance('joomgallerycategories', 'Table');

    $this->writeLogfile('Build the nested set tree');
    if($table->rebuild())
    {
      $this->writeLogfile('Nested set tree successfully build');
    }
    else
    {
      $this->writeLogfile(' -> Error building the nested set tree');
      if($error = $table->getError())
      {
        $this->setError($error);
      }
    }
  }

  /**
   * Creates images from the original one or moves the existing ones
   * into the folders of their category.
   *
   * [jimport('joomla.filesystem.file') has to be called afore]
   *
   * @param   object  $row          Holds information about the new image
   * @param   string  $origimage    The original image
   * @param   string  $detailimage  The detail image
   * @param   string  $thumbnail    The thumbnail
   * @param   boolean $newfilename  True if a new filename shall be generated
   * @param   boolean $copy         True if the image shall be copied into the new directory, not moved
   * @param   boolean $check_owner  Determines whether the owner ID shall be checked against the existing users
   * @return  boolean True on success, false otherwise
   * @since   1.5.0
   */
  public function moveAndResizeImage($row, $origimage, $detailimage = null, $thumbnail = null, $newfilename = false, $copy = false, $check_owner = false)
  {
    // Some checks
    if(!isset($row->id) || $row->id < 1)
    {
      $this->setError('Invalid image ID');

      return false;
    }
    if(!isset($row->imgfilename))
    {
      $this->setError('Image file name wasn\'t found');

      return false;
    }
    if(!isset($row->catid) || $row->catid < 1)
    {
      $this->setError('Invalid category ID');

      return false;
    }
    else
    {
      // If image with category ID 1 comes in we have to set
      // the category ID to the newly created one because
      // category with ID 1 is the ROOT category in JoomGallery
      if($row->catid == 1)
      {
        $row->catid = $this->new_catid;
      }
    }
    if(!isset($row->catpath))
    {
      $row->catpath = JoomHelper::getCatpath($row->catid);
    }
    if(!isset($row->imgtitle))
    {
      $row->imgtitle = str_replace(JFile::getExt($row->imgfilename), '', $row->imgfilename);
    }
    if(!isset($row->alias))
    {
      // Will be created later on
      $row->alias = '';
    }
    if(!isset($row->imgauthor))
    {
      $row->imgauthor = '';
    }
    if(!isset($row->imgtext))
    {
      $row->imgtext = '';
    }
    if(!isset($row->imgdate) || is_numeric($row->imgdate))
    {
      $date = JFactory::getDate();
      $row->imgdate = $date->toMySQL();
    }
    if(!isset($row->hits))
    {
      $row->hits = 0;
    }
    if(!isset($row->imgvotes))
    {
      $row->imgvotes = 0;
    }
    if(!isset($row->imgvotesum))
    {
      $row->imgvotesum = 0;
    }
    if(!isset($row->access) || $row->access < 1)
    {
      $row->access = $this->_mainframe->getCfg('access');
    }
    if(!isset($row->published))
    {
      $row->published = 0;
    }
    if(!isset($row->hidden))
    {
      $row->hidden = 0;
    }
    if(!isset($row->imgthumbname))
    {
      $row->imgthumbname = $row->imgfilename;
    }
    if(!isset($row->checked_out))
    {
      $row->checked_out = 0;
    }
    if(     !isset($row->owner)
        ||  !is_numeric($row->owner)
        ||  $row->owner < 1
        ||  ($check_owner && !JUser::getTable()->load($row->owner))
      )
    {
      $row->owner = 0;
    }
    if(!isset($row->approved))
    {
      $row->approved = 1;
    }
    if(!isset($row->useruploaded))
    {
      $row->useruploaded = 0;
    }
    if(!isset($row->ordering))
    {
      $row->ordering = 0;
    }
    if(!isset($row->params))
    {
      $row->params = '';
    }
    if(!isset($row->metakey))
    {
      $row->metakey = '';
    }
    if(!isset($row->metadesc))
    {
      $row->metadesc = '';
    }

    // Check whether one of the images to migrate already exist in the destination directory
    $orig_exists  = false;
    $img_exists   = false;
    $thumb_exists = false;
    $neworigimage   = $this->_ambit->getImg('orig_path', $row);
    $newdetailimage = $this->_ambit->getImg('img_path', $row);
    $newthumbnail   = $this->_ambit->getImg('thumb_path', $row);
    if(JFile::exists($neworigimage))
    {
      $orig_exists = true;
    }
    if(JFile::exists($newdetailimage))
    {
      $img_exists = true;
    }
    if(JFile::exists($newthumbnail))
    {
      $thumb_exists = true;
    }

    // Generate a new file name if requested or if a file with the current name already exists
    if($newfilename || $orig_exists || $img_exists || $thumb_exists)
    {
      $row->imgfilename   = $this->genFilename($row->imgtitle, $origimage, $row->catid);
      $row->imgthumbname  = $row->imgfilename;
    }

    $result = array();

    // Copy or move original image into the folder of the original images
    if(!$orig_exists)
    {
      // If it doesn't already exists with another name try to copy or move from source directory
      if(!JFile::exists($origimage))
      {
        $this->setError('Original image not found: '.$origimage);

        return false;
      }

      $neworigimage = $this->_ambit->getImg('orig_path', $row);
      if($copy)
      {
        $result['orig'] = JFile::copy(JPath::clean($origimage),
                                      JPath::clean($neworigimage));
        if(!$result['orig'])
        {
          $this->setError('Could not copy original image from '.$origimage.' to '.$neworigimage);

          return false;
        }
      }
      else
      {
        $result['orig'] = JFile::move(JPath::clean($origimage),
                                      JPath::clean($neworigimage));
        if(!$result['orig'])
        {
          $this->setError('Could not move original image from '.$origimage.' to '.$neworigimage);

          return false;
        }
      }
    }
    else
    {
      // If it already exists with another name copy it to a file with the new name
      if(!JFile::copy($neworigimage, $this->_ambit->getImg('orig_path', $row)))
      {
        $this->setError('Could not copy original image from '.$neworigimage.' to '.$this->_ambit->getImg('orig_path', $row));

        return false;
      }

      // Populate the new original file name and path because it will be
      // necessary for deleting it of deleting original images is configured
      $neworigimage = $this->_ambit->getImg('orig_path', $row);
    }

    if(!$img_exists)
    {
      // If it doesn't already exists with another name try to copy or move from source directory or create a new one
      $newdetailimage = $this->_ambit->getImg('img_path', $row);
      if(is_null($detailimage) || !JFile::exists($detailimage))
      {
        // Create new detail image
        $debugoutput = '';
        $result['detail'] = JoomFile::resizeImage($debugoutput,
                                                  $neworigimage,
                                                  $newdetailimage,
                                                  false,
                                                  $this->_config->get('jg_maxwidth'),
                                                  false,
                                                  $this->_config->get('jg_thumbcreation'),
                                                  $this->_config->get('jg_thumbquality'),
                                                  true,
                                                  0
                                                  );
        if(!$result['detail'])
        {
          $this->setError('Could not create detail image '.$newdetailimage);
        }
      }
      else
      {
        // Copy or move existing detail image
        if($copy)
        {
          $result['detail'] = JFile::copy(JPath::clean($detailimage),
                                          JPath::clean($newdetailimage));
          if(!$result['detail'])
          {
            $this->setError('Could not copy detail image from '.$detailimage.' to '.$newdetailimage);
          }
        }
        else
        {
          $result['detail'] = JFile::move(JPath::clean($detailimage),
                                          JPath::clean($newdetailimage));
          if(!$result['detail'])
          {
            $this->setError('Could not move detail image from '.$detailimage.' to '.$newdetailimage);
          }
        }
      }
    }
    else
    {
      // If it already exists with another name copy it to a file with the new name
      $result['detail'] = JFile::copy($newdetailimage, $this->_ambit->getImg('img_path', $row));
      if(!$result['detail'])
      {
        $this->setError('Could not copy detail image from '.$newdetailimage.' to '.$this->_ambit->getImg('img_path', $row));
      }
    }

    if(!$thumb_exists)
    {
      // If it doesn't already exists with another name try to copy or move from source directory or create a new one
      $newthumbnail = $this->_ambit->getImg('thumb_path', $row);
      if(is_null($thumbnail) || !JFile::exists($thumbnail))
      {
        // Create new thumbnail
        $debugoutput = '';
        $result['thumb'] = JoomFile::resizeImage( $debugoutput,
                                                  $neworigimage,
                                                  $newthumbnail,
                                                  $this->_config->get('jg_useforresizedirection'),
                                                  $this->_config->get('jg_thumbwidth'),
                                                  $this->_config->get('jg_thumbheight'),
                                                  $this->_config->get('jg_thumbcreation'),
                                                  $this->_config->get('jg_thumbquality'),
                                                  false,
                                                  $this->_config->get('jg_cropposition')
                                                );
        if(!$result['thumb'])
        {
          $this->setError('Could not create thumbnail '.$newthumbnail);
        }
      }
      else
      {
        // Copy or move existing thumbnail
        if($copy)
        {
          $result['thumb'] = JFile::copy(JPath::clean($thumbnail),
                                         JPath::clean($newthumbnail));
          if(!$result['thumb'])
          {
            $this->setError('Could not copy thumbnail from '.$thumbnail.' to '.$newthumbnail);
          }
        }
        else
        {
          $result['thumb'] = JFile::move(JPath::clean($thumbnail),
                                         JPath::clean($newthumbnail));
          if(!$result['thumb'])
          {
            $this->setError('Could not move thumbnail from '.$thumbnail.' to '.$newthumbnail);
          }
        }
      }
    }
    else
    {
      // If it already exists with another name copy it to a file with the new name
      $result['thumb'] = JFile::copy($newthumbnail, $this->_ambit->getImg('thumb_path', $row));
      if(!$result['thumb'])
      {
        $this->setError('Could not copy thumbnail from '.$newthumbnail.' to '.$this->_ambit->getImg('thumb_path', $row));
      }
    }

    // Delete original image if configured in JoomGallery
    if($this->_config->get('jg_delete_original') == 1)
    {
      $result['delete_orig'] = JFile::delete($neworigimage);
      if(!$result['delete_orig'])
      {
        $this->setError('Could not delete original image '.$neworigimage);
      }
    }

    // Create database entry
    $query = $this->_db->getQuery(true)
          ->insert(_JOOM_TABLE_IMAGES)
          ->columns('id, catid, imgtitle, alias, imgauthor, imgtext, imgdate, hits, imgvotes, imgvotesum, access, published, hidden, imgfilename, imgthumbname, checked_out, owner, approved, useruploaded, ordering, params, metakey, metadesc')
          ->values( (int) $row->id.','.
                    (int) $row->catid.','.
                    $this->_db->quote($row->imgtitle).','.
                    $this->_db->quote($row->alias).','.
                    $this->_db->quote($row->imgauthor).','.
                    $this->_db->quote($row->imgtext).','.
                    $this->_db->quote($row->imgdate).','.
                    (int) $row->hits.','.
                    (int) $row->imgvotes.','.
                    (int) $row->imgvotesum.','.
                    (int) $row->access.','.
                    (int) $row->published.','.
                    (int) $row->hidden.','.
                    $this->_db->quote($row->imgfilename).','.
                    $this->_db->quote($row->imgthumbname).','.
                    (int) $row->checked_out.','.
                    (int) $row->owner.','.
                    (int) $row->approved.','.
                    (int) $row->useruploaded.','.
                    (int) $row->ordering.','.
                    $this->_db->quote($row->params).','.
                    $this->_db->quote($row->metakey).','.
                    $this->_db->quote($row->metadesc)
                  );
    $this->_db->setQuery($query);
    $result[] = $this->runQuery();

    // Create asset and alias
    $table = JTable::getInstance('joomgalleryimages', 'Table');
    $table->load($row->id);
    if($table->check())
    {
      $result['db'] = $table->store();
      if(!$result['db'])
      {
        $this->setError($table->getError(), true);
      }
    }

    if(!in_array(false, $result))
    {
      $this->writeLogfile('Image successfully migrated: ' . $row->id . ' Title: ' . $row->imgtitle);

      return true;
    }
    else
    {
      $this->writeLogfile('-> Error migrating image: ' . $row->id . ' Title: ' . $row->imgtitle);

      return false;
    }
  }

  /**
   * Creates a comment
   *
   * @param   object  $row  Holds the comment data
   * @return  boolean True on success, false otherwise
   * @since   1.5.0
   */
  public function createComment($row)
  {
    // Some checks
    if(!isset($row->cmtpic) || $row->cmtpic < 1)
    {
      $this->setError('Invalid image ID for comment');

      return false;
    }
    if(!isset($row->cmttext) || $row->cmttext == '')
    {
      $this->setError('Comment hasn\'t any text');

      return false;
    }
    if(!isset($row->cmtid))
    {
      $row->cmtid = 0;
    }
    if(!isset($row->cmtip))
    {
      $row->cmtip = '127.0.0.1';
    }
    if(!isset($row->userid))
    {
      $row->userid = 0;
    }
    if(!isset($row->cmtname))
    {
      $row->cmtname = '';
    }
    if(!isset($row->cmtdate) || is_numeric($row->cmtdate))
    {
      $date = JFactory::getDate();
      $row->cmtdate = $date->toMySQL();
    }
    if(!isset($row->published))
    {
      $row->published = 0;
    }
    if(!isset($row->approved))
    {
      $row->approved = 1;
    }

    // Create database entry
    $values = array((int) $row->cmtpic,
                    $this->_db->quote($row->cmtip),
                    (int) $row->userid,
                    $this->_db->quote($row->cmtname),
                    $this->_db->quote($row->cmttext),
                    $this->_db->quote($row->cmtdate),
                    (int) $row->published,
                    (int) $row->approved
                    );

    $query = $this->_db->getQuery(true)
          ->insert(_JOOM_TABLE_COMMENTS);

    if($row->cmtid)
    {
      $query->columns('cmtid');
      array_unshift($values, $row->cmtid);
    }

    $query->columns('cmtpic, cmtip, userid, cmtname, cmttext, cmtdate, published, approved')
          ->values(implode(',', $values));
    $this->_db->setQuery($query);
    if($this->runQuery())
    {
      $this->writeLogfile('Comment with ID '.$row->cmtid.' successfully stored');

      return true;
    }

    return false;
  }

  /**
   * Creates a name tag
   *
   * @param   object  $row  Holds the name tag data
   * @return  boolean True on success, false otherwise
   * @since   1.5.0
   */
  public function createNametag($row)
  {
    // Some checks
    if(!isset($row->npicid) || $row->npicid < 1)
    {
      $this->setError('Invalid image ID for name tag');

      return false;
    }
    if(!isset($row->nxvalue) || $row->nxvalue < 0)
    {
      $this->setError('Invalid x value for name tag');

      return false;
    }
    if(!isset($row->nyvalue) || $row->nyvalue < 0)
    {
      $this->setError('Invalid y value for name tag');

      return false;
    }
    if(!isset($row->nid))
    {
      $row->nid = 0;
    }
    if(!isset($row->userid))
    {
      $row->userid = 0;
    }
    if(!isset($row->nuserip))
    {
      $row->cmtip = '127.0.0.1';
    }
    if(!isset($row->ndate) || is_numeric($row->ndate))
    {
      $date = JFactory::getDate();
      $row->ndate = $date->toMySQL();
    }
    if(!isset($row->nzindex))
    {
      $row->nzindex = 500;
    }

    // Create database entry
    $query = $this->_db->getQuery(true)
          ->insert(_JOOM_TABLE_NAMESHIELDS);

    $values = array((int) $row->npicid,
                    (int) $row->nuserid,
                    (int) $row->nxvalue,
                    (int) $row->nyvalue,
                    $this->_db->quote($row->nuserip),
                    $this->_db->quote($row->ndate),
                    (int) $row->nzindex
                    );
    if($row->nid)
    {
      $query->columns('cmtid');
      array_unshift($values, $row->nid);
    }

    $query->columns('npicid, nuserid, nxvalue, nyvalue, nuserip, ndate, nzindex')
          ->values(implode(',', $values));
    $this->_db->setQuery($query);
    if($this->runQuery())
    {
      $this->writeLogfile('Name tag with ID '.$row->cmtid.' successfully stored');

      return true;
    }

    return false;
  }

  /**
   * Generates a new filename
   * e.g. <Name/gen. Title>_<Date>_<Random Number>.<Extension>
   *
   * @param   string    $title  The title of the image
   * @param   string    $file   Path and file name of the old image file
   * @param   int       $catid  ID of the category into which the image will be stored
   * @param   string    $tag    File extension e.g. 'jpg'
   * @return  string    The generated filename
   * @since   2.0
   */
  protected function genFilename($title, $file, $catid)
  {
    $date = date('Ymd');

    $tag = JFile::getExt($file);

    $filename = JoomFile::fixFilename($title);

    // Remove filetag = $tag incl '.'
    $filename = substr($filename, 0, strlen($filename)-strlen($tag)-1);

    do
    {
      mt_srand();
      $randomnumber = mt_rand(1000000000, 2099999999);

      // New filename
      $newfilename = $filename.'_'.$date.'_'.$randomnumber.'.'.$tag;
    }
    while(    JFile::exists($this->_ambit->getImg('orig_path', $newfilename, null, $catid))
           || JFile::exists($this->_ambit->getImg('img_path', $newfilename, null, $catid))
           || JFile::exists($this->_ambit->getImg('thumb_path', $newfilename, null, $catid))
         );

    return $newfilename;
  }
}
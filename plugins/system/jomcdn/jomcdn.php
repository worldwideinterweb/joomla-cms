<?php
if ( !defined('_JEXEC') ) { die( 'Direct Access to this location is not allowed.' ); }
/**
 * @version		$Id: jomcdn.php 1 2009-10-27 20:56:04Z rafael $
 * @package		jomCDN
 * @copyright	Copyright (C) 2010 'corePHP' / corephp.com. All rights reserved.
 * @license		GNU/GPL, see LICENSE.txt
 */

jimport( 'joomla.plugin.plugin' );

define( 'CDN_DEBUG', true );

/**
* Joomla! CDN Plugin
*
* @package 		jomCDN
*/
class plgSystemJomCDN extends JPlugin
{
	/**
	 * JDatabase object instance
	 *
	 * @var object
	 **/
	protected $_db;

	/**
	 * JCache object instance
	 *
	 * @var object
	 **/
	protected $_cache;

	/**
	 * The md5 hash of the page request variables
	 *
	 * @var string
	 **/
	protected $page_request_hash;

	/**
	 * A list of already cached files,
	 * array values contain the absolute path as it is found on the code
	 *
	 * @var array
	 **/
	protected $files;

	/**
	 * Max execution time when pushing data to cdn
	 *
	 * @var string
	 **/
	protected $execution_time;

	/**
	 * The cache lifetime of a file
	 *
	 * @var string
	 **/
	protected $cdn_cache_lifetime = '+7 days';

	/**
	 * The memory limit when pushing data to cdn
	 *
	 * @var string
	 **/
	protected $memory_limit;

	/**
	 * This variable contains an array of files to be unlinked
	 * before script finishes
	 *
	 * @var array
	 **/
	public $unlinked = array();

	/**
	 * Variable stores value to see if the server can gzip compress
	 *
	 * @var mixed
	 **/
	public $can_compress = false;

	/**
	 * The image extensions to replace
	 *
	 * @var array
	 **/
	public $image_extensions = array( 'jpg', 'jpeg', 'gif', 'png' );

	/**
	 * The extensions for link tags to replace
	 *
	 * @var array
	 **/
	public $stylesheet_extensions = array( 'ico', 'css' );

	/**
	 * This array contains a list of file extensions that can be included
	 * inside a stylesheet
	 *
	 * @var array
	 **/
	public $stylesheet_files_extensions = array( 'htc', 'css' );

	/**
	 * The extensions for script tags to replace
	 *
	 * @var array
	 **/
	public $script_extensions = array( 'js' );

	/**
	 * Document file extensions
	 *
	 * @var array
	 **/
	static $document_extensions = array( 'pdf', 'doc', 'docx', 'log', 'rtf', 'txt', 'wks', 'xls',
	 	'xlsx' );

	/**
	 * Data file extensions
	 *
	 * @var array
	 **/
	static $data_extensions = array( 'csv', 'dat', 'key', 'pps', 'ppt', 'pptx', 'xml' );

	/**
	 * Music file extensions
	 *
	 * @var array
	 **/
	static $music_extensions = array( 'aif', 'aac', 'iff', 'mid', 'm3u', 'midi', 'mpa', 'mp3',
	 	'ra', 'wav', 'wma' );

	/**
	 * Video file extensions
	 *
	 * @var array
	 **/
	static $video_extensions = array( '3g2', '3gp', 'asf', 'asx', 'avi', 'flv', 'mov', 'mp4', 'mpg',
	 	'rm', 'swf', 'wmv' );

	/**
	 * Zip file extensions
	 * 
	 * @var array
	 **/
	static $zip_extensions = array( '7z', 'deb', 'gz', 'pkg', 'rar', 'sit', 'sitx', 'zip', 'zipx' );

	/**
	 * Extra file extensions
	 *
	 * @var array
	 **/
	static $extra_extensions = array();

	/**
	 * Extra file extensions lifetime
	 *
	 * @var array
	 **/
	static $extra_lifetime = array();

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param	object		$subject The object to observe
	 * @param 	array  		$config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function plgSystemJomCDN( &$subject, $config )
	{
		parent::__construct( $subject, $config );
	}

	/**
	 * Table will check to see if the database tables for the plugin have already been created.
	 * If they haven't been created it will create them
	 *
	 * @return void
	 */
	function check_database_tables()
	{
		$query = "DESCRIBE #__jomcdn";
		$this->_db->setQuery( $query );

		if ( !$this->_db->loadResult() ) {
			$query = "CREATE TABLE IF NOT EXISTS `#__jomcdn` (
			  `id` int(11) NOT NULL auto_increment,
			  `cache` varchar(32) NOT NULL COMMENT 'The cache id',
			  `request` varchar(8) NOT NULL COMMENT 'This request column is composed of the component/view/layout/id of the request',
			  PRIMARY KEY  (`id`),
			  UNIQUE KEY `cache` (`cache`)
			)";
			$this->_db->setQuery( $query );
			$this->_db->query();

			$query = "CREATE TABLE IF NOT EXISTS `#__jomcdn_files` (
			  `id` int(11) NOT NULL auto_increment,
			  `file` text NOT NULL COMMENT 'File path as it is found in the wild',
			  `file_path` text NOT NULL COMMENT 'Absolute file path of file',
			  `remote_file` text NOT NULL COMMENT 'The path to the remote file',
			  `remote_file_gz` text NOT NULL COMMENT 'The path to the gzip version of the remote file',
			  `expiration` int(11) NOT NULL COMMENT 'Expiration of cached file',
			  `path_hash` varchar(32) NOT NULL COMMENT 'The md5 hash for the absolute path of the file',
			  `page` bit(64) NOT NULL COMMENT 'This column keeps record of which pages this file has appeared on',
			  PRIMARY KEY  (`id`),
			  UNIQUE KEY `path_hash` (`path_hash`),
			  KEY `component` (`page`)
			)";
			$this->_db->setQuery( $query );
			$this->_db->query();
		}

		$query = "DESCRIBE #__jomcdn_config";
		$this->_db->setQuery( $query );

		if ( !$this->_db->loadResult() ) {
			$query = "
			CREATE TABLE IF NOT EXISTS `#__jomcdn_config` (
			  `name` varchar(255) NOT NULL,
			  `params` text NOT NULL,
			  PRIMARY KEY (`name`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			$this->_db->setQuery( $query );
			$this->_db->query();

			$query = "INSERT INTO #__jomcdn_config
				( `name`, `params`)
				VALUES
				( 'db_version', '1000');";
			$this->_db->setQuery( $query );
			$this->_db->query();
		}

		$query = "SELECT `params`
			FROM #__jomcdn_config
				WHERE `name` = 'db_version'";
		$this->_db->setQuery( $query );
		$db_version = (int) $this->_db->loadResult( $result );
		$new_version = $db_version;

		if ( $db_version < 1001 ) {
			$query = "ALTER TABLE `#__jomcdn`
				ADD `expires` INT( 11 ) NOT NULL COMMENT 'The time that the cache request expires.',
				ADD INDEX ( `expires` )";
			$this->_db->setQuery( $query );
			$this->_db->query();
			$new_version = 1001;
		}

		if ( $db_version != $new_version ) {
			$query = "UPDATE #__jomcdn_config
				SET `params` = '{$new_version}'
					WHERE `name` = 'db_version'";
			$this->_db->setQuery( $query );
			$this->_db->query();
		}
	}

	/**
	 * This function starts the whole CDN deal
	 *
     * This function that runs before the page is sent to the browser.
     * If the S3 cron job parameter is passed, this function will run through the cached HTML pages
     * and push uncached media to the CDN.
     * Otherwise it may store the current HTML page.
     * It will replace all cached files on the HTML before the page is sent to the browser
     *
     * @return bool Always true
     */
	function onAfterRender()
	{
		global $app;

		// If we are not on the live site, then... well buh bye!
		if ( !$app->isSite() ) {
			return true;
		}

		jimport( 'joomla.cache.cache' );

		// Set class variables
		$this->_db               = JFactory::getDBO();
		$this->_cache            = JCache::getInstance();
		$uri                     = JFactory::getURI();
		$this->_cache->setLifeTime( $this->params->get( 'cache_life_time', 172800 ) ); // 2 days
		$this->page_request_hash = md5( $uri->toString() . implode( ',', $_GET ) );
		$run_s3                  = isset(
			$_REQUEST[trim( $this->params->get( 'cron_url', 'cdn_run_cron' ) )] );

		// If S3 cron job parameter is set then run through the caches
		if ( $run_s3 ) {
			define( 'CDN_CRON_RUNNING', true );

			// Set cronjob parameters
			$this->execution_time = $this->params->get( 'execution_time', (1 * 3600) );
			$this->memory_limit   = $this->params->get( 'memory_limit', '512M' );

			// If server can compress
			$this->can_compress = (	function_exists('gzencode') );

			// Check to see if database tables exist
			$this->check_database_tables();

			$this->get_caches();
			return;
		}

		// Get the unique cache request string for this page
		$component = str_pad( str_replace(
			'com_', '', JRequest::getVar( 'component', 'component' ) ), 2, '0', STR_PAD_LEFT );
		$view      = str_pad( JRequest::getVar( 'view', 'view' ), 2, '0', STR_PAD_LEFT );
		$layout    = str_pad( JRequest::getVar( 'layout', 'default' ), 2, '0', STR_PAD_LEFT );
		$id        = str_pad( JRequest::getVar( 'id', '00' ), 2, '0', STR_PAD_LEFT );

		$this->_cache_request = substr( $component, 0, 2 )
			. substr( $view, 0, 2 )
			. substr( $layout, 0, 2 )
			. substr( $id, 0, 2 );

		// Cache current HTML page if we are lucky ;)
		if ( // ( rand( 1, 10 ) < 8 ) // This is to avoid a large sites from slowing down
			// &&
			!$this->cache_exists( $this->page_request_hash )
		) {
			$this->store_this_page();
		}

		// Replace all cached files before page is sent to browser
		$this->replace_files();

		return true;
	}

	/**
	 * Function to check and see if the cache id still exists in the database
	 *
	 * @param string Joomla cache_id
	 * @return bool True if exists
	 */
	function cache_exists( $cache_id )
	{
		$query = "SELECT `id`
			FROM #__jomcdn
				WHERE `cache` = " . $this->_db->Quote( $cache_id );
		$this->_db->setQuery( $query );
		if ( $this->_db->loadResult() ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Stores current Joomla HTML page to the Joomla cache and stores a record to the DB
	 *
	 * @return void
	 */
	function store_this_page()
	{
		$buffer = JResponse::getBody();
		$this->_cache->store( $buffer, $this->page_request_hash, $this->_name );
		$expires = $_SERVER['REQUEST_TIME'] + $this->params->get( 'cache_life_time', 7200 );

		$query = "INSERT INTO #__jomcdn
			( `cache`, `request`, `expires` )
				VALUES ( '{$this->page_request_hash}', '{$this->_cache_request}', '{$expires}' )";
		$this->_db->setQuery( $query );
		$this->_db->query();
	}

	/**
	 * This function will go thorugh all cached HTML files and push their media files to the CDN
	 *
	 * This function first removes all expired files, then gets all HTML pages that are cached
	 * It then also gets all the files that are cached, and then it starts sending all
	 * uncached files to the CDN
	 *
	 * If everything goes right it dies with a 1
	 *
	 * @return void
	 */
	function get_caches()
	{
		jimport( 'joomla.cache.cache' );
		jimport( 'joomla.database.database' );
		jimport( 'joomla.filesystem.file' );
		jimport( 'joomla.filesystem.path' );

		header( 'Cache-Control: no-cache, must-revalidate' );
		header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
		ignore_user_abort( true );
		set_time_limit( $this->execution_time );
		ini_set( 'memory_limit', $this->memory_limit );

		// Require minify classes
		require_once( dirname( __FILE__ ) .DS. $this->_name .DS. 'jsmin.php' );
		require_once( dirname( __FILE__ ) .DS. $this->_name .DS. 'cssmin.php' );

		if ( $this->params->get( 'cron_delete_cache_files', 0 ) ) {
			// Delete all cached files
			$this->delete_cached_files();
		} else {
			// Delete expired files
			$this->delete_cached_files( strtotime( 'now' ) );
		}

		// Get all cached pages
		$query = "SELECT * FROM #__jomcdn";
		$this->_db->setQuery( $query );
		$pages = $this->_db->loadObjectList();

		// Get all cached files
		$_files = $this->get_cached_files( true );

		// Set up extra file extensions
		$_extra_exts = explode( '|', $this->params->get( 'cache_extra_filetypes' ) );
		foreach ( $_extra_exts as $value ) {
			$_extra_ext = explode( ',', trim( $value ) );
			if ( @$_extra_ext[0] && @$_extra_ext[1] ) {
				plgSystemJomCDN::$extra_extensions[] = $_extra_ext[0];
				plgSystemJomCDN::$extra_lifetime[] = $_extra_ext[1];
			}
		}

		$this->files = array();
		foreach ( (array) $_files as $row ) {
			$this->files[$row->remote_file] = $row->file_path;
		}

		foreach ( $pages as $page ) {
			if ( !( $content = $this->_cache->get( $page->cache, $this->_name ) ) ) {
				continue;
			}

			// This is a binary value of the cache
			$this->cache_request = '';
			foreach ( str_split( $page->request ) as $value ) {
				$this->cache_request .= str_pad( decbin( ord( $value ) ), 8, 0, STR_PAD_LEFT );
			}

			// This is a path to help us find the correct file in the filesystem
			$this->cache_abs_path = '';
			$this->parse_page( $content );

			// Delete cache file from Joomla's cache directory
			$this->_cache->remove( $page->cache, $this->_name );
		}

		// Unlink any necessary files
		foreach ( $this->unlinked as $file ) {
			if ( file_exists( $file ) ) {
				unlink( $file );
			}
		}

		// We are done pushing goodies to the CDN
		die('1');
	}

	/**
	 * Function replaces all CDN cached files that are stored on the DB
	 *
	 * It will replace all CDN files before the Joomla page is rendered.
	 *
	 * @return void
	 */
	function replace_files()
	{
		$files = $this->get_cached_files();
		$buffer = JResponse::getBody();

		$user_accepts_gzip
			= ( false !== strpos( strtolower( $_SERVER['HTTP_ACCEPT_ENCODING'] ), 'gzip' ) );
		$add_expiration_time = $this->params->get( 'add_expiration_time', 0 );

		$cdn        = $this->get_cdn_object();
		$cdn_domain = $cdn->get_domain();

		$patterns = array();
		$replacements = array();
		foreach ( $files as $file ) {
			// If accept gzip encoding
			if ( $user_accepts_gzip && !empty( $file->remote_file_gz ) ) {
				$rfile = $file->remote_file_gz;
			} else {
				$rfile = $file->remote_file;
			}

			if ( $add_expiration_time ) {
				$rfile .= '?' . $file->expiration;
			}

			$patterns[] = $file->file;

			$replacements[] = $cdn_domain . $rfile;
		}

		if ( !empty( $patterns ) && !empty( $replacements ) ) {
			$buffer = str_replace( $patterns, $replacements, $buffer );
		}
		$buffer = str_replace( '/http', 'http', $buffer );
		$buffer = str_replace( substr( JURI::root(), 0, -1 ) . $cdn_domain, $cdn_domain, $buffer );

		JResponse::setBody( $buffer );
	}

	/**
	 * Function send all media files to CDN and store record in DB
	 *
	 * It skips files that have already been sent to the CDN to save on bandwidth costs
	 *
	 * @param string The contents of a HTML page
	 * @return bool Always true
	 */
	function parse_page( $content )
	{
		$assets = $this->get_all_assets( $content );

		foreach ( $assets as $file => $file_path ) {
			if ( is_array( $file_path ) ) {
				if ( $this->_file_exists( $file_path['absolute'] ) ) {
					continue;
				}
			} else {
				if ( $this->_file_exists( $file_path ) ) {
					continue;
				}
			}

			// Store file
			if ( !( $cdn_files = $this->add_file( $file, $file_path ) ) ) {
				continue;
			}
		}

		return true;
	}

	/**
	 * Gets all media files found in an HTML page
	 *
	 * @param string The contents of a HTML page
	 * @return array An array that contains all media files found on a page
	 */
	function get_all_assets( $content )
	{
		$images      = (array) $this->get_images( $content );
		$stylesheets = (array) $this->get_stylesheets( $content );
		$scripts     = (array) $this->get_scripts( $content );
		$files       = (array) $this->get_files( $content );

		return array_merge( $images, $stylesheets, $scripts, $files );
	}

	/**
	 * Gets a list of all image that are found on the HTML of a page
	 *
	 * @param string The contents of a HTML page
	 * @return array An array that contains all images found on a page
	 */
	function get_images( $content )
	{
		preg_match_all( '/(img|src)\=(\"|\')[^\"\'\>]+/i', $content, $media );
		$data = preg_replace( '/(img|src)(\"|\'|\=\"|\=\')(.*)/i',"$3", $media[0] );

		$images = $this->clean_media_files( $data, $this->image_extensions );

		$non_existent = array();
		// Loop through the files and make sure they were found
		foreach ( $images as $file => $file_path ) {
			// Check to see if the absolute path for the file was found on method clean_media_files
			if ( intval( $file ) > 0 ) {
				// This means that the absolute path for this file was not found
				$non_existent[] = $file_path;
				unset( $images[$file] );
				continue;
			}
		}

		if ( $this->params->get( 'smushit_please', 0 ) ) {
			if ( !function_exists( 'json_encode' ) ) {
				require_once( dirname( __FILE__ ) .DS. $this->_name .DS. 'JSON' .DS. 'JSON.php' );
			}
			require_once( dirname( __FILE__ ) .DS. $this->_name .DS. 'http' .DS. 'class.http.php' );

			foreach ( $images as $file => $path ) {
				$url = JURI::root() . str_replace( JPATH_ROOT . DS, '', $path );

				if ( CDN_DEBUG ) {
					myPrint( "Smushing: {$url}..." );
				}
				$_new_path = $this->smush_it( $url );

				if ( $_new_path ) {
					$images[$file] = array( 'absolute' => $images[$file], 'temp' => $_new_path );
				}
			}
		}

		return $images;
	}

	/**
	 * Gets a list of files that are wrapped in a <link> tag
	 *
	 * This function will get mostly stylesheets and favico's
	 * When a stylesheet is found it is looped through to find
	 * any files that are included within that stylesheet and then pushes them to the CDN.
	 * If there is a stylesheet that contains files, it sends over all of the files
	 * and then it replaces all of the instances of that file in the stylesheet file contents
	 * then it sends the stylesheet contents to the CDN.
	 *
	 * @param string The contents of a HTML page
	 * @return array An array that contains all urls found on a page inside <link> tags
	 */
	function get_stylesheets( $content )
	{
		preg_match_all( '/<(?:link).*?href=[\'|"](.*?)[\'|"].*?\/?>/i', $content, $media );

		if ( !isset( $media[1] ) || empty( $media[1] ) ) {
			return array();
		}

		$data = $media[1];
		$styles = $this->clean_media_files( $data, $this->stylesheet_extensions );

		// Go through all stylesheets and find images
		foreach ( $styles as $org_style_file => $abs_style_file ) {
			if ( substr( $abs_style_file, -4 ) != '.css' ) {
				continue;
			}

			if ( $this->_file_exists( $abs_style_file ) ) {
				unset( $styles[$org_style_file] );
				continue;
			}

			// This will send all stylesheet type files to the CDN
			$this->parse_stylesheet( $styles, $org_style_file, $abs_style_file );
		}

		return $styles;
	}

	/**
	 * Function will send all stylesheet type files to the CDN.
	 *
	 * The files will more than likely only be htc, css and image
	 * files found within the stylesheet.
	 *
	 * @param array List of stylesheets that are being parsed
	 * @param string The path to the stylesheet as it is found on the system files
	 * @param string Absolute path to the stylesheet that is being persed
	 * @return
	 */
	function parse_stylesheet( &$styles, $org_style_file, $abs_style_file )
	{
		$replaced   = false;
		$cdn        = $this->get_cdn_object();
		$expiration = strtotime( $this->cdn_cache_lifetime );

		if ( !( $stylesheet_contents = JFile::read( $abs_style_file ) ) ) {
			unset( $styles[$org_style_file] );
			return;
		}

		if ( CDN_DEBUG ) {
			myPrint( "Parsing through stylesheet: {$abs_style_file}" );
		}

		// Find all files inside stylesheet
		//'/(?:@import|url\()\s*\'?"?\(?(.*)\'?"?\s*\)?;/i'
		preg_match_all(
			'/(?:@import[^"]*"(.*?)")/i', $stylesheet_contents, $urls1 );

		// Get urls()
		preg_match_all(
			'/(?:url[^\(]*\(\s*\'?"?(.*?)\'?"?\s*\))/i', $stylesheet_contents, $urls2 );

		if ( ( !isset( $urls1[1] ) || empty( $urls1[1] ) )
		 	&& ( !isset( $urls2[1] ) || empty( $urls2[1] ) )
		) {
			if ( CDN_DEBUG ) {
				myPrint( 'No urls found.' );
			}

			return;
		}

		$data = array_merge( @$urls1[1], @$urls2[1] );
		$urls = $this->clean_media_files(
			$data,
			array_merge( $this->image_extensions, $this->stylesheet_files_extensions ),
			dirname( $abs_style_file )
		);

		if ( empty( $urls ) ) {
			if ( CDN_DEBUG ) {
				myPrint( 'No urls found #2.' );
			}

			return;
		}

		$add_expiration_time = $this->params->get( 'add_expiration_time', 0 );
		$replace_style_files = array();
		// Loop through stylesheet files and replace them
		foreach ( $urls as $file => $file_path ) {
			// Check to see if the absolute path for the file was found on method clean_media_files
			if ( intval( $file ) > 0 ) {
				// This means that the absolute path for this file was not found
				// We cannot push over this stylesheet
				$replaced = false;
				unset( $styles[$org_style_file] );

				return false;
			}

			// This is here to prevent URLs from being created incorrectly
			if ( @in_array( $file, $replace_style_files ) ) {
				continue;
			}

			// File already uploaded probably through another stylesheet then just replace
			if ( $this->_file_exists( $file_path ) ) {
				$remote_file = array_search( $file_path, $this->files );
				$replaced = true;

				if ( $add_expiration_time ) {
					$remote_file .= '?' . $expiration;
				}

				$stylesheet_contents
					= str_replace( $file, $remote_file, $stylesheet_contents );
				$replace_style_files[] = $file;
				unset( $remote_file );

				continue;
			}

			// If file found is a stylesheet, then push that file over
			if ( '.css' == substr( $file_path, -4 ) ) {
				$styles[$file] = $file_path;

				// If there is an error when parsing inner stylesheet, then don't parse current
				if ( !( $cdn_files = $this->parse_stylesheet( $styles, $file, $file_path ) ) ) {
					unset( $styles[$org_style_file] );
					return false;
				}

				// This means that no URLs where found, and we can pusht he file
				if ( 'empty' == $cdn_files ) {
					// If file not yet stored and not stylesheet - Store file
					if ( !( $cdn_files = $this->add_file(
							$file, $file_path, $file_path, 'text/css' ) )
					) {
						unset( $styles[$org_style_file] );
						return false;
					}
				}

				if ( $add_expiration_time ) {
					$cdn_files['file'] .= '?' . $expiration;
				}

				// Replace the URL of the new css file on current stylesheet
				$stylesheet_contents = str_replace( $file, $cdn_files['file'],
					$stylesheet_contents );

				$replace_style_files[] = $file;
				$replaced = true;

				continue;
			}

			// Check to see if it is an image so that we smush.it!
			$smushable = false;
			$_info = pathinfo( $file_path );

			if ( isset( $_info['extension'] ) ) {
				if ( in_array( strtolower( $_info['extension'] ), $this->image_extensions ) ) {
					$smushable = true;
				}
			}

			// Smush smush
			if ( $smushable && $this->params->get( 'smushit_please', 0 ) ) {
				if ( !function_exists( 'json_encode' ) ) {
					require_once( dirname( __FILE__ ) .DS. $this->_name .DS. 'JSON' .DS.
						'JSON.php' );
				}
				require_once( dirname( __FILE__ ) .DS. $this->_name .DS. 'http' .DS.
				 	'class.http.php' );

				$url = JURI::root() . str_replace( JPATH_ROOT . DS, '', $file_path );

				if ( CDN_DEBUG ) {
					myPrint( "Smushing: {$url}..." );
				}
				$_new_path = $this->smush_it( $url );

				if ( $_new_path ) {
					$file_path = array( 'absolute' => $file_path, 'temp' => $_new_path );
				}
			}

			// If file not yet stored and not stylesheet - Store file
			if ( !( $cdn_files = $this->add_file( $file, $file_path ) ) ) {
				$replaced = false;
				unset( $styles[$org_style_file] );

				return false;
			}

			if ( $add_expiration_time ) {
				$cdn_files['file'] .= '?' . $expiration;
			}

			$stylesheet_contents = str_replace( $file, $cdn_files['file'],
				$stylesheet_contents );

			$replace_style_files[] = $file;
			$replaced = true;
		}

		// Change the styles array to add the new contents of the stylesheet
		if ( true === $replaced ) {
			// Store file
			$cdn_files = $this->add_file(
				$org_style_file, $abs_style_file, $stylesheet_contents, 'text/css'
			);

			// Regardless we unset the stylesheet as we already stored it
			unset( $styles[$org_style_file] );

			return $cdn_files;
		}
	}

	/**
	 * Gets a list of all scripts that are found on the HTML of a page
	 *
	 * It will only get scripts that have a 'src' attribute
	 *
	 * @param string The contents of a HTML page
	 * @return array An array that contains all scripts found on a page
	 */
	function get_scripts( $content )
	{
		preg_match_all( '/<(?:script).*?src=[\'|"](.*?)[\'|"].*?\/?>/i', $content, $media );

		if ( !isset( $media[1] ) || empty( $media[1] ) ) {
			return array();
		}

		$data = $media[1];
		$scripts = $this->clean_media_files( $data, $this->script_extensions );

		$non_existent = array();
		// Loop through the files and make sure they were found
		foreach ( $scripts as $file => $file_path ) {
			// Check to see if the absolute path for the file was found on method clean_media_files
			if ( intval( $file ) > 0 ) {
				// This means that the absolute path for this file was not found
				// We cannot push over this stylesheet
				$non_existent[] = $file_path;
				unset( $scripts[$file] );
				continue;
			}
		}

		return $scripts;
	}

	/**
	 * Gets a list of all files that are found on the HTML of a page
	 *
	 * @param string The contents of a HTML page
	 * @return array An array that contains all images found on a page
	 */
	function get_files( $content )
	{
		$extensions = '';
		foreach ( array( plgSystemJomCDN::$document_extensions, plgSystemJomCDN::$data_extensions,
			plgSystemJomCDN::$music_extensions, plgSystemJomCDN::$video_extensions,
			plgSystemJomCDN::$zip_extensions, plgSystemJomCDN::$extra_extensions ) as $array
		) {
			$extensions .= implode( '|', $array ) . '|';
		}
		$extensions = substr( $extensions, 0, -1 );

		preg_match_all( '/(?:=|"|\').*(?:' .$extensions. ')(?:=|"|\'|&)/mi', $content, $media );
		$data = preg_replace( '/(.*?)("|=|&)(.*?)("|\'|=|&)/',"$3", $media[0] );

		// There seems to be an issue replacing the ending double quotes
		foreach ( $data as $key => $value ) {
			$data[$key] = trim( $value, '"' );
		}

		$files = $this->clean_media_files( $data, array_merge(
			plgSystemJomCDN::$document_extensions, plgSystemJomCDN::$data_extensions,
			plgSystemJomCDN::$music_extensions, plgSystemJomCDN::$video_extensions,
			plgSystemJomCDN::$zip_extensions, plgSystemJomCDN::$extra_extensions ) );

		$non_existent = array();
		// Loop through the files and make sure they were found
		foreach ( $files as $file => $file_path ) {
			// Check to see if the absolute path for the file was found on method clean_media_files
			if ( intval( $file ) > 0 ) {
				// This means that the absolute path for this file was not found
				$non_existent[] = $file_path;
				unset( $files[$file] );
				continue;
			}
		}

		return $files;
	}

	/**
	 * This function will clean up all of the URL files that are passed to it
	 *
	 * It removes any file that does not have the allowed extension.
	 * This function will only return files from the same domain,
	 * and only files that it can find the absolute path of.
	 * It will return an array that has the key of the original path as it is found
	 * on the HTML and the value as the absolute path.
	 *
	 * @param array A list of files that are found
	 * @param array An array of file types that are allowed
	 * @param string (Optional) The path from where the files where found
	 * @return array A list of valid files, organized as original_file => absolute_file_path
	 */
	function clean_media_files( $media, $allowed_types, $root_path = JPATH_ROOT )
	{
		$files = array();

		// Loop through all files and remove the ones that are not valid
		foreach ( $media as $url ) {
			$info = pathinfo( $url );

			if ( isset( $info['extension'] ) ) {
				if ( in_array( strtolower( $info['extension'] ), $allowed_types ) ) {
					array_push( $files, $url );
				}
			}
		}

		// Eliminate the ones that are not on our domain and find the absolute path for the others
		foreach ( $files as $k => $file ) {
			$file_uri = JURI::getInstance( $file );
			$org_file = $file;

			// If image from other domain -- skip
			if ( $file_uri->toString( array( 'host' ) ) ) {
				if ( false === strpos( $file, JURI::root() ) ) {
					unset( $files[$k] );
					continue;
				}

				// Remove 'scheme', 'user', 'pass', 'host', 'port'
				$file = str_replace(
					$file_uri->toString( array( 'scheme', 'user', 'pass', 'host', 'port' ) ),
					'', $file
				);
			}

			// Remove beginning directory URL stuff
			if ( JURI::root( true ) ) {
				$file = str_replace( JURI::root( true ), '', $file );
			}

			// Get absolute path
			$_file = ltrim( str_replace( array( '/', '\\' ), DS, $file ), DS );
			if ( substr( $file, 0, 1 ) == DS ) {
				$_file = realpath( JPATH_ROOT .DS. $_file );
			} else {
				$_file = realpath( $root_path .DS. $_file );
			}

			// Test with the path that was passed to function
			if ( !file_exists( $_file ) || !is_readable( $_file ) ) {
				// Allow bruteforce
				$brute_force = $this->params->get( 'brute_force_file_path', 1 );

				// Attempt to bruteforce path if it is available as setting
				$_file = ltrim( str_replace( array( '/', '\\' ), DS, $file ), DS );
				$_file = realpath( JPATH_ROOT .DS. $_file );

				if ( !$brute_force || !file_exists( $_file ) || !is_readable( $_file ) ) {

					$_file = ltrim( str_replace( array( '/', '\\' ), DS, $file ), DS );
					$_file = realpath( $this->cache_abs_path .DS. $_file );

					if ( !$brute_force || !file_exists( $_file ) || !is_readable( $_file ) ) {
						// unset( $files[$k] );
						if ( CDN_DEBUG ) {
							myPrint( "Unable to find file: {$org_file}" );
						}
						continue;
					}
				}
			}

			// Replace old path with new updated path
			$files[$file_uri->toString(
				array( 'scheme', 'user', 'pass', 'host', 'port', 'path', 'query', 'fragment' ) )]
					= $_file;
			unset( $files[$k] );
		}

		return $files;
	}

	/**
	 * Will get the instance of the CDN that is being used
	 *
	 * Current available CDNs:
	 * 	 - Amazon S3
	 *
	 * @return An instance of the cdn object
	 */
	function get_cdn_object()
	{
		static $object;

		if ( $object ) {
			return $object;
		}

		// Here is where there would be a check to use other CDNs
		$object = $this->get_s3_object();

		return $object;
	}

	/**
	 * Will get an instance of the Amazon S3 object connection
	 *
	 * For a correct connection, the Amazon access key, secret key and bucket must be set
	 * the SSL setting is not necessary
	 *
	 * @return object Instance of S3_CDN class
	 */
	function get_s3_object()
	{
		switch ( $this->params->get( 'cdn_type' ) ) {
			case 's3':
				$s3_access_key = trim( $this->params->get( 's3_access_key' ) );
				$s3_secret_key = trim( $this->params->get( 's3_secret_key' ) );
				$s3_use_ssl    = $this->params->get( 's3_use_ssl', false );
				$s3_bucket     = trim( $this->params->get( 's3_bucket' ) );

				return new S3_CDN(
					$s3_access_key, $s3_secret_key, $s3_use_ssl, $s3_bucket,
						get_object_vars( $this )
				);
				break;

			case 'rs':
				$rs_api_key  = trim( $this->params->get( 'rs_api_key' ) );
				$rs_username = trim( $this->params->get( 'rs_username' ) );
				$rs_bucket   = trim( $this->params->get( 'rs_bucket' ) );

				return new RACKSPACE_CDN(
					$rs_api_key, $rs_username, $rs_bucket, get_object_vars( $this )
				);
			default:
				return;
				break;
		}
	}

	/**
	 * Get all of the cached files from the database
	 *
	 * @param bool True will get all files, false will only get files for request
	 * @return object A list of all cached files in the database
	 */
	function get_cached_files( $all = false )
	{
		// This is a binary value of the cache
		$this->cache_request = '';
		foreach ( str_split( $this->_cache_request ) as $value ) {
			$this->cache_request .= str_pad( decbin( ord( $value ) ), 8, 0, STR_PAD_LEFT );
		}

		// Get all cached files
		$query = "SELECT *
			FROM #__jomcdn_files";
		if ( false === $all ) {
			$query .= " WHERE ( `page` | b'{$this->cache_request}' ) = `page`";
		}
		$this->_db->setQuery( $query );
		$_files = $this->_db->loadObjectList();

		return (array) $_files;
	}

	/**
	 * Adds local file or string to the CDN and stores a record of it to the database
	 *
	 * The cdn file that is passed can either be an absolute path to a file on the server
	 * or it can be a string containing the contents of the file to be in the CDN
	 *
	 * @param string The path of the original file as it is found on the HTML page
	 * @param string The absolute path of the file in the file system
	 * @param string The CDN file, this can be a string containing the contents of the file to be
	 * @param string The type that will be set as the Content-Type header
	 * @return mixed False if unsucessful, The path to the CDN file if sucessful
	 */
	function add_file( $original_file, $absolute_path, $cdn_file = '', $content_type = null )
	{
		// Lets double check that this is a file by searching for the extension
		if ( false === strpos( $original_file, '.' ) ) {
			return false;
		}

		if ( false !== strpos( $original_file, 'tiny_mce' )
			|| false !== strpos( $original_file, 'tinymce' )
		) {
			return false;
		}

		// Weird way to get the temp and absolute path...
		if ( is_array( $absolute_path ) ) {
			$cdn_file = $absolute_path['temp'];
			$absolute_path = $absolute_path['absolute'];
		}

		if ( CDN_DEBUG ) {
			myPrint( "Adding file: {$absolute_path}" );
		}

		$cdn = $this->get_cdn_object();

		if ( !$cdn_file ) {
			$cdn_file = $absolute_path;
		}

		// Send file to CDN
		if ( !( $cdn_files = $cdn->put_object( $cdn_file, $absolute_path, $content_type ) ) ) {
			if ( CDN_DEBUG ) {
				myPrint( "File not added: {$absolute_path}" );
			}

			return false;
		}

		$expiration = strtotime( $this->cdn_cache_lifetime );
		$path_hash  = md5( $absolute_path );

		$file = $cdn_files['file'];
		$gz_file = $cdn_files['gz_file'];
		if ( $cdn_files['expire'] ) {
			$expiration = strtotime( $cdn_files['expire'] );
		}

		// Save to DB
		$query = "INSERT INTO #__jomcdn_files
			( `file`, `file_path`, `remote_file`, `remote_file_gz`,
			`expiration`, `path_hash`, `page` )
				VALUES
				( " .$this->_db->Quote( $original_file ). ",
				" .$this->_db->Quote( $absolute_path ). ", " .$this->_db->Quote( $file ). ",
				" .$this->_db->Quote( $gz_file ). ", {$expiration},
				'{$path_hash}', b'{$this->cache_request}' )";
		$this->_db->setQuery( $query );
		$this->_db->query();

		// Add file path to $this->files array
		$this->files[$cdn_files['file']] = $absolute_path;

		return $cdn_files;
	}

	/**
	 * Deletes all cached files from database.
	 * If expired parameter is passed, it will delete files that have a timestamp
	 * that is smaller than the time that is passed.
	 *
	 * @param int The timestamp in unix time
	 * @return void
	 */
	function delete_cached_files( $timestamp = 0 )
	{
		$where  = '';
		$where2 = '';
		if ( $timestamp ) {
			$timestamp = intval( $timestamp );
			$where  = "WHERE `expiration` <= {$timestamp}";
			$where2 = "WHERE `expires` <= {$timestamp}";
		}

		$query = "DELETE FROM #__jomcdn_files {$where}";
		$this->_db->setQuery( $query );
		$this->_db->query();

		// Delete file caches
		$query = "SELECT * FROM #__jomcdn {$where2}";
		$this->_db->setQuery( $query );
		$data = $this->_db->loadObjectList();

		foreach ( $data as $page ) {
			// Delete cache file from Joomla's cache directory
			$this->_cache->remove( $page->cache, $this->_name );
		}

		// Delete all file caches from db
		$query = "DELETE FROM #__jomcdn {$where2}";
		$this->_db->setQuery( $query );
		$this->_db->query();
	}

	/**
	 * This function is used to keep track files that are used on multiple components.
	 * If a file already exists, then we need to update the `page` column for the files row
	 * to refled that the file also exist in a different page
	 *
	 * @param string The absolute path to the file in question
	 * @return bool True if file exists, False if file doesn't exist in array
	 **/
	function _file_exists( $file_path )
	{
		// If doesn't exists return now
		if ( !in_array( $file_path, $this->files ) ) {
			return false;
		}

		$query = "SELECT id, BIN(`page`) AS `page`
			FROM #__jomcdn_files
				WHERE `file_path` = " .$this->_db->Quote( $file_path );
		$this->_db->setQuery( $query );
		$file = $this->_db->loadObject();

		// Get the new binary value
		$page = str_pad( $file->page, 64, 0, STR_PAD_LEFT ) | $this->cache_request;

		// Check to make sure it is not already the same, to avoid unecessary queries
		if ( bindec( str_pad( $file->page, 64, 0, STR_PAD_LEFT ) ) != bindec( $page ) ) {
			// Save to DB
			$query = "UPDATE #__jomcdn_files
					SET `page` = b'{$page}'
						WHERE `id` = {$file->id}";
			$this->_db->setQuery( $query );
			$this->_db->query();

			if ( CDN_DEBUG ) {
				myPrint( "Updating page column for file: {$file_path}" );
			}
		}

		return true;
	}

	/**
	 * Function to use Yahoo's smush.it service
	 * It will save the new generated image to Joomla's tmp folder
	 *
	 * @param string URL of local file
	 * @return string New file name
	 **/
	function smush_it( $_original_url )
	{
		global $app;

		$url = 'http://www.smushit.com/ysmush.it/ws.php?img=' . urlencode( $_original_url );

		$options = array(
			'timeout'    => 5
		);

		$http = new Http();
		$http->initialize( $options );
		$http->setUseragent( 'jomCDN/1.1.0 (+http://www.corephp.com/joomla-products/jomcdn.html)' );
		$http->execute( $url );

		if ( $http->error ) {
			return $http->error;
		}

		if ( 200 != $http->status ) {
			if ( CDN_DEBUG ) {
				echo 'Error on request.';
			}
			return false;
		}

		$body = trim( $http->result );
		if ( !$body ) {
			if ( CDN_DEBUG ) {
				echo 'No reponse.';
			}
			return false;
		}

		// Just in case...
		if ( strpos( trim( $body ), '{' ) != 0 ) {
			if ( CDN_DEBUG ) {
				echo 'Bad response from Smush.it';
			}
			return false;
		}

		$data = json_decode( $body );

		if ( -1 === intval( $data->dest_size ) || $data->src_size == $data->dest_size ) {
			if ( CDN_DEBUG ) {
				echo 'No savings, no smush smush fo you!';
			}
			return false;
		}

		if ( !$data->dest ) {
			if ( CDN_DEBUG ) {
				echo ($data->error ? 'Smush.it error: ' . $data->error : 'Unknown error.');
			}
			return false;
		}

		$processed_url = urldecode( $data->dest );

		$options = array(
			'timeout'    => 300
		);
		$http->initialize( $options );
		$http->setUseragent( 'jomCDN/1.1.0 (+http://www.corephp.com/joomla-products/jomcdn.html)' );
		$http->execute( $processed_url );

		if ( 200 != $http->status ) {
			if ( CDN_DEBUG ) {
				echo 'Error on request #2.';
			}
			return false;
		}

		$body = $http->result;
		if ( !$body ) {
			if ( CDN_DEBUG ) {
				echo 'No reponse #2.';
			}
			return false;
		}

		// Get unique filename before store!
		$temp = rtrim( $app->getCfg( 'tmp_path' ), '/' . DS );
		$temp_file = $temp .DS. basename( $_original_url );
		$counter = 1;
		while ( file_exists( $temp_file ) ) {
			$pieces = count( explode( '.', $temp_file ) );
			$_temp = explode( '.', $temp_file, $pieces );
			$temp_file = str_replace( $_temp[ $pieces - 2 ] . '.' . $_temp[ $pieces - 1 ],
					$_temp[ $pieces - 2 ] . $counter . '.' . $_temp[ $pieces - 1 ], $temp_file);
			$counter++;
		}

		$handle = @fopen( $temp_file, 'wb' );
		if ( !$handle ) {
			if ( CDN_DEBUG ) {
				echo 'Could not create temporary file:' . $temp_file;
			}
			return false;
		}

		fwrite( $handle, $body );
		fclose( $handle );

		$savings = intval( $data->src_size ) - intval( $data->dest_size );
		$savings_str = $this->smushit_format_bytes( $savings, 1 );
		$savings_str = str_replace( ' ', '&nbsp;', $savings_str );

		if ( CDN_DEBUG ) {
			printf( 'Reduced by %01.1f%% (%s)', $data->percent, $savings_str );
		}

		// This needs to be unliked eventually
		$this->unlinked[] = $temp_file;

		return $temp_file;
	}

	/**
	 * Return the filesize in a humanly readable format.
	 * Taken from http://www.php.net/manual/en/function.filesize.php#91477
	 */
	function smushit_format_bytes( $bytes, $precision = 2 )
	{
	    $units = array( 'B', 'KB', 'MB', 'GB', 'TB' );

	    $bytes = max( $bytes, 0 );
	    $pow   = floor( ( $bytes ? log( $bytes ) : 0 ) / log( 1024 ) );
	    $pow   = min( $pow, count( $units ) - 1 );
	    $bytes /= pow( 1024, $pow );

	    return round( $bytes, $precision ) . ' ' . $units[$pow];
	}
}

/**
 * Class for creating connection with Amazon S3
 */
class S3_CDN extends CDN_HELER
{
	/**
	 * Amazon S3 class object
	 *
	 * @type Object
	 */
	public $s3;

	/**
	 * The bucket name to store all files
	 *
	 * @type String
	 */
	public $bucket;

	/**
	 * If SSL is on
	 *
	 * @type Bool
	 */
	public $ssl;

	/**
	 * Constructor -- Creates instance of Amazon S3 connection
	 *
	 * @param string Amazon S3 public key
	 * @param string Amazon S3 secret key
	 * @param bool Use SSL
	 * @param string Amazon S3 bucket to use
	 * @return void
	 */
	function __construct( $s3_access_key, $s3_secret_key, $s3_use_ssl, $bucket, $config = array() )
	{
		foreach ( $config as $key => $value ) {
			$this->$key = $value;
		}

		if ( defined( 'CDN_CRON_RUNNING' ) && CDN_CRON_RUNNING ) {
			require_once( dirname( __FILE__ ) .DS. $this->_name .DS. 's3.php' );

			$this->s3 = new CPP_S3( $s3_access_key, $s3_secret_key, $s3_use_ssl );
		}

		$this->ssl    = $s3_use_ssl;
		$this->bucket = $bucket;
	}

	/**
	 * Sends a file to the CDN
	 * The file that is sent can either be a string or the absolute path to a file
	 *
	 * For the headers and caching, you can read more at
	 * {http://www.badpenguin.org/php-howto-control-page-caching}
	 *
	 * @param string File contents or absolute path to file
	 * @param string The path to the file on the CDN
	 * @param mixed The type that will be set as the Content-Type header.
	 * 		It can also be an array of headers.
	 * @return mixed False if unsucessful, Path to CDN file if successful
	 */
	function put_object( $local_file, $cdn_file = '', $type = null )
	{
		if ( '' == $cdn_file ) {
			$cdn_file = $local_file;
		}

		if ( !is_array( $type ) ) {
			$headers = array();
		} else {
			$headers = $type;
		}

		$headers = $this->get_headers( $headers, $cdn_file );
		$input   = null;

		// If the Content-Type is set, lets get it here
		$content_type = '';
		if ( is_string( $type ) ) {
			$content_type = $type;
		} elseif ( !isset( $headers['Content-Type'] ) && is_file( $local_file ) ) {
			$content_type = $this->s3->__getMimeType( $local_file );
		}

		// If is file
		if ( is_file( $local_file ) ) {
			if ( $headers['do_minify'] ) {
				$local_file = JFile::read( $local_file );
			} else {
				$input = $this->s3->inputFile( $local_file );
			}
		}
		if ( null == $input && is_string( $local_file ) ) { // If is content
			if ( $headers['do_minify'] ) {
				switch ( $headers['do_minify'] ) {
					case 'jsmin':
						$local_file = CPP_JSMin::minify( $local_file );
						break;
					case 'cssmin':
						$local_file = CPP_cssmin::minify( $local_file );
						break;
					default:
						break;
				}
			}

			$input = array(
				'data' => $local_file,
				'size' => strlen( $local_file ),
				'md5sum' => base64_encode( md5( $local_file, true ) )
			);
		}

		if ( '' != $content_type ) {
			$input['type'] = $content_type;
		}

		$gz_put = '';
		// If we are gziping the file
		if ( $this->can_compress  && isset( $headers['do_gzip'] ) ) {
			unset( $headers['do_gzip'] );

			// Read file If is file
			if ( is_file( $local_file ) ) {
				$gz_file = JFile::read( $local_file );
			} else {
				$gz_file = $local_file;
			}

			$gz_file = gzencode( $gz_file );
			$gz_file = array(
				'data' => $gz_file,
				'size' => strlen( $gz_file ),
				'md5sum' => base64_encode( md5( $gz_file, true ) )
			);

			// Set type coming from original file
			if ( '' != $content_type ) {
				$gz_file['type'] = $input['type'];
			}

			$gz_cdn_file = str_replace(
				strrchr( $cdn_file, '.' ), '.gz' . strrchr( $cdn_file, '.' ), $cdn_file );
			$gz_cdn_file_path
				= str_replace( DS, '/', str_replace( JPATH_ROOT . DS, '', $gz_cdn_file ) );

			// Set the enconding header
			$headers['Content-Encoding'] = 'gzip';

			$gz_put = $this->s3->putObject(
				$gz_file,
				$this->bucket,
				$gz_cdn_file_path,
				CPP_S3::ACL_PUBLIC_READ,
				array(),
				$headers
			);

			// If success
			if ( $gz_put ) {
				$gz_put = '/' . $gz_cdn_file_path;
			} else {
				return false;
			}

			unset( $headers['Content-Encoding'] );
		}

		$cdn_file_path = str_replace( DS, '/', str_replace( JPATH_ROOT . DS, '', $cdn_file ) );

		$put = $this->s3->putObject(
			$input,
			$this->bucket,
			$cdn_file_path,
			CPP_S3::ACL_PUBLIC_READ,
			array(),
			$headers
		);

		// If success
		if ( $put ) {
			$put = '/' . $cdn_file_path;
		} else {
			return false;
		}

		if ( CDN_DEBUG ) {
			myPrint( "CDN Added:\t{$cdn_file}\n\t\t{$put}\n\t\t{$gz_put}" );
		}

		return array(
			'file'    => $put,
			'gz_file' => $gz_put,
			'expire'  => @$headers['Expires']
		);
	}

	/**
	 * Returns boolean value if we are always using SSL for our files
	 *
	 * @return bool True if we are using SSL for all URLs
	 */
	function always_ssl()
	{
		return ( $this->ssl );
	}

	/**
	 * Gets the Amazon S3 URL
	 *
	 * @param bool If true, it will check current request to see if we should SSL the domain
	 * @return string URL
	 */
	function get_domain( $uri_check = true )
	{
		$is_ssl = false;

		if ( $uri_check ) {
			if ( isset($_SERVER['HTTPS']) ) {
				if ( 'on' == strtolower($_SERVER['HTTPS']) )
					$is_ssl = true;
				if ( '1' == $_SERVER['HTTPS'] )
					$is_ssl = true;
			} elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
				$is_ssl = true;
			}
		}

		$url = ( ( !$this->always_ssl() && !$is_ssl ) ? 'http://' : 'https://' );

		if ( trim( $this->params->get( 's3_cloudfront_domain' ) ) ) {
			$url .= trim( $this->params->get( 's3_cloudfront_domain' ) );
		} else {
			$url .= $this->get_bucket() . '.s3.amazonaws.com';
		}

		return $url;
	}
}

/**
 * Class for creating connection with Amazon S3
 */
class RACKSPACE_CDN extends CDN_HELER
{
	/**
	 * Amazon S3 class object
	 *
	 * @type Object
	 */
	public $rs;

	/**
	 * The bucket name to store all files
	 *
	 * @type String
	 */
	public $bucket;

	/**
	 * If SSL is on
	 *
	 * @type Bool
	 */
	public $ssl;

	/**
	 * Constructor -- Creates instance of Amazon S3 connection
	 *
	 * @param string Amazon S3 public key
	 * @param string Amazon S3 secret key
	 * @param bool Use SSL
	 * @param string Amazon S3 bucket to use
	 * @return void
	 */
	function __construct( $username, $api_key, $bucket, $config = array() )
	{
		foreach ( $config as $key => $value ) {
			$this->$key = $value;
		}

		if ( defined( 'CDN_CRON_RUNNING' ) && CDN_CRON_RUNNING ) {
			$path = dirname( __FILE__ ) .DS. $this->_name .DS. 'rackspace-php-cloudfiles'
				.DS. 'cloudfiles.php';
			if ( $this->params->get( 'rs_account_is_uk', 0 ) ) {
				$path = dirname( __FILE__ ) .DS. $this->_name .DS. 'rackspace-php-cloudfiles'
					.DS. 'cloudfiles-uk.php';
			}
			require_once $path;

			$auth = new CF_Authentication( $api_key, $username );
			$auth->authenticate();

			$conn = new CF_Connection( $auth );
			$this->rs = $conn->create_container( $bucket );
			$this->rs->make_public();
		}

		$this->bucket = $bucket;
	}

	/**
	 * Sends a file to the CDN
	 * The file that is sent can either be a string or the absolute path to a file
	 *
	 * For the headers and caching, you can read more at
	 * {http://www.badpenguin.org/php-howto-control-page-caching}
	 *
	 * @param string File contents or absolute path to file
	 * @param string The path to the file on the CDN
	 * @param mixed The type that will be set as the Content-Type header.
	 * 		It can also be an array of headers.
	 * @return mixed False if unsucessful, Path to CDN file if successful
	 */
	function put_object( $local_file, $cdn_file = '', $type = null )
	{
		if ( '' == $cdn_file ) {
			$cdn_file = $local_file;
		}

		if ( !is_array( $type ) ) {
			$headers = array();
		} else {
			$headers = $type;
		}

		$headers = $this->get_headers( $headers, $cdn_file );
		$input   = null;

		// If the Content-Type is set, lets get it here
		$content_type = '';
		if ( is_string( $type ) ) {
			$content_type = $type;
		} elseif ( !isset( $headers['Content-Type'] ) && is_file( $local_file ) ) {
			$content_type = $this->__getMimeType( $local_file );
		} elseif ( isset( $headers['Content-Type'] ) ) {
			$content_type = $headers['Content-Type'];
		}

		// If is file
		if ( is_file( $local_file ) ) {
			if ( $headers['do_minify'] ) {
				$local_file = JFile::read( $local_file );
			}
		}
		if ( null == $input && is_string( $local_file ) ) { // If is content
			if ( $headers['do_minify'] ) {
				switch ( $headers['do_minify'] ) {
					case 'jsmin':
						$local_file = CPP_JSMin::minify( $local_file );
						break;
					case 'cssmin':
						$local_file = CPP_cssmin::minify( $local_file );
						break;
					default:
						break;
				}
			}
		}

		$cdn_file_path = str_replace( DS, '/', str_replace( JPATH_ROOT . DS, '', $cdn_file ) );

		$asset = $this->rs->create_object( $cdn_file_path );
		$asset->content_type = $content_type;
		$asset->metadata = $headers;

		if ( is_file( $local_file ) ) {
			$asset->load_from_filename( $local_file );
		} else {
			$asset->write( $local_file, strlen( $local_file ) );
		}

		// If success
		$put = $asset->public_uri();

		if ( CDN_DEBUG ) {
			myPrint( "CDN Added:\t{$cdn_file}\n\t\t{$put}" );
		}

		return array(
			'file'    => $put,
			'gz_file' => '', // Nothing for Rackspace as it is done on the FLYYY
			'expire'  => @$headers['Expires']
		);
	}

	/**
	 * Gets the domain for cdn distro
	 *
	 * @return string URL
	 */
	function get_domain()
	{
		$url = '';

		return $url;
	}
}

class CDN_HELER
{
	/**
	 * Function used to get any custom headers for the file to be sent to CDN
	 *
	 * @param
	 * @return array Contains headers
	 */
	function get_headers( $headers, $cdn_file )
	{
		// Set cache headers
		if ( ( $ext = str_replace( '.', '', strrchr( $cdn_file, '.' ) ) ) ) {
			$offset = '';

			if ( in_array( $ext, $this->image_extensions ) ) {
				// Default offset 10 days
				$offset = $this->params->get( 'cache_image_lifetime', 3600 * 24 * 10 );
			}
			// Only for ico files
			if ( $ext == 'ico' ) {
				// Default offset 10 days
				$offset = $this->params->get( 'cache_ico_lifetime', 3600 * 24 * 10 );
			}
			// Only for css files
			if ( $ext == 'css' ) {
				// Default offset 10 days
				$offset = $this->params->get( 'cache_css_lifetime', 3600 * 24 * 10 );

				// Here we are just setting a flag to compress and minify this file type
				$headers['do_gzip'] = true;
				$headers['do_minify'] = 'cssmin';
			}
			if ( in_array( $ext, $this->script_extensions ) ) {
				// Default offset 10 days
				$offset = $this->params->get( 'cache_js_lifetime', 3600 * 24 * 10 );

				// Here we are just setting a flag to compress and minify this file type
				$headers['do_gzip'] = true;
				$headers['do_minify'] = 'jsmin';
			}
			// For htc files
			if ( 'htc' == $ext ) {
				// Default offset 10 days
				$offset = $this->params->get( 'cache_htc_lifetime', 3600 * 24 * 10 );

				$headers['Content-Type'] = 'text/x-component';
			}

			// For document files
			if ( in_array( $ext, plgSystemJomCDN::$document_extensions ) ) {
				// Default offset 15 days
				$offset = $this->params->get( 'cache_documents_lifetime', 3600 * 24 * 15 );
			}

			// For data files
			if ( in_array( $ext, plgSystemJomCDN::$data_extensions ) ) {
				// Default offset 15 days
				$offset = $this->params->get( 'cache_datafiles_lifetime', 3600 * 24 * 15 );
			}

			// For music files
			if ( in_array( $ext, plgSystemJomCDN::$music_extensions ) ) {
				// Default offset 30 days
				$offset = $this->params->get( 'cache_music_lifetime', 3600 * 24 * 30 );
			}

			// For video files
			if ( in_array( $ext, plgSystemJomCDN::$video_extensions ) ) {
				// Default offset 30 days
				$offset = $this->params->get( 'cache_video_lifetime', 3600 * 24 * 30 );
			}

			// For zip files
			if ( in_array( $ext, plgSystemJomCDN::$zip_extensions ) ) {
				// Default offset 30 days
				$offset = $this->params->get( 'cache_compressed_lifetime', 3600 * 24 * 30 );
			}

			// For extra extensions
			if ( ( $key = array_search( $ext, plgSystemJomCDN::$extra_extensions ) ) ) {
				$offset = plgSystemJomCDN::$extra_lifetime[ $key ];
			}

			if ( '' != $offset ) {
				// Calculate the string in GMT not localtime and add the offset
				if ( !isset( $headers['Expires'] ) ) {
					$headers['Expires'] = gmdate( 'D, d M Y H:i:s', time() + $offset ) . ' GMT';
				}

				// Add Cache-Control
				if ( !isset( $headers['Cache-Control'] ) ) {
					$headers['Cache-Control'] = "max-age={$offset}, must-revalidate";
				}
			}
		}

		return $headers;
	}

	/**
	 * Function returns the URL format to access the current bucket
	 *
	 * @return string The URL path to the bucket
	 */
	function get_bucket_domain()
	{
		return $this->get_domain();
	}

	/**
	 * Gets the bucket name
	 *
	 * @return string URL
	 */
	function get_bucket()
	{
		return $this->bucket;
	}

	/**
	* Get MIME type for file
	*
	* @internal Used to get mime types
	* @param string &$file File path
	* @return string
	*/
	function __getMimeType( &$file )
	{
		$type = false;
		// Fileinfo documentation says fileinfo_open() will use the
		// MAGIC env var for the magic file
		if (extension_loaded('fileinfo') && isset($_ENV['MAGIC']) &&
		($finfo = finfo_open(FILEINFO_MIME, $_ENV['MAGIC'])) !== false) {
			if (($type = finfo_file($finfo, $file)) !== false) {
				// Remove the charset and grab the last content-type
				$type = explode(' ', str_replace('; charset=', ';charset=', $type));
				$type = array_pop($type);
				$type = explode(';', $type);
				$type = trim(array_shift($type));
			}
			finfo_close($finfo);

		// If anyone is still using mime_content_type()
		}// elseif (function_exists('mime_content_type'))
			// $type = trim(mime_content_type($file));

		if ($type !== false && strlen($type) > 0 && $type != 'text/plain') return $type;

		// Otherwise do it the old fashioned way
		static $exts = array(
			'3gp' => 'video/3gpp',
			'ai' => 'application/postscript',
			'aif' => 'audio/x-aiff',
			'aifc' => 'audio/x-aiff',
			'aiff' => 'audio/x-aiff',
			'asc' => 'text/plain',
			'atom' => 'application/atom+xml',
			'au' => 'audio/basic',
			'avi' => 'video/x-msvideo',
			'bcpio' => 'application/x-bcpio',
			'bin' => 'application/octet-stream',
			'bmp' => 'image/bmp',
			'cdf' => 'application/x-netcdf',
			'cgm' => 'image/cgm',
			'class' => 'application/octet-stream',
			'cpio' => 'application/x-cpio',
			'cpt' => 'application/mac-compactpro',
			'csh' => 'application/x-csh',
			'css' => 'text/css',
			'dcr' => 'application/x-director',
			'dif' => 'video/x-dv',
			'dir' => 'application/x-director',
			'djv' => 'image/vnd.djvu',
			'djvu' => 'image/vnd.djvu',
			'dll' => 'application/octet-stream',
			'dmg' => 'application/octet-stream',
			'dms' => 'application/octet-stream',
			'doc' => 'application/msword',
			'docx' => 'application/msword',
			'dtd' => 'application/xml-dtd',
			'dv' => 'video/x-dv',
			'dvi' => 'application/x-dvi',
			'dxr' => 'application/x-director',
			'eps' => 'application/postscript',
			'etx' => 'text/x-setext',
			'exe' => 'application/octet-stream',
			'ez' => 'application/andrew-inset',
			'flv' => 'video/x-flv',
			'gif' => 'image/gif',
			'gram' => 'application/srgs',
			'grxml' => 'application/srgs+xml',
			'gtar' => 'application/x-gtar',
			'gz' => 'application/x-gzip',
			'hdf' => 'application/x-hdf',
			'hqx' => 'application/mac-binhex40',
			'htm' => 'text/html',
			'html' => 'text/html',
			'ice' => 'x-conference/x-cooltalk',
			'ico' => 'image/x-icon',
			'ics' => 'text/calendar',
			'ief' => 'image/ief',
			'ifb' => 'text/calendar',
			'iges' => 'model/iges',
			'igs' => 'model/iges',
			'jnlp' => 'application/x-java-jnlp-file',
			'jp2' => 'image/jp2',
			'jpe' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpg' => 'image/jpeg',
			'js' => 'application/x-javascript',
			'kar' => 'audio/midi',
			'latex' => 'application/x-latex',
			'lha' => 'application/octet-stream',
			'lzh' => 'application/octet-stream',
			'm3u' => 'audio/x-mpegurl',
			'm4a' => 'audio/mp4a-latm',
			'm4p' => 'audio/mp4a-latm',
			'm4u' => 'video/vnd.mpegurl',
			'm4v' => 'video/x-m4v',
			'mac' => 'image/x-macpaint',
			'man' => 'application/x-troff-man',
			'mathml' => 'application/mathml+xml',
			'me' => 'application/x-troff-me',
			'mesh' => 'model/mesh',
			'mid' => 'audio/midi',
			'midi' => 'audio/midi',
			'mif' => 'application/vnd.mif',
			'mov' => 'video/quicktime',
			'movie' => 'video/x-sgi-movie',
			'mp2' => 'audio/mpeg',
			'mp3' => 'audio/mpeg',
			'mp4' => 'video/mp4',
			'mpe' => 'video/mpeg',
			'mpeg' => 'video/mpeg',
			'mpg' => 'video/mpeg',
			'mpga' => 'audio/mpeg',
			'ms' => 'application/x-troff-ms',
			'msh' => 'model/mesh',
			'mxu' => 'video/vnd.mpegurl',
			'nc' => 'application/x-netcdf',
			'oda' => 'application/oda',
			'ogg' => 'application/ogg',
			'ogv' => 'video/ogv',
			'pbm' => 'image/x-portable-bitmap',
			'pct' => 'image/pict',
			'pdb' => 'chemical/x-pdb',
			'pdf' => 'application/pdf',
			'pgm' => 'image/x-portable-graymap',
			'pgn' => 'application/x-chess-pgn',
			'pic' => 'image/pict',
			'pict' => 'image/pict',
			'png' => 'image/png',
			'pnm' => 'image/x-portable-anymap',
			'pnt' => 'image/x-macpaint',
			'pntg' => 'image/x-macpaint',
			'ppm' => 'image/x-portable-pixmap',
			'ppt' => 'application/vnd.ms-powerpoint',
			'pptx' => 'application/vnd.ms-powerpoint',
			'ps' => 'application/postscript',
			'qt' => 'video/quicktime',
			'qti' => 'image/x-quicktime',
			'qtif' => 'image/x-quicktime',
			'ra' => 'audio/x-pn-realaudio',
			'ram' => 'audio/x-pn-realaudio',
			'ras' => 'image/x-cmu-raster',
			'rdf' => 'application/rdf+xml',
			'rgb' => 'image/x-rgb',
			'rm' => 'application/vnd.rn-realmedia',
			'roff' => 'application/x-troff',
			'rtf' => 'text/rtf',
			'rtx' => 'text/richtext',
			'sgm' => 'text/sgml',
			'sgml' => 'text/sgml',
			'sh' => 'application/x-sh',
			'shar' => 'application/x-shar',
			'silo' => 'model/mesh',
			'sit' => 'application/x-stuffit',
			'skd' => 'application/x-koan',
			'skm' => 'application/x-koan',
			'skp' => 'application/x-koan',
			'skt' => 'application/x-koan',
			'smi' => 'application/smil',
			'smil' => 'application/smil',
			'snd' => 'audio/basic',
			'so' => 'application/octet-stream',
			'spl' => 'application/x-futuresplash',
			'src' => 'application/x-wais-source',
			'sv4cpio' => 'application/x-sv4cpio',
			'sv4crc' => 'application/x-sv4crc',
			'svg' => 'image/svg+xml',
			'swf' => 'application/x-shockwave-flash',
			't' => 'application/x-troff',
			'tar' => 'application/x-tar',
			'tcl' => 'application/x-tcl',
			'tex' => 'application/x-tex',
			'texi' => 'application/x-texinfo',
			'texinfo' => 'application/x-texinfo',
			'tif' => 'image/tiff',
			'tiff' => 'image/tiff',
			'tr' => 'application/x-troff',
			'tsv' => 'text/tab-separated-values',
			'txt' => 'text/plain',
			'ustar' => 'application/x-ustar',
			'vcd' => 'application/x-cdlink',
			'vrml' => 'model/vrml',
			'vxml' => 'application/voicexml+xml',
			'wav' => 'audio/x-wav',
			'wbmp' => 'image/vnd.wap.wbmp',
			'wbxml' => 'application/vnd.wap.wbxml',
			'webm' => 'video/webm',
			'wml' => 'text/vnd.wap.wml',
			'wmlc' => 'application/vnd.wap.wmlc',
			'wmls' => 'text/vnd.wap.wmlscript',
			'wmlsc' => 'application/vnd.wap.wmlscriptc',
			'wmv' => 'video/x-ms-wmv',
			'wrl' => 'model/vrml',
			'xbm' => 'image/x-xbitmap',
			'xht' => 'application/xhtml+xml',
			'xhtml' => 'application/xhtml+xml',
			'xls' => 'application/vnd.ms-excel',
			'xlsx' => 'application/vnd.ms-excel',
			'xml' => 'application/xml',
			'xpm' => 'image/x-xpixmap',
			'xsl' => 'application/xml',
			'xslt' => 'application/xslt+xml',
			'xul' => 'application/vnd.mozilla.xul+xml',
			'xwd' => 'image/x-xwindowdump',
			'xyz' => 'chemical/x-xyz',
			'zip' => 'application/zip',
		);
		$ext = strtolower(pathInfo($file, PATHINFO_EXTENSION));
		return isset($exts[$ext]) ? $exts[$ext] : 'application/octet-stream';
	}
}

if ( !function_exists( 'myPrint' ) ) :
/**
 * Function for printing data
 * @return
 */
function myPrint( $var, $pre = true )
{
	if( $pre )
		echo '<pre>';
	print_r( $var );
	if( $pre )
		echo '</pre>';
}
endif;


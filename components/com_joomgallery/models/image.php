<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/components/com_joomgallery/models/image.php $
// $Id: image.php 3384 2011-10-09 15:56:22Z erftralle $
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
 * Image Model
 *
 * Creates the output of a single image.
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryModelImage extends JoomGalleryModel
{
  /**
   * The ID of the image
   *
   * @access  protected
   * @var     int
   */
  var $_id;

  /**
   * The image data
   *
   * @access  protected
   * @var     int
   */
  var $_image;

  /**
   * Constructor
   *
   * @access  protected
   * @return  void
   * @since   1.5.5
   */
  function __construct()
  {
    parent::__construct();
  }

  /**
   * Method to set the image id
   *
   * @access  public
   * @param   int   Image ID number
   * @since   1.5.5.0
   */
  function setId($id)
  {
    // Set new image ID if valid and wipe data
    if(!$id)
    {
      JError::raiseError(500, JText::_('COM_JOOMGALLERY_COMMON_NO_IMAGE_SPECIFIED'));
    }
    $this->_id    = $id;
    $this->_image = null;
  }

  /**
   * Method to get the identifier
   *
   * @access  public
   * @return  int identifier
   * @since   1.5.5.0
   */
  function getId()
  {
    return $this->_id;
  }

  /**
   * Method to get the image data
   *
   * @access  public
   * @return  object  Holds the image data
   * @since   1.5.5
   */
  function getImage()
  {
    $id = JRequest::getInt('id');
    $this->setId($id);

    if(!$this->_loadImage())
    {
      return false;
    }

    $image = $this->_image;

    /*if(!in_array($image->access, $this->_user->getAuthorisedViewLevels()))
    {
      $this->_mainframe->redirect(JRoute::_('index.php?view=gallery'),
                                  JText::_('COM_JOOMGALLERY_NOT_ALLOWED_VIEW_PICTURE'), 'notice');
    }*/

    // Source url
    $image->img_src    = $this->_ambit->getImg('img_url', $image);

    // Information about original image if available
    $orig = $this->_ambit->getImg('orig_path', $image);
    if(JFile::exists($orig))
    {
      $image->orig_exists   = true;
      $orig_info            = getimagesize($orig);
      $orig_size            = filesize($orig);
    }
    else
    {
      $image->orig_exists   = false;
      $orig_info[0]  = 0;
      $orig_info[1]  = 0;
    }

    // Information about detail image
    $img = $this->_ambit->getImg('img_path', $image);
    $img_info               = getimagesize($img);
    $img_size               = filesize($img);
    $image->img_size        = number_format($img_size / 1024, 2, JText::_('COM_JOOMGALLERY_COMMON_DECIMAL_SEPARATOR'), JText::_('COM_JOOMGALLERY_COMMON_THOUSANDS_SEPARATOR')).' '.JText::_('COM_JOOMGALLERY_DETAIL_KB');

    $image->orig_width      = $orig_info[0];
    $image->orig_height     = $orig_info[1];
    $image->img_width       = $img_info[0];
    $image->img_height      = $img_info[1];

    $image->bigger_orig     = false;
    if(    $image->orig_exists
        && $image->orig_width  > $image->img_width
        && $image->orig_height > $image->img_height
      )
    {
      $image->bigger_orig   = true;
    }

    if($this->_config->get('jg_resizetomaxwidth'))
    {
      $ratio                = max($image->img_width, $image->img_height);
      $ratio                = ($ratio/$this->_config->get('jg_maxwidth'));
      $ratio                = max($ratio, 1.0);
      $image->width         = (int)($image->img_width / $ratio);
      $image->height        = (int)($image->img_height / $ratio);
    }
    else
    {
      $image->width         = $image->img_width;
      $image->height        = $image->img_height;
    }

    return $image;
  }

  /**
   * Method to increment the hit counter for the image
   *
   * @access  public
   * @return  boolean True on success, false otherwise
   * @since   1.5.5
   */
  function hit()
  {
    if($this->_id && !$this->countstop())
    {
      $image = &$this->getTable('joomgalleryimages');
      $image->hit($this->_id);
      return true;
    }

    return false;
  }

  /**
   * Method to check whether the hit counter should be incremented
   *
   * @access  public
   * @return  boolean True, if the hit counter is locked, false otherwise
   * @since   1.5.5
   */
  function countstop()
  {
    $session    = & JFactory::getSession();
    $session_id = $session->getToken();

    $stoptime   = $this->_mainframe->getCfg('lifetime') * 60;
    $ip         = $_SERVER['REMOTE_ADDR'];

    // Delete all dated entries
    $this->_db->setQuery("DELETE
                          FROM
                            "._JOOM_TABLE_COUNTSTOP."
                          WHERE
                            NOW() > date_add(cstime, interval ".$stoptime." SECOND)
                        ");
    $this->_db->query();

    // Check whether entry exists
    $this->_db->setQuery("SELECT
                            COUNT(cspicid)
                          FROM
                            "._JOOM_TABLE_COUNTSTOP."
                          WHERE
                                cssessionid = '".$session_id."'
                            AND csip        = '".$ip."'
                            AND cspicid     = ".$this->_id."
                        ");

    if($this->_db->loadResult())
    {
      // Lock the counter
      return true;
    }
    else
    {
      // New entry
      $this->_db->setQuery("INSERT INTO
                              "._JOOM_TABLE_COUNTSTOP."
                              (csip, cssessionid, cspicid, cstime)
                            VALUES
                              ('".$ip."', '".$session_id."', ".$this->_id.", NOW())
                          ");
      $this->_db->query();

      return false;
    }
  }

  /**
   * Method to check whether a given image is a gif file
   *
   * @access  public
   * @param   string  $file Path to the image to check
   * @return  boolean True, if the given image is a gif file, false otherwise
   * @since   1.5.5
   */
  function isGif($file)
  {
    jimport('joomla.filesystem.file');

    // Reads file content into string
    $filecontents = JFile::read($file);
    $str_loc      = 0;
    $count        = 0;

    // Checks if there is more than one frame
    while($count < 2)
    {
      $where1 = strpos($filecontents, "\x00\x21\xF9\x04", $str_loc);
      if(!$where1)
      {
        break;
      }
      else
      {
        $str_loc = $where1+1;
        $where2  = strpos($filecontents, "\x00\x2C", $str_loc);
        if(!$where2)
        {
          break;
        }
        else
        {
          if($where1+8 == $where2)
          {
            $count++;
          }
        $str_loc = $where2+1;
        }
      }
    }

    // Returns true if more then one frame is found
    if($count > 1)
    {
      return true;
    }

    return false;
  }

  /**
   * Method to include the watermark selected in
   * the configuration manager into a given image
   *
   * @access  public
   * @param   string    $file     Path to the image into which the watermark shall be included
   * @param   resource  $src_img  GD image resource, will be used instead of $file if it is set
   * @return  resource  image resource
   * @since   1.5.5
   */
  function includeWatermark($file, $src_img = null, $cropwidth = 0, $cropheight = 0)
  {
    // Path to the watermarkfile
    $watermark = JPath::clean($this->_ambit->get('wtm_path').$this->_config->get('jg_wmfile'));

    // Checks if watermark file is existent
    if(!JFile::exists($watermark))
    {
      $this->_mainframe->redirect(JRoute::_('index.php?view=gallery', false), JText::_('Watermark does not exist'), 'error');
    }

    // Gets information of the image (height, width, mime)
    if(!$src_img)
    {
      $info_img = getimagesize($file);
    }
    else
    {
      $info_img = array(0 => imagesx($src_img), 1 => imagesy($src_img));
    }
    $info_wat = getimagesize($watermark);

    // Gets the position of the watermark
    $t_x = 0;
    $t_y = 0;
    $position = $this->_config->get('jg_watermarkpos');
    // Position x
    switch(($position - 1) % 3)
    {
      case 1:
        $pos_x = round(($info_img[0] - $info_wat[0]) / 2, 0);
        break;
      case 2:
        $pos_x = $info_img[0] - $info_wat[0];
        break;
      default:
        $pos_x = 0;
        break;
    }
    // Position y
    switch(floor(($position - 1) / 3))
    {
      case 1:
        $pos_y = round(($info_img[1] - $info_wat[1]) / 2, 0);
        break;
      case 2:
        $pos_y = $info_img[1] - $info_wat[1];
        break;
      default:
        $pos_y = 0;
        break;
    }

    if(!$src_img)
    {
      switch($info_img[2])
      {
        case 1:
          $src_img  = imagecreatefromgif($file);
          $mime_img = 'image/gif';
          break;
        case 2:
          $src_img  = imagecreatefromjpeg($file);
          $mime_img = 'image/jpeg';
          break;
        case 3:
          $src_img  = imagecreatefrompng($file);
          $mime_img = 'image/png';
          break;
        default:
          JError::raiseError(404, JText::sprintf('Mime not allowed: %s', $info_img[2]));
          break;
      }
    }

    // Check if image is smaller than watermark and return image without watermark
    if($info_img[0] < $info_wat[0] || $info_img[1] < $info_wat[1])
    {
      return $src_img;
    }

    // Watermark procedure
    switch($info_wat[2])
    {
      case 1:
        $watermark  = imagecreatefromgif($watermark);
        $mime_wat   = 'image/gif';
        break;
      case 2:
        $watermark  = imagecreatefromjpeg($watermark);
        $mime_wat   = 'image/jpeg';
        break;
      case 3:
        $watermark  = imagecreatefrompng($watermark);
        $mime_wat   = 'image/png';
        break;
     default:
        JError::raiseError(404, JText::sprintf('Mime not allowed: %s', $info_wat[2]));
       break;
    }

    $watermark_width  = imagesx($watermark);
    $watermark_height = imagesy($watermark);

    imagealphablending($src_img, true);
    imagealphablending($watermark, true);
    imagecolortransparent($watermark, imagecolorat($watermark, $t_x, $t_y));
    imagecopyresampled($src_img, $watermark, $pos_x, $pos_y, 0, 0, $watermark_width, $watermark_height, $watermark_width, $watermark_height);

    return $src_img;
  }

  /**
   * Method to load image data
   *
   * @access  private
   * @return  boolean True on success, false otherwise
   * @since   1.5.5
   */
  function _loadImage()
  {
    if(!$this->_id)
    {
      return false;
    }

    // Load the image data if it doesn't already exist
    if(empty($this->_image))
    {
      $query = $this->_db->getQuery(true)
            ->select('a.*, a.owner AS imgowner, c.*')
            ->from(_JOOM_TABLE_IMAGES.' AS a')
            ->from(_JOOM_TABLE_CATEGORIES.' AS c')
            ->from(_JOOM_TABLE_CATEGORIES.' AS p')
            ->where('a.id = '.$this->_id)
            ->where('a.published  = 1')
            ->where('a.approved   = 1')
            ->where('c.published  = 1')
            ->where('c.cid        = a.catid')
            ->where('c.lft BETWEEN p.lft AND p.rgt')
            ->where('p.cid         != 1')
            ->where('a.access     IN ('.implode(',', $this->_user->getAuthorisedViewLevels()).')')
            ->where('c.access     IN ('.implode(',', $this->_user->getAuthorisedViewLevels()).')')
            ->where('p.access     IN ('.implode(',', $this->_user->getAuthorisedViewLevels()).')')
            ->group('a.id')
            ->having('COUNT(p.cid) >= c.level');
      $this->_db->setQuery($query);
      /*$this->_db->setQuery("SELECT
                              a.*,
                              a.owner AS imgowner,
                              c.*
                            FROM
                              "._JOOM_TABLE_IMAGES." AS a,
                              "._JOOM_TABLE_CATEGORIES." AS c,
                              "._JOOM_TABLE_CATEGORIES." AS p
                            WHERE
                                  a.id = ".$this->_id."
                              AND a.published = 1
                              AND a.approved  = 1
                              AND a.access    IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")
                              AND c.published = 1
                              AND c.access    IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")

                              AND c.cid = a.catid
                              AND p.access IN (".implode(',', $this->_user->getAuthorisedViewLevels()).")
                              AND c.lft BETWEEN p.lft AND p.rgt
                              AND p.cid != 1
                            GROUP BY a.id
                            HAVING COUNT(p.cid) >= c.level
                              "
                          );*/

      if(!$row = $this->_db->loadObject())
      {
        JError::raiseError(500, JText::sprintf('Image with ID %d not found', $this->_id));
      }

      $this->_image = $row;

      return true;
    }

    return true;
  }

  /**
   * Method to crop a image
   *
   * @access  public
   * @param   string    $img        Path to image
   * @param   int       $cropwidth  Width of resulting image
   * @param   int       $cropheight Height of resulting image
   * @param   int       $croppos    Offset position of cropping window
   * @param   int       $offsetx    Offset x-coordinate
   * @param   int       $offsety    Offset y-coordinate
   * @return  image     Image ressource of cropped image or image if no cropping
   *                    false if no cropping has been done
   * @since   1.5.6
   */
  function cropImage(&$img, &$cropwidth, &$cropheight, &$croppos, $offsetx = 0, $offsety = 0)
  {
    // Get information of image
    $imginfo    = getimagesize($img);
    // Height/width
    $srcWidth    = $imginfo[0];
    $srcHeight   = $imginfo[1];
    $srcImgtype  = $imginfo[2];

    // If both crop settings identical to the source dimensions, return null
    if($srcWidth == $cropwidth && $srcHeight == $cropheight)
    {
      return null;
    }

    if($croppos)
    {
      // Calculate the offsets for cropping the source image according
      // to thumbposition
      switch($croppos)
      {
        // Right upper corner
        case 1:
          $offsetx = floor($srcWidth - $cropwidth);
          $offsety = 0;
          break;
        // Left lower corner
        case 3:
          $offsetx = 0;
          $offsety = floor($srcHeight - $cropheight);
          break;
        // Right lower corner
        case 4:
          $offsetx = floor($srcWidth - $cropwidth);
          $offsety = floor($srcHeight - $cropheight);
          break;
        // default center
        default:
          $offsetx = floor(($srcWidth - $cropwidth) * 0.5);
          $offsety = floor(($srcHeight - $cropheight) * 0.5);
          break;
      }
    }

    switch($srcImgtype)
    {
      // GIF
      case 1:
        $src_img = imagecreatefromgif($img);
        break;
      // JPEG
      case 2:
        $src_img = imagecreatefromjpeg($img);
        break;
      // PNG
      case 3:
        $src_img = imagecreatefrompng($img);
        break;
      default:
        $src_img = imagecreatefromjpeg($img);
        break;
    }

    $cropimg = imagecreatetruecolor($cropwidth, $cropheight);

    // Check if the cropped image should be filled with background color
    if($cropwidth > $srcWidth || $cropheight > $srcHeight)
    {
      // Get background color for cropped image
      $cropbgcol = $this->_config->get('jg_dyncropbgcol');

      // Calculate a rgb code from hex value
      $rgb[0] = hexdec(substr($cropbgcol, 0, 2));
      $rgb[1] = hexdec(substr($cropbgcol, 2, 2));
      $rgb[2] = hexdec(substr($cropbgcol, 4, 2));

      // Allocate the color and fill the background of cropped image
      $bgcolor=imagecolorallocate($cropimg, $rgb[0], $rgb[1], $rgb[2]);
      imagefill($cropimg, 0, 0, $bgcolor);
    }

    $dst_x = 0;
    $dst_y = 0;
    $src_w = $cropwidth;
    $src_h = $cropheight;

    // The starting position '$offsetx' for the crop must be within the source image
    if($offsetx < 0 || $offsetx > $srcWidth)
    {
      $offsetx = 0;
    }
    // Check, if the cropped image width will be larger than the source image width
    if($cropwidth > $srcWidth)
    {
      // Center source image horizontal
      $dst_x = floor(($cropwidth - $srcWidth) * 0.5);
      // Reduce width to copy to the source image width
      $src_w = $srcWidth;
    }
    // The starting position '$offsety' for the crop must be within the source image
    if($offsety < 0 || $offsety > $srcHeight)
    {
      $offsety = 0;
    }
    // Check, if the cropped image height will be larger than the source image height
    if($cropheight > $srcHeight)
    {
      // Center source image vertical
      $dst_y = floor(($cropheight - $srcHeight) * 0.5);
      // Reduce height to copy to the source image height
      $src_h = $srcHeight;
    }

    imagecopy($cropimg, $src_img, $dst_x, $dst_y, $offsetx, $offsety, $src_w, $src_h);

    return $cropimg;
  }
}
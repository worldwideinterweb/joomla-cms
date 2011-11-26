<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/changelog.php $
// $Id: changelog.php 3508 2011-11-15 17:45:08Z chraneco $
/******************************************************************************\
**   JoomGallery 2                                                            **
**   By: JoomGallery::ProjectTeam                                             **
**   Copyright (C) 2008 - 2011  JoomGallery::ProjectTeam                      **
**   Based on: JoomGallery 1.0.0 by JoomGallery::ProjectTeam                  **
**   Released under GNU GPL Public License                                    **
**   License: http://www.gnu.org/copyleft/gpl.html or have a look             **
**   at administrator/components/com_joomgallery/LICENSE.TXT                  **
\******************************************************************************/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>

CHANGELOG JOOMGALLERY (since Version 2.0.0 BETA)

Legende / Legend:

* -> Security Fix
# -> Bug Fix
+ -> Addition
^ -> Change
- -> Removed
! -> Note

===============================================================================
                                      2.0.0 BETA3
                                      (20111115)
===============================================================================

20111115
# Nested Set tree was not build correctly when moving a category into a category
  which doesn't already have sub-categories

20111114
# AJAX rating not working if display of rating in detail view (image information)
  not enabled

20111113
+ Changing access level for multiple images in the back end

20111108
# Inserting images from MiniJoom wasn't possible after an Ajax request was done

20111107
# SEF of images shouldn't be enabled by default

20111104
# Bug in MiniJoom due to which upload in frontend wasn't possible if upload
  categories were specified

20111102
# Alphabetical sorting of categories in category view

20111101
+ Usability improvements in category, image, comments and maintenance manager

===============================================================================
                                      2.0.0 BETA2
                                      (20111031)
===============================================================================
20111030
+ Improvement in migration manager so that category ordering isn't lost during migration

20111025
# Wrong language constants corrected and missing constants added

20111022
# Missing language constants added

20111021
# Tooltips were not working if 'Enabled with different styling' in configuration
  manager
# Language output in configuration manager for joom_settings.css corrected
# Hits haven't been counted in default detail view if 'Use real paths' was enabled
# Small fixes in the interface

===============================================================================
                                      2.0.0 BETA
                                      (20111016)
===============================================================================
20110917
^ new JAVA-Applet 5.0.5 Build 1566
20110714
+ Options 'Image title/description in DHTML container' renamed to
  'Image title/description in popup' -> functionality enlarged to all boxes
20110630
+ more options for accordion
+ new option 'skip category view'
20110622
+ Batchupload allows now any archive types defined in Joomla!
20110515
^ new jquery version 1.6.1 for thickbox3 because of problems with IE9 and DOMready()
  in older jquery, small changes in thickbox.js

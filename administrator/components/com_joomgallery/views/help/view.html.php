<?php
// $HeadURL: https://joomgallery.org/svn/joomgallery/JG-2.0/JG/trunk/administrator/components/com_joomgallery/views/help/view.html.php $
// $Id: view.html.php 3421 2011-10-16 18:26:07Z mab $
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
 * HTML View class for the help view
 *
 * @package JoomGallery
 * @since   1.5.5
 */
class JoomGalleryViewHelp extends JoomGalleryView
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
    $params = JComponentHelper::getParams('com_joomgallery');

    JToolBarHelper::title(JText::_('COM_JOOMGALLERY_HLPIFO_HELP_MANAGER'), 'systeminfo');
    JToolbarHelper::custom('cpanel', 'options.png', 'options.png', 'COM_JOOMGALLERY_COMMON_TOOLBAR_CPANEL', false);
    JToolbarHelper::spacer();

    $languages = array( 'de-DE-formal'    => array( 'translator'    => 'JoomGallery::ProjectTeam',
                                                    'downloadlink'  => 'http://www.joomgallery.net/downloads/joomgallery-fuer-joomla-25/sprachdateien/die-deutschen-formellen-sprachdateien.html',
                                                    'flag'          => 'de.png',
                                                    'type'          => 'formal'),
                        'de-DE-informal'  => array( 'translator'    => 'JoomGallery::ProjectTeam',
                                                    'downloadlink'  => 'http://www.joomgallery.net/downloads/joomgallery-fuer-joomla-25/sprachdateien/die-deutschen-informellen-sprachdateien.html',
                                                    'flag'          => 'de.png')
/**                        'nl-NL'           => array( 'translator'    => 'Gerard Westerdijk',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-netherlands-language-files.html',
                                                    'flag'          => 'nl.png'),
                        'ru-RU'           => array( 'translator'    => 'Hermann Maurer (Exif by mikenike, IPTC by Michael Grigorev)',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-russian-language-files.html',
                                                    'flag'          => 'ru.png'),
                        'zh-CN'           => array( 'translator'    => 'baijianpeng',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-chinese-simplified-language-files.html',
                                                    'flag'          => 'cn.png'),
                        'zh-TW'           => array( 'translator'    => 'baijianpeng',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-chinese-traditional-language-files.html',
                                                    'flag'          => 'cn.png'),
                        'es-ES'           => array( 'translator'    => 'Ernesto de la Fuente',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-spanish-language-files.html',
                                                    'flag'          => 'es.png'),
                        'hu-HU-formal'    => array( 'translator'    => 'István Kathagen',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-hungarian-formal-language-files.html',
                                                    'flag'          => 'hu.png'),
                        'hu-HU-informal'  => array( 'translator'    => 'István Kathagen',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-hungarian-informal-language-files.html',
                                                    'flag'          => 'hu.png'),
                        'fr-FR'           => array( 'translator'    => 'Pereira Edgar, François-Xavier Duchène & Floris Moriceau',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-french-language-files.html',
                                                    'flag'          => 'fr.png'),
                        'da-DK'           => array( 'translator'    => 'Uffe Christoffersen',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-danish-language-files.html',
                                                    'flag'          => 'dk.png'),
                        'pt-BR'           => array( 'translator'    => 'Joomla Brasil & Edson Katana',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-brazilian-portuguese-language-files.html',
                                                    'flag'          => 'br.png'),
                        'ja-JP'           => array( 'translator'    => 'retromania',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-japanese-language-files.html',
                                                    'flag'          => 'jp.png'),
                        'sv-SE'           => array( 'translator'    => 'Arni Skulason & Mia Steen',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-swedish-language-files.html',
                                                    'flag'          => 'se.png'),
                        'pl-PL'           => array( 'translator'    => 'Stefan Wajda, Bogdan Wróbel and Trzepizur Michał',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-polish-language-files.html',
                                                    'flag'          => 'pl.png'),
                        'fi-FI'           => array( 'translator'    => 'Antti Värre & Sami Haaranen',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-finnish-language-files.html',
                                                    'flag'          => 'fi.png'),
                        'fa-IR'           => array( 'translator'    => 'S.H. Anvari',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-persian-language-files.html',
                                                    'flag'          => 'ir.png'),
                        'it-IT'           => array( 'translator'    => 'Andrea Puddu',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-italian-language-files.html',
                                                    'flag'          => 'it.png'),
                        'lt-LT'           => array( 'translator'    => 'Andrius Balsevičius',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-lithunian-language-files.html',
                                                    'flag'          => 'lt.png'),
                        'bs-BA'           => array( 'translator'    => 'Dinko Rizvić',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-bosnian-language-files.html',
                                                    'flag'          => 'ba.png'),
                        'bg-BG'           => array( 'translator'    => 'Anton Bondoff',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-bulgarian-language-files.html',
                                                    'flag'          => 'bg.png'),
                        'cs-CZ'           => array( 'translator'    => 'phperseus',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-czech-language-files.html',
                                                    'flag'          => 'cz.png'),
                        'hr-HR'           => array( 'translator'    => 'Boris Lukić',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-croatian-language-files.html',
                                                    'flag'          => 'hr.png'),
                        'lv-LV'           => array( 'translator'    => 'Oleg Kosarev',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-latvian-language-files.html',
                                                    'flag'          => 'lv.png'),
                        'nb-NO'           => array( 'translator'    => 'Nils Ally',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-norwegian-language-files.html',
                                                    'flag'          => 'no.png'),
                        'pt-PT'           => array( 'translator'    => 'João Mota',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-portuguese-language-files.html',
                                                    'flag'          => 'pt.png'),
                        'sk-SK'           => array( 'translator'    => 'Radoslav Dudáš',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-slovakian-language-files.html',
                                                    'flag'          => 'sk.png'),
                        'tr-TR'           => array( 'translator'    => 'designer bt, Mustafa Bayraktar & Kadir Özcan',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-turkish-language-files.html',
                                                    'flag'          => 'tr.png'),
                        'uk-UA'           => array( 'translator'    => 'Angelika Polushina',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-ukrainian-language-files.html',
                                                    'flag'          => 'ua.png'),
                        'sr-YU'           => array( 'translator'    => 'Danijel Milenković',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-serbian-language-files.html',
                                                    'flag'          => 'rs.png'),
                        'sr-RS'           => array( 'translator'    => 'Žarko P.',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-serbian-cyrillic-language-files.html',
                                                    'flag'          => 'rs.png'),
                        'vi-VN'           => array( 'translator'    => 'Viet Dũng',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-vietnamese-language-files.html',
                                                    'flag'          => 'vn.png'),
                        'sl-SI'           => array( 'translator'    => 'Gregor Sušanj',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-slovenian-language-files.html',
                                                    'flag'          => 'si.png'),
                        'el-GR'           => array( 'translator'    => 'Stavros Georgiadis',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-greek-language-files.html',
                                                    'flag'          => 'gr.png'),
                        'ar-AA'           => array( 'translator'    => 'Fares Anwar Shammout',
                                                    'downloadlink'  => 'http://www.en.joomgallery.net/downloads/joomgallery-for-joomla-15/languages/the-arabic-unitag-language-files.html',
                                                    'flag'          => 'sy.png')*/
                      );
    if($this->_config->get('jg_checkupdate') && extension_loaded('curl'))
    {
      $params->set('autoinstall_possible', 1);
    }

    $this->assignRef('languages', $languages);
    $this->assignRef('params', $params);

    parent::display($tpl);
  }
}
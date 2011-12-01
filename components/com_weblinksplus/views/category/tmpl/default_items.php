<?php
/**
 * @version		$Id: default_items.php 13471 2009-11-12 00:38:49Z eddieajau
 * @package		Joomla.Site
 * @subpackage	com_weblinks
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;
// Code to support edit links for weblinks
// Create a shortcut for params.
$params = &$this->item->params;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal', 'a.modal');
JHtml::core();

// Get the user object.
$user = JFactory::getUser();
// Check if user is allowed to add/edit based on weblinks permissinos.
$canEdit = $user->authorise('core.edit', 'com_weblinksplus');
$canCreate = $user->authorise('core.create', 'com_weblinksplus');
$canEditState = $user->authorise('core.edit.state', 'com_weblinksplus');

require_once (JPATH_SITE . DS . 'components' . DS . 'com_content' . DS . 'helpers' . DS . 'route.php');

$n = count($this->items);
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<?php if (empty($this->items)) : ?>
    <p> <?php echo JText::_('COM_WEBLINKSPLUS_NO_WEBLINKS'); ?></p>
<?php else : ?>

    <form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
        <?php if ($this->params->get('show_pagination_limit')) : ?>
            <fieldset class="filters">
                <legend class="hidelabeltxt"><?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?></legend>
                <div class="display-limit">
                    <?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
                <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
                <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
            </fieldset>
        <?php endif; ?>

        <?php if ($this->params->get('layout') == 'list') { ?>
            <table class="category">
                <?php if ($this->params->get('show_headings') == 1) : ?>

                    <thead><tr>

                            <th class="title">
                                <?php echo JHtml::_('grid.sort', 'COM_WEBLINKSPLUS_GRID_TITLE', 'title', $listDirn, $listOrder); ?>
                            </th>
                            <?php if ($this->params->get('show_link_hits')) : ?>
                                <th class="hits">
                                    <?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'hits', $listDirn, $listOrder); ?>
                                </th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                <?php endif; ?>
                <tbody>
                    <?php foreach ($this->items as $i => $item) : ?>
                        <?php if ($this->items[$i]->state == 0) : ?>
                            <tr class="system-unpublished cat-list-row<?php echo $i % 2; ?>">
                            <?php else: ?>
                            <tr class="cat-list-row<?php echo $i % 2; ?>" >
                            <?php endif; ?>

                            <td class="title">
                                <p>
                                    <?php if ($this->params->get('icons') == 0) : ?>
                                        <?php echo JText::_('COM_WEBLINKSPLUS_LINK'); ?>
                                    <?php elseif ($this->params->get('icons') == 1) : ?>
                                        <?php if (!$this->params->get('link_icons')) : ?>
                                            <?php echo JHtml::_('image', 'system/' . $this->params->get('link_icons', 'weblink.png'), JText::_('COM_WEBLINKSPLUS_LINK'), NULL, true); ?>
                                        <?php else: ?>
                                            <?php echo '<img src="' . $this->params->get('link_icons') . '" alt="' . JText::_('COM_WEBLINKSPLUS_LINK') . '" />'; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php
                                    // Compute the correct link
                                    $menuclass = 'category' . $this->pageclass_sfx;

                                    $link = $item->link;

                                    if ($item->article_id) {
                                        $db = &JFactory::getDBO();

                                        $query = 'SELECT CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,
                                                CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug 
                                                FROM #__content AS a, #__categories AS cc WHERE cc.id = a.catid AND a.id =' . $item->article_id;

                                        $db->setQuery($query);
                                        $info = $db->loadObject();

                                        $link = JRoute::_(ContentHelperRoute::getArticleRoute($info->slug, $info->catslug));
                                    }

                                    $width = $item->params->get('width');
                                    $height = $item->params->get('height');
                                    if ($width == null || $height == null) {
                                        $width = 600;
                                        $height = 500;
                                    }

                                    if ($item->params->get('showthumbnail')) {
                                        
                                        if ($item->params->get('fullinmodal'))
                                            echo '<a class="modal" href="'.$item->thumbnail.'"  rel="{handler: \'iframe\'}">';
                                        
                                        echo '<img src="media/weblinksplus/thumbs/' . $item->id . '.png" style="padding: 10px; float:left;" />';
                                        
                                        if ($item->params->get('fullinmodal'))
                                            echo '</a>';
                                    }

                                    switch ($item->params->get('target', $this->params->get('target'))) {
                                        case 1:
                                            // open in a new window
                                            echo '<a href="' . $link . '" target="_blank" class="' . $menuclass . '" rel="nofollow">' .
                                            $this->escape($item->title) . '</a>';
                                            break;

                                        case 2:
                                            // open in a popup window
                                            $attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' . $this->escape($width) . ',height=' . $this->escape($height) . '';
                                            echo "<a href=\"$link\" onclick=\"window.open(this.href, 'targetWindow', '" . $attribs . "'); return false;\">" .
                                            $this->escape($item->title) . '</a>';
                                            break;
                                        case 3:
                                            // open in a modal window
                                            ?>
                                            <a class="modal" href="<?php echo $link; ?>"  rel="{handler: 'iframe', size: {x:<?php echo $this->escape($width); ?>, y:<?php echo $this->escape($height); ?>}}">
                                                <?php
                                                echo $this->escape($item->title) . ' </a>';
                                                break;

                                            default:
                                                // open in parent window
                                                echo '<a href="' . $link . '" class="' . $menuclass . '" rel="nofollow">' .
                                                $this->escape($item->title) . ' </a>';
                                                break;
                                        }
                                        ?>
                                        <?php // Code to add the edit link for the weblink. ?>

                                        <?php if ($canEdit) : ?>
                                            <ul class="actions">
                                                <li class="edit-icon">
                                                    <?php echo JHtml::_('icon.edit', $item, $params); ?>
                                                </li>
                                            </ul>
                                        <?php endif; ?>
                                </p>

                                <?php if (($this->params->get('show_link_description')) AND ($item->description != '')): ?>
                                    <?php echo $item->description; ?>
                                <?php endif; ?>
                            </td>
                            <?php if ($this->params->get('show_link_hits')) : ?>
                                <td class="hits">
                                    <?php echo $item->hits; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php } else { 
            
            $total = $this->params->get('columns') * $this->params->get('rows');
            $real = count($this->items);
            
            if ($real > $total)
                array_splice($this->items, $total);
            ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <?php
                $row = 1;
                $col = 1;
                foreach ($this->items as $i => $item) {
//	if ($row > $this->rows) { continue; }
                    if ($col == 1) { ?>
                        <tr>
                    <?php } ?>
                    <td width="<?php echo round(100 / $this->params->get('columns')); ?>" valign="top" align="center">
                    
                    <?php $width = $item->params->get('width');
                    $height = $item->params->get('height');
                    if ($width == null || $height == null) {
                        $width = 600;
                        $height = 500;
                    }
                    
                    if ($item->params->get('showthumbnail')) {
                        if ($item->params->get('fullinmodal'))
                            echo '<a class="modal" href="'.$item->thumbnail.'"  rel="{handler: \'iframe\'}">';
                        
                        echo '<div class="wl_image" style="text-align:center"><img src="media/weblinksplus/thumbs/' . $item->id . '.png" style="padding: 10px;" /></div>';
                        
                        if ($item->params->get('fullinmodal'))
                            echo '</a>';
                    }
                    
                    // Compute the correct link
                    $menuclass = 'category' . $this->pageclass_sfx;
                        
                    $link = $item->link;

                    if ($item->article_id) {
                        $db = &JFactory::getDBO();

                        $query = 'SELECT CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,
                                CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug 
                                FROM #__content AS a, #__categories AS cc WHERE cc.id = a.catid AND a.id =' . $item->article_id;

                        $db->setQuery($query);
                        $info = $db->loadObject();

                        $link = JRoute::_(ContentHelperRoute::getArticleRoute($info->slug, $info->catslug));
                    }
                    
                    switch ($item->params->get('target', $this->params->get('target'))) {
                        case 1:
                            // open in a new window
                            echo '<a href="' . $link . '" target="_blank" class="' . $menuclass . '" rel="nofollow">' .
                            $this->escape($item->title) . '</a>';
                            break;

                        case 2:
                            // open in a popup window
                            $attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' . $this->escape($width) . ',height=' . $this->escape($height) . '';
                            echo "<a href=\"$link\" onclick=\"window.open(this.href, 'targetWindow', '" . $attribs . "'); return false;\">" .
                            $this->escape($item->title) . '</a>';
                            break;
                        case 3:
                            // open in a modal window
                            ?>
                            <a class="modal" href="<?php echo $link; ?>"  rel="{handler: 'iframe', size: {x:<?php echo $this->escape($width); ?>, y:<?php echo $this->escape($height); ?>}}">
                                <?php
                                echo $this->escape($item->title) . ' </a>';
                                break;

                            default:
                                // open in parent window
                                echo '<a href="' . $link . '" class="' . $menuclass . '" rel="nofollow">' .
                                $this->escape($item->title) . ' </a>';
                                break;
                        }
                        ?>
                        <?php // Code to add the edit link for the weblink. ?>

                        <?php if ($canEdit) : ?>
                            <span class="actions">
                                <span class="edit-icon">
                                    <?php echo JHtml::_('icon.edit', $item, $params); ?>
                                </span>
                            </span>
                        <?php endif; ?>
                        <br/>
                        <?php if ($this->params->get('show_link_hits')) : ?>
                            <span class="wl_hits"><?php echo JText::_('Hits:') . ' ' . $item->hits; ?></span><br/>
                        <?php endif; ?>
                        <?php if (($this->params->get('show_link_description')) AND ($item->description != '')): ?>
                            <span class="wl_description"><?php echo $item->description; ?></span><br/>
                        <?php endif; ?>
                        <?php if ($item->article_id) : ?>
                            <a class="wl_readme" href="<?php echo $link; ?>" alt="Read More">
                                <?php echo JText::_('Read More'); ?>
                            </a>
                            <br/>
                        <?php endif; ?>
                        <br/></td>
                        <?php if ($col++ >= $this->params->get('columns')) { ?>
                            </tr>
                            <?php $col = 1;
                            $row++;
                            }
                        }
                        if ($col > 1) { ?>
                            </tr>
                            <?php } ?>
            </table>
        <?php } ?>

        <?php // Code to add a link to submit a weblink.  ?>
        <?php /* if ($canCreate) : // TODO This is not working due to some problem in the router, I think. Ref issue #23685 ?>
          <?php echo JHtml::_('icon.create', $item, $item->params); ?>
          <?php  endif; */ ?>
        <?php if ($this->params->get('show_pagination')) : ?>
            <div class="pagination">
                <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                    <p class="counter">
                        <?php echo $this->pagination->getPagesCounter(); ?>
                    </p>
                <?php endif; ?>
                <?php echo $this->pagination->getPagesLinks(); ?>
            </div>
        <?php endif; ?>
    </form>
<?php endif; ?>
<?php
/*
	JoomlaXTC Weblinks Plus Pro
	
	Version 1.2.0
	
	Copyright (C) 2010  Monev Software LLC.	All Rights Reserved.
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
	
	THIS LICENSE MIGHT NOT APPLY TO OTHER FILES CONTAINED IN THE SAME PACKAGE.
	
	See COPYRIGHT.php for more information.
	See LICENSE.php for more information.
	
	Monev Software LLC
	www.joomlaxtc.com
*/

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.form.formfield');

class JFormFieldJxtcwlpcat extends JFormField {

    protected $_name = 'Jxtcwlpcat';

    protected function getInput() {
        $db = & JFactory::getDBO();
        $q = "SELECT id, title as name FROM #__categories where extension='com_weblinksplus' ORDER BY title";
        $db->setquery($q);
        $result = $db->loadObjectList();
        array_unshift($result,(object)array('id'=>0,'name'=>'ALL CATEGORIES'));
        $size = count($result);
        $size = ceil($size / 10);
        if ($size < 5)
            $size = 5;
        if ($size > 20)
            $size = 20;
        return JHTML::_('select.genericlist', $result, $this->name, ' MULTIPLE size="' . $size . '" class="inputbox"', 'id', 'name', $this->value, $this->id);
    }

}

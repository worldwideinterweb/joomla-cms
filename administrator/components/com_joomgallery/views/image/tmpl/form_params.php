<?php defined('_JEXEC') or die('Restricted access');
$fieldSets = $this->form->getFieldsets('params');
foreach ($fieldSets as $name => $fieldSet) :
  if($name == 'advanced') :
    echo JHtml::_('sliders.panel',JText::_($fieldSet->label), $name.'-params-page'); ?>
    <fieldset class="panelform" >
      <ul class="adminformlist">
        <?php foreach ($this->form->getFieldset($name) as $field) : ?>
          <li><?php echo $field->label; ?>
          <?php echo $field->input; ?></li>
        <?php endforeach; ?>
      </ul>
    </fieldset>
<?php
  endif;
endforeach; ?>
<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   (C) 2015 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

$options = array(
	JHtml::_('select.option', 'c', JText::_('JLIB_HTML_BATCH_COPY')),
	JHtml::_('select.option', 'm', JText::_('JLIB_HTML_BATCH_MOVE'))
);
$published = $this->state->get('filter.published');
$extension = $this->escape($this->state->get('filter.extension'));
?>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="control-group span6">
			<div class="controls">
				<?php echo JHtml::_('batch.language'); ?>
			</div>
		</div>
		<div class="control-group span6">
			<div class="controls">
				<?php echo JHtml::_('batch.access'); ?>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<?php if ($published >= 0) : ?>
			<div class="span6">
				<div class="control-group">
					<label id="batch-choose-action-lbl" for="batch-category-id" class="control-label">
						<?php echo JText::_('JLIB_HTML_BATCH_MENU_LABEL'); ?>
					</label>
					<div id="batch-choose-action" class="combo controls">
						<select name="batch[category_id]" id="batch-category-id">
							<option value=""><?php echo JText::_('JLIB_HTML_BATCH_NO_CATEGORY') ?></option>
							<?php echo JHtml::_('select.options', JHtml::_('category.categories', $extension, array('filter.published' => $published))); ?>
						</select>
					</div>
				</div>
				<div id="batch-copy-move" class="control-group radio">
					<?php echo JText::_('JLIB_HTML_BATCH_MOVE_QUESTION'); ?>
					<?php echo JHtml::_('select.radiolist', $options, 'batch[move_copy]', '', 'value', 'text', 'm'); ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="control-group span6">
			<div class="controls">
				<?php echo JHtml::_('batch.tag'); ?>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<div class="control-group">
				<label id="flip-ordering-id-lbl" for="flip-ordering-id" class="control-label">
					<?php echo JText::_('JLIB_HTML_BATCH_FLIPORDERING_LABEL'); ?>
				</label>
				<?php echo JHtml::_('select.booleanlist', 'batch[flip_ordering]', array(), 0, 'JYES', 'JNO', 'flip-ordering-id'); ?>
			</div>
		</div>
	</div>
</div>


<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomlaupdate
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var JoomlaupdateViewDefault $this */

// JText::script doesn't have a sprintf equivalent so work around this
JFactory::getDocument()->addScriptDeclaration("var COM_JOOMLAUPDATE_VIEW_DEFAULT_SHOW_MORE_EXTENSION_COMPATIBILITY_INFORMATION = '" . JText::sprintf('COM_JOOMLAUPDATE_VIEW_DEFAULT_SHOW_MORE_EXTENSION_COMPATIBILITY_INFORMATION', '<span class="icon-chevron-right large-icon" style="font-size:0.85rem"></span>', true) . "';");
JFactory::getDocument()->addScriptDeclaration("var COM_JOOMLAUPDATE_VIEW_DEFAULT_SHOW_LESS_EXTENSION_COMPATIBILITY_INFORMATION = '" . JText::sprintf('COM_JOOMLAUPDATE_VIEW_DEFAULT_SHOW_LESS_EXTENSION_COMPATIBILITY_INFORMATION', '<span class="icon-chevron-up large-icon" style="font-size:0.85rem"></span>', true) . "';");

$compatibilityTypes = array(
	'COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSIONS_RUNNING_PRE_UPDATE_CHECKS' => array(
		'class' => 'label-default',
		'notes' => 'COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSIONS_RUNNING_PRE_UPDATE_CHECKS_NOTES'
	),
	'COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSIONS_UPDATE_SERVER_OFFERS_NO_COMPATIBLE_VERSION' => array(
		'class' => 'label-important',
		'notes' => 'COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSIONS_UPDATE_SERVER_OFFERS_NO_COMPATIBLE_VERSION_NOTES'
	),
	'COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSIONS_REQUIRING_UPDATES_TO_BE_COMPATIBLE' => array(
		'class' => 'label-warning',
		'notes' => 'COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSIONS_REQUIRING_UPDATES_TO_BE_COMPATIBLE_NOTES'
	),
	'COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSIONS_PROBABLY_COMPATIBLE' => array(
		'class' => 'label-success',
		'notes' => 'COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSIONS_PROBABLY_COMPATIBLE_NOTES'
	),
);

?>
<h2>
	<?php echo JText::sprintf('COM_JOOMLAUPDATE_VIEW_DEFAULT_PREUPDATE_CHECK', $this->updateInfo['latest']); ?>
</h2>
<p>
	<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_EXPLANATION_AND_LINK_TO_DOCS'); ?>
</p>
<div class="row-fluid">
	<fieldset class="span6">
		<legend>
			<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_REQUIRED_SETTINGS'); ?>
		</legend>
		<table class="table">
			<thead>
				<tr>
					<th>
						<?php echo JText::_('COM_JOOMLAUPDATE_PREUPDATE_HEADING_REQUIREMENT'); ?>
					</th>
					<th>
						<?php echo JText::_('COM_JOOMLAUPDATE_PREUPDATE_HEADING_CHECKED'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->phpOptions as $option) : ?>
					<tr>
						<td>
							<?php echo $option->label; ?>
						</td>
						<td>
							<span class="label label-<?php echo $option->state ? 'success' : 'important'; ?>">
								<?php echo JText::_($option->state ? 'JYES' : 'JNO'); ?>
								<?php if ($option->notice) : ?>
									<span class="icon-info icon-white hasTooltip" title="<?php echo $option->notice; ?>"></span>
								<?php endif; ?>
							</span>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</fieldset>
	<fieldset class="span6">
		<legend>
			<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_RECOMMENDED_SETTINGS'); ?>
		</legend>
		<p>
			<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_RECOMMENDED_SETTINGS_DESC'); ?>
		</p>
		<table class="table">
			<thead>
			<tr>
				<td>
					<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_DIRECTIVE'); ?>
				</td>
				<td>
					<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_RECOMMENDED'); ?>
				</td>
				<td>
					<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_ACTUAL'); ?>
				</td>
			</tr>
			</thead>
			<tbody>
				<?php foreach ($this->phpSettings as $setting) : ?>
					<tr>
						<td>
							<?php echo $setting->label; ?>
						</td>
						<td>
							<?php echo JText::_($setting->recommended ? 'JON' : 'JOFF'); ?>
						</td>
						<td>
							<span class="label label-<?php echo ($setting->state === $setting->recommended) ? 'success' : 'warning'; ?>">
								<?php echo JText::_($setting->state ? 'JON' : 'JOFF'); ?>
							</span>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</fieldset>
</div>
<?php if (!empty($this->nonCoreExtensions)) : ?>
	<div>
		<h3>
			<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSIONS'); ?>
		</h3>
		<?php $compatibilityTypeCount = 0; ?>
		<?php foreach ($compatibilityTypes as $compatibilityType => $compatibilityData) : ?>
			<?php $compatibilityDisplayClass = $compatibilityData['class']; ?>
			<?php $compatibilityDisplayNotes = $compatibilityData['notes']; ?>
			<fieldset id="compatibilitytype<?php echo $compatibilityTypeCount;?>" class="span12 compatibilitytypes">
				<legend class="label <?php echo $compatibilityDisplayClass;?>">
					<h3>
						<?php if ($compatibilityType !== "COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSIONS_RUNNING_PRE_UPDATE_CHECKS") : ?>
						<div class="compatibilitytoggle" data-state="closed"><?php echo JText::sprintf('COM_JOOMLAUPDATE_VIEW_DEFAULT_SHOW_MORE_EXTENSION_COMPATIBILITY_INFORMATION', '<span class="icon-chevron-right large-icon" style="font-size:0.85rem"></span>'); ?></div>
						<?php endif; ?>
						<?php echo JText::_($compatibilityType); ?>
					</h3>
				</legend>
				<div class="compatibilityNotes">
					<?php echo JText::_($compatibilityDisplayNotes); ?>
				</div>
				<table class="table">
					<thead class="row-fluid">
						<tr>
							<th class="exname span8">
								<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSION_NAME'); ?>
							</th>
							<th class="extype span4">
								<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSION_TYPE'); ?>
							</th>
							<th class="upcomp hidden">
								<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSION_UPDATE_COMPATIBLE'); ?>
							</th>
							<th class="currcomp hidden">
								<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSION_CURRENTLY_COMPATIBLE'); ?>
							</th>
							<th class="instver hidden">
								<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSION_INSTALLED_VERSION'); ?>
							</th>
						</tr>
					</thead>
					<tbody class="row-fluid">
						<?php // Only include this row once since the javascript moves the results into the right place ?>
						<?php if ($compatibilityType == "COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSIONS_RUNNING_PRE_UPDATE_CHECKS") : ?>
							<?php foreach ($this->nonCoreExtensions as $extension) : ?>
								<tr>
									<td class="exname span8">
										<?php echo JText::_($extension->name); ?>
									</td>
									<td class="extype span4">
										<?php echo JText::_('COM_INSTALLER_TYPE_' . strtoupper($extension->type)); ?>
									</td>
									<td
										class="extension-check upcomp hidden"
										data-extension-id="<?php echo $extension->extension_id; ?>"
										data-extension-current-version="<?php echo $extension->version; ?>"
									>
										<img src="../media/jui/images/ajax-loader.gif" />
									</td>
									<td id="available-version-<?php echo $extension->extension_id; ?>" class="currcomp hidden"/>
									<td class="instver hidden">
										<?php echo $extension->version; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</fieldset>
			<?php $compatibilityTypeCount ++;?>
		<?php endforeach; ?>
	</div>
<?php else: ?>
	<div class="row-fluid">
		<div class="span6">
			<h3>
				<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSIONS'); ?>
			</h3>
			<div class="alert alert-no-items">
				<?php echo JText::_('COM_JOOMLAUPDATE_VIEW_DEFAULT_EXTENSIONS_NONE'); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

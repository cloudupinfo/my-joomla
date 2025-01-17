<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Table;

defined('JPATH_PLATFORM') or die;

/**
 * Usergroup table class.
 *
 * @since  1.7.0
 */
class Usergroup extends Table
{
	/**
	 * Constructor
	 *
	 * @param   \JDatabaseDriver  $db  Database driver object.
	 *
	 * @since   1.7.0
	 */
	public function __construct($db)
	{
		parent::__construct('#__usergroups', 'id', $db);
	}

	/**
	 * Method to check the current record to save
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.7.0
	 */
	public function check()
	{
		// Validate the title.
		if ((trim($this->title)) == '')
		{
			$this->setError(\JText::_('JLIB_DATABASE_ERROR_USERGROUP_TITLE'));

			return false;
		}

		// The parent_id can not be equal to the current id
		if ($this->id === (int) $this->parent_id)
		{
			$this->setError(\JText::_('JLIB_DATABASE_ERROR_USERGROUP_PARENT_ID_NOT_VALID'));

			return false;
		}

		// Check for a duplicate parent_id, title.
		// There is a unique index on the (parent_id, title) field in the table.
		$db = $this->_db;
		$query = $db->getQuery(true)
			->select('COUNT(title)')
			->from($this->_tbl)
			->where('title = ' . $db->quote(trim($this->title)))
			->where('parent_id = ' . (int) $this->parent_id)
			->where('id <> ' . (int) $this->id);
		$db->setQuery($query);

		if ($db->loadResult() > 0)
		{
			$this->setError(\JText::_('JLIB_DATABASE_ERROR_USERGROUP_TITLE_EXISTS'));

			return false;
		}

		// We do not allow to move non public to root and public to non-root
		if (!empty($this->id))
		{
			$table = self::getInstance('Usergroup', 'JTable', array('dbo' => $this->getDbo()));

			$table->load($this->id);

			if ((!$table->parent_id && $this->parent_id) || ($table->parent_id && !$this->parent_id))
			{
				$this->setError(\JText::_('JLIB_DATABASE_ERROR_USERGROUP_PARENT_ID_NOT_VALID'));

				return false;
			}
		}
		// New entry should always be greater 0
		elseif (!$this->parent_id)
		{
			$this->setError(\JText::_('JLIB_DATABASE_ERROR_USERGROUP_PARENT_ID_NOT_VALID'));

			return false;
		}

		// The new parent_id has to be a valid group
		if ($this->parent_id)
		{
			$table = self::getInstance('Usergroup', 'JTable', array('dbo' => $this->getDbo()));
			$table->load($this->parent_id);

			if ($table->id != $this->parent_id)
			{
				$this->setError(\JText::_('JLIB_DATABASE_ERROR_USERGROUP_PARENT_ID_NOT_VALID'));

				return false;
			}
		}

		return true;
	}

	/**
	 * Method to recursively rebuild the nested set tree.
	 *
	 * @param   integer  $parent_id  The root of the tree to rebuild.
	 * @param   integer  $left       The left id to start with in building the tree.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.7.0
	 */
	public function rebuild($parent_id = 0, $left = 0)
	{
		// Get the database object
		$db = $this->_db;

		// Get all children of this node
		$db->setQuery('SELECT id FROM ' . $this->_tbl . ' WHERE parent_id=' . (int) $parent_id . ' ORDER BY parent_id, title');
		$children = $db->loadColumn();

		// The right value of this node is the left value + 1
		$right = $left + 1;

		// Execute this function recursively over all children
		for ($i = 0, $n = count($children); $i < $n; $i++)
		{
			// $right is the current right value, which is incremented on recursion return
			$right = $this->rebuild($children[$i], $right);

			// If there is an update failure, return false to break out of the recursion
			if ($right === false)
			{
				return false;
			}
		}

		// We've got the left value, and now that we've processed
		// the children of this node we also know the right value
		$db->setQuery('UPDATE ' . $this->_tbl . ' SET lft=' . (int) $left . ', rgt=' . (int) $right . ' WHERE id=' . (int) $parent_id);

		// If there is an update failure, return false to break out of the recursion
		try
		{
			$db->execute();
		}
		catch (\JDatabaseExceptionExecuting $e)
		{
			return false;
		}

		// Return the right value of this node + 1
		return $right + 1;
	}

	/**
	 * Inserts a new row if id is zero or updates an existing row in the database table
	 *
	 * @param   boolean  $updateNulls  If false, null object variables are not updated
	 *
	 * @return  boolean  True if successful, false otherwise and an internal error message is set
	 *
	 * @since   1.7.0
	 */
	public function store($updateNulls = false)
	{
		if ($result = parent::store($updateNulls))
		{
			// Rebuild the nested set tree.
			$this->rebuild();
		}

		return $result;
	}

	/**
	 * Delete this object and its dependencies
	 *
	 * @param   integer  $oid  The primary key of the user group to delete.
	 *
	 * @return  mixed  Boolean or Exception.
	 *
	 * @since   1.7.0
	 * @throws  \RuntimeException on database error.
	 * @throws  \UnexpectedValueException on data error.
	 */
	public function delete($oid = null)
	{
		if ($oid)
		{
			$this->load($oid);
		}

		if ($this->id == 0)
		{
			throw new \UnexpectedValueException('Usergroup not found');
		}

		if ($this->parent_id == 0)
		{
			throw new \UnexpectedValueException('Root usergroup cannot be deleted.');
		}

		if ($this->lft == 0 || $this->rgt == 0)
		{
			throw new \UnexpectedValueException('Left-Right data inconsistency. Cannot delete usergroup.');
		}

		$db = $this->_db;

		// Select the usergroup ID and its children
		$query = $db->getQuery(true)
			->select($db->quoteName('c.id'))
			->from($db->quoteName($this->_tbl) . 'AS c')
			->where($db->quoteName('c.lft') . ' >= ' . (int) $this->lft)
			->where($db->quoteName('c.rgt') . ' <= ' . (int) $this->rgt);
		$db->setQuery($query);
		$ids = $db->loadColumn();

		if (empty($ids))
		{
			throw new \UnexpectedValueException('Left-Right data inconsistency. Cannot delete usergroup.');
		}

		// Delete the usergroup and its children
		$query->clear()
			->delete($db->quoteName($this->_tbl))
			->where($db->quoteName('id') . ' IN (' . implode(',', $ids) . ')');
		$db->setQuery($query);
		$db->execute();

		// Rebuild the nested set tree.
		$this->rebuild();

		// Delete the usergroup in view levels
		$replace = array();

		foreach ($ids as $id)
		{
			$replace[] = ',' . $db->quote("[$id,") . ',' . $db->quote('[') . ')';
			$replace[] = ',' . $db->quote(",$id,") . ',' . $db->quote(',') . ')';
			$replace[] = ',' . $db->quote(",$id]") . ',' . $db->quote(']') . ')';
			$replace[] = ',' . $db->quote("[$id]") . ',' . $db->quote('[]') . ')';
		}

		$query->clear()
			->select('id, rules')
			->from('#__viewlevels');
		$db->setQuery($query);
		$rules = $db->loadObjectList();

		$match_ids = array();

		foreach ($rules as $rule)
		{
			foreach ($ids as $id)
			{
				if (strstr($rule->rules, '[' . $id) || strstr($rule->rules, ',' . $id) || strstr($rule->rules, $id . ']'))
				{
					$match_ids[] = $rule->id;
				}
			}
		}

		if (!empty($match_ids))
		{
			$query->clear()
				->set('rules=' . str_repeat('replace(', 4 * count($ids)) . 'rules' . implode('', $replace))
				->update('#__viewlevels')
				->where('id IN (' . implode(',', $match_ids) . ')');
			$db->setQuery($query);
			$db->execute();
		}

		// Delete the user to usergroup mappings for the group(s) from the database.
		$query->clear()
			->delete($db->quoteName('#__user_usergroup_map'))
			->where($db->quoteName('group_id') . ' IN (' . implode(',', $ids) . ')');
		$db->setQuery($query);
		$db->execute();

		return true;
	}
}

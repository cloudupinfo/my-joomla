<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Filesystem
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

JLoader::register('JPath', JPATH_PLATFORM . '/joomla/filesystem/path.php');

/**
 * Tests for the JPath class.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Filesystem
 * @since       3.0.1
 */
class JPathTest extends TestCase
{
	/**
	 * Data provider for testClean() method.
	 *
	 * @return  array
	 *
	 * @since   3.0.1
	 */
	public function getCleanData()
	{
		return array(
			// Input Path, Directory Separator, Expected Output
			'Nothing to do.' => array('/var/www/foo/bar/baz', '/', '/var/www/foo/bar/baz'),
			'One backslash.' => array('/var/www/foo\\bar/baz', '/', '/var/www/foo/bar/baz'),
			'Two and one backslashes.' => array('/var/www\\\\foo\\bar/baz', '/', '/var/www/foo/bar/baz'),
			'Mixed backslashes and double forward slashes.' => array('/var\\/www//foo\\bar/baz', '/', '/var/www/foo/bar/baz'),
			'UNC path.' => array('\\\\www\\docroot', '\\', '\\\\www\\docroot'),
			'UNC path with forward slash.' => array('\\\\www/docroot', '\\', '\\\\www\\docroot'),
			'UNC path with UNIX directory separator.' => array('\\\\www/docroot', '/', '/www/docroot'),
		);
	}

	/**
	 * Tests the clean method.
	 *
	 * @param   string  $input
	 * @param   string  $ds
	 * @param   string  $expected
	 *
	 * @return  void
	 *
	 * @dataProvider  getCleanData
	 * @since         3.0.1
	 */
	public function testClean($input, $ds, $expected)
	{
		$this->assertEquals(
			$expected,
			JPath::clean($input, $ds)
		);
	}

	/**
	 * Tests the JPath::clean method with an array as an input
	 *
	 * @return  void
	 *
	 * @expectedException  UnexpectedValueException
	 * @since   1.7.3
	 */
	public function testCleanArrayPath()
	{
		JPath::clean(array('/path/to/folder') );
	}
}

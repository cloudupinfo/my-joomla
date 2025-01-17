<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2012 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Feed\Parser;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Feed\Feed;
use Joomla\CMS\Feed\FeedEntry;
use Joomla\CMS\Feed\FeedLink;
use Joomla\CMS\Feed\FeedParser;
use Joomla\CMS\Feed\FeedPerson;

/**
 * RSS Feed Parser class.
 *
 * @link   http://cyber.law.harvard.edu/rss/rss.html
 * @since  3.1.4
 */
class RssParser extends FeedParser
{
	/**
	 * @var    string  The feed element name for the entry elements.
	 * @since  3.1.4
	 */
	protected $entryElementName = 'item';

	/**
	 * @var    string  The feed format version.
	 * @since  3.1.4
	 */
	protected $version;

	/**
	 * Method to handle the `<category>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleCategory(Feed $feed, \SimpleXMLElement $el)
	{
		// Get the data from the element.
		$domain    = (string) $el['domain'];
		$category  = (string) $el;

		$feed->addCategory($category, $domain);
	}

	/**
	 * Method to handle the `<cloud>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleCloud(Feed $feed, \SimpleXMLElement $el)
	{
		$cloud = new \stdClass;
		$cloud->domain            = (string) $el['domain'];
		$cloud->port              = (string) $el['port'];
		$cloud->path              = (string) $el['path'];
		$cloud->protocol          = (string) $el['protocol'];
		$cloud->registerProcedure = (string) $el['registerProcedure'];

		$feed->cloud = $cloud;
	}

	/**
	 * Method to handle the `<copyright>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleCopyright(Feed $feed, \SimpleXMLElement $el)
	{
		$feed->copyright = (string) $el;
	}

	/**
	 * Method to handle the `<description>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleDescription(Feed $feed, \SimpleXMLElement $el)
	{
		$feed->description = (string) $el;
	}

	/**
	 * Method to handle the `<generator>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleGenerator(Feed $feed, \SimpleXMLElement $el)
	{
		$feed->generator = (string) $el;
	}

	/**
	 * Method to handle the `<image>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleImage(Feed $feed, \SimpleXMLElement $el)
	{
		// Create a feed link object for the image.
		$image = new FeedLink(
			(string) $el->url,
			null,
			'logo',
			null,
			(string) $el->title
		);

		// Populate extra fields if they exist.
		$image->link         = (string) $el->link;
		$image->description  = (string) $el->description;
		$image->height       = (string) $el->height;
		$image->width        = (string) $el->width;

		$feed->image = $image;
	}

	/**
	 * Method to handle the `<language>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleLanguage(Feed $feed, \SimpleXMLElement $el)
	{
		$feed->language = (string) $el;
	}

	/**
	 * Method to handle the `<lastBuildDate>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleLastBuildDate(Feed $feed, \SimpleXMLElement $el)
	{
		$feed->updatedDate = (string) $el;
	}

	/**
	 * Method to handle the `<link>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleLink(Feed $feed, \SimpleXMLElement $el)
	{
		$link = new FeedLink;
		$link->uri = (string) $el['href'];
		$feed->link = $link;
	}

	/**
	 * Method to handle the `<managingEditor>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleManagingEditor(Feed $feed, \SimpleXMLElement $el)
	{
		$feed->author = $this->processPerson((string) $el);
	}

	/**
	 * Method to handle the `<skipDays>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleSkipDays(Feed $feed, \SimpleXMLElement $el)
	{
		// Initialise the array.
		$days = array();

		// Add all of the day values from the feed to the array.
		foreach ($el->day as $day)
		{
			$days[] = (string) $day;
		}

		$feed->skipDays = $days;
	}

	/**
	 * Method to handle the `<skipHours>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleSkipHours(Feed $feed, \SimpleXMLElement $el)
	{
		// Initialise the array.
		$hours = array();

		// Add all of the day values from the feed to the array.
		foreach ($el->hour as $hour)
		{
			$hours[] = (int) $hour;
		}

		$feed->skipHours = $hours;
	}

	/**
	 * Method to handle the `<pubDate>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handlePubDate(Feed $feed, \SimpleXMLElement $el)
	{
		$feed->publishedDate = (string) $el;
	}

	/**
	 * Method to handle the `<title>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleTitle(Feed $feed, \SimpleXMLElement $el)
	{
		$feed->title = (string) $el;
	}

	/**
	 * Method to handle the `<ttl>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleTtl(Feed $feed, \SimpleXMLElement $el)
	{
		$feed->ttl = (integer) $el;
	}

	/**
	 * Method to handle the `<webmaster>` element for the feed.
	 *
	 * @param   Feed               $feed  The Feed object being built from the parsed feed.
	 * @param   \SimpleXMLElement  $el    The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function handleWebmaster(Feed $feed, \SimpleXMLElement $el)
	{
		// Get the tag contents and split it over the first space.
		$tmp = (string) $el;
		$tmp = explode(' ', $tmp, 2);

		// This is really cheap parsing.  Probably need to create a method to do this more robustly.
		$name = null;

		if (isset($tmp[1]))
		{
			$name = trim($tmp[1], ' ()');
		}

		$email = trim($tmp[0]);

		$feed->addContributor($name, $email, null, 'webmaster');
	}

	/**
	 * Method to initialise the feed for parsing.  Here we detect the version and advance the stream
	 * reader so that it is ready to parse feed elements.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function initialise()
	{
		// Read the version attribute.
		$this->version = $this->stream->getAttribute('version');

		// We want to move forward to the first element after the <channel> element.
		$this->moveToNextElement('channel');
		$this->moveToNextElement();
	}

	/**
	 * Method to handle a `<item>` element for the feed.
	 *
	 * @param   FeedEntry          $entry  The FeedEntry object being built from the parsed feed entry.
	 * @param   \SimpleXMLElement  $el     The current XML element object to handle.
	 *
	 * @return  void
	 *
	 * @since   3.1.4
	 */
	protected function processFeedEntry(FeedEntry $entry, \SimpleXMLElement $el)
	{
		$entry->uri           = (string) $el->link;
		$entry->title         = (string) $el->title;
		$entry->publishedDate = (string) $el->pubDate;
		$entry->updatedDate   = (string) $el->pubDate;
		$entry->content       = (string) $el->description;
		$entry->guid          = (string) $el->guid;
		$entry->isPermaLink   = $entry->guid === '' || (string) $el->guid['isPermaLink'] === 'false' ? false : true;
		$entry->comments      = (string) $el->comments;

		// Add the feed entry author if available.
		$author = (string) $el->author;

		if (!empty($author))
		{
			$entry->author = $this->processPerson($author);
		}

		// Add any categories to the entry.
		foreach ($el->category as $category)
		{
			$entry->addCategory((string) $category, (string) $category['domain']);
		}

		// Add any enclosures to the entry.
		foreach ($el->enclosure as $enclosure)
		{
			$link = new FeedLink(
				(string) $enclosure['url'],
				null,
				(string) $enclosure['type'],
				null,
				null,
				(int) $enclosure['length']
			);

			$entry->addLink($link);
		}
	}

	/**
	 * Method to parse a string with person data and return a FeedPerson object.
	 *
	 * @param   string  $data  The string to parse for a person.
	 *
	 * @return  FeedPerson
	 *
	 * @since   3.1.4
	 */
	protected function processPerson($data)
	{
		// Create a new person object.
		$person = new FeedPerson;

		// This is really cheap parsing, but so far good enough. :)
		$data = explode(' ', $data, 2);

		if (isset($data[1]))
		{
			$person->name = trim($data[1], ' ()');
		}

		// Set the email for the person.
		$person->email = trim($data[0]);

		return $person;
	}
}

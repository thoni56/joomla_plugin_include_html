<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.include_html
 *
 * @copyright   Copyright (C) 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Plug-in to enable loading HTML files into content
 * This uses the {include_html} syntax
 *
 * @package     Joomla.Plugin
 * @subpackage  Content.include_html
 * @since       1.5
 */
class plgContentincludehtml extends JPlugin
{

	/**
	 * Plugin that loads an HTML file within content
	 *
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	int	The 'page' number
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer') {
			return true;
		}

		// simple performance check to determine whether bot should process further
		if (strpos($article->text, 'include_html') === false && strpos($article->text, 'include_html') === false) {
			return true;
		}

		// expression to search for (positions)
		$regex		= '/{include_html\s+(.*?)}/i';

		// Find all instances of plugin and put in $matches for include_html
		// $matches[0] is full pattern match, $matches[1] is the position
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);
		// No matches, skip this
		if ($matches) {
			foreach ($matches as $match) {
				$file_contents = file_get_contents ($match[1]);
                                // Filter the file to exclude dangerous code
                                $safe_file_contents = JFilterInput::getInstance([], [], 1, 1)->clean($file_contents, 'string');
				$article->text = preg_replace('/{include_html\s+(.*?)}/i', $safe_file_contents, $article->text, 1);
			}
		}
		return true;
	}


}

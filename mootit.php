<?php
/**
 * Main File
 *
 * @package         Moot.it Forum Integration
 * @version         1.0.1
 *
 * @author          Andy Melichar <amelichar@a2hosting.com>
 * @link            http://www.a2hosting.com/
 * @copyright       Copyright © 2013 A2 Hosting, Inc. All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;

/**
 * Loads Moot.it Forum
 */
class plgContentMootit extends JPlugin
{
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer')
		{
			return true;
		}

		if (is_object($row))
		{
			return $this->_dorep($row->text, $params);
		}
		return $this->_dorep($row, $params);
	}


	protected function _dorep(&$text, &$params)
	{
		$tr = false;
		$patterns = array(
		"{mootit}" => '<a class="moot" href="http://api.moot.it/' . $this->params->get('mootituname') . '"></a>',
		"{mootitblog}" => '<a class="moot" href="http://api.moot.it/' . $this->params->get('mootituname') . '/blog#my-blog-entry"></a>',
		"{mootitcomments}" => '<a class="moot" href="http://api.moot.it/' . $this->params->get('mootituname') . '/blog/my-large-blog-entry"></a>'
		);
		foreach ($patterns as $f => $r) {
			if (strpos($text, $f) > 0) {
				$text = str_replace($f, $r, $text);
				$tr = true;
			}
		}	
		if ($tr) {
			$document = JFactory::getDocument();
			$document->addStyleSheet('http://cdn.moot.it/1.0/moot.css');
			if ($this->params->get('loadjquery')) {
				$document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
			}
			$document->addScript('http://cdn.moot.it/1.0/moot.min.js');
		}
		return $tr;
	}
}

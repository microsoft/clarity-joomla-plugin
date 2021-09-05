<?php

/**
 *
 * @version		1.0.0
 * @copyright	Copyright (C) 2020 PB Web Development. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

class plgSystemClarity extends JPlugin {

	var $document = null;

	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
	}

	function onAfterRender() {
		// don't run if we are in the index.php or we are not in an HTML view
		$this->document = JFactory::getDocument();
		if(strpos($_SERVER["PHP_SELF"], "index.php") === false || $this->document->getType() != 'html'){
			return;
			}

		
		// Check to see if we are in the admin and if we should track
		$trackadmin = $this->params->get('trackadmin','');
		$mainframe = JFactory::getApplication();
		if($mainframe->isAdmin() && ($trackadmin != 'on')) {
			return;
		}

		
		// Get the Body of the HTML - have to do this twice to get the HTML
		$buffer = JResponse::getBody();
		$buffer = JResponse::getBody();
		// Get our Container ID and Track Admin parameter
		$project_id = $this->params->get('project_id','');

		// String containing the Clarity JavaScript code including the project id 
		$clarity_js_container_code =
		"<script>(function(c,l,a,r,i,t,y){
			c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
			t=l.createElement(r);t.async=1;t.src='//www.clarity.ms/tag/'+i+'?ref=joomla';
			y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
		})(window, document, 'clarity', 'script', '".$project_id."');</script>";


		$buffer = preg_replace ("/(<head(?!er).*>)/i", "$1".$clarity_js_container_code, $buffer, 1);
		
		JResponse::setBody($buffer);
		
		return true;
		}
	}
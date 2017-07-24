<?php

/**
 * @copyright	Copyright (C) 2015 Cédric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * http://www.template-creator.com
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.event.plugin');

class plgSystemImageeffectck extends JPlugin {

	function plgSystemImageeffectck(&$subject, $params) {
		parent::__construct($subject, $params);
	}

	public function onAfterRender() {
		global $ckjqueryisloaded;
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$doctype = $doc->getType();
		$input = new JInput();

		// if in admin we stop
		if ($app->isAdmin()) {
			return false;
		}

		// if not HTML we stop
		if ($doctype !== 'html') {
			return;
		}

		// if edit mode we stop
		if ($input->get('layout') == 'edit') {
			return;
		}

		// get the page code
		$body = JResponse::getBody();

		// load jquery
		$jquerycall = "";
		if (version_compare(JVERSION, '3') >= 1 ) { 
			JHTML::_('jquery.framework', true);
		} else if (! $ckjqueryisloaded) {
			$jquerycall = "\n\t<script src=\"" . JURI::base(true) . "/plugins/system/imageeffectck/assets/jquery.min.js\" type=\"text/javascript\"></script>";
		}

		// retrieve the settings
		$plugin = JPluginHelper::getPlugin('system', 'imageeffectck');

		$declaration = $jquerycall
				. "\n\t<link rel=\"stylesheet\" href=\"" . JURI::base(true) . "/plugins/system/imageeffectck/assets/imageeffectck.css\" type=\"text/css\" />"
				// . "\n\t<script src=\"" . JURI::base(true) . "/plugins/system/imageeffectck/assets/jquery-ui.min.js\" type=\"text/javascript\"></script>"
				. "\n\t<script src=\"" . JURI::base(true) . "/plugins/system/imageeffectck/assets/imageeffectck.js\" type=\"text/javascript\"></script>"
				. "\n\t<script type=\"text/javascript\">"
				. "\n\tjQuery(document).ready(function(){"
					. "jQuery(this).ImageEffectck({ "
						// . "duration: " . (int) $fxduration . ", "
					. "});"
				. "});"
				. "\n\t</script>"
				// . "\n\t<style type=\"text/css\">"
				// . $this->addCss()
				// . "\n\t</style>"
				;
		$body = str_replace("</head>", $declaration . "\n</head>", $body);

		JResponse::setBody($body);
		return;
	}

	/**
	 * Add CSS specific for the plugin
	 *
	 * @return string
	 */
	private function addCss() {
		$plugin = JPluginHelper::getPlugin('system', 'imageeffectck');
		$pluginParams = new JRegistry($plugin->params);
		$captionstyles = self::createCss($pluginParams, 'caption', false);
		$captiontitlestyles = self::createCss($pluginParams, 'captiontitle', false);

		// if ($cssitemnormal['padding'] || $cssitemnormal['margin'] || $cssitemnormal['background'] || $cssitemnormal['gradient'] || $cssitemnormal['borderradius'] || $cssitemnormal['shadow'] || $cssitemnormal['border']
		// ) {
		$captioncss = '.captionck_content_inner {'
			. $captionstyles['padding'] 
			. $captionstyles['margin'] 
			. $captionstyles['background'] 
			. $captionstyles['gradient'] 
			. $captionstyles['borderradius'] 
			. $captionstyles['shadow'] 
			. $captionstyles['border'] 
			. $captionstyles['fontcolor'] 
			. $captionstyles['fontsize'] 
			. $captionstyles['fontweight']
			. " } ";

		$captioncss .= '.captionck_content_title {'
			. 'display: block;'
			. $captiontitlestyles['fontcolor'] 
			. $captiontitlestyles['fontsize'] 
			. $captiontitlestyles['fontweight']
			. '}';

		return $captioncss;
	}

	/**
	 * Create the css properties
	 * @param JRegistry $params
	 * @param string $prefix the xml field prefix
	 *
	 * @return Array
	 */
	static function createCss($params, $prefix = '', $important = false) {
		$css = Array();
		$important = ($important == true ) ? ' !important' : '';
		$csspaddingtop = ($params->get($prefix . 'paddingtop') != '') ? 'padding-top: ' . self::testUnit($params->get($prefix . 'paddingtop', '0')) . $important . ';' : '';
		$csspaddingright = ($params->get($prefix . 'paddingright') != '') ? 'padding-right: ' . self::testUnit($params->get($prefix . 'paddingright', '0')) . $important . ';' : '';
		$csspaddingbottom = ($params->get($prefix . 'paddingbottom') != '') ? 'padding-bottom: ' . self::testUnit($params->get($prefix . 'paddingbottom', '0')) . $important . ';' : '';
		$csspaddingleft = ($params->get($prefix . 'paddingleft') != '') ? 'padding-left: ' . self::testUnit($params->get($prefix . 'paddingleft', '0')) . $important . ';' : '';
		$css['padding'] = $csspaddingtop . $csspaddingright . $csspaddingbottom . $csspaddingleft;
		$cssmargintop = ($params->get($prefix . 'margintop') != '') ? 'margin-top: ' . self::testUnit($params->get($prefix . 'margintop', '0')) . $important . ';' : '';
		$cssmarginright = ($params->get($prefix . 'marginright') != '') ? 'margin-right: ' . self::testUnit($params->get($prefix . 'marginright', '0')) . $important . ';' : '';
		$cssmarginbottom = ($params->get($prefix . 'marginbottom') != '') ? 'margin-bottom: ' . self::testUnit($params->get($prefix . 'marginbottom', '0')) . $important . ';' : '';
		$cssmarginleft = ($params->get($prefix . 'marginleft') != '') ? 'margin-left: ' . self::testUnit($params->get($prefix . 'marginleft', '0')) . $important . ';' : '';
		$css['margin'] = $cssmargintop . $cssmarginright . $cssmarginbottom . $cssmarginleft;
		$bgcolor1 = ($params->get($prefix . 'bgcolor1') && $params->get($prefix . 'bgopacity') !== null && $params->get($prefix . 'bgopacity') !== '') ? self::hex2RGB($params->get($prefix . 'bgcolor1'), $params->get($prefix . 'bgopacity')) : $params->get($prefix . 'bgcolor1');
		$css['background'] = ($params->get($prefix . 'bgcolor1')) ? 'background: ' . $bgcolor1 . $important . ';' : '';
		$css['background'] .= ($params->get($prefix . 'bgcolor1')) ? 'background-color: ' . $bgcolor1 . $important . ';' : '';
		$css['background'] .= ( $params->get($prefix . 'bgimage')) ? 'background-image: url("' . JURI::ROOT() . $params->get($prefix . 'bgimage') . '")' . $important . ';' : '';
		$css['background'] .= ( $params->get($prefix . 'bgimage')) ? 'background-repeat: ' . $params->get($prefix . 'bgimagerepeat') . $important . ';' : '';
		$css['background'] .= ( $params->get($prefix . 'bgimage')) ? 'background-position: ' . ($params->get($prefix . 'bgpositionx')) . ' ' . ($params->get($prefix . 'bgpositiony')) . $important . ';' : '';

		$bgcolor2 = ($params->get($prefix . 'bgcolor2') && $params->get($prefix . 'bgopacity') !== '') ? self::hex2RGB($params->get($prefix . 'bgcolor2'), $params->get($prefix . 'bgopacity')) : $params->get($prefix . 'bgcolor2');
		$css['gradient'] = ($css['background'] AND $params->get($prefix . 'bgcolor2')) ?
				$svggradient
				. "background: -moz-linear-gradient(top,  " . $bgcolor1 . " 0%, " . $bgcolor2 . " 100%)" . $important . ";"
				. "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%," . $bgcolor1 . "), color-stop(100%," . $bgcolor2 . "))" . $important . "; "
				. "background: -webkit-linear-gradient(top,  " . $bgcolor1 . " 0%," . $bgcolor2 . " 100%)" . $important . ";"
				. "background: -o-linear-gradient(top,  " . $bgcolor1 . " 0%," . $bgcolor2 . " 100%)" . $important . ";"
				. "background: -ms-linear-gradient(top,  " . $bgcolor1 . " 0%," . $bgcolor2 . " 100%)" . $important . ";"
				. "background: linear-gradient(top,  " . $bgcolor1 . " 0%," . $bgcolor2 . " 100%)" . $important . "; " : '';
		$css['borderradius'] = ($params->get($prefix . 'roundedcornerstl', '') != '' || $params->get($prefix . 'roundedcornerstr', '') != '' || $params->get($prefix . 'roundedcornersbr', '') != '' || $params->get($prefix . 'roundedcornersbl', '') != '') ?
				'-moz-border-radius: ' . self::testUnit($params->get($prefix . 'roundedcornerstl', '0')) . ' ' . self::testUnit($params->get($prefix . 'roundedcornerstr', '0')) . ' ' . self::testUnit($params->get($prefix . 'roundedcornersbr', '0')) . ' ' . self::testUnit($params->get($prefix . 'roundedcornersbl', '0')) . $important . ';'
				. '-webkit-border-radius: ' . self::testUnit($params->get($prefix . 'roundedcornerstl', '0')) . ' ' . self::testUnit($params->get($prefix . 'roundedcornerstr', '0')) . ' ' . self::testUnit($params->get($prefix . 'roundedcornersbr', '0')) . ' ' . self::testUnit($params->get($prefix . 'roundedcornersbl', '0')) . $important . ';'
				. 'border-radius: ' . self::testUnit($params->get($prefix . 'roundedcornerstl', '0')) . ' ' . self::testUnit($params->get($prefix . 'roundedcornerstr', '0')) . ' ' . self::testUnit($params->get($prefix . 'roundedcornersbr', '0')) . ' ' . self::testUnit($params->get($prefix . 'roundedcornersbl', '0')) . $important . ';' : '';
		$shadowinset = $params->get($prefix . 'shadowinset', 0) ? 'inset ' : '';
		$css['shadow'] = ($params->get($prefix . 'shadowcolor') AND $params->get($prefix . 'shadowblur') != '') ?
				'-moz-box-shadow: ' . $shadowinset . self::testUnit($params->get($prefix . 'shadowoffsetx', '0')) . ' ' . self::testUnit($params->get($prefix . 'shadowoffsety', '0')) . ' ' . self::testUnit($params->get($prefix . 'shadowblur', '')) . ' ' . self::testUnit($params->get($prefix . 'shadowspread', '0')) . ' ' . $params->get($prefix . 'shadowcolor', '') . $important . ';'
				. '-webkit-box-shadow: ' . $shadowinset . self::testUnit($params->get($prefix . 'shadowoffsetx', '0')) . ' ' . self::testUnit($params->get($prefix . 'shadowoffsety', '0')) . ' ' . self::testUnit($params->get($prefix . 'shadowblur', '')) . ' ' . self::testUnit($params->get($prefix . 'shadowspread', '0')) . ' ' . $params->get($prefix . 'shadowcolor', '') . $important . ';'
				. 'box-shadow: ' . $shadowinset . self::testUnit($params->get($prefix . 'shadowoffsetx', '0')) . ' ' . self::testUnit($params->get($prefix . 'shadowoffsety', '0')) . ' ' . self::testUnit($params->get($prefix . 'shadowblur', '')) . ' ' . self::testUnit($params->get($prefix . 'shadowspread', '0')) . ' ' . $params->get($prefix . 'shadowcolor', '') . $important . ';' :
				(($params->get($prefix . 'useshadow') && $params->get($prefix . 'shadowblur') == '0') ? '-moz-box-shadow: none' . $important . ';'
						. '-webkit-box-shadow: none' . $important . ';'
						. 'box-shadow: none' . $important . ';' : '');
		$css['border'] = (($params->get($prefix . 'bordertopwidth') == '0') ? 'border-top: none' . $important . ';' : (($params->get($prefix . 'bordertopwidth') != '' AND $params->get($prefix . 'bordercolor')) ? 'border-top: ' . $params->get($prefix . 'bordercolor', '') . ' ' . self::testUnit($params->get($prefix . 'bordertopwidth', '')) . ' solid ' . $important . ';' : '') )
				. (($params->get($prefix . 'borderrightwidth') == '0') ? 'border-right: none' . $important . ';' : (($params->get($prefix . 'borderrightwidth') != '' AND $params->get($prefix . 'bordercolor')) ? 'border-right: ' . $params->get($prefix . 'bordercolor', '') . ' ' . self::testUnit($params->get($prefix . 'borderrightwidth', '')) . ' solid ' . $important . ';' : '') )
				. (($params->get($prefix . 'borderbottomwidth') == '0') ? 'border-bottom: none' . $important . ';' : (($params->get($prefix . 'borderbottomwidth') != '' AND $params->get($prefix . 'bordercolor')) ? 'border-bottom: ' . $params->get($prefix . 'bordercolor', '') . ' ' . self::testUnit($params->get($prefix . 'borderbottomwidth', '')) . ' solid ' . $important . ';' : '') )
				. (($params->get($prefix . 'borderleftwidth') == '0') ? 'border-left: none' . $important . ';' : (($params->get($prefix . 'borderleftwidth') != '' AND $params->get($prefix . 'bordercolor')) ? 'border-left: ' . $params->get($prefix . 'bordercolor', '') . ' ' . self::testUnit($params->get($prefix . 'borderleftwidth', '')) . ' solid ' . $important . ';' : '') );
		$css['fontsize'] = ($params->get($prefix . 'fontsize') != '') ?
				'font-size: ' . self::testUnit($params->get($prefix . 'fontsize')) . $important . ';' : '';
		$css['fontcolor'] = ($params->get($prefix . 'fontcolor') != '') ?
				'color: ' . $params->get($prefix . 'fontcolor') . $important . ';' : '';
		$css['fontweight'] = ($params->get($prefix . 'fontweight')  != '') ?
				'font-weight: ' . $params->get($prefix . 'fontweight') . $important . ';' : '';
		$css['descfontsize'] = ($params->get($prefix . 'descfontsize') != '') ?
				'font-size: ' . self::testUnit($params->get($prefix . 'descfontsize')) . $important . ';' : '';
		$css['descfontcolor'] = ($params->get($prefix . 'descfontcolor') != '') ?
				'color: ' . $params->get($prefix . 'descfontcolor') . $important . ';' : '';
		$textshadowoffsetx = ($params->get($prefix . 'textshadowoffsetx', '0') == '') ? '0px' : self::testUnit($params->get($prefix . 'textshadowoffsetx', '0'));
		$textshadowoffsety = ($params->get($prefix . 'textshadowoffsety', '0') == '') ? '0px' : self::testUnit($params->get($prefix . 'textshadowoffsety', '0'));
		$css['textshadow'] = ($params->get($prefix . 'textshadowcolor') AND $params->get($prefix . 'textshadowblur')) ?
				'text-shadow: ' . $textshadowoffsetx . ' ' . $textshadowoffsety . ' ' . self::testUnit($params->get($prefix . 'textshadowblur', '')) . ' ' . $params->get($prefix . 'textshadowcolor', '') . $important . ';' :
				(($params->get($prefix . 'textshadowblur') == '0') ? 'text-shadow: none' . $important . ';' : '');
		$css['text-align'] = $params->get($prefix . 'textalign') ? 'text-align: ' . $params->get($prefix . 'textalign') . $important . ';' : ''; '';
		$css['text-transform'] = $params->get($prefix . 'texttransform') ? 'text-transform: ' . $params->get($prefix . 'texttransform') . $important . ';' : ''; '';

		return $css;
	}

	/**
	 * Test if there is already a unit, else add the px
	 *
	 * @param string $value
	 * @return string
	 */
	static function testUnit($value) {
		if ((stristr($value, 'px')) OR (stristr($value, 'em')) OR (stristr($value, '%')) OR (stristr($value, 'auto')) ) {
			return $value;
		}

		if ($value == '') {
			$value = 0;
		}

		return $value . 'px';
	}

	/**
	 * Convert a hexa decimal color code to its RGB equivalent
	 *
	 * @param string $hexStr (hexadecimal color value)
	 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
	 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
	 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
	 */
	static function hex2RGB($hexStr, $opacity) {
		if ($opacity > 1) $opacity = $opacity/100;
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
		$rgbArray = array();
		if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false; //Invalid hex color code
		}
		$rgbacolor = "rgba(" . $rgbArray['red'] . "," . $rgbArray['green'] . "," . $rgbArray['blue'] . "," . $opacity . ")";

		return $rgbacolor;
	}
}
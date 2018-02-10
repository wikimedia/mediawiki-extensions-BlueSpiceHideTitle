<?php
/**
 * BlueSpice MediaWiki
 * Extension: HideTitle
 * Description: Tag to hide the title of an article.
 * Authors: Markus Glaser, Sebastian Ulbricht
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * For further information visit http://www.bluespice.com
 *
 * @author     Markus Glaser <glaser@hallowelt.com>
 * @package    BlueSpiceHideTitle
 * @copyright  Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v3
 * @filesource
 */

class HideTitle extends BsExtensionMW {

	protected $bHideTitle = false;

	protected function initExt() {
		// Hooks
		$this->setHook( 'BeforePageDisplay' );
		$this->setHook( 'BSInsertMagicAjaxGetData', 'onBSInsertMagicAjaxGetData' );
		$this->setHook( 'BSUsageTrackerRegisterCollectors' );
		$this->mCore->registerBehaviorSwitch( 'bs_hidetitle' );
	}

	/**
	 *
	 * @param OutputPage $oOutputPage
	 * @param SkinTemplate $oSkinTemplate
	 * @return boolean
	 */
	public function onBeforePageDisplay(  $oOutputPage, $oSkinTemplate ) {
		$oTitle = $oOutputPage->getTitle();
		$sHideTitlePageProp = BsArticleHelper::getInstance( $oTitle )->getPageProp( 'bs_hidetitle' );
		if( $sHideTitlePageProp === '' ) {
			$oOutputPage->mPagetitle = '';
			$oOutputPage->addModuleStyles( 'ext.bluespice.hidetitle.styles' );
		}

		return true;
	}

	public function onBSInsertMagicAjaxGetData( $oResponse, $type ) {
		if( $type !== 'switches' ) return true;

		$oDescriptor = new stdClass();
		$oDescriptor->id = 'bs:hidetitle';
		$oDescriptor->type = 'switch';
		$oDescriptor->name = 'HIDETITLE';
		$oDescriptor->desc = wfMessage( 'bs-hidetitle-extension-description' )->plain();
		$oDescriptor->code = '__HIDETITLE__';
		$oDescriptor->previewable = false;
		$oResponse->result[] = $oDescriptor;

		return true;
	}

	/**
	 * Register tag with UsageTracker extension
	 * @param array $aCollectorsConfig
	 * @return Always true to keep hook running
	 */
	public static function onBSUsageTrackerRegisterCollectors( &$aCollectorsConfig ) {
		$aCollectorsConfig['bs:hidetitle'] = array(
			'class' => 'Property',
			'config' => array(
				'identifier' => 'bs_hidetitle'
			)
		);
	}
}
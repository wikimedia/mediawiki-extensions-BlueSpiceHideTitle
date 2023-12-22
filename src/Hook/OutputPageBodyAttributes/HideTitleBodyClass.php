<?php

namespace BlueSpice\HideTitle\Hook\OutputPageBodyAttributes;

use MediaWiki\MediaWikiServices;
use OutputPage;
use Skin;

class HideTitleBodyClass {

	/**
	 * @param OutputPage $out
	 * @param Skin $skin
	 * @param array &$bodyAttrs
	 * @return void
	 */
	public static function onOutputPageBodyAttributes( OutputPage $out, Skin $skin, &$bodyAttrs ) {
		$services = MediaWikiServices::getInstance();

		$hideTitlePageProp = $services->getPageProps()
			->getProperties( $out->getTitle(), 'bs_hidetitle' );

		// Add an additional class to html body to give user a chance
		// to hide things in MediaWiki:Common.css
		if ( !$hideTitlePageProp ) {
			$bodyAttrs[ 'class' ] .= ' hide-title';
		}
	}
}

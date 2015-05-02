<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

/** REGISTRATION */
$GLOBALS['wgExtensionCredits']['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'Colorbox',
	'version' => '0.2',
	'url' => 'https://www.mediawiki.org/wiki/Extension:Colorbox',
	'author' => array( 'Toniher' ),
	'descriptionmsg' => 'colorbox-desc',
);


/** RESOURCE Modules **/
global $wgLanguageCode;

$GLOBALS['wgColorboxCSSLibs'] = array( "libs/colorbox/example3/colorbox.css" );

$GLOBALS['wgResourceModules']['ext.Colorbox'] = array(
	'scripts' => array( 'libs/colorbox/jquery.colorbox-min.js', 'libs/ext.Colorbox.js' ),
	'styles' => $GLOBALS['wgColorboxCSSLibs'],
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'Colorbox'
);

/** Include l10n if available **/
if ( file_exists( __DIR__."/libs/colorbox/i18n/jquery.colorbox-".$wgLanguageCode.".js" ) ) {
	array_push( $GLOBALS['wgResourceModules']['ext.Colorbox']['scripts'], "libs/colorbox/i18n/jquery.colorbox-".$wgLanguageCode.".js" );
}

// Cookie life in seconds
$GLOBALS['wgColorBoxCookieLife'] = 3600; //Default 1 hour

/** LOADING OF CLASSES **/
// https://www.mediawiki.org/wiki/Manual:$wgAutoloadClasses
$GLOBALS['wgAutoloadClasses']['Colorbox'] = __DIR__ . '/Colorbox.classes.php';


/** STRINGS AND THEIR TRANSLATIONS **/
$GLOBALS['wgMessagesDirs']['Colorbox'] = __DIR__ . '/i18n';
$GLOBALS['wgExtensionMessagesFiles']['Colorbox'] = __DIR__ . '/Colorbox.i18n.php';
$GLOBALS['wgExtensionMessagesFiles']['ColorboxMagic'] = __DIR__ . '/Colorbox.i18n.magic.php';

/** HOOKS **/

#$wgHooks['OutputPageBeforeHTML'][] = 'Colorbox::onOutputPageBeforeHTML';

$GLOBALS['wgHooks']['ParserFirstCallInit'][] = 'ColorboxSetupParserFunction';
$GLOBALS['wgHooks']['ParserFirstCallInit'][] = 'ColorboxSetupTagExtension';

#Ajax
// TODO: It may be better to change it into an API
$GLOBALS['wgAjaxExportList'][] = 'Colorbox::getPageContent';

// Hook our callback function into the parser
function ColorboxSetupParserFunction( $parser ) {
	
	// {{#colorboximg:file=File|size=32|description=tal i tal|csize=100%}}
	$parser->setFunctionHook( 'colorboxlink', 'Colorbox::linkFunction', SFH_OBJECT_ARGS );
	$parser->setFunctionHook( 'colorboxload', 'Colorbox::loadFunction', SFH_OBJECT_ARGS );
	
	return true;
}

// Hook our callback function into the parser
function ColorboxSetupTagExtension( $parser ) {
	$parser->setHook( 'colorbox', 'Colorbox::printTag' );
	return true;
}





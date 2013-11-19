<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

/** REGISTRATION */
$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'Colorbox',
	'version' => '0.1',
	'url' => 'https://www.mediawiki.org/wiki/Extension:Colorbox',
	'author' => array( 'Toniher' ),
	'descriptionmsg' => 'Colorbox_desc',
);


/** RESOURCE Modules **/
global $wgLanguageCode;
$wgColorboxLangLib = "libs/colorbox/i18n/jquery.colorbox-".$wgLanguageCode.".js";
$wgColorboxCSSLibs = array( "libs/colorbox/example3/colorbox.css" );


$wgResourceModules['ext.Colorbox'] = array(
	'scripts' => array( 'libs/colorbox/jquery.colorbox-min.js', $wgColorboxLangLib, 'libs/ext.colorbox.js' ),
	'styles' => $wgColorboxCSSLibs,
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'Colorbox'
);

/** LOADING OF CLASSES **/
// https://www.mediawiki.org/wiki/Manual:$wgAutoloadClasses
$wgAutoloadClasses['Colorbox'] = __DIR__ . '/Colorbox.classes.php';


/** STRINGS AND THEIR TRANSLATIONS **/
$wgExtensionMessagesFiles['Colorbox'] = __DIR__ . '/Colorbox.i18n.php';
$wgExtensionMessagesFiles['ColorboxMagic'] = __DIR__ . '/Colorbox.i18n.magic.php';

/** HOOKS **/

#$wgHooks['OutputPageBeforeHTML'][] = 'Colorbox::onOutputPageBeforeHTML';

$wgHooks['ParserFirstCallInit'][] = 'ColorboxSetupParserFunction';


#Ajax
$wgAjaxExportList[] = 'Colorbox::getPageContent';


// Hook our callback function into the parser
function ColorboxSetupParserFunction( $parser ) {

	$parser->setFunctionHook( 'colorboxlink', 'Colorbox::linkFunction', SFH_OBJECT_ARGS );
	$parser->setFunctionHook( 'colorboxload', 'Colorbox::loadFunction', SFH_OBJECT_ARGS );
	
	return true;
}




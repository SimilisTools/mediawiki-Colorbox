<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	echo( "This file is an extension to the MediaWiki software and cannot be used standalone.\n" );
	die();
}

class Colorbox {


	/**
	 * @param $out OutputPage
	 * @param $text string
	 * @return $out OutputPage
	*/

	public static function onOutputPageBeforeHTML( &$out, &$text ) {

		// We add Modules
		$out->addModules( 'ext.Colorbox' );

		return $out;
	}


	
	/**
	 * @param $input string
	 * @param $args array
	 * @param $parser Parser
	 * @param $frame Frame
	 * @return string
	*/
	
	public static function printTag( $input, $args, $parser, $frame ) {
		
		$out = $parser->getOutput();
		$out->addModules( 'ext.Colorbox' );
		
		$output = "";
		
		if ( !empty( $input ) ) {
			$output = $parser->recursiveTagParse( trim( $input ), $frame );
			$output = "<span class='colorboximg' >".$output."</span>";
		}
		
		return ( $output );
		
	}

	/**
	 * @param $parser Parser
	 * @param $frame PPFrame
	 * @param $args array
	 * @return string
	*/

	public static function linkFunction( $parser, $frame, $args ) {

		$out = $parser->getOutput();
		$out->addModules( 'ext.Colorbox' );
		
		
		// Starting variables
		$linkstr = "";
		$linkText = "Link";
		$classparam = "";
		
		if ( isset( $args['0'] ) ) {
		
			if ( isset( $args[1] ) ) {
			
				$linkTextPre = trim( $frame->expand( $args[1] ) );
				if ( !empty( $linkTextPre ) ) {
					$linkText = $linkTextPre;
				}
			}
		
			if ( isset( $args[2] ) ) {
				
				$class = trim( $frame->expand( $args[2] ) );
				
				if ( !empty( $class ) ) {
					$found = strpos( $class, "class=" );
				
					if ( $found > -1 ) {
						$class = str_replace( "class=", "", $class );
						$classparam = " data-class='".$class."'";

					}
				}
			}
			
			$page = trim( $frame->expand( $args[0] ) );

			if ( !empty( $page ) ) {	
				$content = "data-page='".trim( $frame->expand( $args[0] ) )."'";
				$linkstr = "<p class='colorboxlink' ".$content.$classparam.">".$linkText."</p>";
			}
		} 
		
		return $linkstr;
		
	}

	/**
	 * @param $parser Parser
	 * @param $frame PPFrame
	 * @param $args array
	 * @return string
	*/

	public static function loadFunction( $parser, $frame, $args ) {

		$out = $parser->getOutput();
		$out->addModules( 'ext.Colorbox' );
		
		
		// Starting variables
		$loadstr = "";
		$classparam = "";
		
		if ( isset( $args['0'] ) ) {
		
			if ( isset( $args[1] ) ) {
			
				$cookieparam = trim( $frame->expand( $args[1] ) );
				
					if ( ! empty( $cookieparam ) ) { 

					$outcome = self::readCookie( $cookieparam );
				
					if ( $outcome ) {
					
						// No outcome, no trigger of colorbox
						return "";

					} else {
				
						// Let's set cookie
						$written = self::writeCookie( $cookieparam );
					
					}
				}
			}
		
			if ( isset( $args[2] ) ) {
				
				$class = trim( $frame->expand( $args[2] ) );
				
				if ( ! empty( $class ) ) {
					$found = strpos( $class, "class=" );
				
					if ( $found > -1 ) {
						$class = str_replace( "class=", "", $class );
						$classparam = " data-class='".$class."'";
					}
				}
			}
			
			$page = trim( $frame->expand( $args[0] ) );

			if ( !empty( $page ) ) {	
			
				$pagecheck = true;
				
				if ( ( strpos( $page, "http:") !== false ) || ( strpos( $page, "https:") !== false ) || ( strpos( $page, "/") !== false ) ) {
					$pagecheck = false; 
				}
				
				if ( $pagecheck ) {
					$content = "data-page='".$page."'";
				} else {
					if ( strpos( $page, "/") == 0 ) {
					
						global $wgServer;
						$page = $wgServer.$page;
					}
				
					$content = "data-url='".$page."'";
				}
				
				$linkstr = "<span id='colorboxload' ".$content.$classparam."></span>";
			}
		} 
		
		return $linkstr;
		
	}
	
	/**
	 * @param $titleText string
	 * @return string
	*/
	
	public static function getPageContent( $titleText ) {
	
		$content = "";
		
		if ( !empty( $titleText ) ) {
			
			$title = Title::newFromText( $titleText );
			if ( $title ) {
				$wikipage = WikiPage::factory( $title );
				$content = $wikipage->getText( REVISION::RAW );
				
				$parser = new Parser();
				$po = new ParserOptions();
				
				$outputPage = $parser->parse( $content, $title, $po );
				
				return $outputPage->getText();

			}
		}

		return $content;
	}
	
	/**
	 * @param $cookieparam string
	 * @return boolean
	*/
	
	private static function readCookie ( $cookieparam ) {
	
		if ( isset( $_COOKIE[$cookieparam] )  ) {
		
			return true;
		}
	
		return false;
	}

	/**
	 * @param $cookieparam string
	 * @return boolean
	*/
	
	private static function writeCookie ( $cookieparam ) {
	
		global $wgColorBoxCookieLife;
		// set the expiration to 15 days
		setcookie ( $cookieparam, "true", time()+$wgColorBoxCookieLife );
		return true;
	}

}

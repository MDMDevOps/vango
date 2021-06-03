import StickyColumn from './include/sticky-columns.js';
import ScrollToggle from './include/scroll-toggle.js';
import StickySidebar from './include/sticky-sidebar.js';
import Slick from './include/slick.js';
// make Masonry a jQuery plugin
import Masonry from 'masonry-layout';
import jQueryBridget from 'jquery-bridget';
jQueryBridget( 'masonry', Masonry, jQuery );

/**
 * Sticky Beaver Builder Columns
 */
const _stickyColumns = () => {
	/**
	 * Columns
	 */
	let columns = document.getElementsByClassName( 'fl-col fl-sticky-column' );
	/**
	 * Init each
	 */
	for( let i = 0; i < columns.length; i++ ) {
		new StickyColumn( columns[i] );
	}
}
// document.addEventListener( 'DOMContentLoaded', _stickyColumns, false );
/**
 * Sticky Sidebar
 */
const _stickySidebar = () => {

	/**
	 * Sidebars
	 */
	let sidebar = document.querySelector( '#secondary.sticky-sidebar' );
	/**
	 * Init
	 */
	if( sidebar ) {
		new StickySidebar( sidebar );
	}
}
// document.addEventListener( 'DOMContentLoaded', _stickySidebar, false );
/**
 * Scroll Toggle
 */
const _scrollToggle = () => {

	/**
	 * get all scrolltoggle elements
	 */
	let elements = document.getElementsByClassName( 'scrolltoggle' );
	/**
	 * Init
	 */
	for( let i = 0; i < elements.length; i++ ) {
		new ScrollToggle( elements[i] );
	}
}
document.addEventListener( 'DOMContentLoaded', _scrollToggle, false );

/**
 * Do jquery stuff here...
 */
jQuery( function ( $ ) {
	'use strict';

	if ( typeof gform === 'object' ) {
		gform.addFilter( 'gform_datepicker_options_pre_init', function( optionsObj, formId, fieldId ) {
			/**
			 * Require 2 days in advance
			 */
			if ( formId == 3 && fieldId == 6 ) {
				optionsObj.minDate = '+2';
			}
			return optionsObj;
		} );
	}


});
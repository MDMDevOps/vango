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

	let $sortlist = $.map( $( '.list-sort' ), function( el ) {
		return new ListSort( $( el ) );
	});

	function ListSort( $el ) {

		let $target, reload, active_class, $buttons, $items;

		const _toggle = ( event ) => {
			event.preventDefault();

			for( let i = 0; i < $buttons.length; i++ ) {
				$buttons[i].removeClass( 'active' );
			}

			event.data.button.addClass( 'active' )

			for( let i = 0; i < $items.length; i++ ) {

				if( event.data.target === true ) {
					$items[i].addClass( 'active' ).removeClass( 'inactive' );
				}
				else {
					if( $items[i].hasClass( event.data.target ) ) {
						$items[i].addClass( active_class ).removeClass( 'inactive' );
					}
					else {
						$items[i].addClass( 'inactive' ).removeClass( active_class );
					}
				}
			}

			document.dispatchEvent( reload );
		}


		$target = $( '#' + $el.data( 'target-list' ) );

		if( $target.length ) {

			reload = new CustomEvent( 'list-sort-reload' );

			active_class = $el.data( 'masonry-tweak' ) == '1' ? 'active masonry_column' : 'active';

			$buttons = $.map( $el.find( 'a' ), function( button ) {

				let $button = $( button );

				$button.on( 'click', { 'target' : $button.data( 'sort-class' ), 'button' : $button },_toggle );

				return $button;

			});

			$items = $.map( $( '.' + $el.data( 'item-class' ) ), function( item ) {

				let $item = $( item );

				return $item;
			});

		}

	}
});
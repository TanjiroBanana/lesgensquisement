<?php
/*  Copyright 2013 Foobar Studio (contact@foobar.studio)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

require_once( CUAR_INCLUDES_DIR . '/core-classes/addon-page.class.php' );
require_once( CUAR_INCLUDES_DIR . '/core-classes/widget-content-authors.class.php' );

if (!class_exists('CUAR_PrivatePageAuthorsWidget')) :

/**
 * Widget to show private page categories
*
* @author Vincent Prat @ Foobar Studio
*/
class CUAR_PrivatePageAuthorsWidget extends CUAR_ContentAuthorsWidget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
				'cuar_private_page_authors', 
				__('WPCA - Page Authors', 'cuar'),
				array( 
						'description' => __( 'Shows the private page author archives', 'cuar' ),
					)
			);
	}

	protected function get_post_type() {
		return 'cuar_private_page';
	}
	
	protected function get_default_title() {
		return __( 'Created By', 'cuar' );
	}
	
	protected function get_link( $author_id ) {
		/** @var CUAR_CustomerPrivatePagesAddOn $addon */
		$addon = cuar_addon( 'customer-private-pages' );

		return $addon->get_author_archive_url( $author_id );
	}
	
}

endif; // if (!class_exists('CUAR_PrivatePageAuthorsWidget')) 

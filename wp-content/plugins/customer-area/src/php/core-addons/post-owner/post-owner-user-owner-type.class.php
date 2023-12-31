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


/**
 * Add-on to allow setting user groups or user roles as owner of a private content
 *
 * @author Vincent Prat @ Foobar Studio
 */
class CUAR_PostOwnerUserOwnerType
{
    /** @var CUAR_PostOwnerAddOn */
    private $po_addon;

    public function __construct($po_addon)
    {
        $this->po_addon = $po_addon;

        add_filter('cuar/core/ownership/owner-types', array(&$this, 'declare_new_owner_types'));
        add_filter('cuar/core/ownership/content/meta-query', array(&$this, 'extend_private_posts_meta_query'), 10, 4);
        add_filter('cuar/core/ownership/real-user-ids?owner-type=usr',
            array(&$this, 'get_post_owner_user_ids_from_usr'), 10, 2);
        add_filter('cuar/core/ownership/validate-post-ownership', array(&$this, 'is_user_owner_of_post'), 10, 5);
        add_filter('cuar/core/ajax/search/post-owners?owner-type=usr',
            array(&$this, 'get_selectable_owners_for_type_usr'), 10, 3);
        add_filter('cuar/core/ownership/saved-displayname', array(&$this, 'saved_post_owner_displayname'), 10, 4);
        add_filter('cuar/core/ownership/owner-display-name?owner-type=usr',
            array(&$this, 'get_owner_display_name'), 10, 2);
    }

    /*------- EXTEND THE OWNER TYPES AVAILABLE ----------------------------------------------------------------------*/

    /**
     * Give the display name for our owner types
     *
     * @param string $displayname
     * @param int    $post_id
     * @param string $owner_type
     * @param array  $owner_ids
     *
     * @return string
     *
     */
    public function saved_post_owner_displayname($displayname, $post_id, $owner_type, $owner_ids)
    {
        if ($owner_type != 'usr')
        {
            return $displayname;
        }

        $names = array();
        foreach ($owner_ids as $id)
        {
            $names[] = $this->get_owner_display_name($displayname, $id);
        }
        asort($names);

        return empty($names) ? $displayname : implode(", ", $names);
    }

    /**
     * @param $name
     * @param $owner_id
     * @return string
     */
    public function get_owner_display_name($name, $owner_id)
    {
        $u = new WP_User($owner_id);
        if ($u != null && $u->exists() && !empty($u->display_name))
        {
            return $u->display_name;
        }

        return $name;
    }

    /**
     * Check if a user owns the given post
     *
     * @param boolean $initial_result
     * @param int     $post_id
     * @param int     $user_id
     * @param string  $post_owner_type
     * @param array   $post_owner_ids
     *
     * @return boolean true if the user owns the post
     */
    public function is_user_owner_of_post($initial_result, $post_id, $user_id, $post_owner_type, $post_owner_ids)
    {
        if ($initial_result)
        {
            return true;
        }

        if ($post_owner_type == 'usr')
        {
            return in_array($user_id, $post_owner_ids);
        }

        return false;
    }

    /**
     * Print a select field with various global rules
     *
     * @param array  $response
     * @param string $search
     * @param int    $page
     * @return array
     */
    public function get_selectable_owners_for_type_usr($response, $search, $page)
    {
        $items = apply_filters('cuar/core/ownership/selectable-owners?owner-type=usr', null, $search, $page);
        if ($items === null)
        {
            return $this->po_addon->ajax()->find_users($search, 'post_owner', $page);
        }

        list($results, $has_more) = $items;
        $response['results'] = $results;
        $response['more'] = $has_more;

        return $response;
    }

    /**
     * Extend the meta query to fetch private posts belonging to a user (also fetches the posts for his role and
     * groups)
     *
     * @param array               $base_meta_query
     * @param int                 $user_id The user we want to fetch private posts for
     * @param CUAR_PostOwnerAddOn $po_addon
     * @param string|array        $post_type
     *
     * @return array
     */
    public function extend_private_posts_meta_query($base_meta_query, $user_id, $po_addon, $post_type = null)
    {
        // For users
        $user_meta_query = array(
            $po_addon->get_owner_meta_query_component('usr', $user_id),
        );

        // Deal with all this
        return array_merge($base_meta_query, $user_meta_query);
    }

    /**
     * Declare the new owner types managed by this add-on
     *
     * @param array $types the existing types
     *
     * @return array The existing types + our types
     */
    public function declare_new_owner_types($types)
    {
        $new_types = array(
            'usr' => __('User', 'cuar'),
        );

        return array_merge($types, $new_types);
    }

    /**
     * Return all user IDs that belong to the given role
     *
     * @param array  $user_ids  The array of user IDs for current owners
     * @param string $owner_ids The owner IDs
     *
     * @return array
     */
    public function get_post_owner_user_ids_from_usr($user_ids, $owner_ids)
    {
        return array_unique($owner_ids, SORT_REGULAR);
    }
}

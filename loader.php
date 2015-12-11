<?php

/*
 Plugin Name: BuddyForms Multisite Network
 Plugin URI:  http://buddyforms.com
 Description: BuddyForms Multisite Network
 Version: 1.5
 Author: Sven Lehnert
 Author URI: https://profiles.wordpress.org/svenl77
 Licence: GPLv3
 Network: false

 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA	02111-1307	USA
 *
 ****************************************************************************
 */

add_action('buddyforms_process_post_start'  , 'bf_msn_start', 10, 1);
add_action('buddyforms_the_loop_start'      , 'bf_msn_start', 10, 1);
function bf_msn_start($args){
    global $buddyforms;

    if(isset($buddyforms[$args['form_slug']]['msn_enabled'])){
        global $switched;
        switch_to_blog($buddyforms[$args['form_slug']]['msn_enabled']);
    }
}


add_action('buddyforms_process_post_end'    , 'bf_msn_end', 10, 1);
add_action('buddyforms_the_loop_end'        , 'bf_msn_end', 10, 1);
function bf_msn_end($args){
    global $buddyforms;

    if(isset($buddyforms[$args['form_slug']]['msn_enabled'])){
        restore_current_blog();
    }

}


add_filter('buddyforms_the_loop_edit_permalink', 'bf_msn_the_loop_edit_permalink', 10, 2 );
function bf_msn_the_loop_edit_permalink($permalink, $page_id){
    restore_current_blog();
    $permalink = get_permalink( $page_id );
    switch_to_blog(2);
    return $permalink;
}



function bf_msn_admin_settings_sidebar_metabox(){
    add_meta_box('buddyforms_msn_', __('Multi Site Network','buddyforms'), 'bf_msn__admin_settings_sidebar_metabox_html', 'buddyforms', 'side', 'low');
}

function bf_msn__admin_settings_sidebar_metabox_html(){
    global $post, $buddyforms, $wpdb;

    if($post->post_type != 'buddyforms')
        return;

    $buddyform = get_post_meta(get_the_ID(), '_buddyforms_options', true);

    $form_setup = array();

    $args = array(
        'network_id' => $wpdb->siteid,
        'public'     => null,
        'archived'   => null,
        'mature'     => null,
        'spam'       => null,
        'deleted'    => null,
        'limit'      => 100,
        'offset'     => 0,
    );

    $sites = wp_get_sites();

    $msn_sites['off'] = 'Disabled';
    foreach($sites as $key => $site){
        $msn_sites[$site['blog_id']] = $site['path'];
    }

    $msn_enabled = 'off';
    if(isset($buddyform['msn_enabled']))
        $msn_enabled = $buddyform['msn_enabled'];

    $form_setup[] = new Element_Select("<b>" . __('Enable', 'buddyforms') . "</b>", "buddyforms_options[msn_enabled]", $msn_sites, array('value' => $msn_enabled, 'shortDesc' => __('Link this form to form to a site in the network.', 'buddyforms')));
    
    foreach($form_setup as $key => $field){
        echo '<div class="buddyforms_field_label">' . $field->getLabel() . '</div>';
        echo '<div class="buddyforms_field_description">' . $field->getShortDesc() . '</div>';
        echo '<div class="buddyforms_form_field">' . $field->render() . '</div>';
    }
}
add_filter('add_meta_boxes','bf_msn_admin_settings_sidebar_metabox');

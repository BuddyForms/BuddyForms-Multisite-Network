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

add_action('buddyforms_process_post_start', 'bf_msn_process_post_start', 10, 1);
function bf_msn_process_post_start(){
    if(isset($_POST['bf_switched'])){
        global $switched;
        switch_to_blog($_POST['bf_switched']);
    }
}


add_action('buddyforms_process_post_end', 'bf_msn_process_post_end', 10, 1);
function bf_msn_process_post_end(){
    if(isset($_POST['bf_switched'])){
        restore_current_blog();
    }
}




?>
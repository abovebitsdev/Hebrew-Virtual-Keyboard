<?php
/*
Plugin Name: GF Hebrew Virtual Keyboard Add-On
Plugin URI: https://abovebits.com
Description: GF Hebrew Virtual Keyboard Add-On that adds an on-screen virtual Keyboard to your project, which will popup when a specified entry field is focused.
Version: 1.0.4
Author: Above Bits LLC
Author URI: https://abovebits.com/
*/

define('GF_HEBREW_VIRTUAL_KEYBOARD_ADDON_VERSION', '1.0.4');

add_action('gform_loaded', array('GF_HebrewVirtualKeyboard_AddOn', 'load'), 5);

class GF_HebrewVirtualKeyboard_AddOn
{

    public static function load()
    {

        if (!method_exists('GFForms', 'include_addon_framework')) {
            return;
        }

        require_once('class-gfHebrewVirtualKeyboard.php');

        GFAddOn::register('GFHebrewVirtualKeyboardAddOn');
    }
}

function gf_HebrewVirtualKeyboard_addon()
{
    return GFHebrewVirtualKeyboardAddOn::get_instance();
}

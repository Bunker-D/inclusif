<?php
/**
 * Plugin Name: Inclusif
 * Version:     0.1
 * Plugin URI:  -  TODO  -
 * Author:      Bunker D
 * Author URI:  http://bunkerd.fr/
 * License:     -  TODO  -
 * License URI: -  TODO  -
 * Text Domain: inclusif
 * Description: -  TODO  -
 *
 * Built on Wordpress 4.9.4.
 *
 * @package Inclusif
 * @author  Bunker D
 * @version 0.1
 */

defined( 'ABSPATH' ) or die();


/* https://codex.wordpress.org/Writing_a_Plugin

Use Filters:

    For instance, before WordPress adds the title of a post to browser output, it first checks to see if any Plugin has registered a function for the "filter" hook called "the_title".

Options:

    See: https://codex.wordpress.org/Creating_Options_Pages
    It's also generally considered a good idea to minimize the number of options you use for your plugin. For example, instead of storing 10 different named options consider storing a serialized array of 10 elements as a single named option.

    Create or update value:    update_option($option_name, $newvalue);
    Get value.                 get_option($option);


https://codex.wordpress.org/Determining_Plugin_and_Content_Directories
*/

/* https://codex.wordpress.org/Plugin_API

Filter Functions
    has_filter()
    add_filter()
    apply_filters()
    apply_filters_ref_array()
    current_filter()
    remove_filter()
    remove_all_filters()
    doing_filter()

Actions Functions
    has_action()
    add_action()
    do_action()
    do_action_ref_array()
    did_action()
    remove_action()
    remove_all_actions()
    doing_action()

Activation/Deactivation/Uninstall Functions
    register_activation_hook()
    register_deactivation_hook()
    register_uninstall_hook()

Relevant filters:
    the_content
    the_title
    comment_text
    the_excerpt
    get_comment_text
    get_comment_excerpt
    (https://codex.wordpress.org/Plugin_API/Filter_Reference)

*/

function bd_incl_replace( $content ) {
//    $options         = $this->get_options();
//    $text_to_replace = apply_filters( 'c2c_text_replace',                $options['text_to_replace'] );
//    $case_sensitive  = apply_filters( 'c2c_text_replace_case_sensitive', $options['case_sensitive'] );
//    $limit           = apply_filters( 'c2c_text_replace_once',           $options['replace_once'] ) ? 1 : -1;
//    $preg_flags      = $case_sensitive ? 's' : 'si';
//    $mb_regex_encoding = null;

    $sep = '·';
    $content = ' ' . $content . ' ';

    // Set the encoding to UTF-8.
    if ( function_exists( 'mb_regex_encoding' ) ) {
        $encoding = mb_regex_encoding();
        mb_regex_encoding( 'UTF-8' );
    } else {
        $encoding = null;
    }

    $srch = array(
                '~(?!<.*?)(?<=(?!<\pL[^>]*?)[\s>])((?:\pP|&\pL*;|&#\d*;|"|\'|“|‘|«|…)*?)(?!http|www)(\pL+)(?:\.|·|•)(?!com|gov|edu|org|xyz|int|fr|be|ch|de|uk|ca|es|it|quebec)(\pL+)(?=(\pP|&\pL*;|&#\d*;|"|\'|”|’|»|…)*?[\s<])(?![^<>]*?>)~Ss',
                '~(?!<.*?)(?<=[\s>])((?:\pP|&\pL*;|&#\d*;|"|\'|“|‘|«|…)*?)(?!http|www)(\pL+)(?:\.|·|•)(\pL+)(?:\.|·|•)s(?=(\pP|&\pL*;|&#\d*;|"|\'|”|’|»|…)*?[\s<])(?![^<>]*?>)~Ss'
            );
    $repl = array(
                "$1$2<span aria-hidden=\"true\" style=\"color:grey\">{$sep}$3</span>",
                "$1$2<span aria-hidden=\"true\" style=\"color:grey\">{$sep}$3{$sep}</span>s"
            );
    $content = preg_replace($srch,$repl,$content);

    // Restore original encoding.
    if ( $encoding ) {
        mb_regex_encoding( $encoding );
    }

    return trim( $content );
}
add_filter( 'comment_text' , 'bd_incl_replace' );
add_filter( 'the_title'    , 'bd_incl_replace' );
add_filter( 'the_content'  , 'bd_incl_replace' );

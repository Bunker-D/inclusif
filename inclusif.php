<?php
/**
 * Plugin Name: Inclusif
 * Version:     1.0
 * Plugin URI:  -  TODO  -
 * Author:      Bunker D
 * Author URI:  http://bunkerd.fr/
 * License:     MIT
 * License URI: https://mit-license.org
 * Text Domain: inclusif
 * Description: Reconnaît les termes inclusifs féminins·masculins, remplacent les points utilisés par la forme choisie par l'administrateur,
 *              et rend l'emploi d'une forme inclusive aux logiciels de lecture automatique (aide aux personnes malvoyantes).
 *
 * Built on Wordpress 4.9.4.
 *
 * @package Inclusif
 * @author  Bunker D
 * @version 1.0
 */

/*
    Copyright (c) 2018 by Bunker D (bunkerd)

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all
    copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
    SOFTWARE.
*/

defined( 'ABSPATH' ) or die();

function bd_incl_replace( $content ) {

    $sep = get_option('bd_inclusif_sep');
    $content = ' ' . $content . ' ';

    // Set the encoding to UTF-8.
    if ( function_exists( 'mb_regex_encoding' ) ) {
        $encoding = mb_regex_encoding();
        mb_regex_encoding( 'UTF-8' );
    } else {
        $encoding = null;
    }



    // Do the relevant substitutions
    $srch = array(
                '~(?!<.*?)(?<=(?!<\pL[^>]*?)[\s>])((?:\pP|&\pL*;|&#\d*;|"|\'|“|‘|«|…)*?)(?!http|www)([\pLéÉèÈêÊîÎôÔûÛ]+)(?:\.|·|•)(?!com|gov|edu|org|xyz|int|fr|be|ch|de|uk|ca|es|it|quebec)([\pLéÉèÈêÊîÎôÔûÛ]+)(?=(\pP|&\pL*;|&#\d*;|"|\'|”|’|»|…)*?[\s<])(?![^<>]*?>)~Ss',
                '~(?!<.*?)(?<=[\s>])((?:\pP|&\pL*;|&#\d*;|"|\'|“|‘|«|…)*?)(?!http|www)([\pLéÉèÈêÊîÎôÔûÛ]+)(?:\.|·|•)([\pLéÉèÈêÊîÎôÔûÛ]+)(?:\.|·|•)s(?=(\pP|&\pL*;|&#\d*;|"|\'|”|’|»|…)*?[\s<])(?![^<>]*?>)~Ss'
            );
    $repl = array(
                "$1$2<span aria-hidden=\"true\">{$sep}$3</span>",
                "$1$2<span aria-hidden=\"true\">{$sep}$3{$sep}</span>s"
            );
    $content = preg_replace($srch,$repl,$content);

    // Restore original encoding.
    if ( $encoding ) {
        mb_regex_encoding( $encoding );
    }

    return trim( $content );
}
add_filter( 'the_title'    , 'bd_incl_replace' );
add_filter( 'the_content'  , 'bd_incl_replace' );
if ( get_option('bd_inclusif_comm') ) {
    add_filter( 'comment_text' , 'bd_incl_replace' );
}


add_action('admin_menu', function() {
    add_options_page( 'Configuration du texte inclusif', 'Texte inclusif', 'manage_options', 'inclusif', 'bd_inclusif_options_page' );
});

add_action( 'admin_init', function() {
    register_setting( 'bd-inclusif-options', 'bd_inclusif_sep' );
    register_setting( 'bd-inclusif-options', 'bd_inclusif_comm' );
});

function bd_inclusif_options_page() {
?>
    <div class="wrap">
        <form action="options.php" method="post">
            <?php
                settings_fields( 'bd-inclusif-options' );
                do_settings_sections( 'bd-inclusif-options' );
            ?>
            <h1>Configuration du texte inclusif</h1>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th>Séprataur utilisé :</th>
                        <td>
                            <select name="bd_inclusif_sep">
                            <option value="·" <?php echo esc_attr( get_option('bd_inclusif_sep') ) == '·' ? 'selected="selected"' : ''; ?>>Point médian : ·</option>
                            <option value="." <?php echo esc_attr( get_option('bd_inclusif_sep') ) == '.' ? 'selected="selected"' : ''; ?>>Point bas : .</option>
                            <option value="•" <?php echo esc_attr( get_option('bd_inclusif_sep') ) == '•' ? 'selected="selected"' : ''; ?>>Point gras : •</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Traitement des commentaires :</th>
                        <td>
                            <input type="checkbox" name="bd_inclusif_comm" <?php echo esc_attr( get_option('bd_inclusif_comm') ) == 'on' ? 'checked="checked"' : ''; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td><?php submit_button(); ?></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
<?php
}

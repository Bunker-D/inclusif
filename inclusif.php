<?php
/**
 * Plugin Name: Inclusif
 * Version:     1.0
 * Plugin URI:  -  TODO  -
 * Author:      Bunker D
 * Author URI:  http://bunkerd.fr/
 * License:     -  TODO  -
 * License URI: -  TODO  -
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

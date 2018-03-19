=== Inclusif ===
Contributors: bunkerd
Tags: inclusif, point, point médian, texte inclusif, langue inclusif, inclusive language, inclusive text
Requires at least: 3.0
Tested up to: 4.9.4
Requires PHP: 4
License: MIT
License URI: https://rem.mit-license.org

Aide à mettre en forme l’écriture inclusive féminin·masculin : utilise le séparateur choisi, et évite d’affecter l’expérience des usagers malvoyants.

== Description ==
Ce plugin a pour but de faciliter la mise en forme des textes inclusifs féminins·masculins, ainsi que d’éviter que ce dernier n’affecte l’expérience des personnes malvoyantes.
- Mise en forme : Qu’ils aient été écrits avec un point médian (·), gras (•) ou bas (.), les termes inclusifs sont reconnus et réécrits en utilisant la ponctuation choisie en option.
- Maintien de l’accessibilité aux personnes malvoyantes : Les éléments ajoutés par l’écriture inclusive (points et seconds suffixes) sont encadrés par des balises html les rendant invisibles aux outils de lecture vocale. (Cette invisibilité est créée grâce au champ html dédié : \"aria-hidden\". Pour que cela soit effectif, si l’outil utilisé le prenne en compte. C’est le cas par exemple ChromeVox.)
Exemple : « Les argiculteur.rice.s » sera affiché « les agriculteur·rice·s », et sera lu « les agriculteurs » par les outils de synthèse vocale.
Le traitement des commentaires est optionnel. Les URL et noms de sites web sont reconnus et laissés intacts. Le contenu des balises html est laissé intact.

This plugin is dedicated to formatting French gender-inclusive writing and to making it invisible to screen readers so that it does not affect the experience of the visually impaired.
- Formatting: Whether they were written using “·”, “•” or “.” as separators, gender-inclusive words are recognized and written using the admin-defined punctuation.
- Maintaining accessibility for the visually impaired: The elements added by the use of gender-inclusive writing (in-word punctuations and second suffixes) are put into html tags that make them invisible to screen-reading tools. (This is done using the dedicated html field: \"aria-hidden\". For being effective, the used screen-reading tool must take this field into account, such as ChromeVox.)
Example: « Les argiculteur.rice.s » will be written as « les agriculteur·rice·s » on screen, and will be read « les agriculteurs » by screen reading tools.
Treatment of comments is optional. URL and website names are recognized and not affected. The content of html tags is not affected.


== Installation ==
## TODO ##
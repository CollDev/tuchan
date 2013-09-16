<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Limpia caracteres especiales de una cadena
 * @param string $cadena
 * @return string
 */
if (!function_exists('limpiar_caracteres')) {

    function limpiar_caracteres($cadena) {
        $a_tofind = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'à', 'á', 'â', 'ã', 'ä', 'å'
            , 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø'
            , 'È', 'É', 'Ê', 'Ë', 'è', 'é', 'ê', 'ë', 'Ç', 'ç'
            , 'Ì', 'Í', 'Î', 'Ï', 'ì', 'í', 'î', 'ï'
            , 'Ù', 'Ú', 'Û', 'Ü', 'ù', 'ú', 'û', 'ü', 'ÿ', 'Ñ', 'ñ');

        $a_replac = array('A', 'A', 'A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a', 'a'
            , 'O', 'O', 'O', 'O', 'O', 'O', 'o', 'o', 'o', 'o', 'o', 'o'
            , 'E', 'E', 'E', 'E', 'e', 'e', 'e', 'e', 'C', 'c'
            , 'I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'
            , 'U', 'U', 'U', 'U', 'u', 'u', 'u', 'u', 'y', 'N', 'n');

        $ncadena = str_replace($a_tofind, $a_replac, $cadena);


        $ncadena = str_replace(
                array("\\", "¨", "º", "-", "~","#", "@", "|", "!", "\"","·", "$", "%", "&", "/",
                    "(", ")", "?", "'", "¡","¿", "[", "^", "`", "]","+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":","."), '', $ncadena
        );

        return $ncadena;
    }

}
// Fin limpiar_caracteres_especiales
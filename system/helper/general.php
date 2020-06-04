<?php

function token($length = 32)
{
    // Create random token
    $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    $max = strlen($string) - 1;

    $token = '';

    for ($i = 0; $i < $length; $i++) {
        $token .= $string[mt_rand(0, $max)];
    }

    return $token;
}

/**
 * Backwards support for timing safe hash string comparisons
 *
 * http://php.net/manual/en/function.hash-equals.php
 */
if (!function_exists('hash_equals')) {

    function hash_equals($known_string, $user_string)
    {
        $known_string = (string)$known_string;
        $user_string = (string)$user_string;

        if (strlen($known_string) != strlen($user_string)) {
            return false;
        } else {
            $res = $known_string ^ $user_string;
            $ret = 0;

            for ($i = strlen($res) - 1; $i >= 0; $i--)
                $ret |= ord($res[$i]);

            return !$ret;
        }
    }

}

/**
 * Exception handler, to throw exception, when used on Warning.
 * https://stackoverflow.com/a/11206244/1720476
 */
if (!function_exists('copona_warning_handler')) {
    function copona_warning_handler($errno, $errstr) {
        throw new Exception("Error $errno:  $errstr") ;
    }
}




function print_var_name($var) {
    foreach($GLOBALS as $var_name => $value) {
        if ($value === $var) {
            return $var_name;
        }
    }
    return false;
}

/* Copona __c function! */

if(!function_exists('__t')) {
    function __t($var, $else = '') {
        //$else - not used pagaidām.
        $text = \Language::getInstance()->get( $var );


        // prd(Language::getInstance());

        return $text ? $text : $var ;
        // return isset($var) && $var ? $var : ($else ? $else : print_var_name($var));
    }
} else {
    prd("'__()' function already defined!");
}


if(!function_exists('_e')) {
    function _e($var) {
        echo __($var);
        // return isset($var) && $var ? $var : ($else ? $else : print_var_name($var));
    }
} else {
    prd("'_e()' function already defined!");
}



/**
 * Echo Form Field! (Copona DEV tests )
 */

if (!function_exists('eff')) {
    function eff(...$args) {
        $key = $args [0];
        $entry_name = $args [1];
        $required = $args [2];
        $type = empty($args [3]) ? 'text' : $args [3];
        $label_col = empty($args [4]) ? 4 : $args [4];
        $props = empty($args [5]) ? [] : $args [5];

        global $current_address;
        $address = $current_address;

        $invalid = !empty($props['invalid']) ? ' is-invalid' : '';

        if(!empty($props['default_value'])) {
            $default_value = $props['default_value'] ;
        } else {
            $default_value = isset($address[$key]) ? $address[$key] : ''; ;
        }

        ?>
        <div class="form-group row<?= ($required ? " $required" : '') ?>">
            <label class="col-lg-<?=$label_col?> col-form-label-sm" for="input-payment-<?=$key?>"><?php echo $entry_name; ?></label>
            <div class="col-lg-<?=(12-$label_col);?>">

                <?php switch($type) {
                    case 'text': ?>
                        <input type="text" name="<?=(!empty($props['named_array']) ? $props['named_array'] . "[" . $key . "]" : $key )?>"
                               value="<?php echo $default_value; ?>"

                               <?php if(empty($props['disable_placeholder'])) { ?>placeholder="<?php echo str_replace(':', '', $entry_name); ?>"<?php } ?>
                               class="form-control form-control-sm<?=$invalid?>"

                               id="input-payment-<?= $key ?>" class="form-control form-control-sm" <?php if (isset($customer_id)) { ?> readonly<?php } ?>
                            <?php if(!empty($props['disabled'])) { echo 'disabled="disabled"'; } ?>

                        />
                        <?php break;

                } ?>

            </div>
        </div>
        <?php
    }
}












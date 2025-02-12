<?php

function sanitizeInput(&$input)
{
    $sanitizedInput = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    $input = null;

    return $sanitizedInput;
}

function unsetSessionVariables()
{
    $sessionVariables = func_get_args();

    foreach ($sessionVariables as $sessionVariable) {
        if (isset($_SESSION[$sessionVariable])) {
            unset($_SESSION[$sessionVariable]);
        }
    }
}

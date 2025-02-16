<?php
function camelCaseToSpaces($input) {

    $input = str_replace('_', ' ', $input);

    $formatted = preg_replace('/([a-z])([A-Z])/', '$1 $2', $input);

    return ucwords($formatted);

}

echo camelCaseToSpaces("camel_case_string")."</br>";

echo camelCaseToSpaces("camelCaseString")."</br>";
?>

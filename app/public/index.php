<?php

if (php_sapi_name() == "cli") {
    $template = "template.tpl";
    $userVariables = search_template($template);
    make_output_cli($userVariables, $template);
} else {
    $template = "template.tpl";
    $userVariables = search_template($template);
    make_output_browser($userVariables);
}

function search_template($template)
{
    $fullText = file_get_contents($template);
    $pattern = "/%\w+%/";
    preg_match_all($pattern, $fullText, $matches);
    $userVariables = $matches[0];

    return $userVariables;
}

function make_output_browser($userVariables)
{
    $quantity = count($userVariables);
    $inputs = prettify_inputs($userVariables);

    if ($quantity !== 0 && !empty($inputs)) {
        require "home.php";
    } else {
        echo "There are no variables to parse in you template";
    }
}

function make_output_cli($userVariables, $template)
{
    $fullText = file_get_contents($template);
    $quantity = count($userVariables);
    $variableInputs = [];
    $number = 0;
    $inputs = prettify_inputs($userVariables);

    for ($i = 0; $i < $quantity;) {
        echo "Enter your " . $inputs[$i] . "\n";
        $value = fgets(STDIN);
        $value = trim($value, "\n");
        $value = trim($value, " ");

        if ($value === "") {
            continue;
        }

        if ($userVariables[$i] === '%MONTHNUM%'
            && (!is_numeric($value)
                || strpos($value, '.') !== false
                || strpos($value, ',') !== false)
        ) {
            continue;
        }

        if ($value !== "") {
            $variableInputs[$i] = $value;

            if ($userVariables[$i] === '%MONTHNUM%') {
                $number = $value;
            }

            $i++;
        }
    }

    for ($j = 0; $j < $quantity; $j++) {
        $fullText = str_replace($userVariables[$j], $variableInputs[$j], $fullText);
    }

    $scriptDate = date("d-m-Y");
    $future = date('d-m-Y', strtotime("+$number month"));

    $fullText = str_replace('#EXECDATE#', $scriptDate, $fullText);
    $fullText = str_replace('#ENDDATE#', $future, $fullText);

    echo $fullText . "\n";
}

function prettify_inputs($userVariables)
{
    $inputs = [];

    for ($k = 0; $k < count($userVariables); $k++) {
        $value = $userVariables[$k];
        $value = substr($value, 1, -1);
        $value = strtolower($value);
        $value = ucwords($value);
        $inputs[$k] = $value;
    }

    return $inputs;
}
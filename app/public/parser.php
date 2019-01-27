<?php

$template = "template.tpl";
$result = check();
$output = parser($result, $template);

if ($output !== "" && $result) {
    require "parsed.php";
}

function check()
{
    $flag = false;
    $params = $_POST;
    foreach ($params as $paramName => $paramValue) {
        if ($paramValue === "") {
            return $flag;
        }

        if ($paramName === "%MONTHNUM%") {
            if (!is_numeric($paramValue)) {
                return $flag;
            }

            if (strpos($paramValue, '.') !== false || strpos($paramValue, ',') !== false) {
                return $flag;
            }
        }
    }
    $flag = true;

    return $flag;
}

function parser($result, $template)
{
    $output = "";

    if ($result) {
        $handle = @fopen($template, "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $pattern = "/%\w+%/";
                preg_match_all($pattern, $buffer, $matches);
                $variables = $matches[0];
                $inputs = [];
                $params = $_POST;

                for ($i = 0; $i < count($variables); $i++) {
                    $inputs[$variables[$i]] = $params["$variables[$i]"];
                }

                foreach ($inputs as $variableName => $variableValue) {
                    $buffer = str_replace($variableName, $variableValue, $buffer);
                }

                $script_date = date("d-m-Y");

                $future = date('d-m-Y', strtotime("+" . $_POST["%MONTHNUM%"] . " month"));

                $buffer = str_replace('#EXECDATE#', $script_date, $buffer);
                $buffer = str_replace('#ENDDATE#', $future, $buffer);

                $output .= $buffer . "</br>";

            }

            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }

            fclose($handle);
        }

    } else {
        echo "You didn't input all variables correctly. Plese go back and make correct input </br>";
        echo "<a href=\"index.php\">Go Back</a>";
    }

    $output = "<p>" . $output . "</p>";

    return $output;
}
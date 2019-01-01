<?php

function get_input_form($label, $var_name) {
    $value ='';
    if (isset($_POST[$var_name])) {
        $value = $_POST[$var_name];
    }
    return "<label>$label:</label>
    <input class='input-field' type='text' value='$value' name='$var_name' id='$var_name'/> <br><br>";
}

function get_pass_form($label, $var_name) {

    return "<label>'$label':</label>
    <input class='input-field' type='password' name='$var_name'> <br><br>";
}

function get_form_btn($val, $name="") {

    return "<input class='btn' type='submit' value='$val' name='$name'/><br><br>";
}


?>
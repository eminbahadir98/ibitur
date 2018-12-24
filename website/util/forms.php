<?php

function get_input_form($label, $var_name) {

    return "<label>$label:</label>
    <input class='input-field' type='text' name='$var_name'/> <br><br>";
}

function get_pass_form($label, $var_name) {

    return "<label>$label:</label>
    <input class='input-field' type='password' name='$var_name'/> <br><br>";
}

function get_form_btn($val) {

    return "<input class='input-field btn' type='submit' value='$val'/><br><br>";
}

?>
<?
echo "<option value=\"0\">-- Seleccione una Comuna</option>\n";
foreach($comunas as $comuna){
    echo "<option value=\"{$comuna['Comuna']['id']}\">{$comuna['Comuna']['nombre']}</option>\n";
}
?>
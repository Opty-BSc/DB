<html>
<style>
    a {
        text-decoration:none;
    }
</style>
<body>
<?php
    $tbl = $_REQUEST['tbl'];
    if ($tbl < 3 || $tbl > 4) exit("tbl undefined");

    $table = [
        "Local_Publico",
        "Item",
        "Anomalia",
        "Correcao",
        "Proposta_De_Correcao"
    ];

    $keys = [
        ["latitude", "longitude", "nome"],
        ["item_id", "item_descricao", "localizacao", "longitude"],
        ["anomalia_id", "zona", "imagem", "lingua", "ts", "anomalia_descricao", "tem_anomalia_descricao"],
        ["email", "nro", "anomalia_id"],
        ["email", "nro", "data_hora", "texto"],
    ];

    $table = $table[$tbl];
    $keys = $keys[$tbl];

    echo("<h2>$table</h2>");

    echo("<table border=1 cellpadding='5px'>");
    echo("<tr>");
    echo("<td>Version</td>");
    foreach($keys as $key) {
        echo("<td>$key</td>");
    }
    echo("<td>Opcao</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td>Current</td>");
    foreach ($keys as $key) {
        $value = $_REQUEST[$key];
        echo("<td>$value</td>");
    }
    echo("<td></td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td>New</td>");
    echo("<form action='executar_update.php' method='post'>");
    echo("<input type='hidden' name='tbl' value='$tbl'>");
    foreach ($keys as $key) {
        $value = $_REQUEST[$key];
        echo("<input type='hidden' name='$key' value='$value'>");
        $newKey = "new_".$key;
        echo("<td><input type='text' name='$newKey'></td>");
    }
    echo("<td><input type='submit' value='editar'></td>");
    echo("</form>");
    echo("</tr>");
    echo("</table>");

    echo("<br>");
    echo("<table>");
    echo("<tr>");
    $href = "../index.php";
    echo("<td><ul><h3><li><a href='$href'>Menu</a></li></h3></ul></td>");
    $href = "change.php?chg=editar&tbl=$tbl";
    echo("<td><ul><h3><li><a href='$href'>Back</a></li></h3></ul></td>");
    echo("</tr>");
    echo("</table>\n");
?>
</body>
</html>

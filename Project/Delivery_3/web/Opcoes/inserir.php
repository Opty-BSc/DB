<html>
<style>
    a {
        text-decoration:none;
    }
</style>
<body>
<?php
try {
    $tbl = $_REQUEST['tbl'];
    if ($tbl < 0 || $tbl > 2) exit("tbl undefined");

    $host = "db.tecnico.ulisboa.pt";
    $user = "ist190774";
    $password = "ist190774@psqlpass";
    $dbname = $user;

    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $table = [
        "Local_Publico",
        "Item",
        "Anomalia",
    ];

    $keys = [
        ["latitude", "longitude", "nome"],
        ["item_id", "item_descricao", "localizacao", "latitude", "longitude"],
        ["anomalia_id", "zona", "imagem", "lingua", "ts", "anomalia_descricao", "tem_anomalia_redacao"],
    ];

    $table = $table[$tbl];
    $keys = $keys[$tbl];

    $sql = "SELECT * FROM $table;";
    $result = $db->prepare($sql);
    $result->execute();

    $result->setFetchMode(PDO::FETCH_ASSOC);

    echo("<h2>Inserir $table</h2>");
    echo("<table border=1 cellpadding='5px'>");
    echo("<tr>");
    foreach($keys as $key) {
        echo("<td>$key</td>");
    }
    echo("<td>Opcao</td>");
    echo("</tr>");
    echo("<tr>");
    if ($tbl == 2) $action = "executar_inserir_anomalia.php";
    else $action = "executar_inserir.php";
    echo("<form action='$action' method='post'>");
    echo("<input type='hidden' name='tbl' value='$tbl'>");
    foreach ($keys as $key) {
        echo("<td><input type='text' name='$key'></td>");
    }
    echo("<td><input type='submit' value='inserir'></td>");
    echo("</form>");
    echo("</tr>");
    echo("</table>");

    $tables = [$table => "*"];
    if ($tbl == 1) $tables["Local_Publico"] = "*";

    foreach ($tables as $table => $params) {
        $sql = "SELECT $params FROM $table;";
        $result = $db->prepare($sql);
        $result->execute();

        $first = TRUE;
        $result->setFetchMode(PDO::FETCH_ASSOC);

        echo("<br>");
        echo("<h2>$table</h2>");
        echo("<table border=1 cellpadding='5px'>\n");
        while ($row = $result->fetch()) {
            if ($first) {
                $first = FALSE;
                echo("<tr>");
                foreach ($row as $key => $val) {
                    echo("<td>$key</td>\n");
                }
                echo("</tr>");
            }
            echo("<tr>");
            foreach ($row as $key => $val) {
                echo("<td>$val</td>\n");
            }
            echo("</tr>");
        }
        echo("</table>\n");
    }

    $db = null;

} catch (PDOException $e) {
    echo("<p>Erro a Inserir</p>");

} finally {
    echo("<br>");
    echo("<table>");
    echo("<tr>");
    $href = "../index.php";
    echo("<td><ul><h3><li><a href='$href'>Menu</a></li></h3></ul></td>");
    echo("</tr>");
    echo("</table>\n");
}
?>
</body>
</html>

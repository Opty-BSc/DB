<html>
<style>
    a {
        text-decoration:none;
    }
</style>
<body>
<?php
try {
    $chg = $_REQUEST['chg'];
    $tbl = $_REQUEST['tbl'];
    if ($tbl < 0 || $tbl > 4) exit("tbl undefined");

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
        "Correcao",
        "Proposta_De_Correcao"
    ];

    $table = $table[$tbl];

    $sql = "SELECT * FROM $table;";
    $result = $db->prepare($sql);
    $result->execute();

    echo("<h2>$table</h2>");

    $first = TRUE;
    $result->setFetchMode(PDO::FETCH_ASSOC);

    echo("<table border=1 cellpadding='5px'>\n");
    while ($row	= $result->fetch()){
        if ($first) {
            $first = FALSE;
            echo("<tr>");
            foreach($row as $key => $val) {
                echo("<td>$key</td>\n");
            }
            echo("<td>opcao</td>\n");
            echo("</tr>");
        }
        echo("<tr>");
        echo("<form action='executar_$chg.php' method='post'>");
        echo("<input type='hidden' name='tbl' value='$tbl'>");
        foreach($row as $key => $val) {
            echo("<input type='hidden' name='$key' value='$val'>");
            echo("<td>$val</td>\n");
        }
        echo("<td><input type='submit' value='$chg'></td>\n");
        echo("</form>");
        echo("</tr>");
    }
    echo("</table>\n");

    $db = null;

} catch (PDOException $e) {
    echo("<p>Erro ao Alterar</p>");

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

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

    $keys = [
        ["latitude", "longitude"],
        ["item_id"],
        ["anomalia_id"],
        ["email", "nro", "anomalia_id"],
        ["email", "nro"],
    ];

    $table = $table[$tbl];
    $keys = $keys[$tbl];

    $sql = "DELETE FROM $table WHERE";
    $exe = [];

    $first = TRUE;
    foreach($keys as $key) {
        if (!$first) $sql .= " AND";
        else $first = FALSE;
        $sql .= " $key = :$key";
        $exe[":".$key] = $_REQUEST[$key];
    }
    $sql .= ";";

    $result = $db->prepare($sql);
    $result->execute($exe);

    $db = null;
    echo("<p>Removido</p>");

} catch (PDOException $e) {
    echo("<p>Erro a Remover</p>");

} finally {
    echo("<br>");
    echo("<table>");
    echo("<tr>");
    $href = "../index.php";
    echo("<td><ul><h3><li><a href='$href'>Menu</a></li></h3></ul></td>");
    $href = "change.php?chg=remover&tbl=$tbl";
    echo("<td><ul><h3><li><a href='$href'>Back</a></li></h3></ul></td>");
    echo("</tr>");
    echo("</table>\n");
}
?>
</body>
</html>

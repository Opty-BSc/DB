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
    if ($tbl != 2) exit("tbl undefined");

    $host = "db.tecnico.ulisboa.pt";
    $user = "ist190774";
    $password = "ist190774@psqlpass";
    $dbname = $user;

    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = [
        "Anomalia" => ["anomalia_id", "zona", "imagem", "lingua", "ts", "anomalia_descricao", "tem_anomalia_redacao"],
        "Anomalia_Traducao" => ["anomalia_id", "zona2", "lingua2"]
    ];

    $db->query("START TRANSACTION");

    foreach ($tables as $table => $keys) {
        $sql = "INSERT INTO $table VALUES (";
        $exe = [];

        $first = TRUE;
        foreach($keys as $key) {
            if (!$first) $sql .= ", ";
            else $first = FALSE;
            $sql .= ":".$key;
            $exe[":".$key] = $_REQUEST[$key];
        }
        $sql .= ");";

        $result = $db->prepare($sql);
        $result->execute($exe);
    }

    $db->query("COMMIT");

    $db = null;
    echo("<p>Inserido</p>");

} catch (PDOException $e) {
    $db->rollBack();
    echo("<p>Erro a Inserir</p>");

} finally {
    echo("<br>");
    echo("<table>");
    echo("<tr>");
    $href = "../index.php";
    echo("<td><ul><h3><li><a href='$href'>Menu</a></li></h3></ul></td>");
    $href = "inserir.php?tbl=$tbl";
    echo("<td><ul><h3><li><a href='$href'>Back</a></li></h3></ul></td>");
    echo("</tr>");
    echo("</table>\n");
}
?>
</body>
</html>

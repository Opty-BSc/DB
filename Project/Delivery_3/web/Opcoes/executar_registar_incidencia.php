<html>
<style>
    a {
        text-decoration:none;
    }
</style>
<body>
<?php
try {
    $host = "db.tecnico.ulisboa.pt";
    $user = "ist190774";
    $password = "ist190774@psqlpass";
    $dbname = $user;

    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $table = "Incidencia";
    $keys = ["anomalia_id", "item_id", "email"];

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

    $db = null;
    echo("<p>Registado</p>");

} catch (PDOException $e) {
    echo("<p>Erro a Registar</p>");

} finally {
    echo("<br>");
    echo("<table>");
    echo("<tr>");
    $href = "../index.php";
    echo("<td><ul><h3><li><a href='$href'>Menu</a></li></h3></ul></td>");
    $href = "registar_incidencia.php";
    echo("<td><ul><h3><li><a href='$href'>Back</a></li></h3></ul></td>");
    echo("</tr>");
    echo("</table>\n");
}
?>
</body>
</html>

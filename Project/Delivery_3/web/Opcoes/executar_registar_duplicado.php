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

    $sql = "INSERT INTO Duplicado VALUES (:item1_id, :item2_id)";

    $result = $db->prepare($sql);

    $ids = [$_REQUEST['id1'], $_REQUEST['id2']];

    $result->execute([
        ':item1_id' => min($ids),
        ':item2_id' => max($ids),
    ]);

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
    $href = "registar_duplicado.php";
    echo("<td><ul><h3><li><a href='$href'>Back</a></li></h3></ul></td>");
    echo("</tr>");
    echo("</table>\n");
}
?>
</body>
</html>

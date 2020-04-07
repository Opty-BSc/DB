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

    $sql = "SELECT * FROM Item;";
    $result = $db->prepare($sql);
    $result->execute();

    echo("<h2>Duplicados</h2>");
    echo("<table border=1 cellpadding='5px'>");
    echo("<tr>");
    echo("<td></td>");
    echo("<td>Item</td>");
    echo("<td>Item Duplicado</td>");
    echo("<td>Opcao</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<td>ID</td>");
    echo("<form action='executar_registar_duplicado.php' method='post'>");
    echo("<td><input type='text' name='id1'></td>");
    echo("<td><input type='text' name='id2'></td>");
    echo("<td><input type='submit' value='registar'></td>");
    echo("</form>");
    echo("</tr>");
    echo("</table>");
    echo("<br>");

    $first = TRUE;
    $result->setFetchMode(PDO::FETCH_ASSOC);

    echo("<h2>Item</h2>");
    echo("<table border=1 cellpadding='5px'>\n");
    while ($row	= $result->fetch()){
        if ($first) {
            $first = FALSE;
            echo("<tr>");
            foreach($row as $key => $val) {
                echo("<td>$key</td>\n");
            }
            echo("</tr>");
        }
        echo("<tr>");
        foreach($row as $key => $val) {
            echo("<td>$val</td>\n");
        }
        echo("</tr>");
    }
    echo("</table>\n");

    $db = null;

} catch (PDOException $e) {
    echo("<p>Erro a Registar</p>");

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

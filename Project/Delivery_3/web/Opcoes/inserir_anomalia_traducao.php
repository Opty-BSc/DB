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

    $table = "Anomalia_Traducao";
    $normalKeys = ["anomalia_id", "zona", "imagem", "lingua", "ts", "anomalia_descricao", "tem_anomalia_redacao"];
    $keys = ["zona2", "lingua2"];

    echo("<h2>Inserir $table</h2>");
    echo("<table border=1 cellpadding='5px'>");
    echo("<tr>");
    echo("<td>segunda zona</td>");
    echo("<td>segunda lingua</td>");
    echo("<td>Opcao</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<form action='executar_inserir_anomalia_traducao.php' method='post'>");
    echo("<input type='hidden' name='tbl' value='$tbl'>");
    foreach ($normalKeys as $key) {
        $value = $_REQUEST[$key];
        echo("<input type='hidden' name='$key' value='$value'>");
    }
    foreach ($keys as $key) {
        echo("<td><input type='text' name='$key'></td>");
    }
    echo("<td><input type='submit' value='inserir'></td>");
    echo("</form>");
    echo("</tr>");
    echo("</table>");
    echo("<br>");

    $sql = "SELECT * FROM $table;";
    $result = $db->prepare($sql);
    $result->execute();

    $first = TRUE;
    $result->setFetchMode(PDO::FETCH_ASSOC);

    echo("<table border=1 cellpadding='5px'>\n");
    while ($row	= $result->fetch()) {
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

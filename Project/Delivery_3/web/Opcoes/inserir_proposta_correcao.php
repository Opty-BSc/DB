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

    $table = "Proposta_De_Correcao & Correcao";
    $keys = ["email", "nro", "data_hora", "texto", "anomalia_id"];

    echo("<h2>Inserir $table</h2>");
    echo("<table border=1 cellpadding='5px'>");
    echo("<tr>");
    foreach($keys as $key) {
        echo("<td>$key</td>");
    }
    echo("<td>Opcao</td>");
    echo("</tr>");
    echo("<tr>");
    echo("<form action='executar_inserir_proposta_correcao.php' method='post'>");
    foreach ($keys as $key) {
        echo("<td><input type='text' name='$key'></td>");
    }
    echo("<td><input type='submit' value='inserir'></td>");
    echo("</form>");
    echo("</tr>");
    echo("</table>");

    $tables = ["Proposta_De_Correcao" => "*", "Correcao" => "*", "Anomalia" => "*", "Utilizador_Qualificado" => "*"];

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

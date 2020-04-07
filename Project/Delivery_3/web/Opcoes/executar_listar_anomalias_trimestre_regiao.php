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

    $params = "anomalia_id, zona, imagem, lingua, ts, anomalia_descricao, tem_anomalia_redacao";
    $sql = "SELECT * FROM Anomalia NATURAL JOIN Incidencia NATURAL JOIN Item
            WHERE (ts BETWEEN localtimestamp - INTERVAL '3 Months' AND localtimestamp) AND
                  (latitude BETWEEN :minLat AND :maxLat) AND (longitude BETWEEN :minLong AND :maxLong);";

    $result = $db->prepare($sql);

    $midPoint = [floatval($_REQUEST['XYlatitude']), floatval($_REQUEST['XYlongitude'])];
    $delta = [floatval($_REQUEST['dXdYlatitude']), floatval($_REQUEST['dXdYlongitude'])];

    $latitudes = [$midPoint[0] - $delta[0], $midPoint[0] + $delta[0]];
    $longitudes = [$midPoint[1] - $delta[1], $midPoint[1] + $delta[1]];

    $result->execute([
        ':minLat' => min($latitudes),
        ':maxLat' => max($latitudes),
        ':minLong' => min($longitudes),
        ':maxLong' => max($longitudes)
    ]);

    echo("<h2>Anomalia</h2>");

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
    echo("<p>Erro a Listar</p>");

} finally {
    echo("<br>");
    echo("<table>");
    echo("<tr>");
    $href = "../index.php";
    echo("<td><ul><h3><li><a href='$href'>Menu</a></li></h3></ul></td>");
    $href = "listar_anomalias_trimestre_regiao.php";
    echo("<td><ul><h3><li><a href='$href'>Back</a></li></h3></ul></td>");
    echo("</tr>");
    echo("</table>\n");
}
?>
</body>
</html>

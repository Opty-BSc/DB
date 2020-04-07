<html>
<style>
    h3 {
        line-height:99%;
    }
    a {
        text-decoration:none;
    }
</style>
<body>
<?php
    echo("<h1>Opcoes</h1>");

    echo("<h2>Inserir</h2>");
    echo("<ul>\n");
    $href = "Opcoes/inserir.php?tbl=0";
    echo("<h3><li><a href={$href}>Inserir Local</a></li></h3>\n");
    $href = "Opcoes/inserir.php?tbl=1";
    echo("<h3><li><a href={$href}>Inserir Item</a></li></h3>\n");
    $href = "Opcoes/inserir.php?tbl=2";
    echo("<h3><li><a href={$href}>Inserir Anomalia</a></li></h3>\n");
    $href = "Opcoes/inserir_correcao.php";
    echo("<h3><li><a href={$href}>Inserir Correcao</a></li></h3>\n");
    $href = "Opcoes/inserir_proposta_correcao.php";
    echo("<h3><li><a href={$href}>Inserir Proposta de Correcao</a></li></h3>\n");
    echo("</ul>");

    echo("<h2>Remover</h2>");
    echo("<ul>\n");
    $href = "Opcoes/change.php?chg=remover&tbl=0";
    echo("<h3><li><a href={$href}>Remover Local</a></li></h3>\n");
    $href = "Opcoes/change.php?chg=remover&tbl=1";
    echo("<h3><li><a href={$href}>Remover Item</a></li></h3>\n");
    $href = "Opcoes/change.php?chg=remover&tbl=2";
    echo("<h3><li><a href={$href}>Remover Anomalia</a></li></h3>\n");
    $href = "Opcoes/change.php?chg=remover&tbl=3";
    echo("<h3><li><a href={$href}>Remover Correcao</a></li></h3>\n");
    $href = "Opcoes/change.php?chg=remover&tbl=4";
    echo("<h3><li><a href={$href}>Remover Proposta de Correcao</a></li></h3>\n");
    echo("</ul>");

    echo("<h2>Editar</h2>");
    echo("<ul>\n");
    $href = "Opcoes/change.php?chg=editar&tbl=3";
    echo("<h3><li><a href={$href}>Editar Correcao</a></li></h3>\n");
    $href = "Opcoes/change.php?chg=editar&tbl=4";
    echo("<h3><li><a href={$href}>Editar Proposta de Correcao</a></li></h3>\n");
    echo("</ul>");

    echo("<h2>Listar</h2>");
    echo("<ul>\n");
    $href = "Opcoes/listar_utilizadores.php";
    echo("<h3><li><a href={$href}>Listar Utilizadores</a></li></h3>\n");
    $href = "Opcoes/listar_anomalias_entre_locais.php";
    echo("<h3><li><a href={$href}>Listar Anomalias entre dois locais publicos</a></li></h3>\n");
    $href = "Opcoes/listar_anomalias_trimestre_regiao.php";
    echo("<h3><li><a href={$href}>Listar Anomalias realizadas nos ultimos 3 meses dada uma regiao</a></li></h3>\n");
    echo("</ul>");

    echo("<h2>Registar</h2>");
    echo("<ul>\n");
    $href = "Opcoes/registar_incidencia.php";
    echo("<h3><li><a href={$href}>Registar Incidencia</a></li></h3>\n");
    $href = "Opcoes/registar_duplicado.php";
    echo("<h3><li><a href={$href}>Registar Duplicado</a></li></h3>\n");
    echo("</ul>");
?>
</body>
</html>

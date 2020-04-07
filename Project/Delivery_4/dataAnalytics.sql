SELECT COALESCE(CAST(tipo_anomalia AS varchar), 'TODOS OS TIPOS') AS tipo_anomalia,
       COALESCE(CAST(lingua AS varchar), 'TODAS AS LINGUAS') AS lingua,
       COALESCE(CAST(dia_da_semana AS varchar), 'TODOS OS DIAS') AS dia_da_semana,
       count AS total
FROM (
    SELECT tipo_anomalia, lingua, dia_da_semana, COUNT(*)
    FROM f_anomalia
        NATURAL JOIN d_lingua
        NATURAL JOIN d_tempo
    GROUP BY tipo_anomalia, lingua, dia_da_semana
    UNION
    SELECT tipo_anomalia, lingua, NULL, COUNT(*)
    FROM f_anomalia
        NATURAL JOIN d_lingua
        NATURAL JOIN d_tempo
    GROUP BY tipo_anomalia, lingua
    UNION
    SELECT tipo_anomalia, NULL, NULL, COUNT(*)
    FROM f_anomalia
        NATURAL JOIN d_lingua
        NATURAL JOIN d_tempo
    GROUP BY tipo_anomalia
    UNION
    SELECT NULL, NULL, NULL, COUNT(*)
    FROM f_anomalia
        NATURAL JOIN d_lingua
        NATURAL JOIN d_tempo
    ORDER BY tipo_anomalia, lingua, dia_da_semana
    ) AS T;

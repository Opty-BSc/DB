-- Populate d_utilizador
INSERT INTO d_utilizador(email, tipo)
    SELECT email, 'Qualificado' AS tipo
    FROM utilizador_qualificado
    UNION
    SELECT email, 'Regular' AS tipo
    FROM utilizador_regular;

-- Populate d_tempo
CREATE OR REPLACE FUNCTION insert_d_tempo()
RETURNS VOID AS $$
    DECLARE ts timestamp;
    BEGIN
        ts = '2000-01-01 00:00:00';
        WHILE ts < '2025-01-01 00:00:00' LOOP
            INSERT INTO d_tempo(dia, dia_da_semana, semana, mes, trimestre, ano)
            VALUES (
                date_part('day', ts),
                date_part('dow', ts) + 1,
                date_part('week', ts),
                date_part('month', ts),
                date_part('month', ts) / 3 + 1,
                date_part('year', ts)
                );
            ts = ts + INTERVAL '1 day';
        END LOOP;
    END;
    $$ LANGUAGE plpgsql;

SELECT insert_d_tempo();

-- Populate d_local
INSERT INTO d_local(latitude, longitude, nome)
    SELECT latitude, longitude, nome
    FROM local_publico;

-- Populate d_lingua
INSERT INTO d_lingua(lingua)
    SELECT lingua
    FROM anomalia
    UNION
    SELECT lingua2 AS lingua
    FROM anomalia_traducao;

-- Populate f_anomalia
CREATE OR REPLACE FUNCTION check_corr(IN mail varchar(254))
RETURNS BOOLEAN AS $$
    BEGIN
        IF EXISTS (
            SELECT email
            FROM proposta_de_correcao
            WHERE email = mail
        ) THEN RETURN TRUE;
        END IF;
        RETURN FALSE;
    END;
    $$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION anomalia_type(IN is_redacao BOOLEAN)
RETURNS VARCHAR(8) AS $$
    BEGIN
        IF is_redacao THEN
            RETURN 'Redacao';
        END IF;
        RETURN 'Traducao';
    END;
    $$ LANGUAGE plpgsql;

INSERT INTO f_anomalia(id_utilizador, id_tempo, id_local, id_lingua, tipo_anomalia, com_proposta)
    SELECT id_utilizador, id_tempo, id_local, id_lingua, tipo_anomalia, com_proposta
    FROM (
        SELECT email,
            date_part('day', ts) AS dia,
            date_part('month', ts) AS mes,
            date_part('year', ts) AS ano,
            latitude,
            longitude,
            lingua,
            anomalia_type(tem_anomalia_redacao) AS tipo_anomalia,
            check_corr(email) AS com_proposta
        FROM utilizador
            NATURAL JOIN incidencia
            NATURAL JOIN item
            NATURAL JOIN anomalia
        ) AS T
        NATURAL JOIN d_utilizador
        NATURAL JOIN d_tempo
        NATURAL JOIN d_local
        NATURAL JOIN d_lingua;

DROP TABLE correcao CASCADE;
DROP TABLE proposta_de_correcao CASCADE;
DROP TABLE incidencia CASCADE;
DROP TABLE utilizador_regular CASCADE;
DROP TABLE utilizador_qualificado CASCADE;
DROP TABLE utilizador CASCADE;
DROP TABLE duplicado CASCADE;
DROP TABLE anomalia_traducao CASCADE;
DROP TABLE anomalia CASCADE;
DROP TABLE item CASCADE;
DROP TABLE local_publico CASCADE;

CREATE TABLE local_publico (
    latitude decimal(8, 6) not null,
    longitude decimal(9, 6) not null,
    nome varchar(200) not null,
    primary key(latitude, longitude)
);

CREATE TABLE item (
    item_id integer not null,
    item_descricao text not null,
    localizacao varchar(255) not null,
    latitude decimal(8, 6) not null,
    longitude decimal(9, 6) not null,
    primary key(item_id),
    foreign key(latitude, longitude)
        references local_publico(latitude, longitude) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE anomalia (
    anomalia_id integer not null,
    zona box not null,
    imagem varchar(2083) not null,
    lingua char(3) not null,
    ts timestamp without time zone not null,
    anomalia_descricao text not null,
    tem_anomalia_redacao boolean not null,
    primary key(anomalia_id)
);

CREATE TABLE anomalia_traducao (
    anomalia_id integer not null,
    zona2 box not null,
    lingua2 char(3) not null,
    primary key(anomalia_id),
    foreign key(anomalia_id)
        references anomalia(anomalia_id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE duplicado (
    item1_id integer not null,
    item2_id integer not null,
    primary key(item1_id, item2_id),
    foreign key(item1_id)
        references item(item_id) ON UPDATE CASCADE ON DELETE CASCADE,
    foreign key(item2_id)
        references item(item_id) ON UPDATE CASCADE ON DELETE CASCADE,
    check(item1_id < item2_id)
);

CREATE TABLE utilizador (
    email varchar(254) not null,
    password varchar(40) not null,
    primary key(email)
);

CREATE TABLE utilizador_qualificado (
    email varchar(254) not null,
    primary key(email),
    CONSTRAINT fk_user foreign key(email)
        references utilizador(email) ON UPDATE CASCADE ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE
);

CREATE TABLE utilizador_regular (
    email varchar(254) not null,
    primary key(email),
    CONSTRAINT fk_user foreign key(email)
        references utilizador(email) ON UPDATE CASCADE ON DELETE CASCADE DEFERRABLE INITIALLY IMMEDIATE
);

CREATE TABLE incidencia (
    anomalia_id integer not null,
    item_id integer not null,
    email varchar(254) not null,
    primary key(anomalia_id),
    foreign key(anomalia_id)
        references anomalia(anomalia_id) ON UPDATE CASCADE ON DELETE CASCADE,
    foreign key(item_id)
        references item(item_id) ON UPDATE CASCADE ON DELETE CASCADE,
    foreign key(email)
        references utilizador(email) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE proposta_de_correcao (
    email varchar(254) not null,
    nro integer not null,
    data_hora timestamp without time zone not null,
    texto text not null,
    primary key(email, nro),
    foreign key(email)
        references utilizador_qualificado(email) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE correcao (
    email varchar(254) not null,
    nro integer not null,
    anomalia_id integer not null,
    primary key(anomalia_id, email, nro),
    foreign key(email, nro)
        references proposta_de_correcao(email, nro) ON UPDATE CASCADE ON DELETE CASCADE,
    foreign key(anomalia_id)
        references incidencia(anomalia_id) ON UPDATE CASCADE ON DELETE CASCADE
);

-- Triggers
-- RI - 1
CREATE OR REPLACE FUNCTION check_box_overlaps_proc()
RETURNS TRIGGER AS $$
    BEGIN
        IF EXISTS (
            SELECT zona
            FROM anomalia
            WHERE anomalia_id = NEW.anomalia_id AND
                  zona && NEW.zona2
        ) THEN
            RAISE EXCEPTION 'Zona2 Invalida: %', NEW.zona2
            USING HINT = 'Verifique os limites da Zona';
        END IF;
        RETURN NEW;
    END;
    $$ LANGUAGE plpgsql;

CREATE TRIGGER check_box_overlaps BEFORE INSERT OR UPDATE ON anomalia_traducao
FOR EACH ROW EXECUTE PROCEDURE check_box_overlaps_proc();

-- RI - 4
CREATE OR REPLACE FUNCTION check_user_email_proc()
RETURNS TRIGGER AS $$
    BEGIN
        IF NOT EXISTS (
            SELECT email
            FROM utilizador_qualificado
            WHERE email = NEW.email
            UNION
            SELECT email
            FROM utilizador_regular
            WHERE email = NEW.email
        ) THEN
            RAISE EXCEPTION 'Email Invalido: %', NEW.email
            USING HINT = 'Verifique o Email';
        END IF;
        RETURN NEW;
    END;
    $$ LANGUAGE plpgsql;

CREATE TRIGGER check_user_email BEFORE INSERT OR UPDATE ON utilizador
FOR EACH ROW EXECUTE PROCEDURE check_user_email_proc();

-- RI - 5
CREATE OR REPLACE FUNCTION check_qual_user_email_proc()
RETURNS TRIGGER AS $$
    BEGIN
        IF EXISTS (
            SELECT email
            FROM utilizador_regular
            WHERE email = NEW.email
        ) THEN
            RAISE EXCEPTION 'Email Invalido: %', NEW.email
            USING HINT = 'Utilizador so pode ser de um tipo';
        END IF;
        RETURN NEW;
    END;
    $$ LANGUAGE plpgsql;

CREATE TRIGGER check_qual_user_email BEFORE INSERT OR UPDATE ON utilizador_qualificado
FOR EACH ROW EXECUTE PROCEDURE check_qual_user_email_proc();

-- RI - 6
CREATE OR REPLACE FUNCTION check_reg_user_email_proc()
RETURNS TRIGGER AS $$
    BEGIN
        IF EXISTS (
            SELECT email
            FROM utilizador_qualificado
            WHERE email = NEW.email
        ) THEN
            RAISE EXCEPTION 'Email Invalido: %', NEW.email
            USING HINT = 'Utilizador so pode ser de um tipo';
        END IF;
        RETURN NEW;
    END;
    $$ LANGUAGE plpgsql;

CREATE TRIGGER check_reg_user_email BEFORE INSERT OR UPDATE ON utilizador_regular
FOR EACH ROW EXECUTE PROCEDURE check_reg_user_email_proc();

-- Funcao para inserir utilizadores
CREATE OR REPLACE FUNCTION create_user(IN mail varchar(254), IN pass varchar(40), IN isQualified BOOLEAN)
RETURNS VOID AS $$
    BEGIN
        SET CONSTRAINTS fk_user DEFERRED;
        IF isQualified THEN
            INSERT INTO utilizador_qualificado VALUES(mail);
        ELSE
            INSERT INTO utilizador_regular VALUES(mail);
        END IF;
        INSERT INTO utilizador VALUES(mail, pass);
    END;
    $$ LANGUAGE plpgsql;

-- Indexes
-- 1.1.
-- Tendo como base o facto de apenas existir dados unclustered em PSQL,
-- e em 80% das invocacoes a query devolver > 10%
-- do total de registos da tabela, decidimos que nao havia necessidade de
-- criar um index, visto que o numero de I/O's continuara a ser bastante elevado.
-- Pois com > 10% dos registos da tabela a serem devolvidos,
-- existe uma grande probabilidade de carregar o mesmo numero de paginas,
-- criando ou nao um index.

-- 1.2.
-- Tendo como base o facto de apenas existir dados unclustered em PSQL,
-- e em 80% das invocacoes a query devolver < 0.001% (< 10%)
-- do total de registos da tabela, decidimos utilizar uma B+ Tree
-- como estrutura de indexacao.
-- Pois, ao contrario da alinea anterior, tendo em conta a % de
-- registos da tabela a serem devolvidos, com a utilizacao de um index,
-- o numero de I/O's sera bastante mais reduzido.
CREATE INDEX idx_data_hora ON proposta_de_correcao
    USING btree (data_hora);

-- 2.
-- Apesar da pesquisa efectuada ser feita atraves de uma primary key (anomalia_id),
-- e sabendo que essa pesquisa, em PSQL, é feita numa B+ Tree,
-- e como estamos a fazer uma pesquisa por igualdade,
-- esta estrutura de indexacao nao nos parece ser a mais eficiente,
-- pelo que decidimos que utilizar uma Hash seria o mais apropriado,
-- dai a criacao deste index.
CREATE INDEX idx_anomalia_id ON incidencia
    USING hash (anomalia_id);

-- 3.1.
-- 3.2.
-- Ao termos uma primary key composta na tabela correcao,
-- do qual a anomalia_id faz parte, isto permite-nos que
-- simplesmente alterando a ordem pela qual os campos da primary key
-- sao declarados (email, nro, anomalia_id) -> (anomalia_id, email, nro)
-- optimize a procura em B+ Tree pela anomalia_id, e tendo em conta que
-- a query seleciona apenas o email, e este faz parte dos campos da primary key,
-- entao nem e necessario fazer um acesso a tabela, tornando assim a query
-- o mais eficiente, com o menor numero de I/O's possivel.

-- 4.
-- Como a query seleciona apenas as linhas que tem_anomalia_redacao = TRUE,
-- podemos criar um parcial composite index com o ts e a lingua,
-- como queremos comparar o ts num certo range e usar o like para procurar a lingua,
-- usamos uma estrutura de indexacao B+ Tree.
CREATE INDEX idx_ts ON anomalia
    USING btree (ts, lingua)
    WHERE tem_anomalia_redacao IS TRUE;

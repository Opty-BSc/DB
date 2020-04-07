DROP TABLE f_anomalia;
DROP TABLE d_utilizador;
DROP TABLE d_tempo;
DROP TABLE d_local;
DROP TABLE d_lingua;

CREATE TABLE d_utilizador (
    id_utilizador serial not null,
    email varchar(254) not null,
    tipo varchar(11) not null,
    primary key(id_utilizador)
);

CREATE TABLE d_tempo (
    id_tempo serial not null,
    dia integer not null,
    dia_da_semana integer not null,
    semana integer not null,
    mes integer not null,
    trimestre integer not null,
    ano integer not null,
    primary key(id_tempo)
);

CREATE TABLE d_local (
    id_local serial not null,
    latitude decimal(8, 6) not null,
    longitude decimal(9, 6) not null,
    nome varchar(200) not null,
    primary key(id_local)
);

CREATE TABLE d_lingua (
    id_lingua serial not null,
    lingua char(3) not null,
    primary key(id_lingua)
);

CREATE TABLE f_anomalia (
    id_utilizador serial not null,
    id_tempo serial not null,
    id_local serial not null,
    id_lingua serial not null,
    tipo_anomalia varchar(8) not null,
    com_proposta boolean not null,
    primary key (id_utilizador, id_tempo, id_local, id_lingua),
    foreign key(id_tempo) references d_tempo(id_tempo),
    foreign key(id_local) references d_local(id_local),
    foreign key(id_lingua) references d_lingua(id_lingua)
);

create table steimetz.clients
(
    id_client        serial
        primary key,
    nom              varchar(50),
    prenom           varchar(50),
    adresse          varchar(100),
    code_postal      varchar(10),
    ville            varchar(50),
    pays             varchar(50),
    date_inscription date
);

create table steimetz.commande
(
    id_commande     serial
        primary key,
    id_client       integer
        references steimetz.clients,
    code_exemplaire integer
        references public.exemplaire,
    quantite        integer,
    prix            numeric(10, 2),
    date            date
);

create table steimetz.panier
(
    id_panier       serial
        primary key,
    id_client       integer
        references steimetz.clients,
    code_exemplaire integer
        references public.exemplaire,
    quantite        integer
);
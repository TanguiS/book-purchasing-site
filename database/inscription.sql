CREATE OR REPLACE FUNCTION inscription_adherent(
    nom VARCHAR(50),
    prenom VARCHAR(50),
    adresse VARCHAR(100),
    cp VARCHAR(10),
    ville VARCHAR(50),
    pays VARCHAR(50),
    date_inscription DATE DEFAULT current_date
) RETURNS INTEGER AS $$
DECLARE
    code_client INTEGER;
BEGIN
    SELECT id_client INTO code_client
    FROM clients
    WHERE UPPER(clients.nom) = UPPER($1) AND UPPER(clients.prenom) = UPPER($2) AND UPPER(clients.adresse) = UPPER($3);
    
    IF code_client IS NOT NULL THEN
        RETURN 0;
    END IF;

    INSERT INTO clients(nom, prenom, adresse, code_postal, ville, pays, date_inscription)
    VALUES($1, $2, $3, $4, $5, $6, $7)
    RETURNING id_client INTO code_client;

    RETURN code_client;
END;    
$$ LANGUAGE plpgsql;

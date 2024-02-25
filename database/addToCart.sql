CREATE OR REPLACE FUNCTION addToCart(
    p_id_client integer,
    p_code_exemplaire integer
) RETURNS void AS $$
DECLARE
    v_panier_row panier%ROWTYPE;
BEGIN
    SELECT * INTO v_panier_row
    FROM panier
    WHERE id_client = p_id_client AND code_exemplaire = p_code_exemplaire;

    IF FOUND THEN
        UPDATE panier
        SET quantite = v_panier_row.quantite + 1
        WHERE id_client = p_id_client AND code_exemplaire = p_code_exemplaire;
    ELSE
        INSERT INTO panier (id_client, code_exemplaire, quantite)
        VALUES (p_id_client, p_code_exemplaire, 1);
    END IF;
END;
$$ LANGUAGE plpgsql;

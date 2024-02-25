CREATE OR REPLACE FUNCTION uploadCommand(
    p_id_client integer
) RETURNS void AS $$
DECLARE
    v_client_row clients%ROWTYPE;
    v_panier_row panier%ROWTYPE;
    p_price numeric;
BEGIN
    SELECT * INTO v_client_row
    FROM clients
    WHERE id_client = p_id_client;

    FOR v_panier_row IN SELECT * FROM panier WHERE id_client = p_id_client LOOP
        SELECT prix INTO p_price
        FROM exemplaire
        WHERE code = v_panier_row.code_exemplaire; 
        INSERT INTO commande (id_client, code_exemplaire, quantite, prix, date)
        VALUES (p_id_client, v_panier_row.code_exemplaire, v_panier_row.quantite, p_price, CURRENT_DATE);

        PERFORM deleteCart(p_id_client);
    END LOOP;
END;
$$ LANGUAGE plpgsql;

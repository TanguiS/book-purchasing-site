CREATE OR REPLACE FUNCTION deleteCart(
    p_client_id integer
) RETURNS void AS $$
BEGIN
    DELETE FROM panier WHERE id_client = p_client_id;
END;
$$ LANGUAGE plpgsql;

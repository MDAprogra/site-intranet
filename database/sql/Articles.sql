SELECT
    fc_references.fo_rep_code AS gest,
    CASE fc_references.fo_site  WHEN  'I' THEN CONCAT( 'Interfas', '|', 'Interfas Nord')  WHEN  'L' THEN  'ILD' WHEN  'N' THEN  'Interfas Nord' ELSE CONCAT( 'Interfas', '|', 'Interfas Nord')  END  AS site,
    fs_stock.st_seq_compt AS st_seq_compt,
    CASE fs_stock.st_client  WHEN  '*' THEN  'III STK' ELSE fs_stock.st_client  END  AS st_client,
    CONCAT(fs_stock.st_modele , '/',fs_stock.st_version_modele )  AS article,
    fs_stock.st_art_ref_client AS ref_client,
    fs_stock.st_lib_1_conso AS libelle,
    fs_stock.st_q_physique AS qte,
    fs_stock.st_px_vente_le_1000 AS prix_vente,
    fs_stock.st_pmp AS PMP,
    fd_types_prod.typpro_lib AS typpro_lib,
    ( ( SELECT SUM( lot_q_en_m_f )  AS Q
        FROM fs_lot
        WHERE ( ( ( ( lot_statut =  1) AND ( lot_genre =  'P') ) AND ( lot_mag =  'S') ) AND ( fs_lot.lot_article = fs_stock.st_seq_compt ) )
        GROUP BY lot_article   )  )  AS Qte_lot,
    ( fs_stock.st_q_physique - ( ( SELECT SUM( lot_q_en_m_f )  AS Q
                                   FROM fs_lot
                                   WHERE ( ( ( ( lot_statut =  1) AND ( lot_genre =  'P') ) AND ( lot_mag =  'S') ) AND ( fs_lot.lot_article = fs_stock.st_seq_compt ) )
                                   GROUP BY lot_article   )  ) )  AS en_quarantaine,
    fs_stock.st_dernier_mvt AS dernier_mvt,
    fs_stock.st_q_cmdee AS en_fab,
    fs_stock.st_q_reservee AS en_cde,
    fs_stock.st_q_min_de_reappro AS q_min_reapro,
    fs_stock.st_niveau_de_reappro AS q_niveau_reapro,
    fs_stock.st_niveau_de_secu AS stk_secu,
    ( ( ( SELECT SUM( lot_q_en_m_f )  AS Q
          FROM fs_lot
          WHERE ( ( ( ( lot_statut =  1) AND ( lot_genre =  'P') ) AND ( lot_mag =  'S') ) AND ( fs_lot.lot_article = fs_stock.st_seq_compt ) )
          GROUP BY lot_article   )  ) - fs_stock.st_niveau_de_secu )  AS stk_moins_secu,
    ( ( ( ( SELECT SUM( lot_q_en_m_f )  AS Q
            FROM fs_lot
            WHERE ( ( ( ( lot_statut =  1) AND ( lot_genre =  'P') ) AND ( lot_mag =  'S') ) AND ( fs_lot.lot_article = fs_stock.st_seq_compt ) )
            GROUP BY lot_article   )  ) + fs_stock.st_q_cmdee ) - fs_stock.st_q_reservee )  AS potentielle
FROM
    (
        fs_stock
            LEFT OUTER JOIN
            fc_references
        ON fs_stock.st_client = fc_references.fo_reference
        )
        LEFT OUTER JOIN
    fd_types_prod
    ON fd_types_prod.typpro_code = fs_stock.st_type
WHERE
    (
        fs_stock.st_genre = 'P'
            AND	fs_stock.st_inactif = 0
            AND	fs_stock.st_client NOT IN ('ZZZ')
            AND	fs_stock.st_art_date_crea >= current_date
        )
ORDER BY
    st_seq_compt DESC;

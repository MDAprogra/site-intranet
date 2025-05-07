-- TODO : Ajouter les données transports

SELECT d.BO_NO                                  AS numero_bon_livraison, --OK
       Coalesce(d.BO_NO_DOSSIER, '')            AS numero_dossier, --OK
       d.BO_REF_DE_LIVRAISON                    AS reference_livraison, --OK
       BO_CODE_REP                              AS code_representant, --OK
       d.BO_SITE                                AS code_site, --OK
       d.BO_GENRE_DE_LIVRAISON                  AS genre_livraison, --OK
       d.BO_TYPE_LIVRAISON                      AS type_livraison, --OK
       d.BO_STATUT_LIVRAISON                    AS statut_livraison, --OK
       Round(d.BO_QUANT_LIVREE_TOTAL)           AS QaLivrer, --OK
       Cast(dMvt.MVT_LIVRE AS DOUBLE PRECISION) AS QLivree, --OK
       Cast(MVT_Q AS DOUBLE PRECISION)          AS QCommandee, --OK
       d.BO_ADRESSE_1                           AS adresse_livraison_1, --OK
       d.BO_ADRESSE_2                           AS adresse_livraison_2, --OK
       d.BO_ADRESSE_3                           AS adresse_livraison_3, --OK
       d.BO_CODE_POSTAL                         AS code_postal_livraison, --OK
       d.BO_VILLE                               AS ville_livraison, --OK
       d.BO_ADFACT_ADRESSE_1                    AS adresse_facturation_1, --OK
       d.BO_ADFACT_ADRESSE_2                    AS adresse_facturation_2, --OK
       d.BO_ADFACT_ADRESSE_3                    AS adresse_facturation_3, --OK
       d.BO_ADFACT_CODE_POST                    AS code_postal_facturation, --OK
       d.BO_ADFACT_VILLE                        AS ville_facturation, --OK
       d.BO_DATE_SOUHAITEE                      AS date_souhaitee_livraison, --OK
       d.BO_DATE_IMPERATIVE                     AS date_imperative_livraison, --OK
       d.BO_DATE_REELLE                         AS date_reelle_livraison, --OK
       CONCAT(
           d.BO_DESCRIPTIF_1, '<br>',
           d.BO_DESCRIPTIF_2, '<br>',
           d.BO_DESCRIPTIF_3, '<br>',
           d.BO_DESCRIPTIF_4, '<br>',
           d.BO_DESCRIPTIF_5
       )                                        AS descriptif_livraison, --OK
       dCli.FO_REFERENCE                    AS reference_client --OK
FROM ((((FF_LIVRAISON d LEFT JOIN FC_GESTION_CLIENT_SITE dCliSite
    ON dCliSite.FOSITE_SITE = '' AND dCliSite.FOSITE_REFERENCE = d.BO_REF)
    LEFT JOIN FC_REFERENCES dCli ON dCli.FO_REFERENCE = d.BO_REF LEFT JOIN FS_LIGNE_MVT dMvt
        ON dMvt.MVT_TYP_MVT = 3 AND dMvt.MVT_IDENTIFIANT_LIGNE_CMD = d.BO_NO_DOSSIER AND
           d.BO_TYPE_LIVRAISON = 1) LEFT JOIN FS_STOCK dMvtStk
       ON dMvtStk.ST_SEQ_COMPT = dMvt.MVT_SEQ_ARTICLE) LEFT JOIN FS_TARIF_PF dMvtStkTar
      ON dMvtStk.ST_CODE_TARIF <> '' AND dMvtStkTar.PF_CODE = dMvtStk.ST_CODE_TARIF)
         LEFT JOIN FF_CODE_PAYS_PAR_CP dpays ON dpays.PTVA_CODE = d.BO_PAYS
WHERE ((d.bo_date_reelle >= CURRENT_DATE))
ORDER BY BO_NO desc;

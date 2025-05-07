SELECT
    CASE fo_type_c_f_p WHEN 'C' THEN 'Client' WHEN 'P' THEN 'Prospect' WHEN 'F' THEN 'Fournisseur' END AS Typ ,
    fo_reference,
    fo_nom_1,
    fo_cpte_gene,
    fo_date_crea AS date_creation ,
    (SELECT
         fosite_rep_code
     FROM
         fc_gestion_client_site
     WHERE
         fosite_reference=fo_reference
       AND
         fosite_site =''
    ) AS gest ,
    fo_code_maison_mere,
    REPLACE(REPLACE(REPLACE(fo_adresse_1,CHR(13),'##'),CHR(10),'##'),CHR(9),'     ') AS fo_adresse_1,
    REPLACE(REPLACE(REPLACE(fo_code_postal,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_code_postal ,
    REPLACE(REPLACE(REPLACE( fo_ville ,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_ville,
    REPLACE(REPLACE(REPLACE( fo_telephone ,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_telephone,
    fo_devise,
    CONCAT(fo_lettre_tva,REPLACE(REPLACE(REPLACE( fo_no_tva ,CHR(13),''),CHR(10),''),CHR(9),'     ')) AS code_tva,
    fo_pays ,
    REPLACE(REPLACE(REPLACE( fo_adress_email ,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_adress_email,
    CASE fo_site WHEN 'I' THEN CONCAT('Interfas','|','Interfas Nord') WHEN 'L' THEN 'ILD' WHEN 'N' THEN 'Interfas Nord' ELSE CONCAT('Interfas','|','Interfas Nord') END AS site,
    fo_siret AS siret,
    (SELECT  fosite_niveau_1_message FROM fc_gestion_client_site WHERE fo_reference=fosite_reference LIMIT 1) AS msg,
    CASE (SELECT  fosite_niveau_1_action FROM fc_gestion_client_site WHERE fo_reference=fosite_reference LIMIT 1) WHEN 0 THEN '' ELSE 'Bloqué' END AS statut,
    fr.fo_seq AS sequentiel

FROM
    fc_references fr
WHERE
    fo_inactif ='0'
  AND
    (
        fo_date_modif >=current_date OR fo_date_crea >=current_date OR fo_date_maj >=current_date
        )
  AND
    fo_reference <>'*'
  AND
    fo_nom_1 <>''
  AND
    fo_nom_1 IS NOT NULL
  AND
    fo_reference NOT IN ('ZZZZZZZZ','ZZZ')
  AND
    fo_type_c_f_p IN('C','P','F')
UNION
SELECT
    'Fournisseur' AS Typ ,
    fo_reference,
    fo_nom_1,
    fo_cpte_gene,
    fo_date_crea AS date_creation ,
    't.mullinghausen' AS gest ,
    fo_code_maison_mere,
    REPLACE(REPLACE(REPLACE(fo_adresse_1,CHR(13),'##'),CHR(10),'##'),CHR(9),'     ') AS fo_adresse_1,
    REPLACE(REPLACE(REPLACE(fo_code_postal,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_code_postal ,
    REPLACE(REPLACE(REPLACE( fo_ville ,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_ville,
    REPLACE(REPLACE(REPLACE( fo_telephone ,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_telephone,
    fo_devise,
    CONCAT(fo_lettre_tva,REPLACE(REPLACE(REPLACE( fo_no_tva ,CHR(13),''),CHR(10),''),CHR(9),'     ')) AS code_tva,
    fo_pays ,
    REPLACE(REPLACE(REPLACE( fo_adress_email ,CHR(13),''),CHR(10),''),CHR(9),'     ') AS fo_adress_email,
    CASE fo_site WHEN 'I' THEN CONCAT('Interfas','|','Interfas Nord') WHEN 'L' THEN 'ILD' WHEN 'N' THEN 'Interfas Nord' ELSE CONCAT('Interfas','|','Interfas Nord') END AS site,
    '' AS siret
    ,
    (SELECT  fosite_niveau_1_message FROM fc_gestion_client_site WHERE fo_reference=fosite_reference LIMIT 1) AS msg,
    CASE (SELECT  fosite_niveau_1_action FROM fc_gestion_client_site WHERE fo_reference=fosite_reference LIMIT 1) WHEN 0 THEN '' ELSE 'Bloqué' END AS statut,
    fr.fo_seq AS sequentiel
FROM
    fc_references fr
WHERE
    fo_inactif ='0'
  AND
    fo_reference <>'*'
  AND
    (
        fo_date_modif >=current_date OR fo_date_crea >=current_date OR fo_date_maj >=current_date
        )
  AND
    fo_nom_1 <>''
  AND
    fo_nom_1 IS NOT NULL
  AND
    fo_reference NOT IN ('ZZZZZZZZ','ZZZ')
  AND
    fo_type_c_f_p ='F';

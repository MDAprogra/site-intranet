SELECT flm.mgdev_coduniq                                  AS devis,
       fed.endv_cclient                                   AS client,
       fed.endv_date                                      AS ddate,
       endv_identif                                       AS libelle,
       (SELECT fed3.eldv_ftx_ouv * 10
        FROM fd_elem_devis fed3
        WHERE fed3.eldv_endvuniq = fed.endv_coduniq
        LIMIT 1)                                          AS largeur_produit,
       (SELECT fed3.eldv_fty_ouv * 10
        FROM fd_elem_devis fed3
        WHERE fed3.eldv_endvuniq = fed.endv_coduniq
        LIMIT 1)                                          AS hauteur_produit,
       (SELECT tp.typpro_lib
        FROM fd_types_prod AS tp
        WHERE tp.typpro_code = endv_cat_prod)             AS typ_prod,
       endv_quant                                         AS quantite,
       endv_rmq                                           AS remarques,
       SUM(flm.mgdev_montant_tx1)                         AS total,
       SUM(flm.mgdev_montant_tx1 / fed.endv_quant * 1000) AS b100_au_1000,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '10'
          AND flm.mgdev_coduniq = mgdev_coduniq)          AS matière,
       (SELECT CONCAT(cat_info_fournisseur, ' (', cat_ref, ') : ', cat_fournisseur, CHR(13), 'Famille: ', cat_famille,
                      CHR(13), 'Laize: ', cat_format_x * 10, CHR(13), 'Type: ', cat_type, CHR(13), 'Couleur: ',
                      cat_coulstd, CHR(13), 'Grammage: ', cat_grammage, 'g', CHR(13), 'Epaisseur: ', cat_epaisseur, 'µ')
        FROM fs_catalogue AS fc

        WHERE cat_compt = (SELECT eldv_pap_seq_impo
                           FROM fd_elem_devis AS fed2
                           WHERE fed2.eldv_endvuniq = fed.endv_coduniq
                           LIMIT 1)
        LIMIT 1)                                          AS ref_matière,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '42'
          AND flm.mgdev_coduniq = mgdev_coduniq)          AS roulage,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '22'
          AND flm.mgdev_coduniq = mgdev_coduniq)          AS Outils,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '23'
          AND flm.mgdev_coduniq = mgdev_coduniq)          AS encre,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '29'
          AND flm.mgdev_coduniq = mgdev_coduniq)          AS emballage,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '30'
          AND flm.mgdev_coduniq = mgdev_coduniq)          AS pao,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '41'
          AND flm.mgdev_coduniq = mgdev_coduniq)          AS calage,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '50'
          AND flm.mgdev_coduniq = mgdev_coduniq)          AS finition,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '59'
          AND flm.mgdev_coduniq = mgdev_coduniq)          AS conditionnement,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '90'
          AND flm.mgdev_coduniq = mgdev_coduniq)          AS divers,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '91'
          AND flm.mgdev_coduniq = mgdev_coduniq)          AS transport,
       (SELECT CASE WHEN fed4.eldv_pxkg_pap = 0 THEN 0 ELSE mgdev_montant_tx1 / fed4.eldv_pxkg_pap END
        FROM fd_liste_marges,
             fd_elem_devis fed4
        WHERE mgdev_code_marge = '10'
          AND flm.mgdev_coduniq = mgdev_coduniq
          AND fed4.eldv_endvuniq = fed.endv_coduniq
        LIMIT 1)                                          AS qte_mat,
       CASE endv_representant
           WHEN 'PSE' THEN 'p.seronde'
           WHEN 'LBD' THEN 'l.bagard'
           WHEN 'AI' THEN 'christian.linossier'
           WHEN 'JBY' THEN 'j.bounay'
           WHEN 'CBD' THEN 'c.barraud'
           WHEN 'CLR' THEN 'christian.linossier'
           WHEN 'CZI' THEN 'c.zampini'
           WHEN 'DRU' THEN 'd.roumieu'
           WHEN 'FBT' THEN 'f.brandt'
           WHEN 'JBS' THEN 'j.bes'
           WHEN 'JLM' THEN 'jl.micoud'
           WHEN 'TPT' THEN 't.persault'
           WHEN 'LDE' THEN 'l.delabroise'
           WHEN 'LMN' THEN 'l.marin'
           WHEN 'MEN' THEN 'm.eeckeman'
           WHEN 'OPE' THEN 'o.paturle'
           WHEN 'PDE' THEN 'p.descusse'
           WHEN 'PGN' THEN 'p.guerin'
           WHEN 'PMR' THEN 'p.monnier'
           WHEN 'PPD' THEN 'p.primard'
           WHEN 'SAB' THEN 's.aitbraham'
           WHEN 'SMN' THEN 's.mangin'
           WHEN 'VGD' THEN 'vincent.guillard'
           WHEN 'YLP' THEN 'y.lepenhuizic'
           WHEN 'JJE' THEN 'j.jeanne'
           WHEN 'MBE' THEN 'm.borniche'
           WHEN 'ELE' THEN 'e.lejalle'
           ELSE 'm.borniche' END                          AS rep,
       CASE fed.endv_init_dev
           WHEN 'DGY' THEN 'david.giry@interfas.fr'
           WHEN 'DBD' THEN 'david.briand@interfas.fr'
           WHEN 'IAE' THEN 'i.alouache@interfas.fr'
           WHEN 'CCE' THEN 'c.crisante@interfas.fr'
           WHEN 'MRI' THEN 'm.rabai@interfas.fr'
           WHEN 'TSE' THEN 'tristan.silvestre@interfas.fr'
           WHEN 'CLR' THEN 'christian.linossier@interfas.fr'
           ELSE '' END                                    AS deviseur
FROM fd_liste_marges AS flm,
     fd_entete_devi AS fed
WHERE fed.endv_date >= current_date
  AND fed.endv_no_dossier = ''
  AND fed.endv_coduniq = flm.mgdev_coduniq
  AND endv_quant <> 0
GROUP BY flm.mgdev_coduniq,
         endv_cclient,
         endv_identif,
         endv_rmq,
         endv_representant,
         typ_prod,
         endv_cat_prod,
         quantite,
         endv_coduniq,
         endv_date,
         endv_init_dev
UNION

SELECT flm.mgdev_coduniq                                    AS devis,
       fed.endv_cclient                                     AS client,
       fed.endv_date                                        AS ddate,
       endv_identif                                         AS libelle,
       (SELECT fed3.eldv_ftx_ouv * 10
        FROM fd_elem_devis fed3
        WHERE fed3.eldv_endvuniq = fed.endv_coduniq
        LIMIT 1)                                            AS largeur_produit,
       (SELECT fed3.eldv_fty_ouv * 10
        FROM fd_elem_devis fed3
        WHERE fed3.eldv_endvuniq = fed.endv_coduniq
        LIMIT 1)                                            AS hauteur_produit,
       (SELECT tp.typpro_lib
        FROM fd_types_prod AS tp
        WHERE tp.typpro_code = endv_cat_prod)               AS typ_prod,
       endv_quant_2                                         AS quantite,
       endv_rmq                                             AS remarques,
       SUM(flm.mgdev_montant_tx1)                           AS total,
       SUM(flm.mgdev_montant_tx1 / fed.endv_quant_2 * 1000) AS b100_au_1000,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '10'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS matière,
       (SELECT CONCAT(cat_info_fournisseur, ' (', cat_ref, ') : ', cat_fournisseur, CHR(13), 'Famille: ', cat_famille,
                      CHR(13), 'Laize: ', cat_format_x * 10, CHR(13), 'Type: ', cat_type, CHR(13), 'Couleur: ',
                      cat_coulstd, CHR(13), 'Grammage: ', cat_grammage, 'g', CHR(13), 'Epaisseur: ', cat_epaisseur, 'µ')
        FROM fs_catalogue AS fc
        WHERE cat_compt = (SELECT eldv_pap_seq_impo
                           FROM fd_elem_devis AS fed2
                           WHERE fed2.eldv_endvuniq = fed.endv_coduniq
                           LIMIT 1)
        LIMIT 1)                                            AS ref_matière,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '42'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS roulage,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '22'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS Outils,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '23'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS encre,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '29'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS emballage,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '30'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS pao,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '41'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS calage,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '50'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS finition,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '59'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS conditionnement,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '90'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS divers,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '91'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS transport,
       (SELECT CASE WHEN fed4.eldv_pxkg_pap = 0 THEN 0 ELSE mgdev_montant_tx1 / fed4.eldv_pxkg_pap END
        FROM fd_liste_marges,
             fd_elem_devis fed4
        WHERE mgdev_code_marge = '10'
          AND flm.mgdev_coduniq = mgdev_coduniq
          AND fed4.eldv_endvuniq = fed.endv_coduniq
        LIMIT 1)                                            AS qte_mat,
       CASE endv_representant
           WHEN 'PSE' THEN 'p.seronde'
           WHEN 'LBD' THEN 'l.bagard'
           WHEN 'AI' THEN 'christian.linossier'
           WHEN 'JBY' THEN 'j.bounay'
           WHEN 'CBD' THEN 'c.barraud'
           WHEN 'CLR' THEN 'christian.linossier'
           WHEN 'CZI' THEN 'c.zampini'
           WHEN 'DRU' THEN 'd.roumieu'
           WHEN 'FBT' THEN 'f.brandt'
           WHEN 'JBS' THEN 'j.bes'
           WHEN 'JLM' THEN 'jl.micoud'
           WHEN 'TPT' THEN 't.persault'
           WHEN 'LDE' THEN 'l.delabroise'
           WHEN 'LMN' THEN 'l.marin'
           WHEN 'MEN' THEN 'm.eeckeman'
           WHEN 'OPE' THEN 'o.paturle'
           WHEN 'PDE' THEN 'p.descusse'
           WHEN 'PGN' THEN 'p.guerin'
           WHEN 'PMR' THEN 'p.monnier'
           WHEN 'PPD' THEN 'p.primard'
           WHEN 'SAB' THEN 's.aitbraham'
           WHEN 'SMN' THEN 's.mangin'
           WHEN 'VGD' THEN 'vincent.guillard'
           WHEN 'YLP' THEN 'y.lepenhuizic'
           WHEN 'JJE' THEN 'j.jeanne'
           WHEN 'MBE' THEN 'm.borniche'
           WHEN 'ELE' THEN 'e.lejalle'
           ELSE 'm.borniche' END                            AS rep,
       CASE fed.endv_init_dev
           WHEN 'DGY' THEN 'david.giry@interfas.fr'
           WHEN 'DBD' THEN 'david.briand@interfas.fr'
           WHEN 'IAE' THEN 'i.alouache@interfas.fr'
           WHEN 'CCE' THEN 'c.crisante@interfas.fr'
           WHEN 'MRI' THEN 'm.rabai@interfas.fr'
           WHEN 'TSE' THEN 'tristan.silvestre@interfas.fr'
           WHEN 'CLR' THEN 'christian.linossier@interfas.fr'
           ELSE '' END                                      AS deviseur
FROM fd_liste_marges AS flm,
     fd_entete_devi AS fed
WHERE fed.endv_date >= current_date
  AND fed.endv_no_dossier = ''
  AND CONCAT(fed.endv_coduniq
          , '/2') = flm.mgdev_coduniq
  AND endv_quant_2 <> 0
GROUP BY flm.mgdev_coduniq,
         endv_cclient,
         endv_identif,
         endv_rmq,
         endv_representant,
         typ_prod,
         endv_cat_prod,
         quantite,
         endv_coduniq,
         endv_date,
         endv_init_dev
UNION
SELECT flm.mgdev_coduniq                                    AS devis,
       fed.endv_cclient                                     AS client,
       fed.endv_date                                        AS ddate,
       endv_identif                                         AS libelle,
       (SELECT fed3.eldv_ftx_ouv * 10
        FROM fd_elem_devis fed3
        WHERE fed3.eldv_endvuniq = fed.endv_coduniq
        LIMIT 1)                                            AS largeur_produit,
       (SELECT fed3.eldv_fty_ouv * 10
        FROM fd_elem_devis fed3
        WHERE fed3.eldv_endvuniq = fed.endv_coduniq
        LIMIT 1)                                            AS hauteur_produit,
       (SELECT tp.typpro_lib
        FROM fd_types_prod AS tp
        WHERE tp.typpro_code = endv_cat_prod)               AS typ_prod,
       endv_quant_2                                         AS quantite,
       endv_rmq                                             AS remarques,
       SUM(flm.mgdev_montant_tx1)                           AS total,
       SUM(flm.mgdev_montant_tx1 / fed.endv_quant_2 * 1000) AS b100_au_1000,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '10'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS matière,
       (SELECT CONCAT(cat_info_fournisseur, ' (', cat_ref, ') : ', cat_fournisseur, CHR(13), 'Famille: ', cat_famille,
                      CHR(13), 'Laize: ', cat_format_x * 10, CHR(13), 'Type: ', cat_type, CHR(13), 'Couleur: ',
                      cat_coulstd, CHR(13), 'Grammage: ', cat_grammage, 'g', CHR(13), 'Epaisseur: ', cat_epaisseur, 'µ')
        FROM fs_catalogue AS fc
        WHERE cat_compt = (SELECT eldv_pap_seq_impo
                           FROM fd_elem_devis AS fed2
                           WHERE fed2.eldv_endvuniq = fed.endv_coduniq
                           LIMIT 1)
        LIMIT 1)                                            AS ref_matière,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '42'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS roulage,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '22'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS Outils,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '23'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS encre,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '29'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS emballage,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '30'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS pao,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '41'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS calage,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '50'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS finition,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '59'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS conditionnement,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '90'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS divers,
       (SELECT mgdev_montant_tx1
        FROM fd_liste_marges
        WHERE mgdev_code_marge = '91'
          AND flm.mgdev_coduniq = mgdev_coduniq)            AS transport,
       (SELECT CASE WHEN fed4.eldv_pxkg_pap = 0 THEN 0 ELSE mgdev_montant_tx1 / fed4.eldv_pxkg_pap END
        FROM fd_liste_marges,
             fd_elem_devis fed4
        WHERE mgdev_code_marge = '10'
          AND flm.mgdev_coduniq = mgdev_coduniq
          AND fed4.eldv_endvuniq = fed.endv_coduniq
        LIMIT 1)                                            AS qte_mat,
       CASE endv_representant
           WHEN 'PSE' THEN 'p.seronde'
           WHEN 'LBD' THEN 'l.bagard'
           WHEN 'AI' THEN 'christian.linossier'
           WHEN 'JBY' THEN 'j.bounay'
           WHEN 'CBD' THEN 'c.barraud'
           WHEN 'CLR' THEN 'christian.linossier'
           WHEN 'CZI' THEN 'c.zampini'
           WHEN 'DRU' THEN 'd.roumieu'
           WHEN 'FBT' THEN 'f.brandt'
           WHEN 'JBS' THEN 'j.bes'
           WHEN 'JLM' THEN 'jl.micoud'
           WHEN 'TPT' THEN 't.persault'
           WHEN 'LDE' THEN 'l.delabroise'
           WHEN 'LMN' THEN 'l.marin'
           WHEN 'MEN' THEN 'm.eeckeman'
           WHEN 'OPE' THEN 'o.paturle'
           WHEN 'PDE' THEN 'p.descusse'
           WHEN 'PGN' THEN 'p.guerin'
           WHEN 'PMR' THEN 'p.monnier'
           WHEN 'PPD' THEN 'p.primard'
           WHEN 'SAB' THEN 's.aitbraham'
           WHEN 'SMN' THEN 's.mangin'
           WHEN 'VGD' THEN 'vincent.guillard'
           WHEN 'YLP' THEN 'y.lepenhuizic'
           WHEN 'JJE' THEN 'j.jeanne'
           WHEN 'MBE' THEN 'm.borniche'
           WHEN 'ELE' THEN 'e.lejalle'
           ELSE 'm.borniche' END                            AS rep,
       CASE fed.endv_init_dev
           WHEN 'DGY' THEN 'david.giry@interfas.fr'
           WHEN 'DBD' THEN 'david.briand@interfas.fr'
           WHEN 'IAE' THEN 'i.alouache@interfas.fr'
           WHEN 'CCE' THEN 'c.crisante@interfas.fr'
           WHEN 'MRI' THEN 'm.rabai@interfas.fr'
           WHEN 'TSE' THEN 'tristan.silvestre@interfas.fr'
           WHEN 'CLR' THEN 'christian.linossier@interfas.fr'
           ELSE '' END                                      AS deviseur
FROM fd_liste_marges AS flm,
     fd_entete_devi AS fed
WHERE fed.endv_date >= current_date
  AND fed.endv_no_dossier = ''
  AND CONCAT(fed.endv_coduniq
          , '/2') = flm.mgdev_coduniq
  AND endv_quant_2 <> 0
GROUP BY flm.mgdev_coduniq,
         endv_cclient,
         endv_identif,
         endv_rmq,
         endv_representant,
         typ_prod,
         endv_cat_prod,
         quantite,
         endv_coduniq,
         endv_date,
         endv_init_dev

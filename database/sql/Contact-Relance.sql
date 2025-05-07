SELECT DISTINCT
    frc.relcont_code AS Societe,
    rf.fo_cpte_gene AS fo_cpte_gene,
    ( ( SELECT STRING_AGG( f1.relcont_adpro_email ,  ';')  AS Expr1
        FROM fc_relation_contact f1
        WHERE ( UPPER( f1.relcont_prenom_nom  ) LIKE '%RELANCE%' AND ( f1.relcont_code = frc.relcont_code ) )  )  )  AS mail
FROM
    fc_relation_contact frc,
    fc_references rf
WHERE
    frc.relcont_code = rf.fo_reference
  AND
    (
        UPPER( frc.relcont_prenom_nom  )  LIKE '%RELANCE%'
            AND	frc.relcont_clifoupro = 'C'
            AND	frc.relcont_adpro_email <> ''
        )
ORDER BY
    Societe ASC;

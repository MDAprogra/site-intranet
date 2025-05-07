SELECT OPRE_SAL,
       OPRE_POSTE,
       OPRE_DATE,
       to_char(OPRE_H_DEBUT, 'HH24:MI') as "Heure départ",
       to_char(OPRE_H_FIN, 'HH24:MI')   as "Heure fin",
       OPRE_DUREE,
       OPRE_TAUX_1,
       OPRE_TAUX_2,
       OPRE_QUANTITE,
       OPRE_CODE_OP,
       OPRE_LIBELLE_OPE
FROM FP_OPERA_REEL
WHERE OPRE_DOSSIER = ''
  AND OPRE_DATE = ?
  AND OPRE_LIBELLE_OPE = 'Attente Matière Première';

<?php

namespace App\Http\Controllers\Indicateurs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class BepsController extends Controller
{
    public function AfficherBeps()
    {
        $v_BEPS = $this->GET_BEPS();

        return view('indicateurs.beps', [
            'v_BEPS' => $v_BEPS,
        ]);
    }
    public function AfficherCommandes()
    {
        $v_Commandes = $this->GET_Commandes();

        return view('indicateurs.beps', [
            'v_Commandes' => $v_Commandes,
        ]);
    }

     public function GET_BEPS(): Collection
     {
         $value = collect(DB::connection('pgsql')->select("SELECT st_site,
       extract(day from (now() - ST_DERNIER_MVT)) as days_last_mvt,
       FO_NOM_1,
       FO_REP_CODE,
       FS_STOCK.ST_SEQ_COMPT,
       FS_STOCK.ST_CLIENT,
       FS_STOCK.ST_MODELE,
       FS_STOCK.ST_VERSION_MODELE,
       FS_STOCK.ST_ART_REF_CLIENT,
       FS_STOCK.ST_LIB_1_CONSO,
       FS_STOCK.ST_Q_PHYSIQUE,
       FS_STOCK.ST_PX_VENTE_LE_1000,
       FS_STOCK.ST_DERNIER_MVT,
       FS_STOCK.ST_ART_FAMILLE,
       FS_STOCK.ST_ART_SFAMILLE,
       ST_ART_DEVIS_LIE,
       FS_STOCK.ST_TYPE,
       FS_STOCK.ST_PMP,
       FS_STOCK.ST_DERNIER_PRIX_ACHAT,
       ST_PMP * ST_Q_PHYSIQUE / 1000              AS Val_beps,
       fd_types_prod.typpro_lib                   as typpro_lib
FROM FS_STOCK
         left join fc_references on ST_CLIENT = FO_REFERENCE

         LEFT JOIN fd_types_prod
                   ON fd_types_prod.typpro_code = ST_TYPE

WHERE ST_GENRE = 'P'
  AND FS_STOCK.ST_Q_PHYSIQUE <> 0"));

         return $value;
     }

     public function GET_Commandes()
     {
            $value = collect(DB::connection('pgsql')->select("SELECT rep_code,
       mvt_date_mvt,
       rep_libelle,
       mvt_article,
       mvt_info_commande,
       mvt_q,
       mvt_px_unit,
       mvt_chiffre_affaires,
       B100,
       mvt_chiffre_affaires - B100 AS mvt_marge,
       mvt_site,
       mvt_solde,
       mvt_livre,
       mvt_fourn_cli,
       mvt_unit_pf,
       mvt_unit_px,
       typpro_lib
FROM (SELECT fs_ligne_mvt.mvt_no_cmd_reserv                       AS mvt_no_cmd_reserv,
             fs_ligne_mvt.mvt_date_mvt                            AS mvt_date_mvt,
             fs_ligne_mvt.mvt_genre                               AS mvt_genre,
             fs_ligne_mvt.mvt_typ_mvt                             AS mvt_typ_mvt,
             fs_ligne_mvt.mvt_seq_article                         AS mvt_seq_article,
             fs_ligne_mvt.mvt_article                             AS mvt_article,
             fs_ligne_mvt.mvt_info_commande                       AS mvt_info_commande,
             fs_ligne_mvt.mvt_q                                   AS mvt_q,
             fs_ligne_mvt.mvt_px_unit                             AS mvt_px_unit,
             fs_ligne_mvt.mvt_q * fs_ligne_mvt.mvt_px_unit / 1000 AS mvt_chiffre_affaires,
             CASE fs_ligne_mvt.mvt_unit_px
                 WHEN 1 THEN
                     CASE
                         WHEN (fs_ligne_mvt.mvt_unit_pf = 0) THEN (fs_ligne_mvt.mvt_info_commande) * fs_ligne_mvt.mvt_q
                         ELSE ((fs_ligne_mvt.mvt_info_commande) * fs_ligne_mvt.mvt_q) / fs_ligne_mvt.mvt_unit_pf
                         END
                 WHEN 0 THEN (fs_ligne_mvt.mvt_info_commande / 1000) * fs_ligne_mvt.mvt_q
                 END                                              AS B100,
             fs_ligne_mvt.mvt_site                                AS mvt_site,
             fs_ligne_mvt.mvt_solde                               AS mvt_solde,
             fs_ligne_mvt.mvt_livre                               AS mvt_livre,
             fs_ligne_mvt.mvt_fourn_cli                           AS mvt_fourn_cli,
             fs_ligne_mvt.mvt_unit_pf                             AS mvt_unit_pf,
             fs_ligne_mvt.mvt_unit_px                             AS mvt_unit_px,
             fs_ligne_mvt.mvt_seq_compt                           AS mvt_seq_compt,
             fs_ligne_mvt.mvt_identifiant_ligne_cmd               AS mvt_identifiant_ligne_cmd,
             fs_ligne_mvt.mvt_code_tva                            AS mvt_code_tva,
             fs_ligne_mvt.mvt_date_reception                      AS mvt_date_reception,
             fs_ligne_mvt.mvt_date_ech                            AS mvt_date_ech
      FROM fs_ligne_mvt
      WHERE fs_ligne_mvt.mvt_date_mvt >= '20220101'
        AND fs_ligne_mvt.mvt_genre = 'P'
        AND fs_ligne_mvt.mvt_typ_mvt = 3) AS commandes

         LEFT JOIN (SELECT fd_ent_cmde.ent_no      AS ent_no,
                           fd_ent_cmde.ent_site    AS ent_site,
                           fd_ent_cmde.ent_rep_int AS ent_rep_int

                    FROM fd_ent_cmde
                    where fd_ent_cmde.ent_no <> '') AS entetes_commandes
                   ON commandes.mvt_no_cmd_reserv = entetes_commandes.ent_no
                       AND commandes.mvt_site = entetes_commandes.ent_site

         LEFT JOIN (SELECT fc_representants.rep_code    as rep_code,
                           fc_representants.rep_libelle as rep_libelle
                    FROM fc_representants) AS representants
                   ON representants.rep_code = entetes_commandes.ent_rep_int

         LEFT JOIN (SELECT fs_stock.st_seq_compt as st_seq_compt,
                           fs_stock.st_type      as st_type
                    FROM fs_stock) AS stock
                   ON stock.st_seq_compt = commandes.mvt_seq_article

         LEFT JOIN (SELECT fd_types_prod.typpro_code as typpro_code,
                           fd_types_prod.typpro_lib  as typpro_lib
                    FROM fd_types_prod) AS types_prod
                   ON types_prod.typpro_code = stock.st_type

order by mvt_date_mvt desc"));

        return $value;
     }

}

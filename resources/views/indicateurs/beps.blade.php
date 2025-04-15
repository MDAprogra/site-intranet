<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Données BEPS</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<h1>Données BEPS</h1>

@if ($v_BEPS->isEmpty())
    <p>Aucune donnée BEPS à afficher.</p>
@else
    <table>
        <thead>
        <tr>
            <th>Site</th>
            <th>Jours Dernier Mouvement</th>
            <th>Nom Fournisseur</th>
            <th>Code Représentant Fournisseur</th>
            <th>Séquence Comptable</th>
            <th>Client</th>
            <th>Modèle</th>
            <th>Version Modèle</th>
            <th>Référence Client Article</th>
            <th>Libellé Article</th>
            <th>Quantité Physique</th>
            <th>Prix de Vente / 1000</th>
            <th>Dernier Mouvement</th>
            <th>Famille Article</th>
            <th>Sous-Famille Article</th>
            <th>Devis Lié</th>
            <th>Type</th>
            <th>PMP</th>
            <th>Dernier Prix d'Achat</th>
            <th>Valeur BEPS</th>
            <th>Type Produit</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($v_BEPS as $beps)
            <tr>
                <td>{{ $beps->st_site }}</td>
                <td>{{ $beps->days_last_mvt }}</td>
                <td>{{ $beps->fo_nom_1 }}</td>
                <td>{{ $beps->fo_rep_code }}</td>
                <td>{{ $beps->st_seq_compt }}</td>
                <td>{{ $beps->st_client }}</td>
                <td>{{ $beps->st_modele }}</td>
                <td>{{ $beps->st_version_modele }}</td>
                <td>{{ $beps->st_art_ref_client }}</td>
                <td>{{ $beps->st_lib_1_conso }}</td>
                <td>{{ $beps->st_q_physique }}</td>
                <td>{{ $beps->st_px_vente_le_1000 }}</td>
                <td>{{ $beps->st_dernier_mvt }}</td>
                <td>{{ $beps->st_art_famille }}</td>
                <td>{{ $beps->st_art_sfamille }}</td>
                <td>{{ $beps->st_art_devis_lie }}</td>
                <td>{{ $beps->st_type }}</td>
                <td>{{ $beps->st_pmp }}</td>
                <td>{{ $beps->st_dernier_prix_achat }}</td>
                <td>{{ number_format($beps->val_beps, 2, ',', ' ') }}</td>
                <td>{{ $beps->typpro_lib }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

</body>
</html>
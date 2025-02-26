<div class="overflow-x-auto">
    <div>
        <h1 class="font-extrabold text-2xl">Chiffre du mois précedent {{$mois}}</h1>
    </div>
    <span class="font-bold text-1xl">Total : {{$nombreDeDevisMensuel}}</span>
    <div>
        <h1 class="font-extrabold text-2xl">Chiffre de la semaine précedente ({{$semaine}})</h1>
    </div>
    <span class="font-bold text-1xl">Total : {{$nombreDeDevis}}</span>
    <div>
        <ul>
            @if (!empty($devisParSemaineParDeviseur))
                @foreach ($devisParSemaineParDeviseur as $row)
                    <li>{{ $row->endv_init_dev }} → {{ $row->nombre }}</li>
                @endforeach
            @else
                <li>Aucun devis trouvé pour la semaine précédente.</li>
            @endif
        </ul>
    </div>
</div>

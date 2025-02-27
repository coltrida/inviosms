<x-layouts.stile>

<div class="bg-body-tertiary p-5 rounded">
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">cap</th>
            <th scope="col">citta</th>
            <th scope="col">cell</th>
        </tr>
        </thead>
        <tbody>
        @foreach($clients as $client)
        <tr>
            <td>{{$client->cap}}</td>
            <td>{{$client->citta}}</td>
            <td>{{$client->cellulare}}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

    tot Clienti: {{$totClients}} <br>
    tot cellulari: {{$totCell}} <br>
    verifica cell: {{$clients->sum('cellulare')}} <br>
    tot fissi: {{$totFissi}} <br>
</div>

</x-layouts.stile>

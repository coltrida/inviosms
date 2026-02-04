<div>
    @if($visualizzaBool)

        <h2 class="mt-4">
            <button wire:click="esportaClient" class="btn btn-warning btn-sm">
                esporta - ( {{count($clientsAsl)}} )
            </button>
        </h2>

        <table class="table table-striped">
            <tbody>
            <tr>
                <td>Data fattura</td>
                <td>Nome paziente</td>
                <td>città</td>
                <td>filiale</td>
            </tr>
            @foreach($clientsAsl as $client)

                <tr>
                    <td>{{ \Carbon\Carbon::make($client->dataDocumento)->format('d-m-Y') }}</td>
                    <td>{{ $client->interm->fullname }}</td>
                    <td>{{ $client->interm->citta }}</td>
                    <td>{{ $client->interm->strutture->nome }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>

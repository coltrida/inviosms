<div>
    @if($visualizzaBool)

        <h2 class="mt-4">
            <button wire:click="esportaClient" class="btn btn-warning btn-sm">
                esporta - ( {{count($clients)}} )
            </button>
        </h2>

        <table class="table table-striped">
            <tbody>
            <tr>
                <td>Tipo</td>
                <td>Nome</td>
                <td>Telefono</td>
                <td>città</td>
                <td>cap</td>
            </tr>
            @foreach($clients as $client)
                <tr>
                    <td>{{ $client->tipo }}</td>
                    <td>{{ $client->fullname }}</td>
                    <td>{{ $client->telefono }}</td>
                    <td>{{ $client->citta }}</td>
                    <td>{{ $client->cap }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>

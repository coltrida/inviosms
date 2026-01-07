<div>
    @if($visualizzaBool)

        <h2 class="mt-4">
            <button wire:click="esportaClient" class="btn btn-warning btn-sm">
                appuntamenti - ( {{count($appointments)}} )
            </button>
        </h2>

        <table class="table table-striped">
            <tbody>
            <tr>
                <td>Tipo</td>
                <td>Nome</td>
                <td>Telefono</td>
                <td>Filiale</td>
                <td>note</td>
            </tr>
            @foreach($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->client?->tipo }}</td>
                    <td>{{ $appointment->client?->fullname }}</td>
                    <td>{{ $appointment->client?->telefono }}</td>
                    <td>{{ $appointment->client?->strutture->nome }}</td>
                    <td>{{ $appointment->note }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <h2 class="mt-4">
            <button wire:click="esportaClient" class="btn btn-warning btn-sm">
                telefonate - ( {{count($phones)}} )
            </button>
        </h2>

        <table class="table table-striped">
            <tbody>
            <tr>
                <td>Tipo</td>
                <td>Nome</td>
                <td>Telefono</td>
                <td>Filiale</td>
                <td>note</td>
            </tr>
            @foreach($phones as $phone)
                <tr>
                    <td>{{ $phone->client?->tipo }}</td>
                    <td>{{ $phone->client?->fullname }}</td>
                    <td>{{ $phone->client?->telefono }}</td>
                    <td>{{ $phone->client?->strutture->nome }}</td>
                    <td>{{ $phone->note }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @endif
</div>

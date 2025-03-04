<div>
    <div class="bg-body-tertiary p-5 rounded">
        <button wire:click="doppioni" class="btn btn-primary">doppioni</button>
        <button wire:click="senzaNumero" class="btn btn-primary">senza numero</button>
        <button wire:click="senzaStore" class="btn btn-primary">senza store</button>
        <button wire:click="clientiNoAppuntamento" class="btn btn-primary">CL senza appuntamenti futuri</button>
    </div>

    @if(isset($result))
        <button wire:click="esporta" class="btn btn-success">esporta</button>

        tot: {{count($result)}}
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">nome</th>
                <th scope="col">cognome</th>
                <th scope="col">telefono</th>
                <th scope="col">città</th>
                <th scope="col">indirizzo</th>
                <th scope="col">cap</th>
                <th scope="col">store</th>
            </tr>
            </thead>
            <tbody>
            @foreach($result as $client)
                <tr>
                    <td>{{$client->id}}</td>
                    <td>{{$client->nome}}</td>
                    <td>{{$client->cognome}}</td>
                    <td>{{$client->telefono}}</td>
                    <td>{{$client->citta}}</td>
                    <td>{{$client->indirizzo}}</td>
                    <td>{{$client->cap}}</td>
                    <td>{{$client->strutture ? $client->strutture->nome : null}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    @if(isset($filialiConClientiSenzaAppuntamenti))
        @foreach($filialiConClientiSenzaAppuntamenti as $filiale)
            <h2>{{$filiale->nome}}</h2>
            <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">tipo</th>
                <th scope="col">nome</th>
                <th scope="col">cognome</th>
                <th scope="col">telefono</th>
                <th scope="col">città</th>
                <th scope="col">indirizzo</th>
                <th scope="col">cap</th>
                <th scope="col">store</th>
            </tr>
            </thead>
            <tbody>
            @foreach($filiale->clients as $client)
                <tr>
                    <td>{{$client->id}}</td>
                    <td>{{$client->tipo}}</td>
                    <td>{{$client->nome}}</td>
                    <td>{{$client->cognome}}</td>
                    <td>{{$client->telefono}}</td>
                    <td>{{$client->citta}}</td>
                    <td>{{$client->indirizzo}}</td>
                    <td>{{$client->cap}}</td>
                    <td>{{$client->strutture ? $client->strutture->nome : null}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endforeach
    @endif
</div>

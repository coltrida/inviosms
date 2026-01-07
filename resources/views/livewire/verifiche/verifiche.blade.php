<div>
    <div class="bg-body-tertiary p-5 rounded">
        <button wire:click="doppioni" class="btn btn-primary">doppioni</button>
        <button wire:click="senzaNumero" class="btn btn-primary">senza numero</button>
        <button wire:click="senzaStore" class="btn btn-primary">senza store</button>
        <button wire:click="clientiNoAppuntamento" class="btn btn-primary">CL senza appuntamenti futuri</button>
        <button wire:click="contattoChiamatoConAppuntamento" class="btn btn-primary">contat chiamato con appunt</button>
        <button wire:click="clientiNoProforma" class="btn btn-primary">clienti no proforma</button>
        <button wire:click="leadConAppuntamenti" class="btn btn-primary">lead con appuntamenti</button>
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
        <h1 class="mt-4">Clienti Senza Appuntamenti</h1>
        <div class="mt-3">
            @foreach($filialiConClientiSenzaAppuntamenti as $filiale)
                <a href="#{{$filiale->id}}" class="btn btn-success btn-sm">
                    {{$filiale->nome}}
                </a>
            @endforeach
        </div>

        @foreach($filialiConClientiSenzaAppuntamenti as $filiale)
            <h2 class="mt-4" id="{{$filiale->id}}">
                {{$filiale->nome}} - ( {{count($filiale->clients)}} ) -
                <button wire:click="esportaClientiNoAppuntamenti({{$filiale->id}})" class="btn btn-warning btn-sm">
                    esporta
                </button>
            </h2>
            <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">tipo</th>
                <th scope="col">cognome</th>
                <th scope="col">nome</th>
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
                    <td>{{$client->cognome}}</td>
                    <td>{{$client->nome}}</td>
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

    @if(isset($filialiConContattoChiamatoConAppuntamento))
        <h1 class="mt-4">Contatti Chiamati e Con Appuntamenti presentati</h1>
        <div class="mt-3">
            @foreach($filialiConContattoChiamatoConAppuntamento as $filiale)
                <a href="#{{$filiale->id}}" class="btn btn-success btn-sm">
                    {{$filiale->nome}}
                </a>
            @endforeach
        </div>

        @foreach($filialiConContattoChiamatoConAppuntamento as $filiale)
            <h2 class="mt-4" id="{{$filiale->id}}">
                {{$filiale->nome}} - ( {{count($filiale->clients)}} ) -
                <button wire:click="esportaContattoChiamatoConAppuntamento({{$filiale->id}})" class="btn btn-warning btn-sm">
                    esporta
                </button>
            </h2>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">tipo</th>
                    <th scope="col">cognome</th>
                    <th scope="col">nome</th>
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
                        <td>{{$client->cognome}}</td>
                        <td>{{$client->nome}}</td>
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

    @if(isset($filialiConClientSenzaProforma))
        <h1 class="mt-4">Clienti Senza Proforma</h1>
        <div class="mt-3">
            @foreach($filialiConClientSenzaProforma as $filiale)
                <a href="#{{$filiale->id}}" class="btn btn-success btn-sm">
                    {{$filiale->nome}}
                </a>
            @endforeach
        </div>

        @foreach($filialiConClientSenzaProforma as $filiale)
            <h2 class="mt-4" id="{{$filiale->id}}">
                {{$filiale->nome}} - ( {{count($filiale->clients)}} ) -
                <button wire:click="esportaClientSenzaProforma({{$filiale->id}})" class="btn btn-warning btn-sm">
                    esporta
                </button>
            </h2>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">tipo</th>
                    <th scope="col">cognome</th>
                    <th scope="col">nome</th>
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
                        <td>{{$client->cognome}}</td>
                        <td>{{$client->nome}}</td>
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

    @if(isset($filialiConLeadConAppuntamenti))
        <h1 class="mt-4">Lead e Con Appuntamenti presentati</h1>
        <div class="mt-3">
            @foreach($filialiConLeadConAppuntamenti as $filiale)
                <a href="#{{$filiale->id}}" class="btn btn-success btn-sm">
                    {{$filiale->nome}}
                </a>
            @endforeach
        </div>

        @foreach($filialiConLeadConAppuntamenti as $filiale)
            <h2 class="mt-4" id="{{$filiale->id}}">
                {{$filiale->nome}} - ( {{count($filiale->clients)}} ) -
                <button wire:click="esportaLeadConAppuntamento({{$filiale->id}})" class="btn btn-warning btn-sm">
                    esporta
                </button>
            </h2>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">tipo</th>
                    <th scope="col">cognome</th>
                    <th scope="col">nome</th>
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
                        <td>{{$client->cognome}}</td>
                        <td>{{$client->nome}}</td>
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

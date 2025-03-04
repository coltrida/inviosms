<div>
    <div class="row g-3 align-items-center">
        <div class="col-auto">
            <label for="inputPassword6" class="col-form-label">ricerca</label>
        </div>
        <div class="col-auto">
            <input type="text" id="inputPassword6" wire:model.live="testoRicerca" class="form-control" aria-describedby="passwordHelpInline">
        </div>
        @if($testoRicerca)
            <div class="col-auto">
                <svg wire:click="eliminaRicerca" style="cursor: pointer; color: red" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                    <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
                </svg>
            </div>
        @endif
        <div class="col-auto">
            <button wire:click="esporta" type="button" class="btn btn-primary">Esporta</button>
        </div>
        <div class="col-auto">
            <button wire:click="resetRicerca" type="button" class="btn btn-warning">Reset</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered mt-3" style="width: 1400px">
        <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">
                <select wire:model.live="tipo" class="form-select" aria-label="Default select example">
                    <option selected value="">tipo</option>
                    <option>Lead</option>
                    <option>Cliente</option>
                    <option>Potenziale Cliente</option>
                    <option>Contatto Chiamato</option>
                </select>
            </th>
            <th scope="col">
                <select wire:model.live="store_id" class="form-select" aria-label="Default select example">
                    <option selected value="">store</option>
                    @foreach($listastore as $item)
                        <option value="{{$item->id}}">{{$item->nome}}</option>
                    @endforeach
                </select>
            </th>
            <th scope="col">Nome</th>
            <th scope="col">telefono</th>
            <th scope="col">indirizzo</th>
            <th scope="col">città</th>
            <th scope="col">cap</th>
            <th scope="col">Azioni</th>
        </tr>
        </thead>
        <tbody>
        @foreach($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td style="width: 350px">{{ $client->tipo }}</td>
                <td style="width: 370px">{{$client->strutture?->nome}} </td>
                <td style="width: 560px">{{ $client->fullname }}</td>
                <td style="width: 250px">{{$client->telefono}} </td>
                <td style="width: 690px">{{$client->indirizzo}} </td>
                <td style="width: 370px">{{$client->citta}} </td>
                <td style="width: 200px">{{$client->cap}} </td>
                <td class="d-flex justify-content-between align-items-center" style="width: 120px">
                    <a href="{{route('clienti.appuntamenti', $client->id)}}" class="btn btn-primary position-relative" title="appuntamenti">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journals" viewBox="0 0 16 16">
                            <path d="M5 0h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2 2 2 0 0 1-2 2H3a2 2 0 0 1-2-2h1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1H1a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v9a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1H3a2 2 0 0 1 2-2"/>
                            <path d="M1 6v-.5a.5.5 0 0 1 1 0V6h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V9h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 2.5v.5H.5a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1H2v-.5a.5.5 0 0 0-1 0"/>
                        </svg>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{$client->appointments_count}}
                        </span>
                    </a>

                    <a href="{{route('clienti.telefonate', $client->id)}}" class="btn btn-success position-relative" title="telefonate">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                        </svg>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{$client->phones_count}}
                        </span>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>

    {{$clients->links(data: ['scrollTo' => false])}}
</div>

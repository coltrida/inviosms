<x-layouts.stile>

<div class="bg-body-tertiary p-5 rounded">

    <form action="{{route('ricercaAnagrafica')}}" method="get">
        @csrf
        <div class="d-flex mb-3">
            <div>
                <input class="form-control" type="text" name="cerca" placeholder="cerca"
                    value="{{$testoRicerca ?? null}}">
            </div>
            <div>
                <button type="submit" class="btn btn-primary">cerca</button>
                @if(isset($testoRicerca))
                    <a href="{{route('clienti')}}" class="btn btn-warning">reset</a>
                @endif
            </div>

        </div>

    </form>

    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nome</th>
            <th scope="col">telefono</th>
            <th scope="col">indirizzo</th>
            <th scope="col">città</th>
            <th scope="col">cap</th>
            <th scope="col">Store</th>
            <th scope="col">Azioni</th>
        </tr>
        </thead>
        <tbody>
{{--        {{dd($clients)}}--}}
        @foreach($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->fullname }}</td>
                <td>{{$client->telefono}} </td>
                <td>{{$client->indirizzo}} </td>
                <td>{{$client->citta}} </td>
                <td>{{$client->cap}} </td>
                <td>{{$client->strutture->nome}} </td>
                <td class="d-flex justify-content-between align-items-center">
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
            {{--@if($client->appointments->isNotEmpty())
                @foreach($client->appointments as $index => $appointment)
                    <tr>
                        @if($index === 0) --}}{{-- Solo per la prima riga del cliente --}}{{--
                        <td rowspan="{{ $client->appointments->count() }}">{{ $client->id }}</td>
                        <td rowspan="{{ $client->appointments->count() }}">{{ $client->fullname }}</td>
                        @endif
                        <td>{{ $appointment->fullname }}</td>
                        <td>{{ $appointment->esito }}</td>
                        <td>{{ $appointment->tipo }}</td>
                        <td>{{ $appointment->previsto }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->fullname }}</td>
                    <td colspan="4" class="text-center text-muted">Nessun appuntamento</td>
                </tr>
            @endif--}}
        @endforeach
        </tbody>
    </table>

    {{$clients->links()}}
</div>

</x-layouts.stile>

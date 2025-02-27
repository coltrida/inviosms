<x-layouts.stile>

    <div class="bg-body-tertiary p-5 rounded">
        <a href="{{route('doppioni')}}" class="btn btn-primary">doppioni</a>
        <a href="{{route('senzaNumero')}}" class="btn btn-primary">senza numero</a>
    </div>

    @if(isset($doppioni))
        tot: {{count($doppioni)}}
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
{{--            {{dd($doppioni[1])}}--}}
            @foreach($doppioni as $client)
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

    @if(isset($senzaNumero))
        tot: {{count($senzaNumero)}}
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
            {{--            {{dd($doppioni[1])}}--}}
            @foreach($senzaNumero as $client)
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
</x-layouts.stile>

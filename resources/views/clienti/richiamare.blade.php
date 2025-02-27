<x-layouts.stile>

<div class="bg-body-tertiary p-5 rounded">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="text-left">Da Richiamare (pc da piu di 6 mesi) - {{ $filiali->sum(fn($filiale) => $filiale->clients->count()) }}</h3>
        <a href="{{ url()->previous() }}" class="btn btn-warning ml-auto">
            Back
        </a>
    </div>

    @foreach($filiali as $filiale)
        {{$filiale->nome}} - ( {{$filiale->clients->count()}} )
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
        <tr>
            <th scope="col">tipo</th>
            <th scope="col">nome</th>
            <th scope="col">telefono</th>
            <th scope="col">indirizzo</th>
            <th scope="col">città</th>
            <th scope="col">provincia</th>
        </tr>
        </thead>
        <tbody>
        @foreach($filiale->clients as $client)
            <tr>
                <td class="text-nowrap">{{ $client->tipo }}</td>
                <td>{{ $client->fullname }}</td>
                <td class="text-nowrap">{{$client->telefono}} </td>
                <td>{{$client->indirizzo}} </td>
                <td>{{$client->citta}} </td>
                <td>{{$client->provincia}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endforeach
</div>

</x-layouts.stile>

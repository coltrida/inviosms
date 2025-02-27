<x-layouts.stile>

<div class="bg-body-tertiary p-5 rounded">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="text-left">Appuntamenti: {{$client->fullname}}</h3>
        <a href="{{ url()->previous() }}" class="btn btn-warning ml-auto">
            Back
        </a>
    </div>


    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Previsto</th>
            <th scope="col">tipo</th>
            <th scope="col">esito</th>
            <th scope="col">note</th>
        </tr>
        </thead>
        <tbody>
        @foreach($client->appointments as $appointment)
            <tr>
                <td class="text-nowrap">{{ $appointment->previsto }}</td>
                <td>{{ $appointment->tipo }}</td>
                <td class="text-nowrap">{{$appointment->esito}} </td>
                <td>{{$appointment->note}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

</x-layouts.stile>

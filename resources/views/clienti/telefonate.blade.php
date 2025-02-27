<x-layouts.stile>

<div class="bg-body-tertiary p-5 rounded">

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3 class="text-left">Telefonate: {{$client->fullname}}</h3>
        <a href="{{ url()->previous() }}" class="btn btn-warning ml-auto">
            Back
        </a>
    </div>


    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
        <tr>
            <th scope="col">data</th>
            <th scope="col">esito</th>
            <th scope="col">note</th>
        </tr>
        </thead>
        <tbody>
        @foreach($client->phones as $phone)
            <tr>
                <td class="text-nowrap">{{ $phone->created_at->format('d-m-Y') }}</td>
                <td class="text-nowrap">{{ $phone->esito }} </td>
                <td>{{ $phone->note }} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

</x-layouts.stile>

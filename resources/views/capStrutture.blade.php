<x-layouts.stile>

    <div class="bg-body-tertiary p-5 rounded">

        <div class="d-flex justify-content-between align-items-center">
            <form action="{{route('ricercaStruttura')}}" method="get">
                @csrf
                <div class="d-flex mb-3">
                    <div>
                        <input class="form-control" type="text" name="cerca" placeholder="cerca"
                               value="{{$testoRicerca ?? null}}">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">cerca</button>
                        @if(isset($testoRicerca))
                            <a href="{{route('capStrutture')}}" class="btn btn-warning">reset</a>
                        @endif
                    </div>

                </div>
            </form>

            <form action="{{route('associacap')}}" method="post">
                @csrf
                <div class="d-flex mb-3">
                    <div>
                        <input class="form-control" type="number" name="cap" placeholder="cap">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">associa</button>
                    </div>
                </div>
        </div>

        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
            <tr>
                <th scope="col">tipo</th>
                <th scope="col">Nome</th>
                <th scope="col">indirizzo</th>
                <th scope="col">città</th>
                <th scope="col">provincia</th>
                <th scope="col">cap</th>
                <th scope="col">cap Associati</th>
                <th scope="col">Azioni</th>
            </tr>
            </thead>
            <tbody>
            @foreach($strutture as $struttura)
                <tr>
                    <td>{{ $struttura->tipo }}</td>
                    <td>{{ $struttura->nome }}</td>
                    <td>{{$struttura->indirizzo}} </td>
                    <td>{{$struttura->citta}} </td>
                    <td>{{$struttura->provincia}} </td>
                    <td>{{$struttura->cap}} </td>
                    <td>
                        @foreach($struttura->caps as $altriCap)
                            {{$altriCap->cap}} <br>
                        @endforeach
                    </td>
                    <td>
                        <div class="form-check">
                            <input value="{{$struttura->id}}" class="form-check-input" type="radio" name="idStruttura" id="flexRadioDefault1">
                            <label class="form-check-label" for="flexRadioDefault1">
                                selez.
                            </label>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </form>
        {{$strutture->links()}}
    </div>

</x-layouts.stile>


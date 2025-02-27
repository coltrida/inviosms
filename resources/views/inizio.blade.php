<x-layouts.stile>

    <div class="bg-body-tertiary p-5 rounded">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <form action="{{route('estrai')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col">
                            Cap: <input name="cap" type="number" class="form-control" placeholder="cap" aria-label="First name">
                        </div>
                        <div class="col">
                            Numeri:
                            <select name="telefono" class="form-select" aria-label="Default select example">
                                <option value="3">mobili</option>
                                <option value="0">fissi</option>
                            </select>
                        </div>
                        <div class="col">
                            passati da mesi:
                            <select name="mesiPassati" class="form-select" aria-label="Default select example">
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </form>
            </div>

            <div>

                <form method="POST" action="{{route('rispostagemini')}}">
                    @csrf
                    <div class="row">
                        <div class="col">
                            ChatGPT:
                            <select name="numero" class="form-select" aria-label="Default select example">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                            </select>
                        </div>
                        <div class="col">
                            tipo:
                            <select name="tipo" class="form-select" aria-label="Default select example">
                                <option value="Potenziale Cliente">PC</option>
                                <option value="Lead">Lead</option>
                                <option value="Cliente">CL</option>
                            </select>
                        </div>
                        <div class="col">
                            città:
                            <select name="citta" class="form-select" aria-label="Default select example">
                                <option>Roma</option>
                                <option>Genova</option>
                                <option>Viareggio</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </form>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <form method="POST" action="{{route('rispostagemini')}}">
                @csrf
                <div class="row">
                    <div class="col">
                        filiale:
                        <select name="numero" class="form-select" aria-label="Default select example">
                            <option value="">filiale</option>
                            @foreach($filiali as $filiale)
                                <option value="{{$filiale->id}}">{{$filiale->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        Recapito:
                        <select name="tipo" class="form-select" aria-label="Default select example">
                            <option value="">Recapito</option>
                            @foreach($recapiti as $recapito)
                                <option value="{{$recapito->id}}">{{$recapito->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
        </div>

        <div id="answer">
            @if (isset($dati))
                <table class="table table-striped">
                    <tbody>
{{--                    <tr>
                        <td>{{$response}}</td>
                        <td></td>
                        <td></td>
                    </tr>--}}
                        @foreach($dati as $item)
                            <tr>
                                <td>{{ $item['Tipo'] }}</td>
                                <td>{{ $item['Fullname'] }}</td>
                                <td>{{ $item['Telefono'] }}</td>
                                <td>{{ $item['Città'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</x-layouts.stile>

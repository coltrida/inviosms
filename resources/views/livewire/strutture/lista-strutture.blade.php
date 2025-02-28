<div>

    <div class="d-flex justify-content-between align-items-center">
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
        </div>

        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <input type="number" wire:model="nuovocap" class="form-control" aria-describedby="passwordHelpInline" placeholder="cap">
            </div>
            <div class="col-auto">
                <button wire:click="associa" type="button" class="btn btn-primary">Associa</button>
            </div>
        </div>
    </div>

    <table class="table table-striped table-bordered mt-3">
        <thead class="thead-dark">
        <tr>
            <th scope="col">
                <select wire:model.live="tipo" class="form-select" aria-label="Default select example">
                    <option selected value="">tipo</option>
                    <option>Corporate</option>
                    <option>Recapito</option>
                    <option>Screening</option>
                </select>
            </th>
            <th scope="col">Nome</th>
            <th scope="col">indirizzo</th>
            <th scope="col">
                <select wire:model.live="citta" class="form-select" aria-label="Default select example">
                    <option selected value="">città</option>
                    @foreach($listacitta as $item)
                        <option>{{$item}}</option>
                    @endforeach
                </select>
            </th>
            <th scope="col">
                <select wire:model.live="provincia" class="form-select" aria-label="Default select example">
                    <option selected value="">Provincia</option>
                    @foreach($listaprovince as $item)
                        <option>{{$item}}</option>
                    @endforeach
                </select>
            </th>
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
                        <input value="{{$struttura->id}}" class="form-check-input" type="radio" wire:model="idStruttura" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                            selez.
                        </label>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{$strutture->links(data: ['scrollTo' => false])}}
</div>

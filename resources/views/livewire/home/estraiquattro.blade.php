<div>
    Estrai 4
        <div class="row mt-3">
            <div class="col">
                Recapito:
                <select wire:change="recapitoSelezionato($event.target.value)" class="form-select" aria-label="Default select example">
                    <option value="">Recapito</option>
                    @foreach($recapiti as $recapito)
                        <option value="{{$recapito}}">{{$recapito->nome}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                @foreach($caps as $cap)
                    {{$cap}} <br>
                @endforeach
            </div>
        </div>
        <button wire:click="visualizza" class="btn btn-primary mt-2">Submit</button>
</div>

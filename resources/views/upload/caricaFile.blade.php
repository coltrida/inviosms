<x-layouts.stile>

    <div class="bg-body-tertiary p-5 rounded">

        @if(session()->has('message'))
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                {{session()->get('message')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

            <h3>Upload Anagrafiche</h3>
            <button type="submit" class="mt-3 btn btn-primary">Submit</button>
    </div>

    <div class="bg-body-tertiary p-5 rounded mt-3">
        <h3>Upload Appuntamenti</h3>
            <button type="submit" class="mt-3 btn btn-primary">Submit</button>
    </div>

    <div class="bg-body-tertiary p-5 rounded mt-3">
        <h3>Upload Telefonate</h3>
            <button type="submit" class="mt-3 btn btn-primary">Submit</button>
    </div>

    <div class="bg-body-tertiary p-5 rounded mt-3">
        <h3>Upload Strutture</h3>
            <a href="{{route('caricaStrutture')}}" class="mt-3 btn btn-primary">Submit</a>
    </div>

</x-layouts.stile>

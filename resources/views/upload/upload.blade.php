<x-layouts.stile>

    <div class="bg-body-tertiary p-5 rounded mt-3">

        @if(session()->has('message'))
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                {{session()->get('message')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h3>Upload Totale</h3>
        <a href="{{route('caricaStrutture')}}" class="mt-3 btn btn-primary">Submit</a>
    </div>

    <div class="bg-body-tertiary p-5 rounded mt-3">
        <h3>Upload Anagrafiche</h3>
        <form action="{{route('uploadAnagrafichePost')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="my-3">
                <input class="form-control" type="file" name="fileExcel">
            </div>
            <button type="submit" class="mt-3 btn btn-primary">Submit</button>
        </form>
    </div>

    <div class="bg-body-tertiary p-5 rounded mt-3">
        <h3>Upload Appuntamenti</h3>
        <form action="{{route('uploadAppuntamentiPost')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="my-3">
                <input class="form-control" type="file" name="fileExcel">
            </div>
            <button type="submit" class="mt-3 btn btn-primary">Submit</button>
        </form>
    </div>

    <div class="bg-body-tertiary p-5 rounded mt-3">
        <h3>Upload Telefonate</h3>
        <form action="{{route('uploadTelefonatePost')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="my-3">
                <input class="form-control" type="file" name="fileExcel">
            </div>
            <button type="submit" class="mt-3 btn btn-primary">Submit</button>
        </form>
    </div>

    <div class="bg-body-tertiary p-5 rounded mt-3">
        <h3>Upload Strutture</h3>
        <form action="{{route('uploadStrutturePost')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="my-3">
                <input class="form-control" type="file" name="fileExcel">
            </div>
            <button type="submit" class="mt-3 btn btn-primary">Submit</button>
        </form>
    </div>

</x-layouts.stile>

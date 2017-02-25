@extends('layouts.app')
@section('page.title','Foto Home')
@section('head')

@endsection
@section('content')
<div class="container">


    @foreach($homefoto as $foto)

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">@yield('page.title')</h3>
        </div>
        <div class="panel-body" data-foto="{{ $foto->id }}">
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-sm-4">
                    <img src="http://www.halex.it{{ $foto->url }}" alt="" class="img-thumbnail center-block" style="margin-bottom: 20px; max-height: 250px;">
                </div>
                <div class="col-sm-8">

                    <div class="form-group form-group-sm">
                        <button class="btn btn-primary cambia-immagine" id="{{ $foto->id }}">Cambia Immagine</button>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-group-sm">
                                <label for="" class="control-label">Titolo</label>
                                <input type="text" name="titolo" class="form-control" value="{{ old('titolo', $foto->titolo) }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-group-sm">
                                <label for="" class="control-label">Titolo Inglese</label>
                                <input type="text" name="titolo_en" class="form-control" value="{{ old('titolo_en', $foto->titolo_en) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-group-sm">
                                <label for="" class="control-label">Url</label>
                                <input type="text" name="link" class="form-control" value="{{ old('link', $foto->link) }}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-group-sm">
                                <label for="" class="control-label">Url Inglese</label>
                                <input type="text" name="link_en" class="form-control" value="{{ old('link_en', $foto->link_en) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-sm">
                        <button class="btn btn-default salva-modifiche">Salva Modifiche</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @endforeach

</div>
@endsection

@section('bottom')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/plupload/2.2.1/plupload.full.min.js"></script>
    <script>

        $(function() {
            $('button.cambia-immagine').each(function() {

                var $pulsante = $(this);


                var uploader = new plupload.Uploader({
                    browse_button: $pulsante.attr('id'),
                    url: '{{ route('fotohome.upload') }}',
                    multipart_params: {
                        id: $pulsante.attr('id'),
                        _token: Laravel.csrfToken
                    }
                });

                uploader.init();

                uploader.bind('QueueChanged', function() {
                    uploader.start();
                    $pulsante.prop('disabled',true);
                });

                uploader.bind('UploadProgress', function(up, file) {
                    $pulsante.html('Caricamento in corso... ' + file.percent + "%");
                });

                uploader.bind('fileUploaded', function(uploader, file, response) {

                    var homefoto = JSON.parse(response.response);

                    var $box = $('.panel-body[data-foto='+homefoto.id+']');
                    $box.find('img').attr('src','http://www.halex.it'+homefoto.url);

                    $pulsante.prop('disabled',false).text('Cambia Immagine');
                })


            });
            $('button.salva-modifiche').on('click',function(e) {
                e.preventDefault();
                var $pulsante = $(this);

                var data = $pulsante.parents('.panel-body').first().find('input[type=text]').map(function(id, input) {

                    return {
                        name: $(input).attr('name'),
                        value: $(input).val()
                    }

                });

                data.push({
                    name: '_token',
                    value: Laravel.csrfToken
                });

                data.push({
                    name: 'id',
                    value: $pulsante.parents('.panel-body').attr('data-foto')
                });

                $.post('{{ route('fotohome.save') }}', data);

            })
        })

    </script>

@endsection
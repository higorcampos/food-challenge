@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Edit Distance') }}</div>
                <div class="card-body">
                    {!! Form::model($distance, ['route' => ['distances.update', $distance->id]]) !!}
                        @include('forms._form')
                        <div class="form-group mt-3">
                            {!! Form::submit('Save Distance', ['class'=>'btn btn-primary']) !!}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

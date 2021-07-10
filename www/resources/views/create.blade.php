@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('New Distance') }}</div>
                <div class="card-body">
                    {{ Form::open(['route' => 'distances.store']) }}
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

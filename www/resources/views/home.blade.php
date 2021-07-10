@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 p-3">
            <a href="{{ route('distances.create') }}" class="btn btn btn-success btn-sm float-end">New Distance</a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('My Distances') }}</div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Origin</th>
                            <th>Destiny</th>
                            <th>Distance</th>
                            <th>Date Created</th>
                            <th>Date Updated</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($distances as $distance)
                        <tr>
                            <td>{{ $distance->postcode_origin }}</td>
                            <td>{{ $distance->postcode_destiny }}</td>
                            <td>{{ $distance->calculated_distance }}</td>
                            <td>{{ $distance->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $distance->updated_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{route('distances.edit', ['distance'=>$distance->id])}}" class="btn btn-primary"><i class="far fa-edit"></i></a>
                                <a href="{{route('distances.destroy', ['distance'=>$distance->id])}}" class="btn btn-danger"><i class="far fa-fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!!  $distances->links('pagination::bootstrap-4')!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

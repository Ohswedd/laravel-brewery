@extends('layouts.app')

@section('title', 'Lista Birrerie')

@section('content')
    <h1 class="mb-4">Lista delle Birrerie</h1>
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nome</th>
                <th>Città</th>
                <th>Stato</th>
            </tr>
        </thead>
        <tbody>
            @foreach($breweries as $brewery)
                <tr>
                    <td>{{ $brewery['name'] }}</td>
                    <td>{{ $brewery['city'] }}</td>
                    <td>{{ $brewery['state'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between mb-4">
        <a href="{{ route('home', ['page' => $page - 1, 'per_page' => $perPage]) }}"
           class="btn btn-primary @if($page <= 1) disabled @endif">Precedente</a>
        <span class="align-self-center">Pagina {{ $page }}</span>
        <a href="{{ route('home', ['page' => $page + 1, 'per_page' => $perPage]) }}"
           class="btn btn-primary">Successiva</a>
    </div>
@endsection

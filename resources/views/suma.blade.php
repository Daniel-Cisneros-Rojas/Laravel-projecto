@extends('layouts.app')

@section('content')
    <h2>Suma de 2 números</h2>
    <form action="/suma" method="POST">
        @csrf
        <label for="num1">Número 1:</label>
        <input type="number" name="num1" id="num1" required>
        <br>
        <label for="num2">Número 2:</label>
        <input type="number" name="num2" id="num2" required>
        <br>
        <button type="submit">Calcular</button>
    </form>
    @if(isset($resultado))
        <h3>Resultado: {{ $resultado }}</h3>
    @endif
    
@endsection
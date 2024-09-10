@extends('layouts.app')

@section('content')
<h2>Reporte de Resumen Financiero</h2>
<p>Ingresos Totales: {{ $ingresos }}</p>
<p>Egresos Totales: {{ $egresos }}</p>
<p>Flujo de Caja: {{ $flujoCaja }}</p>
@endsection

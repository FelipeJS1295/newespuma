<nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
            <br></br>
            @if (in_array(auth()->user()->role, ['admin', 'venta']))
            <li>
                <a class="active-menu" href="{{ route('dashboard') }}"><i class="fa fa-dashboard fa-3x"></i> Dashboard</a>
            </li>
            @endif
            
            {{-- Mostrar Producción solo para roles admin y produccion --}}
            @if (in_array(auth()->user()->role, ['admin', 'produccion']))
                <li>
                    <a href="{{ route('produccion.index') }}"><i class="fa fa-desktop fa-3x"></i> Producción</a>
                </li>
            @endif
            
            {{-- Mostrar Inventarios solo para roles admin, produccion y ventas --}}
            @if (in_array(auth()->user()->role, ['admin', 'produccion', 'venta']))
                <li>
                    <a href="{{ route('inventarios.index') }}"><i class="fa fa-table fa-3x"></i> Inventarios</a>
                </li>
            @endif
            
            {{-- Mostrar Ventas solo para roles admin y ventas --}}
            @if (in_array(auth()->user()->role, ['admin', 'venta']))
                <li>
                    <a href="{{ route('ventas.index') }}"><i class="fa fa-edit fa-3x"></i> Ventas</a>
                </li>
            @endif
            
            {{-- Mostrar Despacho solo para roles admin y despacho --}}
            @if (in_array(auth()->user()->role, ['admin', 'despacho']))
                <li>
                    <a href="{{ route('despacho.index') }}"><i class="fa fa-truck fa-3x"></i> Despacho</a>
                </li>
            @endif
            
            {{-- Mostrar Flujos de Caja solo para roles admin y ventas --}}
            @if (in_array(auth()->user()->role, ['admin', 'venta']))
                <li>
                    <a href="{{ route('conciliacion.index') }}"><i class="fa fa-money fa-3x"></i> Flujos de Caja</a>
                </li>
            @endif
            
            @if (in_array(auth()->user()->role, ['admin', 'venta']))
            <li>
                <a href="{{ route('reportes.index') }}"><i class="fa fa-bar-chart-o fa-3x"></i> Reportes</a>
            </li>
            @endif
            <li>
                <a href="{{ route('tickets.index') }}"><i class="fa fa-ticket fa-3x"></i> Tickets</a>
            </li>
        </ul>
    </div>
</nav>
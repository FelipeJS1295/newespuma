<nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ route('dashboard') }}">NuevaEspuma</a> 
    </div>
    
    <!-- Barra de herramientas -->
    <div style="color: white; padding: 15px 50px 5px 50px; float: right; font-size: 16px;">
        <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Buscar...">
            </div>
        </form>
        
                <!-- Mostrar nombre del usuario conectado -->
        <span>Usuario: {{ auth()->user()->name }}</span>

        <!-- Menú desplegable de Mantenedores -->
        @if (in_array(auth()->user()->role, ['admin', 'venta']))
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: white;">
                    Mantenedores <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('productos.index') }}">Productos</a></li>
                    <li><a href="{{ route('clientes.index') }}">Clientes</a></li>
                    @if (in_array(auth()->user()->role, ['admin']))
                    <li><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
                    @endif
                </ul>
            </li>
        </ul>
        @endif



        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>
</nav>

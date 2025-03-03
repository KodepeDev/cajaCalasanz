@extends('adminlte::page')

@section('title', 'Perfil de Usuario')

@section('content_header')
    <h1>Perfil de Usuario</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                src="{{ asset('storage/usuarios/' . $user->Foto) }}" alt="User profile picture">
                        </div>
                        <h3 class="profile-username text-center">{{ $user->first_name }}</h3>
                        <p class="text-muted text-center">{{ $user->last_name }}</p>
                        {{-- <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Followers</b> <a class="float-right">1,322</a>
                            </li>
                            <li class="list-group-item">
                                <b>Following</b> <a class="float-right">543</a>
                            </li>
                            <li class="list-group-item">
                                <b>Friends</b> <a class="float-right">13,287</a>
                            </li>
                        </ul> --}}
                    </div>

                </div>


                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Sobre Mí</h3>
                    </div>

                    <div class="card-body">
                        <strong><i class="fas fa-book mr-1"></i> Correo</strong>
                        <p class="text-muted">
                            {{ $user->email }}
                        </p>
                        <hr>
                        <strong><i class="fas fa-book mr-1"></i> Documento</strong>
                        <p class="text-muted">{{ $user->document }}</p>
                        <hr>
                        <strong><i class="fas fa-check mr-1"></i> Status</strong>
                        <p class="text-muted">
                            <span class="tag tag-danger">{{ $user->status == 1 ? 'Activo' : 'No Activo' }}</span>
                        </p>
                        <hr>
                    </div>

                </div>

            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a
                                    class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}"
                                    href="{{ route('user.profile') }}">Actividad</a></li>
                            <li class="nav-item"><a
                                    class="nav-link {{ request()->routeIs('user.settings') ? 'active' : '' }}"
                                    href="{{ route('user.settings') }}">Ajustes</a></li>
                            <li class="nav-item"><a
                                    class="nav-link {{ request()->routeIs('user.change_password') ? 'active' : '' }}"
                                    href="{{ route('user.change_password') }}">Cambiar Contraseña</a></li>
                            <li class="nav-item"><a
                                    class="nav-link {{ request()->routeIs('user.recipts') ? 'active' : '' }}"
                                    href="{{ route('user.recipts') }}">Mi Cobranza</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane {{ request()->routeIs('user.profile') ? 'active' : '' }}" id="activity">
                                <h5>Ultimas 10 actividades</h5>
                                <div class="timeline">
                                    <!-- timeline time label -->
                                    @foreach ($bitacoras as $item)
                                        <div>
                                            @switch($item->type)
                                                @case('add')
                                                    <i class="fas fa-plus bg-success"></i>
                                                @break

                                                @case('update')
                                                    <i class="fas fa-pencil-alt bg-warning"></i>
                                                @break

                                                @case('delete')
                                                    <i class="fas fa-trash-alt bg-danger"></i>
                                                @break

                                                @case('transfer')
                                                    <i class="fas fa-random bg-info"></i>
                                                @break

                                                @default
                                                    <i class="fas fa-envelope bg-blue"></i>
                                            @endswitch
                                            <div class="timeline-item">
                                                <span class="time"><i
                                                        class="fas fa-clock"></i>{{ $item->created_at->format('d/m/Y h:i A') }}</span>
                                                <h3 class="timeline-header"><a href="#">{{ $item->user->first_name }}
                                                    </a><span
                                                        class="badge badge-pill badge-primary">{{ $item->type }}</span>
                                                </h3>

                                                <div class="timeline-body">
                                                    {{ $item->activity }}
                                                </div>
                                                <div class="timeline-footer">
                                                    {{-- <a class="btn btn-primary btn-sm">Read more</a>
                                                    <a class="btn btn-danger btn-sm">Delete</a> --}}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div>
                                        <i class="fas fa-clock bg-gray"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="tab-pane" id="timeline">

                                <div class="timeline timeline-inverse">

                                    <div class="time-label">
                                        <span class="bg-danger">
                                            10 Feb. 2014
                                        </span>
                                    </div>


                                    <div>
                                        <i class="fas fa-envelope bg-primary"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i> 12:05</span>
                                            <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email
                                            </h3>
                                            <div class="timeline-body">
                                                Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                                weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                                jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                                quora plaxo ideeli hulu weebly balihoo...
                                            </div>
                                            <div class="timeline-footer">
                                                <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                                <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                            </div>
                                        </div>
                                    </div>


                                    <div>
                                        <i class="fas fa-user bg-info"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i> 5 mins ago</span>
                                            <h3 class="timeline-header border-0"><a href="#">Sarah Young</a> accepted
                                                your friend request
                                            </h3>
                                        </div>
                                    </div>


                                    <div>
                                        <i class="fas fa-comments bg-warning"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i> 27 mins ago</span>
                                            <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post
                                            </h3>
                                            <div class="timeline-body">
                                                Take me to your leader!
                                                Switzerland is small and neutral!
                                                We are more like Germany, ambitious and misunderstood!
                                            </div>
                                            <div class="timeline-footer">
                                                <a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="time-label">
                                        <span class="bg-success">
                                            3 Jan. 2014
                                        </span>
                                    </div>


                                    <div>
                                        <i class="fas fa-camera bg-purple"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-clock"></i> 2 days ago</span>
                                            <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos
                                            </h3>
                                            <div class="timeline-body">
                                                <img src="https://placehold.it/150x100" alt="...">
                                                <img src="https://placehold.it/150x100" alt="...">
                                                <img src="https://placehold.it/150x100" alt="...">
                                                <img src="https://placehold.it/150x100" alt="...">
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <i class="far fa-clock bg-gray"></i>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="tab-pane {{ request()->routeIs('user.settings') ? 'active' : '' }}" id="settings">
                                <form class="form-horizontal" action="{{ route('user.update_info', $user->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('POST')
                                    @if (session('status'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <strong>{{ session('status') }}!</strong>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @elseif (session('error'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <strong>{{ session('error') }}!</strong>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Nombres</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputName"
                                                value="{{ $user->first_name }}" name="firstName" placeholder="Nombres">
                                            @error('firstName')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputLastName" class="col-sm-2 col-form-label">Apellidos</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputLastName"
                                                value="{{ $user->last_name }}" name="lastName" placeholder="Apellidos">
                                            @error('lastName')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Correo Electrónico</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" name="email"
                                                value="{{ $user->email }}" id="inputEmail" placeholder="Correo">
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="dni" class="col-sm-2 col-form-label">DNI</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" value="{{ $user->document }}"
                                                id="dni" name="dni" placeholder="DNI" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" class="btn btn-danger">Actualizar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane {{ request()->routeIs('user.change_password') ? 'active' : '' }}"
                                id="change_password">
                                <form class="form-horizontal" action="{{ route('user.update_password') }}"
                                    method="POST">
                                    @csrf
                                    @method('POST')
                                    @if (session('status'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <strong>{{ session('status') }}!</strong>
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @elseif (session('error'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <strong>{{ session('error') }}!</strong>
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    <div class="form-group row">
                                        <label for="old_password" class="col-sm-2 col-form-label">Contraseña
                                            Actual</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="old_password"
                                                name="old_password" placeholder="Contraseña Actual">

                                            @error('old_password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password" class="col-sm-2 col-form-label">Nueva Contraseña</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder=" Nueva Contraseña">

                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="password_confirmation" class="col-sm-2 col-form-label">Confirmar nueva
                                            contraseña</label>
                                        <div class="col-sm-10">
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" placeholder="Confirmar nueva contraseña">

                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" class="btn btn-danger">Actualizar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane {{ request()->routeIs('user.recipts') ? 'active' : '' }}"
                                id="mi_cobranza">
                                @livewire('usuarios.user-recipts-component')
                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop

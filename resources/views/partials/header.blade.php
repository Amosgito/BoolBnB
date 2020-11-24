
@auth  
    @php
    // se l'utente è loggato si esegue questo piccolo php che serve a cercare se ci sono messaggi dell'utente non letti. a partire dall'id dell'utente loggato si fa una semplice query sui suoi appartamenti e sui messaggi dove la colonna read ha valore '0'. con count() si salva nella variabile $msgs il numero di messaggi non letti poi più sotto si farà un if($msgs) che sarà falso se i messaggi non letti sono 0 e vero per qualsiasi numero diverso da 0. così si mostrerà o no la notifica messaggi non letti (pallino su menu hamburger e voce messaggi del menu). Scrivere il php qua permette di non dover riscriverlo in ogni controller
        $usrId = Auth::user() -> id;

        $msgs = App\Apartment::where('user_id', '=', $usrId) 
                -> join('messages', 'messages.apartment_id', '=', 'apartments.id')
                -> where('messages.read', '=', '0')
                -> count();

    @endphp
@endauth
<header id="header">
    <div class="container-fluid">

      <div class="logo float-left">
        <a href="{{ url('/') }}">
            <img src={{ asset('./img/stemmino.png') }} alt="" id="logo1" class="img-fluid">
            <img src={{ asset('./img/scritta.png') }} alt="" id="logo2" calss="img-fluid d-none">
        </a>
      </div>

      <button type="button" class="nav-toggle"><i class="fas fa-bars fa-1x"></i>
    
        @auth

            @if ($msgs)
                <div id="hamburger-dot" class="dot"></div>
            @endif
            
        @endauth
    
      </button>
      <nav class="nav-menu">
        <ul>
            @guest
                    <li>
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li>
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="active">
                        <a class="nav-link">
                            <i class=" px-1 fas fa-user"></i>
                            {{ Auth::user()->name }}
                        </a>
                    </li>
                    <li>
                        <a class="" href="{{ route('profile', Auth::user() -> id) }}">
                            My Profile
                        </a>
                    </li> 
                    <li>
                        <a class="d-inline-block" href="{{ route('messages', Auth::user() -> id) }}">
                            My Messages
                            @auth

                                @if ($msgs)
                                    <div class="dot"></div>
                                @endif
                                
                            @endauth
                        </a>
                    </li>  
                    <li>
                        <a class="" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>      
                @endguest
          
        </ul>
      </nav><!-- .nav-menu -->

    </div>
  </header><!-- End #header -->
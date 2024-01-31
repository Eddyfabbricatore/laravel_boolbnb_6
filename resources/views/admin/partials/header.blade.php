<nav id="full-navbar" class="navbar navbar-expand-md">
  <div class="w-100 mx-5">

      <!-- BUTTON FOR MOBILE VIEW -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
          <span class="navbar-toggler-icon"></span>
      </button>

      <!-- FULL NAVBAR -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side -->
          <ul class="navbar-nav me-auto">
              <li class="nav-item">
                  <a class="nav-link" href="http://localhost:5173/">{{ __('Vai al sito') }}</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="{{route('dashboard')}}">{{ __('Dashboard') }}</a>
              </li>
          </ul>

          <!-- Right Side -->
          <ul class="navbar-nav ml-auto">
              <!-- Authentication Links -->
              @guest
              <li class="nav-item">
                  <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
              </li>
              @if (Route::has('register'))
              <li class="nav-item">
                  <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
              </li>
              @endif
              @else
              <li class="nav-item dropdown">
                  <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                      {{ Auth::user()->name }}
                  </a>

                  <div class="dropdown-menu dropdown-menu-right bg-black" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item " href="{{ url('profile') }}">{{__('Profile')}}</a>
                      <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                          {{ __('Logout') }}
                      </a>

                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                          @csrf
                      </form>
                  </div>
              </li>
              @endguest
          </ul>
      </div>
  </div>
</nav>

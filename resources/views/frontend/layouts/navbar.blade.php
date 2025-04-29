<header class="header bg-white">
    <div class="container px-0 px-lg-3">
        <nav class="navbar navbar-expand-lg navbar-light py-3 px-lg-0">
            <a class="navbar-brand" href="{{ route('frontend.index') }}">
          <span class="font-weight-bold text-uppercase text-dark">
            @auth
                  Welcome-{{ config('app.name') }}, <span class="text-primary">{{ Auth::user()->first_name }}</span>
              @else
                  Ecommerce
              @endauth
          </span>
            </a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <!-- Link--><a class="nav-link active" href="{{ route('frontend.index') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <!-- Link--><a class="nav-link" href="{{ route('frontend.shop') }}">Shop</a>
                    </li>
                    {{--            <li class="nav-item">--}}
                    {{--              <!-- Link--><a class="nav-link" href="{{ route('frontend.product') }}">Product detail</a>--}}
                    {{--            </li>--}}
                    <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" id="pagesDropdown" href="#"
                                                     data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pages</a>
                        <div class="dropdown-menu mt-3" aria-labelledby="pagesDropdown"><a
                                class="dropdown-item border-0 transition-link" href="{{ route('frontend.index') }}">Homepage</a>
                            <a class="dropdown-item border-0 transition-link" href="{{ route('frontend.shop') }}">Category</a>
                            {{--                  <a class="dropdown-item border-0 transition-link" href="{{ route('frontend.product') }}">Product detail</a>--}}
                            <a class="dropdown-item border-0 transition-link" href="{{ route('frontend.cart') }}">Shopping
                                cart</a>
                            <a class="dropdown-item border-0 transition-link" href="{{ route('frontend.checkout') }}">Checkout</a>
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">

                    <livewire:frontend.cart-component/>

                    <li class="nav-item dropdown">
                        @auth
                        <livewire:frontend.header.notification-component/>
                        @endauth
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="authDropdown" href="#" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">

                            @auth
                                {{ Auth::user()->full_name }}
                            @else
                                Account
                            @endauth
                        </a>
                        <div class="dropdown-menu mt-3" aria-labelledby="authDropdown">
                            @auth
                                <a class="dropdown-item border-0 transition-link"
                                   href="{{ route('frontend.customer.dashboard') }}">My Profile</a>
                                <a class="dropdown-item border-0 transition-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            @else
                                <a class="dropdown-item border-0 transition-link" href="{{ route('login') }}">Login</a>
                                <a class="dropdown-item border-0 transition-link" href="{{ route('register') }}">Register</a>
                            @endauth
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>

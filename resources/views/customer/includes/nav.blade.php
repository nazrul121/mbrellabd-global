<nav class="navbar navBar2 navbar-expand-lg navbar-dark bg-dark border border-top-warning">
    <div class="container">
      <a class="navbar-brand" href="/" target="_blank">Home</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('customer.dashbaord',app()->getLocale()) }}">Dashbaord</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('customer.orders',app()->getLocale()) }}">My Orders</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('customer.address', app()->getLocale()) }}">My Address</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('customer.account-info', app()->getLocale()) }}">Profile</a>
          </li>
        </ul>
      </div>
    </div>
</nav>

      <!-- Display Success Message -->
      @if (session('message'))
      <div class="alert alert-success">
          {{ session('message') }}
      </div>
  @endif

  <!-- Display Error Message -->
  @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

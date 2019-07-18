<navbar :active-route="'{{ Route::currentRouteName() }}'" @guest :guest="true" @else :user="{{ Auth::user() }}" @endguest>
</navbar>
  @guest
  @else
    @if (Auth::user()->role =='tester')
      <div class="masquerade-controller masquerading p-2 border form-group-{{ Auth::user()->role}} align-self-start">
        <div class="help-block mb-2 text-center">
        {{-- {{ Auth::user()->role }}<br /> --}}
          <strong>Viewing as User:</strong> {{ Auth::user()->viewas_guid }}
        </div>
        <div class="d-flex">
          <div>
            <b-input class="text-center" id="guid-form-guid" placeholder="enter guid" ></b-input>
          </div>
          <div class="ml-2 flex-grow-1">
            <a class="text-nowrap" href="" onclick="this.href='/masquerade?guid='+document.getElementById('guid-form-guid').value">Switch  User View ></a>
            <br>
            <a class="text-nowrap" href="/end-masquerade">Stop Masquerading ></a>
          </div>
        </div>
      </div>
    @endif
  @endguest

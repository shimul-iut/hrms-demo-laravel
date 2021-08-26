@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Employee Profile') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('update-profile') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="employee_id" class="col-md-4 col-form-label text-md-right">{{ __('Employee ID') }}</label>

                            <div class="col-md-6">
                                <input id="employee_id" type="text" class="form-control" name="employee_id" value="{{ $user->first()->Employee->id}}" readonly="readonly" >

                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                            <div class="col-md-6">
                                <input id="Fname" type="text" class="form-control @error('Fname') is-invalid @enderror" name="Fname" value="{{ $user->first()->Employee->FirstName }}" required autocomplete="Fname" autofocus>

                                @error('Fname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

                            <div class="col-md-6">
                                <input id="Lname" type="text" class="form-control @error('Lname') is-invalid @enderror" name="Lname" value="{{$user->first()->Employee->LastName }}" required autocomplete="Lname" autofocus>

                                @error('Lname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ Auth::user()->email }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="text" class="form-control @error('password') is-invalid @enderror" name="phone" value="{{$user->first()->Employee->PhoneNumber}}" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="manager_name" class="col-md-4 col-form-label text-md-right">{{ __('Manager ID') }}</label>

                            <div class="col-md-6">
                                <input id="manager_name" type="text" class="form-control" name="manager_name" value="{{ $user->first()->Employee->ManagerID}}" disabled >

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="notification" class="col-md-4 col-form-label text-md-right">{{ __('Notification Settings') }}</label>

                            <div class="col-md-6">
                              <select name="notification" id="notification" class="form-control">
                                @foreach($options as $option)
                                  @if($preference === $option)
                                    <option value="{{$option}}" selected>{{$option}}</option>
                                  @else
                                    <option value="{{$option}}">{{$option}}</option>
                                  @endif
                                @endforeach
                              </select>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" onclick="initFirebaseMessagingRegistration()">
                                    {{ __('Edit Profile') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
<script>

    var firebaseConfig = {
      apiKey: "AIzaSyBDUEps9dzkPfSAaNwvlvJfZGR5SUNo6cU",
      authDomain: "hrms-enosis-demo.firebaseapp.com",
      projectId: "hrms-enosis-demo",
      storageBucket: "hrms-enosis-demo.appspot.com",
      messagingSenderId: "623157559575",
      appId: "1:623157559575:web:c33ee8ee0c17fc8d28b752",
      measurementId: "G-P7JB328SDE"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    function initFirebaseMessagingRegistration() {
            messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function(token) {
                console.log(token);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route("save-token") }}',
                    type: 'POST',
                    data: {
                        token: token
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        alert('Token saved successfully.');
                    },
                    error: function (err) {
                        console.log('User Chat Token Error'+ err);
                    },
                });

            }).catch(function (err) {
                console.log('User Chat Token Error'+ err);
            });
     }

    messaging.onMessage(function(payload) {
        const noteTitle = payload.notification.title;
        const noteOptions = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(noteTitle, noteOptions);
    });

</script>

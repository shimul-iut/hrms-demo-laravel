@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
              @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
              @endif
                <div class="card-header">{{ __('Submit Leave Request') }}</div>

                <div class="card-body">

                  <form method="POST" action="{{ route('create-leave') }}">
                      @csrf
                      <div class="form-group row">
                          <label for="fromDate" class="col-md-4 col-form-label text-md-right">{{ __('From Date') }}</label>

                          <div class="col-md-6">
                              <input id="fromDate" type="date" class="form-control" name="fromDate" value="" required autofocus>

                          </div>
                      </div>

                      <div class="form-group row">
                          <label for="toDate" class="col-md-4 col-form-label text-md-right">{{ __('To Date') }}</label>

                          <div class="col-md-6">
                              <input id="toDate" type="date" class="form-control"  name="toDate"  value="" required autofocus>

                          </div>
                      </div>
                      <div class="form-group row">


                          <div class="col-md-6">
                              <input id="userID" type="hidden" class="form-contro"  name="userID"  value="{{Auth::user()->id}}" required autofocus>

                              @error('toDate')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                              @enderror
                          </div>
                      </div>
                 <div class="form-group row mb-0">
                     <div class="col-md-6 offset-md-4">
                         <button type="submit" class="btn btn-primary">
                             {{ __('Submit') }}
                         </button>
                     </div>
                 </div>
               </form>
                </div>
              </div>
              <div class="card">

                @if(App\Notification::where('employee_id', Auth::user()->id)->count() > 0)

                <table class="table">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col">Request</th>
                      <th scope="col">Creation Date</th>
                      <th scope="col">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach(App\Notification::where('employee_id', Auth::user()->id)->get() as $notification)
                    <tr>
                    <td>{{$notification->notificationText}}</td>
                    <td>{{$notification->created_at}}</td>
                    @if($notification->notificationStatus == "pending")
                      <td><span class="badge badge-primary">{{ucfirst($notification->notificationStatus)}}</span></td>
                    @elseif($notification->notificationStatus == "approved")
                      <td><span class="badge badge-success">{{ucfirst($notification->notificationStatus)}}</span></td>
                    @else
                      <td><span class="badge badge-danger">{{ucfirst($notification->notificationStatus)}}</span></td>
                    </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
                @endif
              </div>
            </div>
          </div>
        </div>
@endsection
<script>
console.log('af');

</script>

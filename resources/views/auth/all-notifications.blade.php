@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('All Requests') }}</div>
                <div class="card-body">
                  <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th scop="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Request</th>
                        <th scope="col">Creation Date</th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach(App\Notification::where('needs_approval' , 1)->get() as $notification)
                      <tr>
                      @if($notification->notificationStatus == "pending")
                        <td><input class="checkToProcessRequest"  data-id= "{{$notification->id }}" type="checkbox" /></td>
                      @else
                        <td></td>
                      @endif
                      <td>{{App\Employee::find($notification->employee_id)->FirstName}} {{App\Employee::find($notification->employee_id)->LastName}} ({{App\User::find($notification->employee_id)->username}})</td>
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
                <div class="form-group row mb-0">
                    <div class="col-md-4 offset-md-2">
                        <button class="btn btn-success approveLeaveRequest" disabled>
                            {{ __('Approve') }}
                        </button>
                    </div>
                    <div class="col-md-4 offset-md-2">
                        <button  class="btn btn-danger declineLeaveRequest" disabled>
                            {{ __('Decline') }}
                        </button>
                    </div>
                </div>
                </div>
              </div>
          </div>
      </div>
  </div>
  @endsection


  <script type="text/javascript">

  window.onload = () => {
      var ids = [];
      document.querySelectorAll('.checkToProcessRequest').forEach(function (check) {
        check.addEventListener('change', function () {
            if (this.checked) {
                ids.push(this.dataset.id);
                document.querySelector('.approveLeaveRequest').disabled = false;
                document.querySelector('.declineLeaveRequest').disabled = false;
                console.log(ids);
            } else {
                ids.splice(ids.indexOf(this.dataset.id), 1);
                if (ids.length == 0) {
                  document.querySelector('.approveLeaveRequest').disabled = true;
                  document.querySelector('.declineLeaveRequest').disabled = true;
                }
            }
        });
    });
    document.querySelector('.approveLeaveRequest').addEventListener('click', function () {
      $.ajax({
          url: 'api/v1/leave/approve',
          type: "post",
          data: {
            _token: "{{ csrf_token() }}",
              ids: ids
          },
          success: function (data) {
              console.log(data);
          }
      });
    });
      document.querySelector('.approveLeaveRequest').addEventListener('click', function () {
        $.ajax({
            url: 'api/v1/leave/approve',
            type: "post",
            data: {
              _token: "{{ csrf_token() }}",
                ids: ids
            },
            success: function (data) {
                location.reload();
            }
        });
      });
        document.querySelector('.declineLeaveRequest').addEventListener('click', function () {
          $.ajax({
              url: 'api/v1/leave/decline',
              type: "post",
              data: {
                _token: "{{ csrf_token() }}",
                  ids: ids
              },
              success: function (data) {
                  location.reload();
              }
          });
        });
}

  </script>

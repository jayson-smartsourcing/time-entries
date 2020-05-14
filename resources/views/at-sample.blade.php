@extends('layouts.app')

@section('content')

<div class="col border rounded border-info bg-white col-md-8 offset-md-2">
     
  <div class="row-md-6 action-form" id="file-import-form">
  

      <div class=" bg-white" style="font-family:Nunito">

      @if(session()->has('message'))
                <div class="alert alert-success col-md-12  alert-dismissible" role="alert" style=" text-align:center">
                    {{ session()->get('message') }}
                
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
            @endif

          <h4 style=" text-align:center" class="time-entry-form-input">Missing Logs</h4>
         
          <table class="table">
              <thead>
              <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Group</th>
                  <th scope="col">Current Date</th>
                  <th scope="col">Sprout ID</th>
                  <th scope="col">Action</th>
              </tr>
              </thead>

                  <tbody>
                 
                  @foreach ($missing as $key => $miss)
                 
                      <tr>
                      <td> 
                        <form  method='POST' action="/api/import/logs/csv/update" enctype='multipart/form-data'>
                           {{ csrf_field() }}
                        <input type="text" readonly class="form-control-plaintext" id="user" value="{{ $miss['user'] }}" name="user">
                      </td>

                      <td> 
                        <input type="text" readonly class="form-control-plaintext" id="groups" value="{{ $miss['groups'] }}" name="groups">
                      </td>
                      

                      <td> 
                        <input type="text" readonly class="form-control-plaintext" id="current_date" value="{{ $miss['current_date'] }}" name="current_date">
                      </td>

                      <td name="current_date" hidden>{{ $miss['current_date']}}</td>

                      <td>
                        <select name="sprout_name_id" class="form-control">
                          <option value="">--Select the correct Sprout ID--</option>
                            @foreach ($emp_sprout as $value)
                              <option value="{{$value}}">{{$value}}</option>
                            @endforeach
                        </select>
                                                
                      </td>

                      <td>
                        <button class="btn btn-success" id="button-{{$key}}" type='submit'>Update Attendance ID</button>
                        </form>
                      </td>
                      </tr>
                      
                      @endforeach
                    
                  </tbody>
          </table>
      </div> 
  </div>

</div>

     
@endsection
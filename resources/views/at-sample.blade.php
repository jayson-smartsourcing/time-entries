@extends('layouts.app')

@section('content')

<div class="col border rounded border-info bg-white col-md-6 offset-md-3">
     
  <div class="row-md-6 action-form" id="file-import-form">

      <div class=" bg-white" style="font-family:Nunito">
          <h4 style=" text-align:center" class="time-entry-form-input">Missing Logs</h4>

          <form  method='POST' action="/import/logs/csv/update" enctype='multipart/form-data'>
          <table class="table">
              <thead>
              <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Current Date</th>
                  <th scope="col">Sprout ID</th>
                  <th scope="col"></th>
              </tr>
              </thead>

                  <tbody>
              
                  @foreach ($missing as $miss)
                  
                  {{ csrf_field() }}
                      <tr>
                      <td> <input type="text" readonly class="form-control-plaintext" id="user" value="{{ $miss['user'] }}" name="user"></td>
                      <td> <input type="text" readonly class="form-control-plaintext" id="current_date" value="{{ $miss['current_date'] }}" name="current_date"></td>
                          <td name="current_date" hidden>{{ $miss['current_date']}}</td>
                          <td>
                            <select name="sprout_name_id">
                            <option value="">--Select the correct Sprout ID--</option>
                              @foreach ($emp_sprout as $value)
                                  <option value="{{$value}}">{{ $value }}</option>
                              @endforeach
                            </select>
                                                    
                          </td>
                          <td><button class="btn btn-success" type='submit'>Update Attendance ID</button></td>
                      </tr>
                    
                  @endforeach

                  </tbody>
                
            
          </table>
          </form>

      </div> 
  </div>

</div>

     
@endsection
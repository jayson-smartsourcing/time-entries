@extends('layouts.app')

@section('content')
     
     <div class="container" style="font-family:Nunito">

        <!-- Success Message -->
        @if(session()->has('message'))
            <div class="alert alert-success col-md-6 offset-md-3  alert-dismissible" role="alert" style=" text-align:center">
                {{ session()->get('message') }}
            
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
        @endif
        <!-- End of Success Message -->
        
        <div class="col border rounded border-info bg-white col-md-6 offset-md-3">
           <br/>

            <div>
                <span class="primary" id="dashboard-header"><h2 style=" text-align:center" class="time-entry-form-input">Activtrak Working Hours</h2></span>
            </div>
                   
            <hr/>
          
            <!-- Working hours Import Form -->
            <div class="row-md-6 action-form" id="file-import-form">

                <div class=" bg-white" style="font-family:Nunito">
                    <h4 style=" text-align:center" class="time-entry-form-input">Import CSV File</h4>
                        <form method='POST' action="/api/import/logs/csv" enctype='multipart/form-data' >
                            {{ csrf_field() }}    

                            <div class="form-group time-entry-form-input">
                            <label> CSV File </label>
                                <input type='file' name='file' id="csv-file" class="form-control-file" required>        
                            </div>
                    
                            <div class="row justify-content-md-center">
                                <button class="btn btn-primary btn-lg time-entry-form-button submit-button-at" type='submit' >
                                    Submit
                                </button>

                                <button class="btn btn-primary btn-lg time-entry-form-button spin-button-at hidden"  >
                                    <img src="{{ asset('img/spinner.svg') }}" class="spinner">
                                </button>
                            </div>
                        </form>
                </div> 
            </div>
            <!-- End of Working hours Import Form -->

            <br/>       
        </div>

        <br/>

        <!-- MISSING LOGS DISPLAY -->
        <div class="col border rounded border-info bg-white col-md-10 offset-md-1">
            <div>
                <span class="primary" id="dashboard-header"><h2 style=" text-align:center" class="time-entry-form-input">All Missing Logs</h2></span>
            </div>

            <table class="table">
              <thead>
              <tr>
                  <th >Name</th>
                  <th >Group</th>
                  <th >Current Date</th>
                  <th >Sprout ID</th>
                  <th >Action</th>
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
                    <td name="current_date" hidden>
                        {{ $miss['current_date']}}
                    </td>
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
        <!-- END OF MISSING LOGS DISPLAY -->

     </div>

  

      @endsection

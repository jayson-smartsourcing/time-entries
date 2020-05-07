@extends('layouts.app')

@section('content')
     
     <div class="container" style="font-family:Nunito">


        <div class="col border rounded border-info bg-white col-md-6 offset-md-3">
            <!-- Action Selection Dropdown -->
            <div>
                <span class="primary" id="dashboard-header"><h2 style=" text-align:center" class="time-entry-form-input">Activtrak Working Hours</h2></span>
                
            </div>
           
            <!-- End of Action Selection Dropdown -->
     
            <hr/>
          
            <!-- Time Entries Import Form -->
            <div class="row-md-6 action-form" id="file-import-form">
            
            <!-- Success and Error Messages -->
            @if(session()->has('message'))
                <div class="alert alert-success col-md-12  alert-dismissible" role="alert" style=" text-align:center">
                    {{ session()->get('message') }}
                
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger col-md-12  alert-dismissible" role="alert" style=" text-align:center">
                    {{$errors->first()}}
                    
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <!-- End of Success and Error Messages -->

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
            <!-- End of Time Entries Import Form -->

            <br/>
            <hr/>
            <br/>


            <!-- missing logs display -->
            <!-- <div class="row-md-6 action-form" id="file-import-form">

                <div class=" bg-white" style="font-family:Nunito">
                    <h4 style=" text-align:center" class="time-entry-form-input">Missing Logs</h4>

                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Sprout ID</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                      
                            <tbody>
        
                            </tbody>
                           
                      
                    </table>

                </div> 
            </div> -->
            <!-- missing logs display -->

        </div>
     </div>

  

      @endsection

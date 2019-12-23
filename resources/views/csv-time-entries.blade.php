@extends('layouts.app')

@section('content')
     
     <div class="container" style="font-family:Nunito">


        <div class="col border rounded border-info bg-white col-md-6 offset-md-3">
            <!-- Action Selection Dropdown -->
            <div>
                <span class="primary" id="dashboard-header"><h2 style=" text-align:center" class="time-entry-form-input">Ticket Dashboard</h2></span>

                <div class="row justify-content-center dropdown" style="font-family:Nunito">
                    <button class="btn btn-lg btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                        Select Action
                    </button>
                    <div class="dropdown-menu" id="action-select" aria-labelledby="dropdownMenuButton">
                       <a class="dropdown-item" id="te-import" value="file-import-form" >Import CSV File for Time Entries</a>
                        <a class="dropdown-item" id="tx-refresh" value="tx-link-refresh-form" >Refresh Ticket Export</a>
                        <input class="hidden" id="default_dropdown" value="{{session()->get('name')}}"/>
                    </div>

                </div>
            </div>
           
            <!-- End of Action Selection Dropdown -->

            <br/>
            <hr/>
            <br/>

                <!-- Time Entries Import Form -->
                <div class="row-md-6 action-form hidden" id="file-import-form">
                
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
                        <h4 style=" text-align:center" class="time-entry-form-input">Import Time Entries</h4>
                            <form method='POST' action="/import-parse" enctype='multipart/form-data' >
                                {{ csrf_field() }}    
                            
                                    <div class="form-group time-entry-form-input">
                                        <label for="api-key"> Enter API Key </label> 
                                        <input type="text" name="api_key" id="api-key" placeholder="API Key" class="form-control  {{ $errors->any() ? 'input-error' : ''}} " 
                                        required>     
                                    </div>

                                    <div class="form-group time-entry-form-input">
                                    <label> CSV File </label>
                                        <input type='file' name='csv_file' id="csv-file" class="form-control-file" required>        
                                    </div>
                        
                                <div class="row justify-content-md-center">
                                    <button class="btn btn-primary btn-lg time-entry-form-button submit-button" type='submit' >
                                        Submit
                                    </button>

                                    <button class="btn btn-primary btn-lg time-entry-form-button spin-button hidden"  >
                                        <img src="{{ asset('img/spinner.svg') }}" class="spinner">
                                    </button>
                                </div>
                            </form>
                    </div> 
                    </div>
                    <!-- End of Time Entries Import Form -->


                    <!-- Ticket Export Refresh From -->
                    <div class="row-md-6 action-form hidden" id="tx-link-refresh-form">
                        
                        <!-- Success and Error Messages -->
                                <div class="alert alert-success col-md-12  alert-dismissible hidden" id="tx-success-msg" role="alert" style=" text-align:center" >
                                    Refresh Successful
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="alert alert-danger col-md-12  alert-dismissible hidden" id="tx-error-msg" role="alert" style=" text-align:center">
                                    Incorrect API Key
                                    
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                        <!-- End of Success and Error Messages -->

                        <div class="bg-white" style="font-family:Nunito">
                            <h4 style=" text-align:center" class="tx-refresh-form-input">Ticket Export - Refresh</h4>
                                <form method='' action="" enctype='multipart/form-data' >
                                    {{ csrf_field() }}    
                                
                                        <div class="form-group time-entry-form-input">
                                            <label for="api-key"> Enter API Key </label> 
                                            <input type="text" name="api_key_refresh" id="api-key-refresh" placeholder="API Key" class="form-control" >
                                            <!-- <span class="error hidden"> </span>      -->
                                        </div>
                            
                                    <div class="row justify-content-md-center">
                                        <button class="btn btn-primary btn-lg tx-refresh-form-button tx-submit-button" type='submit' id='tx-refresh-btn' >
                                            Submit
                                        </button>

                                        <button class="btn btn-primary btn-lg tx-refresh-form-button tx-spin-button hidden"  >
                                            <img src="{{ asset('img/spinner.svg') }}" class="spinner">
                                        </button>
                                    </div>
                                </form>
                        </div> 
                    </div>
                    <!-- End of Ticket Export Refresh From -->

        </div>
          

     </div>

  

      @endsection







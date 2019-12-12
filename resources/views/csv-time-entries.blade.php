@extends('layouts.app')

@section('content')
     <!-- Form -->
     <div class="container " >
        <div class="row">
            <div class="col-md-6 offset-md-3 border rounded border-info bg-white" style="font-family:Nunito">
                <h4 style=" text-align:center" class="time-entry-form-input">Import Time Entries</h4>
                    <form method='POST' action="/import-parse" enctype='multipart/form-data' >
                    {{ csrf_field() }}    
                    
                            <div class="form-group time-entry-form-input">
                                <label for="api-key"> Enter API Key </label>
                                   
                                <input type="text" name="api_key" id="api-key" placeholder="API Key" class="form-control  {{ $errors->any() ? 'input-error' : ''}} " 
                                required>     
                                
                                    @if($errors->any())
                                        <span class="error-span">{{$errors->first()}}</span>
                                    @endif
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
        
        
     </div>

  

      @endsection







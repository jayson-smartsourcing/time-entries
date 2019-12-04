@extends('layouts.app')

@section('content')
     <!-- Form -->
     <div class="container " >
        <div class="row">
            <div class="col-md-6 offset-md-3 border rounded border-info bg-white" >
                <h4 style="text-align:center" class="time-entry-form-input">Import Time Entries</h4>
                    <form method='POST' action="/import-parse" enctype='multipart/form-data' >
                    {{ csrf_field() }}    
                    
                            <div class="form-group time-entry-form-input">
                                <label> Enter API Key </label>
                                <input type="text" name="api_key" placeholder="API Key" class="form-control" required>
                            </div>

                            <div class="form-group time-entry-form-input">
                            <label> CSV File </label>
                                <input type='file' name='csv_file' class="form-control-file" required>        
                            </div>
                
                        <div class="row justify-content-md-center">
                        <button class="btn btn-primary btn-lg time-entry-form-button" type='submit' data-toggle="modal" data-target="#exampleModal">Import File</button>
                        </div>
                    </form>
            </div> 
        </div>
        <!-- End of Form -->

        <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Success</h5>
                </div>
                <div class="modal-body">
                    <h4>File Import Success</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
            </div>
        <!-- End of Modal -->
        
     </div>
      

      @endsection







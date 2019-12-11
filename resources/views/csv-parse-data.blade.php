@extends('layouts.app')

@section('content')
 <div class="container">
    <div class="row">
       
        <div class="col-md-6 offset-md-3 border rounded border-info bg-white" style="font-family:Nunito">
            <h4 style="text-align:center" class="time-entry-form-input">{{$return_message}}</h4>

            <div class="row justify-content-md-center">
                    <a class="btn btn-primary btn-lg time-entry-form-button"  href='/import-time-entries'>
                    Return to Time Entries File Import
                    </a>
            </div>
        </div>


    </div>
 </div>
 @endsection
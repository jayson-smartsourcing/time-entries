<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Poll</title>

        <!-- Fonts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="{{asset('js/main.js')}}"></script>
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #0c0b0b;
                font-family: 'Raleway', sans-serif;
                font-weight: 900;
                height: 100vh;
                font-size:16px;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
            .m-b-md {
                margin-bottom: 30px;
            }
            .x-navbar-inner{
              min-height: 75px;
              border-color: #14a0fa;
              background-color:#14a0fa;
            }
            .max {
                max-width: 1200px;
            }
            .logo{
                margin-left: 20%;
                padding-top:23px;
            }
            img {
                width: 200px;
                height: auto;
            }
            .content {
                width: 50%;
                margin: auto;
            }

            .margin-top {
                margin-top: 60px;
            }
            .yellow {
                width:25%;
                background-color:#ffff00e3;
                height: 15px;
            }
            .green {
                width:25%;
                background-color:#92f100fa;
                height: 15px;
            }
            .blue {
                width:25%;
                background-color:#03a9f4c2;
                height: 15px;
            }

            .pink {
                width:25%;
                background-color:#e91e63b8;
                height: 15px;
            }
            .float-left {
                float: left;
            }

            .main-container {
               padding-left:50px;
               padding-right:50px;
               padding-top:30px;
               padding-bottom: 20px;
            }


            .text {
                line-height:25px;
                text-align: justify;
            }

            .label {
                text-align: justify;
                padding-top:10px;
            }

            span + span {
                margin-left: 57px;
            }

            input + input {
                margin-left: 50px;
            }

            .form-input{
                text-align:justify;
                padding-top:15px;
                max-width:80%;
                
            }
            span {
                line-height:20px;
            }
            select {
                height:40px;
                font-size:15px;
                font-weight:200px;
                border:1px solid;
                border-color:gray;
                min-width: 40%;
                width: auto;
            }
            textarea{
                border-radius: 10px 10px;
                border-color:gray;
                min-width:80%;
            }

            button {
                padding:13px;
                width: 100px;
                background-color:#28a745;
                border-radius: 6px;
                color:white;
                font-size:12px;
            }

            @media only screen and (max-width : 768px) {
                .content {
                    width: 90%;
                }

                textarea,select {
                    width: 100%;
                }
            }

            .form-field * {
                vertical-align: left;
            }
            .option-label {
                margin-top:-10px;
            }
            
            .wrapper {
                list-style-type: none;
                margin-left: -40px;
            }

            .wrapper li {
                float:left;
                width:8%;
            }

            .wrapper:after {
                content:'';
                display:block;
                clear: both;
            }
            .wrapper-label {
                margin-top:-10px;
            }
            .radio-input {
                text-align:justify;
                width:100%;
            }

            .error-class {
                color:red;
                font-size:12px;
            }

            .alert-primary {
                padding:20px;
                border:red solid 1px;   
                margin-left:auto;
                margin-right:auto;
                color: #721c24;
                background-color: #f8d7da;
                border-color: #f5c6cb;
                margin-bottom: 20px;
            }

            .errors {
                content:'';
                display:block;
                clear: both;
                line-height: 1.5em;
            }

            .hidden {
                display:none;
            }
            .header {
                margin-bottom:60px;
            }

            .main-container {
                background: #f6f6f6;
            }
            .red-font {
                color:red;
            }
            .disabled{
                background:gray;
            }

            /* iPads (portrait and landscape) ----------- */
            @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) {
                .content {
                    width: 80%;
                }
            }

            /* iPads (landscape) ----------- */
            @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : landscape) {
                .content {
                    width: 60%;
                }
            }

            /* iPads (portrait) ----------- */
            @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
                .content {
                    width: 60%;
                }
            }
            /**********
            iPad 3
            **********/
            @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : landscape) and (-webkit-min-device-pixel-ratio : 2) {
                .content {
                    width: 60%;
                }
            }

            @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) and (-webkit-min-device-pixel-ratio : 2) {
                
            }
            /* Desktops and laptops ----------- */
            @media only screen  and (min-width : 1224px) {
            /* Styles */
            }

            .green-text {
                color:#155724
            } 
        </style>
    </head>
    <body>
        <div class="header x-navbar-inner">
            <div class="logo">
             <img src="<?php echo asset("img/white-logo-transparent.png")?>"></img>
            </div>
        </div>
    
        <div class="content">
            <div class="alert alert-primary error-class hidden" role="alert">
            </div>
            <div class="yellow float-left"></div>
            <div class="green float-left "></div>
            <div class="blue float-left"></div>
            <div class="pink float-left"></div>
            <div class="main-container">
                    <div class="heading">
                        <h2 class="green-text">Successfully rate for month of  {{now()->format('F')}} </h2>
                    </div>
        </div>
        
    </body>
</html>

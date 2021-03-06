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

            #loader {
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 1;
            margin: -75px 0 0 -75px;
            border: 13px solid #f3f3f3;
            border-radius: 50%;
            border-top: 13px solid #3498db;
            width: 60px;
            height: 60px;  
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            }

            @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
            }

            @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
            }

            /* Add animation to "page content" */
            .animate-bottom {
            position: relative;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 1s;
            animation-name: animatebottom;
            animation-duration: 1s
            }

            @-webkit-keyframes animatebottom {
            from { bottom:-100px; opacity:0 } 
            to { bottom:0px; opacity:1 }
            }

            @keyframes animatebottom { 
            from{ bottom:-100px; opacity:0 } 
            to{ bottom:0; opacity:1 }
            }

            #myDiv {
                display: none;
                text-align: center;
            }

            .loading {
                position: fixed;
                z-index: 999;
                height: 100%;
                width: 100%;
                overflow: show;
                margin: auto;
                top: 0;
                left: 0;
                bottom: 0;
                right: 0;
                background-color:#80808080;
            }

            .loader {
                margin-top:25%;
                margin-left:45%;
                border: 10px solid #fff;
                border-radius: 50%;
                border-top: 10px solid #3498db;
                width: 50px;
                height: 50px;
                -webkit-animation: spin 2s linear infinite; /* Safari */
                animation: spin 2s linear infinite;
            }

            /* Safari */
            @-webkit-keyframes spin {
                0% { -webkit-transform: rotate(0deg); }
                100% { -webkit-transform: rotate(360deg); }
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
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
                        <h2>Smartsourcing Employee Satisfaction Score {{now()->format('F')}} {{now()->year}}</h2>
                    </div>
                    <div class="row">
                        <div class="text">
                            <p>
                                We are always striving to make Smartsourcing a great place to work.
                            </p>
                            <p>
                                With this poll, we want to find out how you feel about Smartsourcing as a place to work. 
                            </p>
                            <p>
                                In the past years, you have been instrumental in implementing changes to Smartsourcing that has continuously made our culture what it is today. 
                            </p>
                            <p>
                                Let’s take it one step further with you telling us your satisfaction score every month!
                            </p>
                            <p>
                                The results of this survey will teach us insights on how we can make Smartsourcing even better.  Don’t worry, you’ll remain completely anonymous.
                                Thank you for taking time to give us your feedback!
                            </p>

                        </div>
                    </div>
                    <div class="row poll-form">
                        <div class="label">
                            <span class="rate-label">
                               <span class="red-font">*</span> How likely are you to recommend Smartsourcing to a friend as a place to work? 
                            </span>
                            <div class="row">
                                <span>1 - Very Unlikely</span><span>10 - Very Likely</span>  
                            </div>
                        </div>
                        <div class="radio-input">

                        <ul class="wrapper">
                            <li>
                                <input id="option1" type="radio" name="rate" value="1" class="radio-button"/> 
                            </li>
                            <li>
                                <input id="option1" type="radio" name="rate" value="2" class="radio-button"/>
                            </li>       
                            <li>
                                <input id="option1" type="radio" name="rate" value="3" class="radio-button"/>
                            </li>
                            <li>
                                <input id="option1" type="radio" name="rate" value="4" class="radio-button"/>
                            </li>
                            <li>
                                <input id="option1" type="radio" name="rate" value="5" class="radio-button"/>
                            </li>
                            <li>
                                <input id="option1" type="radio" name="rate" value="6" class="radio-button"/>
                            </li>
                            <li>
                                <input id="option1" type="radio" name="rate" value="7" class="radio-button"/>
                            </li>
                            <li>
                                <input id="option1" type="radio" name="rate" value="8" class="radio-button"/>
                            </li>
                            <li>
                                <input id="option1" type="radio" name="rate" value="9" class="radio-button"/>
                            </li>
                            <li>
                                <input id="option1" type="radio" name="rate" value="10" class="radio-button"/>
                            </li>
                        </ul >
                        <ul class="wrapper wrapper-label">
                            <li><span>1</span></li>
                            <li><span>2</span></li>
                            <li><span>3</span></li>
                            <li><span>4</span></li>
                            <li><span>5</span></li>
                            <li><span>6</span></li>
                            <li><span>7</span></li>
                            <li><span>8</span></li>
                            <li><span>9</span></li>
                            <li><span>10</span></li>
                        </ul>                
                        <div class="row label">
                            <span class="reason-label">
                             <span class="red-font">*</span> Could you please tell us why you choose that score?
                            </span>
                        </div>
                        <div class="form-input">
                        <textarea name="reason" id="reason" rows="12" ></textarea>
                        </div>
                        <div class="form-input">
                            <button class="submit">SUBMIT</button>
                        </div>
                    </div>
                </div>
            
        </div>
        <div class="loading hidden"><div class="loader"></div></div>
        
    </body>
</html>

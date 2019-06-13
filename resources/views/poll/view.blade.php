<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Poll</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #0c0b0b;
                font-family: 'Raleway', sans-serif;
                font-weight: 200;
                height: 100vh;
                font-size:16px;
                margin: 0;
                min-height: 105%;
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
                background: #f6f6f6;
                width: 50%;
                min-height: 800px;
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
               padding-left:40px;
               padding-right:40px;
               padding-top:30px;
            }


            .text {
                line-height:25px;
                size: 8px;
                text-align: justify;
            }

            .label {
                text-align: justify;
            }

            span + span {
                margin-left: 60px;
            }

            .form-input{
                text-align:justify;
                padding-top:15px;
                max-width:80%;
                
            }
            span {
                font-size:13px;
                line-height:15px;
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



        </style>
    </head>
    <body>
        <div class="header x-navbar-inner">
            <div class="logo">
             <img src="<?php echo asset("img/white-logo-transparent.png")?>"></img>
            </div>
        </div>
        <div class="content margin-top">
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
                        <span>
                            How likely are you to recommend Smartsourcing to a friend as a place to work?
                        </span>
                        <div class="row">
                            <span>1 - Very Unlikely</span><span>10 - Very Likely</span>  
                        </div>
                    </div>
                    <div class="form-input">
                        <select name="rate" id="">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            
                        </select>
                    </div>

                    <div class="label">
                        <span>
                            Could you please tell us why you choose that score?
                        </span>
                    </div>
                    <div class="form-input">
                       <textarea name="reason" id="" rows="12" ></textarea>
                    </div>
                    <div class="form-input">
                       <button>SUBMIT</button>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
</html>

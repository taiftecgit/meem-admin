<html>
<head>
    <title>404 not found: {!! env('APP_NAME') !!}</title>



<style>
    @import url('https://fonts.googleapis.com/css?family=Lato');
    .message{
        text-align: center;
        padding: 78px;
        background: #a94b142b;
        border-radius: 15px;
    }
    $animationTime: 2s;

    * {
        position: relative;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Lato', sans-serif;
    }

    body {
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: linear-gradient(to bottom right, #fda70a, #f90);
        color: #fff;
    }

    a{
        color:#000;
        text-decoration: none;
    }

    h1 {
        margin: 40px 0 20px;
    }

    .lock {
        border-radius: 5px;
        width: 55px;
        height: 45px;
        background-color: #333;
        animation: dip 1s;
        animation-delay: ($animationTime - .5);

    &::before,
    &::after {
         content: '';
         position: absolute;
         border-left: 5px solid #333;
         height: 20px;
         width: 15px;
         left: calc(50% - 12.5px);
     }



    &::before {
         top: -30px;
         border: 5px solid #333;
         border-bottom-color: transparent;
         border-radius: 15px 15px 0 0;
         height: 30px;
         animation: lock $animationTime, spin $animationTime;
     }

    &::after {
         top: -10px;
         border-right: 5px solid transparent;
         animation: spin $animationTime;
     }
    }

    @keyframes lock {
        0% {
            top: -45px;
        }
        65% {
            top: -45px;
        }
        100% {
            top: -30px;
        }
    }

    @keyframes spin {
        0% {
            transform: scaleX(-1);
            left: calc(50% - 30px);
        }
        65% {
            transform: scaleX(1);
            left: calc(50% - 12.5px);
        }
    }

    @keyframes dip {
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(10px);
        }
        100% {
            transform: translateY(0px);
        }
    }

</style>
</head>
<body>

<div class="message" style="text-align: center">
    <img src="{!! env('APP_PUBLIC_URL') !!}assets/images/logo-dashboard.png" />
    <h1>We can't find that page</h1>
    <p>Please check with the site admin if you believe this is a mistake.</p>
    <p style="text-align: center"><a href="{!! env('APP_URL') !!}dashboard"> Go to dashboard</a></p>
</div>
</body>
</html>
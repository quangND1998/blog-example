<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="favicon.ico" />
        <link href="https://fonts.googleapis.com/css?family=Be+Vietnam&display=swap" rel="stylesheet">
        <title>HBlog | Login</title>
        <!-- Styles -->
        <style>
            body {
                overflow-y: hidden;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                font-family: 'Be Vietnam', sans-serif;
            }
            .container {
                padding: 60px 0 40px;
                text-align: center;
                width: 400px;
                border: 1px solid #e1e2e8;
                border-radius: 5px;
                background-color: #fff;
                box-shadow: 0 3px 7px 0 rgba(110,142,247,.13);
            }
            .wraper {
                margin-top: 50px
            }
            .img {
                width: 150px;
                height: auto;
            }
            .button {
                color: #fff;
                display: inline-block;
                line-height: 40px;
                text-align: center;
                text-decoration: none;
                font-size: 15px;
                font-weight: 700;
                width: 300px;
                height: 40px;
                border-radius: 3px;
                box-sizing: border-box;
                margin: 0 auto 20px;
                cursor: pointer;
                letter-spacing: 0.5px;
                word-spacing: 3px;
            }
            .google {
                background-color: #ea4335;
            }
            .facebook {
                background-color: #0084ff;
            }
            .github {
                background-color: #211f1f;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <img src="{{ env('APP_URL') . '/api/images/logo.svg' }}" class="img"/>
            <div class="wraper">
                <a href="/login/google" class="button google">
                    LOGIN WITH GOOGLE
                </a>
                <br style="display: block" />
                <a href="/login/facebook" class="button facebook">
                    LOGIN WITH FACEBOOK
                </a>
                <br style="display: block" />
                <a href="/login/github" class="button github">
                    LOGIN WITH GITHUB
                </a>
            </div>
        </div>
    </body>
</html>

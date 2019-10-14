
@extends('layouts.app')

@section('content')


<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Audio Player</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.min.css">

        <link rel="stylesheet" type="text/css" href="/css/app.css" />


        

        
    </head>

    <body>
      
        <div id="app" class="container">
          
          <router-view></router-view>


      </div>

    
    </body>

</html>


@endsection


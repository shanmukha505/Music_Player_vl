
@extends('layouts.app')

@section('content')

<!DOCTYPE html>

<html>

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Audio Player</title>

        <link rel="stylesheet" type="text/css" href="/css/app.css" />
        
    </head>

    <body>
      
        <div id="app">
          
          <router-view></router-view>

      </div>

    </body>

</html>

@endsection


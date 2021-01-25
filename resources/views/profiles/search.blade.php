@extends('layouts.app')

@section('content')
<div class="container">
  <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <tr align="center">
     <th colspan="8" >Users</th> 
    </tr>
    <tr>
  
  <th>Name</th>
 
  @foreach ($user as $users)

      <tr>

        <td><a href=" /profile/{{$users->id}}">{{ $users->username }}</a></td>
    

      </tr>
  @endforeach
          
            

    
  



</div>
@endsection

@extends('master') 

@section('content')

<div class="container">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>ID</th>
				<th>File Name</th>
				<th>State Prefix</th>
				<th>Image Data</th>
				<th>Ready For Update</th>
				<th>Date Created</th>
				<th>Date Modified</th>
			</tr>
		</thead>
		<tbody>
			@foreach($files as $key=>$file)
			<tr>
				<td>{{$file->id}}</td>
				<td>{{$file->file_name}}</td>
				<td>{{$file->state_prefix}}</td>
				<td>blob</td>
				<td>{{$file->ready_for_update}}</td>
				<td>{{$file->date_created}}</td>
				<td>{{$file->date_modified}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>

@endsection

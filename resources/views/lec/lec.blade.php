@extends('lec.layouts.master')

@section('title','Home')

@section('styles')
@stop

@section('content')
	<div class="container pb-5">
		<div class="row pt-2 pb-2">
			<div class="col-sm-5"></div>
			<div class="col-sm-2 text-center">
				<img class="user-avatar rounded-circle" src="{{ asset('img/dashboard/admin.png') }}" alt="User Avatar">
			</div>
		</div>
		<hr>
		<div class="row pt-2 mb-3">
			<div class="col-sm-3">
				<strong>Name : </strong>
			</div>
			<div class="col-sm-9">
				<span>Lorem Ipsum Dolor</span>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-3">
				<strong>Location Assigned : </strong>
			</div>
			<div class="col-sm-9">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>REGION</th>
							<th>PROVINCE</th>
							<th>CITY</th>
							<th>MUNICIPALITY/DISTRICT</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td rowspan="2" class="align-middle">lorem</td>
							<td>lorem</td>
							<td>lorem</td>
							<td>lorem</td>
						</tr>
						<tr>
							<td rowspan="2">lorem</td>
							<td>lorem</td>
							<td>lorem</td>
						</tr>
						<tr>
							<td>lorem</td>
							<td>lorem</td>
							<td>lorem</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
@stop

@section('scripts')
@stop
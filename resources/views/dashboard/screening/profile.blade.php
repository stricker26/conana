@extends('dashboard.layouts.master')

@section('title','Profile')

@section('styles')
@stop

@section('content')
	<div class="container pb-5">
		<div id="alert-handler" style="display: none;">
			<div class="content mt-3 pl-3 pr-3 success-alert" style="display: none;">
	            <div class="col-sm-12">
	                <div class="alert alert-success alert-dismissible fade show" role="alert">
	                  <span class="badge badge-pill badge-success message-alert">Success</span> Data saved
	                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	                        <span aria-hidden="true">&times;</span>
	                    </button>
	                </div>
	            </div>
	        </div> 

	        <div class="content pl-3 pr-3 failed-alert" style="display: none;">
	            <div class="col-sm-12">
	                <div class="alert alert-danger alert-dismissible fade show" role="alert">
	                  <span class="badge badge-pill badge-danger message-alert">Failed</span> Data not saved
	                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	                        <span aria-hidden="true">&times;</span>
	                    </button>
	                </div>
	            </div>
	        </div>
	    </div>
		<div class="mt-5 pb-4">
			<div class="text-center">
				<h3 class="text-center">Profiling</h3>
			</div>
		</div>
		<div class="row picture-candidate pb-5">
			<div class="col-sm-5"></div>
			<div class="col-sm-2 text-center">
				<img class="rounded-circle" src="../img/dashboard/admin.png" alt="User Avatar">
			</div>
			<div class="col-sm-5"></div>
		</div>
		<div id="candidateSummary" class="row pr-2 pl-2 prof-summary-data">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-12 text-center pb-3">
						<h3>{{ucwords(strtolower($candidate->lastname))}},&nbsp;{{ucwords(strtolower($candidate->firstname))}}&nbsp;{{ucwords(strtolower($candidate->middlename))}}</h3>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 text-center">
						<div class="pb-1">
							<span>(Position Aspired for)</span>
						</div>
						<div class="text-center">
							<h3>{{$candidate->candidate_for}}</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row data-candidates">
			<input type="hidden" id="prof_id" value="{{$candidate->id}}">
			<div class="col-sm-6 left-div">
				<div class="row">
					<div class="col-sm-12 pb-3">
						<h4>Personal Data</h4>
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">Last Name :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{ucwords(strtolower($candidate->lastname))}}</span>
						<input type="text" class="form-control" id="prof_lastname" style="display:none;" value="{{$candidate->lastname}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">First Name :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{ucwords(strtolower($candidate->firstname))}}</span>
						<input type="text" class="form-control" id="prof_firstname" style="display:none;" value="{{$candidate->firstname}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">Middle Name :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{ucwords(strtolower($candidate->middlename))}}</span>
						<input type="text" class="form-control" id="prof_middlename" style="display:none;" value="{{$candidate->middlename}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">Birthdate :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{$candidate->birthdate}}</span>
						<input type="date" class="form-control" id="prof_birthdate" style="display:none;" value="{{$candidate->birthdate}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">Residential Address :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{$candidate->address}}</span>
						<input type="text" class="form-control" id="prof_address" style="display:none;" value="{{$candidate->address}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">Email :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{$candidate->email}}</span>
						<input type="text" class="form-control" id="prof_email" style="display:none;" value="{{$candidate->email}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">Landline :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{$candidate->landline}}</span>
						<input type="text" class="form-control" id="prof_landline" style="display:none;" value="{{$candidate->landline}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">Mobile :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{$candidate->mobile}}</span>
						<input type="text" class="form-control" id="prof_mobile" style="display:none;" value="{{$candidate->mobile}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-5">
						<span class="font-weight-bold">Social Media Accounts :</span>
					</div>
					<div class="col-sm-1">
						<div>
							<i class="fab fa-facebook-square pr-2" style="color:#4267b2;"></i>
						</div>
					</div>
					@php
						$arraySMA = explode(",",$candidate->sma);
					@endphp
					<div class="col-sm-6 row-content">
						<div>
							<a style="color:#212529;" href="#facebook"><i><span>{{$arraySMA[0]}}</span></i></a><input type="text" class="form-control" id="prof_fb" style="display:none;" value="{{$arraySMA[0]}}">
						</div>
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-5"></div>
					<div class="col-sm-1">
						<div>
							<i class="fab fa-twitter-square pr-2" style="color:#1da1f2;"></i>
						</div>
					</div>
					<div class="col-sm-6 row-content">
						<div>
							<a style="color:#212529;" href="#twitter"><i><span>{{$arraySMA[1]}}</span></i></a><input type="text" class="form-control" id="prof_twitter" style="display:none;" value="{{$arraySMA[1]}}">
						</div>
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-5"></div>
					<div class="col-sm-1">
						<div>
							<i class="fab fa-instagram pr-2" style="color:#e4405f;"></i>
						</div>
					</div>
					<div class="col-sm-6 row-content">
						<div>
							<a style="color:#212529;" href="#instagram"><i><span>{{$arraySMA[2]}}</span></i></a><input type="text" class="form-control" id="prof_ig" style="display:none;" value="{{$arraySMA[2]}}">
						</div>
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-5"></div>
					<div class="col-sm-1">
						<div>
							<i class="fab fa-chrome pr-2" style="color:#00AFF0;"></i>
						</div>
					</div>
					<div class="col-sm-6 row-content">
						<div>
							<a style="color:#212529;" href="#website"><i><span>{{$arraySMA[3]}}</span></i></a><input type="text" class="form-control" id="prof_website" style="display:none;" value="{{$arraySMA[3]}}">
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 right-div">
				<div class="row">
					<div class="col-sm-12 pb-3">
						<h4>Location</h4>
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">Province :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{ucwords(strtolower($province->lgu))}}</span>
						<input type="text" class="form-control" id="prof_province" style="display:none;" value="{{ucwords(strtolower($province->lgu))}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">City :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{ucwords(strtolower($city->city))}}</span>
						<input type="text" class="form-control" id="prof_city" style="display:none;" value="{{ucwords(strtolower($city->city))}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">District :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{ucwords(strtolower($district->district))}}</span>
						<input type="text" class="form-control" id="prof_district" style="display:none;" value="{{ucwords(strtolower($district->district))}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">Municipality :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{ucwords(strtolower($district->municipality))}}</span>
						<input type="text" class="form-control" id="prof_municipality" style="display:none;" value="{{ucwords(strtolower($district->municipality))}}">
					</div>
				</div>
				<div class="row mt-5">
					<div class="col-sm-12 pb-3">
						<h4>Chief of Staff</h4>
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">Name :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{ucwords(strtolower($cos->name))}}</span>
						<input type="text" class="form-control" id="prof_cos_name" style="display:none;" value="{{ucwords(strtolower($cos->name))}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">Relationship/Position :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{$cos->relationship}}</span>
						<input type="text" class="form-control" id="prof_cos_name" style="display:none;" value="{{ucwords(strtolower($cos->relationship))}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">Address :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{$cos->address}}</span>
						<input type="text" class="form-control" id="prof_cos_name" style="display:none;" value="{{$cos->address}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">Contact :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{$cos->contact}}</span>
						<input type="text" class="form-control" id="prof_cos_name" style="display:none;" value="{{$cos->contact}}">
					</div>
				</div>
				<div class="row row-body">
					<div class="col-sm-6">
						<span class="font-weight-bold">Email :</span>
					</div>
					<div class="col-sm-6 row-content">
						<span>{{$cos->email}}</span>
						<input type="text" class="form-control" id="prof_cos_name" style="display:none;" value="{{$cos->email}}">
					</div>
				</div>
			</div>
		</div>
		<div class="text-center">
			<div class="d-inline pr-2">
				<button type="button" class="btn btn-secondary" id="close_btn" style="display:none;">Close</button>
			</div>
			<div class="d-inline">
				<form id="saveBtnAjax" class="d-inline">
					<button type="button" class="btn btn-success" id="save_btn" style="display:none;">Save</button>
				</form>
			</div>
			<div class="d-inline pr-2">
				<button type="button" class="btn btn-secondary" id="edit_btn">Edit</button>
			</div>
			<div class="d-inline pr-2">
				<button type="button" class="btn btn-success" id="approve_btn">Approve</button>
			</div>
			<div class="d-inline pr-2">
				<button type="button" class="btn btn-danger" id="reject_btn">Reject</button>
			</div>
		</div>
	</div>
@stop

@section('scripts')
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="{{asset('js/screening/profileData.js')}}"></script>
@stop
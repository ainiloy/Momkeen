@extends('layouts.adminmaster')

		@section('styles')
		
		<!-- INTERNAL Data table css -->
		<link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />
		<link href="{{asset('assets/plugins/datatable/responsive.bootstrap5.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

		<!-- INTERNAL Sweet-Alert css -->
		<link href="{{asset('assets/plugins/sweet-alert/sweetalert.css')}}?v=<?php echo time(); ?>" rel="stylesheet" />

		@endsection

							@section('content')

							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h4 class="page-title"><span class="font-weight-normal text-muted ms-2">Customers</span></h4>
								</div>
							</div>
							<!--End Page header-->

							<!-- Customer List -->
							<div class="col-xl-12 col-lg-12 col-md-12">
								<div class="card ">
									<div class="card-header border-0 d-sm-max-flex">
										<h4 class="card-title">Customers List</h4>
										<div class="card-options mt-sm-max-2">
											@can('Customers Create')

											<a href="{{url('admin/customer/create')}}" class="btn btn-success me-3"><i class="feather feather-user-plus"></i> Add Customers </a>
											@endcan
											
										</div>
									</div>
									<div class="card-body" >
										<div class="table-responsive spruko-delete">
											
											<table class="table table-vcenter text-nowrap table-bordered table-striped ticketdeleterow w-100" id="support-customerlist">
												<thead>
													<tr>
														<th  width="10">Id</th>
														<th  width="10">#SlNo</th>
														

														

														<th >Name</th>
														<th >Gender</th>
														<th >User type</th>
														<th >Varification</th>
														<th >Register date</th>
														<th class="w-5">Status</th>
														<th >Action</th>
													</tr>
												</thead>
												<tbody>
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<!-- End Customer List -->
							@endsection

		@section('scripts')

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

		<!-- INTERNAL Data tables -->
		<script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>

		<!-- INTERNAL Index js-->
		<script src="{{asset('assets/js/support/support-sidemenu.js')}}"></script>

		<!-- INTERNAL Sweet-Alert js-->
		<script src="{{asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>

		<script type="text/javascript">

			"use strict";

			(function($)  {

				// Variables
				var SITEURL = '{{url('')}}';

				// Csrf Field
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				// Datatable
				$('#support-customerlist').DataTable({
					processing: true,
					serverSide: true,
					ajax: {
						url: "{{ url('/admin/customer') }}"
					},
					columns: [
						{data: 'id', name: 'id', 'visible': false},
						{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,searchable: false},
						
						{ data: 'username', name: 'username' },
						{ data: 'gender', name: 'gender' },
						{ data: 'userType', name: 'userType' },
						{ data: 'verified', name: 'verified' },
						{ data: 'created_at', name: 'created_at'},
						{ data: 'status', name: 'status'},
						{data: 'action', name: 'action', orderable: false},
						
					],
					order:[],
					responsive: true,
					drawCallback: function () {
						var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
						var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
							return new bootstrap.Tooltip(tooltipTriggerEl)
						});
						$('.form-select').select2({
							minimumResultsForSearch: Infinity,
							width: '100%'
						});
						$('#customCheckAll').prop('checked', false);

						$('.checkall').on('click', function(){
							if($('.checkall:checked').length == $('.checkall').length){
								$('#customCheckAll').prop('checked', true);
							}else{
								$('#customCheckAll').prop('checked', false);
							}
						});
					},
				});

				// Delete the customer
				$('body').on('click', '#show-delete', function () {
					var _id = $(this).data("id");
					swal({
						title: `Are you sure you want to continue?`,
						text: "This might erase your records permanently",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							$.ajax({
								type: "get",
								url: SITEURL + "/admin/customer/delete/"+_id,
								success: function (data) {
									toastr.error(data.error);
									var oTable = $('#support-customerlist').dataTable();
									oTable.fnDraw(false);
								},
								error: function (data) {
								console.log('Error:', data);

								}
							});
						}
					});
				});

				// Mass Delete 
				$('body').on('click', '#massdelete', function () {
					var id = [];
					$('.checkall:checked').each(function(){
						id.push($(this).val());
					});
					if(id.length > 0){
						swal({
							title: `{{trans('langconvert.admindashboard.wanttocontinue')}}`,
							text: "{{trans('langconvert.admindashboard.eraserecordspermanently')}}",
							icon: "warning",
							buttons: true,
							dangerMode: true,
						})
						.then((willDelete) => {
							if (willDelete) {
								$.ajax({
									url:"{{ url('admin/masscustomer/delete')}}",
									method:"GET",
									data:{id:id},
									success:function(data)
									{
										$('#support-customerlist').DataTable().ajax.reload();
										toastr.error(data.error);				
									},
									error:function(data){

									}
								});
							}
						});
					}else{
						toastr.error('{{trans('langconvert.functions.checkboxselect')}}');
					}

				});

				// Checkbox check all
				$('#customCheckAll').on('click', function() {
					$('.checkall').prop('checked', this.checked);
				});
				
			})(jQuery);

		</script>
		@endsection



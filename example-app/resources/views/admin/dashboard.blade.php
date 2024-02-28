@extends('layouts.adminmaster')

		@section('styles')

		<!-- INTERNAL Data table css -->
		<link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
		<link href="{{asset('assets/plugins/datatable/responsive.bootstrap5.css')}}" rel="stylesheet" />
		<link href="{{asset('assets/plugins/datatable/buttonbootstrap.min.css')}}" rel="stylesheet" />

		<!-- INTERNAL Sweet-Alert css -->
		<link href="{{asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
		
		@cannot('Ticket Delete')

		<style>
			.ticketdelete{
				display: none;
			}
		</style>
		@endcannot
	

		@endsection

							@section('content')

							<!--Page header-->
							<div class="page-header d-xl-flex d-block">
								<div class="page-leftheader">
									<h4 class="page-title"><span class="font-weight-normal text-muted ms-2">Dashboard</span></h4>
								</div>
								<div class="page-rightheader ms-md-auto">
									<div class="d-flex align-items-end flex-wrap my-auto end-content breadcrumb-end">
										<div class="d-flex breadcrumb-res">
											<div class="header-datepicker me-3">
												
											</div>
											<div class="header-datepicker picker2 me-3">
												
											</div><!-- wd-150 -->
										</div>
									</div>
								</div>
							</div>
							<!--End Page header-->

							<!--Dashboard List-->
							<div class="row">

								<div class="col-xl-12 col-md-12 col-lg-12">
									<div class="row">
										<div class="col-xl-3 col-lg-6 col-md-12">
											<div class="card">
												<a href="{{route('admin.ticket.all')}}" class="admintickets"></a>
												<div class="card-body">
													<div class="row">
														<div class="col-8">
															<div class="mt-0 text-start"><span class="fs-14 font-weight-semibold">All Tickets</span>
																<h3 class="mb-0 mt-1 mb-2">{{$tickets->count()}}</h3>
															</div>
														</div>
														<div class="col-4">
															<div class="icon1 bg-primary my-auto  float-end"> <i class="las la-ticket-alt"></i> </div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-xl-3 col-lg-6 col-md-12">
											<div class="card">
												<a href="{{route('admin.activeticket')}}" class="admintickets"></a>
												<div class="card-body">
													<div class="row">
														<div class="col-8">
															<div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold">Active Tickets</span>
															<h3 class="mb-0 mt-1  mb-2">{{$active->count()}}</h3> </div>
														</div>
														<div class="col-4">
															<div class="icon1 bg-primary brround my-auto  float-end"> <i class="las la-ticket-alt"></i> </div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-xl-3 col-lg-6 col-md-12">
											<div class="card">
												<a href="{{route('admin.closedticket')}}" class="admintickets"></a>
												<div class="card-body">
													<div class="row">
														<div class="col-8">
															<div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold">Closed Tickets</span>
																<h3 class="mb-0 mt-1 mb-2">{{$closed->count()}}</h3>
															</div>
														</div>
														<div class="col-4">
															<div class="icon1 bg-primary my-auto  float-end"> <i class="las la-ticket-alt"></i> </div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-xl-3 col-lg-6 col-md-12">
											<div class="card">
												<a href="{{route('admin.onholdticket')}}" class="admintickets"></a>
												<div class="card-body">
													<div class="row">
														<div class="col-8">
															<div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold">On-Hold Tickets</span>
															<h3 class="mb-0 mt-1  mb-2">{{$onhold->count()}}</h3> </div>
														</div>
														<div class="col-4">
															<div class="icon1 bg-primary brround my-auto  float-end"> <i class="las la-ticket-alt"></i> </div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-xl-3 col-lg-6 col-md-12">
											<div class="card">
												<a href="{{url('/admin/myticket')}}" class="admintickets"></a>
												<div class="card-body">
													<div class="row">
														<div class="col-8">
															<div class="mt-0 text-start"><span class="fs-14 font-weight-semibold">My Tickets</span>
																<h3 class="mb-0 mt-1 mb-2">{{$myticket->count()}}</h3>
															</div>
														</div>
														<div class="col-4">
															<div class="icon1 bg-primary my-auto  float-end"> <i class="las la-ticket-alt"></i> </div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-xl-3 col-lg-6 col-md-12">
											<div class="card">
												<a href="{{url('/admin/assignedtickets')}}" class="admintickets"></a>
												<div class="card-body">
													<div class="row">
														<div class="col-8">
															<div class="mt-0 text-start"> <span class="fs-14 font-weight-semibold">Assigned Tickets</span>
															<h3 class="mb-0 mt-1  mb-2">{{$assigned->count()}}</h3> </div>
														</div>
														<div class="col-4">
															<div class="icon1 bg-primary brround my-auto  float-end"> <i class="las la-ticket-alt"></i> </div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-xl-3 col-lg-6 col-md-12">
											<div class="card">
												<a href="{{url('/admin/myassignedtickets')}}" class="admintickets"></a>
												<div class="card-body">
													<div class="row">
														<div class="col-8">
															<div class="mt-0 text-start"><span class="fs-14 font-weight-semibold">My Assigned Tickets</span>
																<h3 class="mb-0 mt-1 mb-2">{{$myassigned->count()}}</h3>
															</div>
														</div>
														<div class="col-4">
															<div class="icon1 bg-primary my-auto  float-end"> <i class="las la-ticket-alt"></i> </div>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<div class="col-xl-12 col-lg-12 col-md-12">
											<div class="card mb-0">
												<div class="card-header border-0">
													<h4 class="card-title">Tickets Summary</h4>
												</div>
												<div class="card-body" >
													<div class="table-responsive delete-button" >
													<table class="table table-vcenter text-nowrap table-bordered table-striped w-100 ticketdeleterow" id="supportticket-dashe">
														<thead>
															<tr >
																<th >Id</th>
														<th >S.No</th>
														<th >#ID</th>
														<th >User</th>
														<th >Tilte</th>
														<th >Priority</th>
														<th >Category</th>
														<th >Date</th>
														<th >Status</th>
														<th >Assign to</th>
														<th >Last reply</th> 
														<th >Action</th>
																
															</tr>
														</thead>
														<tbody id="refresh">
															
														</tbody>
													</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--Dashboard List-->

							@endsection
		@section('scripts')

		<!-- INTERNAL Vertical-scroll js-->
		<script src="{{asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js')}}"></script>

		<!-- INTERNAL Data tables -->
		<script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/responsive.bootstrap5.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/datatablebutton.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatable/buttonbootstrap.min.js')}}"></script>


		<!-- INTERNAL Index js-->
		<script src="{{asset('assets/js/support/support-sidemenu.js')}}"></script>
		<script src="{{asset('assets/js/select2.js')}}"></script>

		<!-- INTERNAL Sweet-Alert js-->
		<script src="{{asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		
        <script type="text/javascript">

			"use strict";

			(function($)  {

				// Variables
				var SITEURL = '{{url('')}}';

				@if(Auth::user()->usetting != null)
				@if (Auth::user()->usetting->ticket_refresh == 1)

				// Auto Refresh Datatable js
				setInterval(function(){
					$('#supportticket-dashe').DataTable().ajax.reload();
					
				},30000);
					
				@endif
				@endif

				// csrf field
				$.ajaxSetup({
					headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				// Datatable
				var table = $('#supportticket-dashe').DataTable({
					dom: '<"row"<"col-md-12 col-lg-1"l><"col-md-12 col-lg-4"B><"col-md-12 col-lg-7"f>r>tip',
					buttons: [
						{
							className:'ticketdelete',
							text: `<i class="fe fe-trash"></i> {{trans('langconvert.admindashboard.delete')}}`,
							action: function ( e, dt, node, conf ) {
								var id = [];
								
									$('.checkall:checked').each(function(){
										id.push($(this).val());
									});
								if(id.length > 0){
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
											url:"{{ url('admin/ticket/delete/tickets')}}",
											method:"GET",
											data:{id:id},
											success:function(data)
											{

											$('#supportticket-dashe').DataTable().ajax.reload();
											
											toastr.error(data.error);
												
											},
											error:function(data){

											}
										});
									}
								});
								
							}
							else{
								toastr.error('Please select at least one check box.');
							}
							
								
							}
						},
						{
							className: '',
							text: ` <i class="fe fe-refresh-cw"></i> `,
							action: function ( e, dt, node, config ) {
								$('#supportticket-dashe').DataTable().ajax.reload();
							}
						},	
					],
					processing: true,
					serverSide: true,
					ajax: {
						url: "{{ url('admin') }}",
					},
					columns: [
						
						{data: 'id', name: 'id', 'visible': false},
						{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false,searchable: false},
						
						{ data: 'ticket_id', name: 'ticket_id' },
						{ data: 'user_id', name: 'user_id' },
						
						{ data: 'subject', name: 'subject' },
						{ data: 'priority', name: 'priority' },
						{ data: 'category_id', name: 'category_id' },
						{ data: 'created_at', name: 'created_at' },
						{ data: 'status', name: 'status' },
						{ data: 'toassignuser_id', name: 'toassignuser_id' },
						{ data: 'last_reply', name: 'last_reply' },
						{data: 'action', name: 'action', orderable: false},
					],
					order: [],
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

				//Auto reload on/off
				$('body').on('click', '#myonoffswitch18', function () {
					var _id = $(this).data("id");
					var status = $(this).prop('checked') == true ? '1' : '0';
						$.ajax({
							type: "post",
							url: SITEURL + "/admin/refresh/"+_id,
							data: {'status': status,
							'id': _id},
							success: function (data) {
								if(toastr.success(data.success))
								{
									window.location.reload();
								};
							},
							error: function (data) {
								console.log('Error:', data);
							}
						});
				});

				// TICKET DELETE SCRIPT
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
									url: SITEURL + "/admin/delete-ticket/"+_id,
								success: function (data) {
									toastr.error(data.error);
									var oTable = $('#supportticket-dashe').dataTable();
									oTable.fnDraw(false);
								},
								error: function (data) {
									console.log('Error:', data);
								}
							});
						}
					});

				});
				// TICKET DELETE SCRIPT END

				// when user click its get modal popup to assigned the ticket
				$('body').on('click', '#assigned', function () {
					var assigned_id = $(this).data('id');
					$('.select2_modalassign').select2({
						dropdownParent: ".sprukosearch",
						minimumResultsForSearch: '',
						placeholder: "Search",
						width: '100%'
					});
					$.get('admin/assigned/' + assigned_id , function (data) {
						$('#AssignError').html('');
						$('#assigned_id').val(data.assign_data.id);
						$(".modal-title").text('{{trans('langconvert.admindashboard.assigntoagent')}}');
						$('#username').html(data.table_data);
						$('#addassigned').modal('show');
					});
				});

				// Assigned Submit button
            	$('body').on('submit', '#assigned_form', function (e) {
					e.preventDefault();
					var actionType = $('#btnsave').val();
					var fewSeconds = 2;
					$('#btnsave').html('Sending..');
					$('#btnsave').prop('disabled', true);
						setTimeout(function(){
							$('#btnsave').prop('disabled', false);
						}, fewSeconds*1000);
					var formData = new FormData(this);
					$.ajax({
						type:'POST',
						url: SITEURL + "/admin/assigned/create",
						data: formData,
						cache:false,
						contentType: false,
						processData: false,

						success: (data) => {

							$('#AssignError').html('');
							$('#assigned_form').trigger("reset");
							$('#addassigned').modal('hide');
							$('#btnsave').html('Save Changes');
							var oTable = $('#supportticket-dashe').dataTable();
							oTable.fnDraw(false);
							toastr.success(data.success);
						},
						error: function(data){
							$('#AssignError').html('');
							$('#AssignError').html(data.responseJSON.errors.assigned_user_id);
							$('#btnsave').html('Save Changes');
						}
					});	
				});

				// Remove the assigned from the ticket
                $('body').on('click', '#btnremove', function () {
					var asid = $(this).data("id");
					swal({
						title: `{{trans('langconvert.admindashboard.agentremove')}}`,
						text: "{{trans('langconvert.admindashboard.agentremove1')}}",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {

							$.ajax({
								type: "get",
								url: SITEURL + "/admin/assigned/update/"+asid,
								success: function (data) {
                                var oTable = $('#supportticket-dashe').dataTable();
					            oTable.fnDraw(false);
								toastr.error(data.error);
								
								},
								error: function (data) {
								console.log('Error:', data);
								}
								});

						}
					});
				});

				// checkbox checkall
				$('#customCheckAll').on('click', function() {
					$('.checkall').prop('checked', this.checked);
				});

			})(jQuery);

		</script>

		@endsection

		@section('modal')
		
 		@include('admin.modalpopup.assignmodal')
		@endsection
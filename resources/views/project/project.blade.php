@extends('layouts.home')


@section('maincontent')
<!-- Content area -->
<div class="content">


<!-- Widgets with charts -->
<div class="card text-center bg-info-300">
<div class="mb-3 pt-2 ml-2">
	<h5 class="mb-0">
		LIST OF PROJECT
	</h5>
</div>
</div> 
<div class="row">
	<div class="col-sm-6 col-xl-3">
		<div class="card card-body bg-blue-400 has-bg-image">
			<div class="media">
				<div class="media-body">
					<h3 class="mb-0">75</h3>
					<span class="text-uppercase font-size-xs">jumlah project</span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-stats-bars2 icon-3x opacity-75"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-6 col-xl-3">
		<div class="card card-body bg-danger-400 has-bg-image">
			<div class="media">
				<div class="media-body">
					<h3 class="mb-0">521,123K</h3>
					<span class="text-uppercase font-size-xs">nilai project</span>
				</div>

				<div class="ml-3 align-self-center">
					<i class="icon-bag icon-3x opacity-75"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-6 col-xl-3">
		<div class="card card-body bg-success-400 has-bg-image">
			<div class="media">
				<div class="mr-3 align-self-center">
					<i class="icon-pointer icon-3x opacity-75"></i>
				</div>

				<div class="media-body text-right">
					<h3 class="mb-0">60</h3>
					<span class="text-uppercase font-size-xs">Project Selesai</span>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-6 col-xl-3">
		<div class="card card-body bg-indigo-400 has-bg-image">
			<div class="media">
				<div class="mr-3 align-self-center">
					<i class="icon-enter6 icon-3x opacity-75"></i>
				</div>

				<div class="media-body text-right">
					<h3 class="mb-0">5</h3>
					<span class="text-uppercase font-size-xs">Project Proses</span>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /simple statistics -->

<!-- Dashboard content -->
<div class="row">
	<div class="col-xl-12">

		<!-- Basic columns -->
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">PROJECT</h5>
				<div class="header-elements">
					<div class="list-icons">
						<a class="list-icons-item" data-action="collapse"></a>
						<a class="list-icons-item" data-action="reload"></a>
						<a class="list-icons-item" data-action="remove"></a>
					</div>
				</div>
			</div>

			<div class="card-body">
				<div class="chart-container">
					<div class="chart has-fixed-height" id="columns_basic"></div>
				</div>
			</div>
		</div>
		<!-- /basic columns -->

	</div>

</div>
<!-- /dashboard content -->

<!-- Task manager table -->
<div class="card">
	<div class="card-header bg-transparent header-elements-inline">
		<h6 class="card-title">List Of Project</h6>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="collapse"></a>
				<a class="list-icons-item" data-action="reload"></a>
				<a class="list-icons-item" data-action="remove"></a>
			</div>
		</div>
	</div>

	<table class="table tasks-list table-lg">
		<thead>
			<tr>
				<th>#</th>
				<th>Period</th>
				<th>Project Description</th>
				<th>Priority</th>
				<th>Due Date</th>
				<th>Status</th>
				<th>PIC</th>
				<th class="text-center text-muted" style="width: 30px;"><i class="icon-checkmark3"></i></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>#1</td>
				<td>Today</td>
				<td>
					<div class="font-weight-semibold"><a href="task_manager_detailed.html">Pengadaan Pakaian Seragam</a></div>
					<div class="text-muted">by Telkom</div>
				</td>
				<td>
					<div class="btn-group">
						<a href="#" class="badge bg-orange dropdown-toggle" data-toggle="dropdown">High</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="#" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
						</div>
					</div>
				</td>
				<td>
					<div class="d-inline-flex align-items-center">
						<i class="icon-calendar2 mr-2"></i>
						<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="22 January, 21">
					</div>
				</td>
				<td>
					<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
						<option value="open">Open</option>
						<option value="hold">On hold</option>
						<option value="resolved" selected="selected">Resolved</option>
						<option value="closed">Closed</option>
					</select>
				</td>
				<td>
					Person B
					
					
				</td>
				<td class="text-center">
					<div class="list-icons">
						<div class="dropdown">
							<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
							<div class="dropdown-menu dropdown-menu-right">
								<a href="#" class="dropdown-item"><i class="icon-alarm-add"></i> Detail Project</a>
								
								
								<div class="dropdown-divider"></div>
								<a href="#" class="dropdown-item"><i class="icon-pencil7"></i> Edit project</a>
								<a href="#" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
							</div>
						</li>
					</div>
				</td>
			</tr>

			<tr>
				<td>#2</td>
				<td>Today</td>
				<td>
					<div class="font-weight-semibold"><a href="task_manager_detailed.html">Event Organizer A</a></div>
					<div class="text-muted">By Telkom</div>
				</td>
				<td>
					<div class="btn-group">
						<a href="#" class="badge bg-orange dropdown-toggle" data-toggle="dropdown">High</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="#" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
						</div>
					</div>
				</td>
				<td>
					<div class="d-inline-flex align-items-center">
						<i class="icon-calendar2 mr-2"></i>
						<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="22 January, 21">
					</div>
				</td>
				<td>
					<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
						<option value="open">Open</option>
						<option value="hold">On hold</option>
						<option value="resolved">Resolved</option>
						<option value="closed">Closed</option>
					</select>
				</td>
				<td>
					Person AAA
				</td>
				<td class="text-center">
					<div class="list-icons">
						<div class="dropdown">
							<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
							<div class="dropdown-menu dropdown-menu-right">
								<a href="#" class="dropdown-item"><i class="icon-alarm-add"></i> Detail Project</a>
								
								
								<div class="dropdown-divider"></div>
								<a href="#" class="dropdown-item"><i class="icon-pencil7"></i> Edit project</a>
								<a href="#" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
							</div>
						</li>
					</div>
				</td>
			</tr>

			<tr>
				<td>#3</td>
				<td>Today</td>
				<td>
					<div class="font-weight-semibold"><a href="task_manager_detailed.html">Perbaikan Ruangan A</a></div>
					<div class="text-muted">By Telkom</div>
				</td>
				<td>
					<div class="btn-group">
						<a href="#" class="badge bg-primary dropdown-toggle" data-toggle="dropdown">Normal</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
							<a href="#" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
						</div>
					</div>
				</td>
				<td>
					<div class="d-inline-flex align-items-center">
						<i class="icon-calendar2 mr-2"></i>
						<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="22 January, 21">
					</div>
				</td>
				<td>
					<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
						<option value="open">Open</option>
						<option value="hold" selected="selected">On hold</option>
						<option value="resolved">Resolved</option>
						
						
						
						<option value="closed">Closed</option>
					</select>
				</td>
				<td>
						Person X
					
					
				</td>
				<td class="text-center">
					<div class="list-icons">
						<div class="dropdown">
							<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
							<div class="dropdown-menu dropdown-menu-right">
								<a href="#" class="dropdown-item"><i class="icon-alarm-add"></i> Detail Project</a>
								
								
								<div class="dropdown-divider"></div>
								<a href="#" class="dropdown-item"><i class="icon-pencil7"></i> Edit project</a>
								<a href="#" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
							</div>
						</li>
					</div>
				</td>
			</tr>

			<tr>
				<td>#4</td>
				<td>Today</td>
				<td>
					<div class="font-weight-semibold"><a href="task_manager_detailed.html">Pengadaan Makanan Acara A</a></div>
					<div class="text-muted">By Telkom</div>
				</td>
				<td>
					<div class="btn-group">
						<a href="#" class="badge bg-primary dropdown-toggle" data-toggle="dropdown">Normal</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
							<a href="#" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
						</div>
					</div>
				</td>
				<td>
					<div class="d-inline-flex align-items-center">
						<i class="icon-calendar2 mr-2"></i>
						<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="22 January, 21">
					</div>
				</td>
				<td>
					<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
						<option value="open">Open</option>
						<option value="hold">On hold</option>
						<option value="resolved" selected="selected">Resolved</option>
						<option value="closed">Closed</option>
					</select>
				</td>
				<td>
					Person CC
					
					
					
				</td>
				<td class="text-center">
					<div class="list-icons">
						<div class="dropdown">
							<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
							<div class="dropdown-menu dropdown-menu-right">
								<a href="#" class="dropdown-item"><i class="icon-alarm-add"></i> Detail Project</a>
								
								
								<div class="dropdown-divider"></div>
								<a href="#" class="dropdown-item"><i class="icon-pencil7"></i> Edit project</a>
								<a href="#" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
							</div>
						</li>
					</div>
				</td>
			</tr>

			<tr>
				<td>#5</td>
				<td>Today</td>
				<td>
					<div class="font-weight-semibold"><a href="task_manager_detailed.html">Pengadaan Furniture</a></div>
					<div class="text-muted">By Telkom</div>
				</td>
				<td>
					<div class="btn-group">
						<a href="#" class="badge bg-orange dropdown-toggle" data-toggle="dropdown">High</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
							<a href="#" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
						</div>
					</div>
				</td>
				<td>
					<div class="d-inline-flex align-items-center">
						<i class="icon-calendar2 mr-2"></i>
						<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="21 January, 21">
					</div>
				</td>
				<td>
					<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
						<option value="open">Open</option>
						<option value="hold">On hold</option>
						<option value="resolved">Resolved</option>
						
						<option value="invalid" selected="selected">Invalid</option>
						
						<option value="closed">Closed</option>
					</select>
				</td>
				<td>
					Person A
				</td>
				<td class="text-center">
					<div class="list-icons">
						<div class="dropdown">
							<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
							<div class="dropdown-menu dropdown-menu-right">
								<a href="#" class="dropdown-item"><i class="icon-alarm-add"></i> Detail Project</a>
								
								
								<div class="dropdown-divider"></div>
								<a href="#" class="dropdown-item"><i class="icon-pencil7"></i> Edit project</a>
								<a href="#" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
							</div>
						</li>
					</div>
				</td>
			</tr>

			<tr>
				<td>#6</td>
				<td>Today</td>
				<td>
					<div class="font-weight-semibold"><a href="task_manager_detailed.html">Event Organizer X</a></div>
					<div class="text-muted">By Telkom</div>
				</td>
				<td>
					<div class="btn-group">
						<a href="#" class="badge bg-danger dropdown-toggle" data-toggle="dropdown">Blocker</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="#" class="dropdown-item active"><span class="badge badge-mark mr-2 bg-danger border-danger"></span> Blocker</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-orange border-orange"></span> High priority</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-primary border-primary"></span> Normal priority</a>
							<a href="#" class="dropdown-item"><span class="badge badge-mark mr-2 bg-success border-success"></span> Low priority</a>
						</div>
					</div>
				</td>
				<td>
					<div class="d-inline-flex align-items-center">
						<i class="icon-calendar2 mr-2"></i>
						<input type="text" class="form-control datepicker p-0 border-0 bg-transparent" value="22 January, 21">
					</div>
				</td>
				<td>
					<select name="status" class="form-control form-control-select2" data-placeholder="Select status" data-fouc>
						<option value="open">Open</option>
						<option value="hold">On hold</option>
						<option value="resolved" selected="selected">Resolved</option>
						<option value="closed">Closed</option>
					</select>
				</td>
				<td>
					Person C
					
				</td>
				<td class="text-center">
					<div class="list-icons">
						<div class="dropdown">
							<a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown"><i class="icon-menu9"></i></a>
							<div class="dropdown-menu dropdown-menu-right">
								<a href="#" class="dropdown-item"><i class="icon-eye8"></i> Detail Project</a>
								
								
								<div class="dropdown-divider"></div>
								<a href="#" class="dropdown-item"><i class="icon-pencil7"></i> Edit project</a>
								<a href="#" class="dropdown-item"><i class="icon-cross2"></i> Remove</a>
							</div>
						</li>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<!-- /task manager table -->
</div>
<!-- /content area -->
@endsection
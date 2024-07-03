@extends('layouts.admin')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
          <div class="card mb-4">
                @if(session()->has('status'))
                <div class="alert alert-success">
                    {{ session()->get('status') }}
                </div>
                @endif
                @if(session()->has('Fail'))
                <div class="alert alert-danger">
                    {{ session()->get('Fail') }}
                </div>
                @endif
                <div class="d-flex card-header justify-content-between">
                    <div class="pt-2">
                        <h6>Job Description</h6>
                    </div>
                    <div class="pb-0">
                        <a href="{{ route('user_add_form') }}" class="btn btn-sm bg-gradient-primary btn-round mb-0 me-1">Add Job</a>
                    </div>
                </div>
               <div class="card-body pb-2">
                    <div class="table-responsive">
                         <table class="table table-bordered align-items-center mb-0" id="mytable">
                           <thead>
                                 <tr>
                                    <th style="width:7px;">No</th>
                                    <th>Job Description</th>
                                    <th>Industry Type</th>
                                    <th  style="width:10px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>1</td>
                                    <td>XXXXXXXXXXXXXX XXXX</td>
                                    <td>XXXXXXXX</td>
                                     <td >
                                        <a href="#!" class="ft-edit text-primary"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> &nbsp;&nbsp;&nbsp;
                                        <a href="#!" class="ft-trash text-danger"> <i class="fa fa-trash-o" aria-hidden="true"></i> </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>




    </div>
</div>

@endsection
@section('js')

<script>
$(document).ready(function() {
    $('#mytable').DataTable();
});
</script>
@endsection
@extends('layouts.admin')
@section('content')
<div class="container-fluid py-4">
      <div class="row">
      <div class="col-12">
          <div class="card mb-4">
         
          <div class="card-header">
              <h6>Job Description</h6>
            </div>
            
                <div class="card-body">
                  <form role="form" method="post" action="" enctype="multipart/form-data">
                  @csrf
                  
                    
                    
                  <label>Departments</label><span class="text-danger"> *</span>
                    <div class="mb-3">
                      <input type="text" class="form-control" name="departments" placeholder="job description">
                      @error('departments')
                                <div class="alert alert-danger">{{ $departments }}</div>
                            @enderror
                    </div>
                    
                    
                    <div class="text-center col-2">
                      <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Submit</button>
                    </div>
                  </form>
                </div>
               
                </div>
                </div>


      </div>
      </div>

@endsection
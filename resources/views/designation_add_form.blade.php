@extends('layouts.admin')
@section('content')
<div class="container-fluid py-4">
      <div class="row">
      <div class="col-12">
          <div class="card mb-4">
         
            <div class="card-header">
              <h6>Designation</h6>
            </div>
            
            
                <div class="card-body">
                  <form role="form" method="post" action="" enctype="multipart/form-data">
                  @csrf
                  
                    
                    
                    <label>Designation</label><span class="text-danger"> *</span>
                    <div class="mb-3">
                      <input type="text" class="form-control" name="departments" placeholder="job description">
                      @error('Designation')
                                <div class="alert alert-danger">{{ $Designation }}</div>
                            @enderror
                    </div>
                    
                    <label>Departments</label><span class="text-danger"> *</span>
                    <div class="mb-3">
                      <select class="form-control" name="industrytype" id="industrytype">
                        <option value="">Choose Departments</option>
                        <option value="">1</option>
                        <option value="">2</option>
                        <option value="">3</option>
                      </select>
                      @error('industrytype')
                          <div class="alert alert-danger">{{ $industrytype }}</div>
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
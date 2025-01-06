@extends("master")

@section("content")

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            @if (session('msg'))
                <div class="alert alert-success mt-4">
                    {{ session('msg') }}
                </div>
            @endif
            
            <form method="POST" action="{{ url('transfered') }}">
            @csrf
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                Budget Transfer
                </div>
                <div class="card-body">
                <div class="row"> 
                    <div class="col-md-6 col-lg-6">      
                                <label for="from_year_id">FROM:</label>
                                <select class="custom-select" name="from_year_id" required>
                                <option value=''>Select Year here</option>
                                @foreach ($swap_from as $year)
                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                                @endforeach
                                </select>  
                                <span class="small text-danger">{{ $errors->first('from_year_id') }}</span>
                    </div>
                    <div class="col-md-6 col-lg-6">      
                                <label for="to_year_id">TO:</label>
                                <select class="custom-select" name="to_year_id" required>
                                <option value=''>Select Year here</option>
                                @foreach ($swap_to as $year)
                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                                @endforeach
                                </select>    
                                <span class="small text-danger">{{ $errors->first('to_year_id') }}</span>
                    </div>
                </div>
                <div class="row mt-4"> 
                    <div class="col-md-12 col-lg-12"> 
                        <button type="submit" name="swap" class="btn btn-success float-right">Transfer this budget</button>
                    </div>
                </div>
            </div>  
            </div>
            </form>   
        </div>
    </main>
    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; Your Website 2020</div>
                <div>
                    <a href="#">Privacy Policy</a>
                    &middot;
                    <a href="#">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </footer>
</div>
           
@endsection
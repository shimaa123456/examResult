@extends("layout")

@php
    $pageName = "grade";
@endphp


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Add New grade</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('grade.index') }}"> Back</a>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('grade.store') }}" method="POST">
    @csrf

    <div class="row mb-3" style="margin-top: 50px;">
        <label for="gradeName" class="col-sm-2 col-form-label">Grade Name</label>
        <div class="col-sm-10">
            <input type="gradeName" class="form-control" id="gradeName" name="gradeName">
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Create grade</button>

</form>
@endsection



@extends('layout.master')
@push('stylesheets')
    <link href="{{ asset('') }}assets/css/tab.min.css" rel="stylesheet" type="text/css" />
@endpush
@section('title', 'SMS Contact/type - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h6>Contact Group Name</h6>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>
                    </div>
                    @include('communication::contacts.type.bodyPartial.index')
                </div>
            </div>
        </div>
    </div>

    <form id="deleted_units_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

@endsection
@push('scripts')
    @include('communication::contacts.type.contact_type_script')
@endpush

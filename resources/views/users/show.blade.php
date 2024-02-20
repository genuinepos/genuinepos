@extends('layout.master')
@push('stylesheets')

@endpush
@section('title', 'View User - ')
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>{{ __("View User") }}</h6>
            </div>

            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
        </div>
        <div class="container-fluid p-0">
            <form id="add_user_form" action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="p-1">
                    <div class="row gx-1">

                        <div class="col-md-6">
                            <div class="form_element rounded mt-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="display table table-sm">
                                                <tr>
                                                    <th colspan="2" class="text-primary"><b>{{ __("Role Permission") }}</b></th>
                                                </tr>

                                                <tr>
                                                    <th width="50">{{ __("Username") }}</th>
                                                    <td width="50">: {!! $user->username ? $user->username : '<span class="badge bg-secondary">'.__("Not-Allowed-to-Login") .'</span>' !!}</td>
                                                </tr>
                                                <tr>
                                                    <th width="50">{{ __("Role") }}</th>
                                                    <td width="50">:
                                                        @if ($user->role_type == 1)
                                                            Super-Admin
                                                        @elseif($user->role_type == 2)
                                                            Admin
                                                        @elseif($user->role_type == 3)
                                                            {{ $user?->roles?->first()?->name }}
                                                        @else
                                                            <span class="badge bg-warning">No-Role</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th colspan="2" class="text-primary"><b>{{ __("Personal Information") }}</b></th>
                                                </tr>

                                                <tr>
                                                    <th width="50">{{ __("Date of Birth") }}</th>
                                                    <td width="50">: {{ $user->date_of_birth }}</td>
                                                </tr>

                                                <tr>
                                                    <th width="50">{{ __("Gender") }}</th>
                                                    <td width="50">: {{ $user->gender }}</td>
                                                </tr>

                                                <tr>
                                                    <th width="50">{{ __("Marital Status") }}</th>
                                                    <td width="50">: {{ $user->marital_status }}</td>
                                                </tr>

                                                <tr>
                                                    <th width="50">{{ __("Blood Group") }}</th>
                                                    <td width="50">: {{ $user->blood_group }}</td>
                                                </tr>

                                                <tr>
                                                    <th width="50">{{ __("Phone Number") }}</th>
                                                    <td width="50">: {{ $user->phone }}</td>
                                                </tr>

                                                <tr>
                                                    <th width="50">{{ __("Id Proof Name") }}</th>
                                                    <td width="50">: {{ $user->id_proof_name }}</td>
                                                </tr>

                                                <tr>
                                                    <th width="50">{{ __("Id Proof Number") }}</th>
                                                    <td width="50">: {{ $user->id_proof_number }}</td>
                                                </tr>

                                                <tr>
                                                    <th colspan="2" class="text-primary"><b>{{ __("Bank Information") }}</b></th>
                                                </tr>

                                                <tr>
                                                    <th width="50">{{ __("Account Name") }}</th>
                                                    <td width="50">: {{ $user->bank_ac_holder_name }}</td>
                                                </tr>

                                                <tr>
                                                    <th width="50">{{ __("Account Number") }}</th>
                                                    <td width="50">: {{ $user->bank_ac_no }}</td>
                                                </tr>

                                                <tr>
                                                    <th width="50">{{ __("Account Number") }}</th>
                                                    <td width="50">: {{ $user->bank_name }}</td>
                                                </tr>

                                                <tr>
                                                    <th width="50">{{ __("Bank Identifier Code") }}</th>
                                                    <td width="50">: {{ $user->bank_identifier_code }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form_element rounded mt-0 mb-3">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="display table table-sm">
                                                <tr>
                                                    <th colspan="2" class="text-primary"><b>{{ __("Basic Information") }}</b></th>
                                                </tr>

                                                <tr>
                                                    <th>{{ __("Full Name") }}</th>
                                                    <td>: {{ $user->prefix.' '.$user->name.' '.$user->last_name }} </td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __("Email") }}</th>
                                                    <td>:
                                                        {{ $user->email}}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __("Phone") }}</th>
                                                    <td>:
                                                        {{ $user->phone}}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th colspan="2" class="text-primary"><b>{{ __("Other Information") }}</b></th>
                                                </tr>

                                                <tr>
                                                    <th>{{ __("Guardian Name") }}</th>
                                                    <td>: {{ $user->guardian_name }}</td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __("Facebook Link") }}</th>
                                                    <td>: {{ $user->facebook_link }}</td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __("X Link") }}</th>
                                                    <td>: {{ $user->twitter_link }}</td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __("Instagram Link") }}</th>
                                                    <td>: {{ $user->instagram_link }}</td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __("Permanent Address") }}</th>
                                                    <td>: {{ $user->permanent_address }}</td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __("Current Address") }}</th>
                                                    <td>: {{ $user->current_address }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')

@endpush

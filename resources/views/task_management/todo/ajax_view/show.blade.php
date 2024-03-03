<div class="modal-dialog col-55-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("View Todo") }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body" id="show_modal_body">
            <div class="row">
                <div class="col-md-4">
                    <p><b>{{ __("Todo ID") }} : </b>{{ $todo->todo_no }}</p>
                    <p><b>{{ __("Entry Date") }} : </b>{{ date($generalSettings['business_or_shop__date_format'], strtotime($todo->created_at)) }}</p>
                    <p><b>{{ __("Task") }} : </b> {{ $todo->task }}</p>
                </div>

                <div class="col-md-4">
                    <p><b>{{ __("Due Date") }} : </b>{{ date($generalSettings['business_or_shop__date_format'], strtotime($todo->due_date)) }}</p>
                    <p><b>{{ __("Status") }} : </b> {{ $todo->status }}</p>
                    <p><b>{{ __("Priority") }} : </b> {{ $todo->priority }}</p>
                </div>

                <div class="col-md-4">
                    <p><b>{{ __("Assigned By") }} : </b> {{ $todo?->createdBy?->prefix . ' ' . $todo?->createdBy?->name . ' ' . $todo?->createdBy?->last_name }}</p>
                    <p><b>{{ __("Assigned To") }} : </b>
                        @foreach ($todo->users as $todoUser)
                            {{ $todoUser?->user?->prefix . ' ' . $todoUser?->user?->name . ' ' . $todoUser?->user?->last_name }},
                        @endforeach
                    </p>
                </div>
                <hr class="mt-1">
            </div>

            <div class="row">
                <p><b>{{ __("Short Description") }}</b></p>
                <p>{{ $todo->description }}</p>
            </div>
        </div>
    </div>
</div>

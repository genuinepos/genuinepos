<x-saas::admin>
    <div class="container">
        <div class="card mt-3">
            <div class="card-header">
                Welcome <b>{{ auth()?->user()?->name }}</b> to your Dashbaord
            </div>
            <div class="card-body">
                
            </div>
        </div>
    </div>
</x-saas::admin>
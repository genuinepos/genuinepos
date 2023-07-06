<h2>Welcome to our app {{ config('app.name') }}</h2>

<h6>Welcome {{ $customer->name }}</h6>
<hr>
<p>You have registered with this  email: {{ $customer->email }}</p>

<button class="btn btn-primary">More Action</button>


@php
    use Carbon\Carbon;
@endphp

@foreach ($messages as $msg)
    @if ($msg->user_id == auth()->user()->id)
        <div class="message-box">
            <div class="user-block">
                @if ($msg->user_id == auth()->user()->id && auth()->user()->can('messages_delete'))
                    <a href="{{ route('messages.delete', $msg->id) }}" class="float-end delete_message" id="delete"><i class="fas fa-trash"></i></a>
                @endif

                <div class="left">
                    <p class="user">{{ $msg->user_id == auth()->user()->id ? 'Me' : $msg->u_prefix . ' ' . $msg->u_name . ' ' . $msg->u_last_name }}</p>
                    <p class="message-time"><i class="far fa-clock"></i> {{ Carbon::parse($msg->created_at)->diffForHumans() }}</p>
                </div>
            </div>

            <div class="message-text text-end">
                <p>{{ $msg->description }}</p>
            </div>
        </div>
    @else
        <div class="message-box row">
            <div class="left row">
                <p><span class="user">{{ $msg->user_id == auth()->user()->id ? 'Me' : $msg->u_prefix . ' ' . $msg->u_name . ' ' . $msg->u_last_name }}</span> <span class="message-time text-muted"><i class="far fa-clock"></i> {{ Carbon::parse($msg->created_at)->diffForHumans() }}</span></p>
            </div>

            <div class="message-text">
                <p>{{ $msg->description }}</p>
            </div>
        </div>
    @endif
@endforeach

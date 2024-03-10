<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $subject }}</title>
</head>
<body>
    <h1>{{ $subject }}</h1>
    <p></p>
    
    @if (!empty($attachments))
        @foreach($attachments as $attachment)
            <a href="{{ Storage::url($attachment['path']) }}">{{ $attachment['name'] }}</a>
        @endforeach
    @else
        <p>No attachments</p>
    @endif
</body>
</html>

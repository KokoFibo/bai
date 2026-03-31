@php
    $data = json_decode($a->properties, true);
    $olds = $data['old'] ?? [];
    $news = $data['attributes'] ?? [];
@endphp
@foreach ($news as $key => $value)
    <strong>{{ ucfirst($key) }}: {{ number_format($value) }}</strong><br>
@endforeach

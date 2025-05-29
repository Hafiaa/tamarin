<!DOCTYPE html>
<html>
<head>
    <title>Test Images</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .image-container { margin-bottom: 30px; }
        .image-container img { max-width: 400px; height: auto; margin-top: 10px; border: 1px solid #ddd; }
        .url { font-family: monospace; background: #f5f5f5; padding: 5px; word-break: break-all; }
    </style>
</head>
<body>
    <h1>Test Image Access</h1>
    @foreach($images as $name => $url)
        <div class="image-container">
            <h2>{{ ucfirst($name) }}</h2>
            <div class="url">{{ $url }}</div>
            <img src="{{ $url }}" alt="{{ $name }}">
        </div>
    @endforeach
</body>
</html>

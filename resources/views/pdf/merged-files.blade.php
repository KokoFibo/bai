<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Merge</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .file-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        img {
            max-width: 90%;
            height: auto;
            margin-bottom: 10px;
        }

        .page-break {
            page-break-after: always;
            display: block;
            height: 0;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <h2>Files from Folder: {{ $nama_karyawan }}</h2>
    @foreach ($data as $index => $file)
        <div>
            <p class="file-name">{{ $file['name'] }}</p>
            @if ($file['type'] === 'image')
                <img src="{{ $file['path'] }}" alt="{{ $file['name'] }}">
            @else
                <p>File: {{ $file['name'] }}</p>
            @endif
        </div>

        {{-- Page break hanya ditambahkan jika bukan file terakhir --}}
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>

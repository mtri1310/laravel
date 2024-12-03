<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMDB Auto-Complete</title>
</head>
<body>
    <h1>IMDB Auto-Complete Results</h1>

    @if(isset($data['d']))
        <ul>
            @foreach($data['d'] as $item)
                <li>
                    <strong>{{ $item['l'] }}</strong> ({{ $item['s'] }})
                    <br>
                    <em>{{ $item['y'] }}</em>
                </li>
            @endforeach
        </ul>
    @else
        <p>No results found</p>
    @endif
</body>
</html>

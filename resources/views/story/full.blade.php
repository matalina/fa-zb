<!doctype html>
<html lang="en">
    <head>
        <title>The Story so Far...</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous"/>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                     <h1>
                        The Story So Far...
                    </h1>

                    <table class="table table-hover table-sm">
                        <thead class="thead-inverse">
                            <tr>
                                <th>&nbsp;</th>
                                <th>Date</th>
                                <th>Thread</th>
                                <th>Participants</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($story->all() as $s)
                            <tr>
                                <td>
                                    @if($s['end_date']->gt(Carbon\Carbon::yesterday()))
                                        New
                                    @endif
                                </td>
                                <td>{{ $s['start_date']->toDayDateTimeString() }}</td>
                                <td>
                                    <a href="{{ $s['link'] }}">
                                        {{ $s['title'] }}
                                    </a> on {{ $s['forum'] }}
                                </td>
                                <td>{{ $s['characters'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </body>
</html>

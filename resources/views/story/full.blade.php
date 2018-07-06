<!doctype html>
<html lang="en">
    <head>
        <title>The Story so Far...</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous"/>
        <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h1>
                        The Story So Far...<br/>
                        <small>The First Age from beginning to present</small>
                    </h1>
                    
                    <p class="text-center">
                         <strong>Legend: </strong>
                         <span class="text-success"><i class="fas fa-certificate fa-fw"></i></span> Updated in the past day 
                         <span class="text-warning"><i class="fas fa-certificate fa-fw"></i></span> Updated this past week 
                         <span class="text-muted"><i class="fas fa-certificate fa-fw"></i></span> Updated this past month 
                    </p>
                    
                    
                    <table class="table table-hover table-sm">
                        <thead class="thead-inverse">
                            <tr>
                                <th>&nbsp;</th>
                                <th style="width: 15rem;">Date</th>
                                <th style="width: 30rem;">Thread</th>
                                <th>Participants</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($story->all() as $s)
                            <tr>
                                <td>
                                    @if($s['end_date']->gt(Carbon\Carbon::yesterday()))
                                        <span class="text-success"><i class="fas fa-certificate fa-3x fa-fw"></i></span>
                                    @elseif($s['end_date']->gt(Carbon\Carbon::now()->subDays(7)))
                                        <span class="text-warning"><i class="fas fa-certificate fa-3x fa-fw"></i></span>
                                    @elseif($s['end_date']->gt(Carbon\Carbon::now()->subMonth(1)))
                                        <span class="text-muted"><i class="fas fa-certificate fa-3x fa-fw"></i></span>
                                    @endif
                                </td>
                                <td>{{ $s['start_date']->toDayDateTimeString() }}</td>
                                <td>
                                    <a href="{{ $s['link'] }}">
                                        {{ $s['title'] }}
                                    </a><br/>
                                    <small>on {{ $s['forum'] }}</small>
                                </td>
                                <td>{{ $s['characters'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <p class="text-center">
                         <strong>Legend: </strong>
                         <span class="text-success"><i class="fas fa-certificate fa-fw"></i></span> Updated in the past day 
                         <span class="text-warning"><i class="fas fa-certificate fa-fw"></i></span> Updated this past week 
                         <span class="text-muted"><i class="fas fa-certificate fa-fw"></i></span> Updated this past month 
                    </p>
                    
                </div>
                
            </div>
        </div>
    </body>
</html>

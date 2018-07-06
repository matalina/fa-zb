Today's word is <strong>{{ $word->word }}</strong>. Get the definition from <a href="http://www.dictionary.com/browse/{{ strtolower($word->word) }}">dictionary.com</a> or from 
<a href="https://www.urbandictionary.com/define.php?term={{ strtolower($word->word) }}">the urban dictionary</a>

<p><small>
If you use a WordPress, pingbacks should automatically track back to this page if you put in a link.  If you use another blogging system, post your link in the comments so others can follow your work.  Spam will be deleted.
    </small></p>


<p><small>
Last used on: {{ Carbon\Carbon::parse($word->last_used)->format('M j, Y') }}<br/>
Times used: {{ $word->times_used }}
</small></p>

[tags Daily Prompt, postaday, Word Of The Day, Daily Post, Write Every Day]

[category Word]

[end]
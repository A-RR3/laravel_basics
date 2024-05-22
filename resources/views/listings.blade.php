<h1>{{$heading}}</h1>
{{-- @php
$tt=1;
@endphp --}}

@if(count($listings) == 0)
<p>No listings found</p>
@endif

@foreach($listings as $listing)
    <h1><a href='listings/{{$listing['id']}}'>{{ $listing['title']}}</a></h1>
    <p>{{ $listing['description']}}</p>

@endforeach


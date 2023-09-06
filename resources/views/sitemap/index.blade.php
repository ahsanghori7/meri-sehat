@php
    // $base_url = 'https://www.merisehat.pk';
    $base_url = rtrim(env('APP_URL', 'https://www.merisehat.pk/'), '/');
    $changefreq = 'monthly';
    $priority = '0.5';
@endphp
<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{$base_url}}</loc>
        <priority>1</priority>
    </url>
    {{--
        <url>
            <loc>{{$base_url}}/ur</loc>
            <priority>1</priority>
        </url>
        --}}
    @foreach ($footers as $footer)
        <url>
            <loc>{{$base_url.'/'.ltrim($footer['link'], '/')}}</loc>
            <priority>{{$priority}}</priority>
        </url>
    @endforeach

    @foreach ($articles as $article)

            @if($article['language']['id'] != 1)
                {{--
                 <url>
                <loc>{{$base_url.'/'.$article['language']['slug'].'/article/'.$article['slug']}}</loc>
                <priority>{{$priority}}</priority>
                </url>
                --}}
            @else
            <url>
                <loc>{{$base_url.'/article/'.$article['slug']}}</loc>
                <priority>{{$priority}}</priority>
            </url>
            @endif


    @endforeach

    @foreach ($diseases as $disease)

            @if($disease['language']['id'] != 1)
                {{-- <url> <loc>{{$base_url.'/'.$disease['language']['slug'].'/disease/'.$disease['slug']}}</loc>
                 <priority>{{$priority}}</priority></url>
                 --}}
            @else
            <url>
                <loc>{{$base_url.'/disease/'.$disease['slug']}}</loc>
                <priority>{{$priority}}</priority>
            </url>
            @endif


    @endforeach

    {{--
        @foreach ($drugs as $drug)
             <url>
                 @if($drug['language']['id'] != 1)
          <loc>{{$base_url.'/'.$drug['language']['slug'].'/drug/'.$drug['slug']}}</loc>
           <loc>{{$base_url.'/drug/'.$drug['slug']}}</loc>
        @else

        @endif
        <priority>{{$priority}}</priority>
        </url>
    @endforeach
--}}
</urlset>

@foreach($articles as $article)

@include("pub::models.article.standard")

@endforeach
@if(method_exists($articles, 'links'))
<div class="text-center">
{{$articles->appends(['q' => request('q')])->links()}}		
</div>
@endif

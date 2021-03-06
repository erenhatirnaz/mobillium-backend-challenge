<x-layout>
    <x-slot name="title">
        {{ $article->title }} - {{ config('app.name', 'Mobillium Back-end Challange') }}
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">{{ $article->title }}</div>
                <div class="card-body">
                    <p class="text-muted">
                        Published at {{ $article->published_at->diffForHumans() }}
                        @if ($article->published_at->notEqualTo($article->updated_at))
                        and last modified at {{ $article->updated_at->diffForHumans() }}
                        @endif
                    </p>
                    <p class="card-text">{!! $article->content !!}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 d-flex justify-content-lg-around">
            @if ($article->next_article_link)
            <a href="{{ $article->next_article_link }}" class="btn btn-primary">< Next Article</a>
            @endif
            @if ($article->previous_article_link)
            <a href="{{ $article->previous_article_link }}" class="btn btn-primary">Previous Article ></a>
            @endif
        </div>
    </div>
</x-layout>

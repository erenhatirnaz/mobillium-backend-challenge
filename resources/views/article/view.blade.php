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
                    <br/>
                    <p class="card-text">Rating: {{ $article->average_rating }}</p>
                    @auth
                    @if ($vote = Auth::user()->hasVote($article->id))
                    <p class="card-text">
                        Your vote: {{ $vote->rating }}
                        <a href="{{ route('vote.delete') }}" onclick="event.preventDefault(); document.getElementById('form-delete-vote').submit();">Delete my vote</a>

                        <form id="form-delete-vote" action="{{ route('vote.delete') }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="vote_id" value="{{ $vote->id }}"/>
                        </form>
                    </p>
                    @else
                    <form action="{{ route('vote.add') }}" class="form-inline" method="post">
                        @csrf
                        <input type="hidden" name="article-id" value="{{ $article->id }}"/>
                        <label for="rating">Vote this article: </label>
                        <input class="form-control form-control-sm" type="number" id="rating" name="rating" min="1" value="3" max="5">
                        <button class="btn btn-success">Vote!</button>
                    </form>
                    @endif
                    @endauth
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

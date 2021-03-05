<x-layout>
    <div class="row">
        @foreach ($posts as $post)
        <div class="col-lg-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">{{ $post->title }}</h3>
                    <h6>Author: {{ $post->user->full_name }}</h6>
                    <p class="card-text">{{ $post->content_summary }}</p>
                    <p class="card-text"><small class="text-muted">Created at {{ $post->created_at->diffForHumans() }}</small></p>
                    <a href="{{ $post->link }}" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-layout>

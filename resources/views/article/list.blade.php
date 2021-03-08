<x-layout>
    <x-slot name="title">
        {{ $panelName }}
        - {{ config('app.name', 'Mobillium Back-End Challenge') }}
    </x-slot>

    <x-slot name="panelHome">
        @if (Route::is('admin.*'))
        {{ route('admin.dashboard') }}
        @else
        {{ route('writer.dashboard') }}
        @endif
    </x-slot>

    <x-slot name="panelName">
        {{ $panelName }}
    </x-slot>

    <div class="row">
        <div class="col-12 d-flex justify-content-start mb-3">
            <a href="{{ route('article.create') }}" class="btn btn-primary">Create New Article</a>
        </div>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Title</th>
                @if (Auth::user()->hasRole('admin'))
                <th>Author</th>
                @endif
                <th scope="col">Created At</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($articles as $article)
            <tr>
                <th scope="row">{{ $article->title }}</th>
                @if (Auth::user()->hasRole('admin'))
                <td>{{ $article->user->full_name }}</td>
                @endif
                <td>{{ $article->created_at }}</td>
                <td>
                    {{ $article->status }}
                    @if ($article->status == "SCHEDULED")
                    <br/>
                    ({{$article->published_at}})
                    @endif
                </td>
                <td class="d-flex justify-content-end">
                    <a href="{{ route('article.editPage', ['id' => $article->id]) }}" class="btn btn-sm btn-info">Edit</a>
                    @if ($article->status == "DRAFT" || $article->status == "SCHEDULED")
                    <form method="POST" action="{{ route('article.publish', ['id' => $article->id]) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" href="{{ route('article.publish', ['id' => $article->id]) }}" class="btn btn-sm btn-primary">Publish</button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('article.unpublish', ['id' => $article->id]) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" href="{{ route('article.unpublish', ['id' => $article->id]) }}" class="btn btn-sm btn-primary">Unpublish</button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('article.delete', ['id' => $article->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" href="{{ route('article.delete', ['id' => $article->id]) }}" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-layout>

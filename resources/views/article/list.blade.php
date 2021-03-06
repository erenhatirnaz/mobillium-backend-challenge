<x-layout>
    <x-slot name="title">
        {{ $panelName }}
        - {{ config('app.name', 'Mobillium Back-End Challange') }}
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
        <div class="col-12 d-flex justify-content-end mb-3">
            <a href="#" class="btn btn-primary">Create New Article</a>
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
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="#" class="btn btn-info">Edit</a>
                        @if ($article->status == "DRAFT" || $article->status == "SCHEDULED")
                        <a href="#" class="btn btn-primary">Publish</a>
                        @else
                        <a href="#" class="btn btn-primary">Unpublish</a>
                        @endif
                        <a href="#" class="btn btn-danger">Delete</a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-layout>

<x-layout>
    <x-slot name="title">
        @if (!isset($article))
        Create New Article
        @else
        Edit Article
        @endif
        - {{ config('app.name', 'Mobillium Back-End Developer') }}
    </x-slot>

    <div class="row">
        <div class="col-lg-12">
            <form method="POST" action="@if (!isset($article)) {{ route('article.create') }} @else {{ route('article.edit') }} @endif">
                @csrf
                @if (isset($article)) @method("PUT") @endif
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" required class="form-control" id="title" name="title" value="@if (!isset($article)){{ old('title') }}@else{{ $article->title }}@endif">
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control" required id="content" name="content" rows="15" value="@if (!isset($article)){{ old('content') }}@else {{ $article->content }} @endif"></textarea>
                </div>
                @if (!isset($article))
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked id="publish" name="publish">
                        <label class="form-check-label" for="publish">
                            Publish now
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="scheduled-time">Or schedule to:</label>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="date" class="form-control" id="scheduled-date" name="scheduled-date">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="time" class="form-control" id="scheduled-time" name="scheduled-time">
                        </div>
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>

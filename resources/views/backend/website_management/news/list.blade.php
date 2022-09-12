@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header d-flex align-items-center">
				<h4 class="header-title">{{ _lang('All News') }}</h4>
				<a class="btn btn-primary btn-sm ml-auto" href="{{ route('news.create') }}"><i class="icofont-plus-circle"></i> {{ _lang('Add New') }}</a>
			</div>
			<div class="card-body">
				<table id="news_table" class="table table-bordered data-table">
					<thead>
					    <tr>
						    <th>{{ _lang('Image') }}</th>
							<th>{{ _lang('Title') }}</th>
							<th>{{ _lang('Status') }}</th>
							<th class="text-center">{{ _lang('Action') }}</th>
					    </tr>
					</thead>
					<tbody>
					    @foreach($newss as $news)
					    <tr data-id="row_{{ $news->id }}">
							<td class='image'><img src="{{ media_images($news->image) }}" class="thumb-sm img-thumbnail"/></td>
							<td class='title'>{{ $news->translation->title }}</td>
							<td class='status'>{!! xss_clean(status($news->status)) !!}</td>

							<td class="text-center">
								<span class="dropdown">
								  <button class="btn btn-primary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								  {{ _lang('Action') }}
								  <i class="fas fa-angle-down"></i>
								  </button>
								  <form action="{{ action('NewsController@destroy', $news['id']) }}" method="post">
									{{ csrf_field() }}
									<input name="_method" type="hidden" value="DELETE">

									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a href="{{ action('NewsController@edit', $news['id']) }}" class="dropdown-item dropdown-edit dropdown-edit"><i class="icofont-ui-edit"></i> {{ _lang('Edit') }}</a>
										<button class="btn-remove dropdown-item" type="submit"><i class="icofont-trash"></i> {{ _lang('Delete') }}</button>
									</div>
								  </form>
								</span>
							</td>
					    </tr>
					    @endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection
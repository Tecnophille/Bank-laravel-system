@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <form method="post" id="permissions" class="validate" autocomplete="off" action="{{ route('permission.store') }}">
            @csrf
			<div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">{{ _lang('Select Role') }}</label>
                                    <select class="form-control select2" id="role_id" name="role_id" required>
                                        <option value="">{{ _lang('Select One') }}</option>
                                        {{ create_option("roles", "id", "name", $role_id) }}
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <span class="d-none panel-title">{{ _lang('Permission Control') }}</span>

                <div class="card-body">
                    <div class="row">
						<div class="col-md-12">
							<div id="accordion">
								@php $i = 1; @endphp
								@foreach($permission as $key => $val)
								<div class="card mb-3">
									<div class="card-header">
										<h4>
											<a class="card-link" data-toggle="collapse"
												href="#collapse-{{ explode("\\",$key)[3] }}">
												<i class="icofont-double-right"></i>
												{{ str_replace("Controller","",explode("\\",$key)[3]) }}
											</a>
										</h4>
									</div>
									<div id="collapse-{{ explode("\\",$key)[3] }}" class="collapse">
										<div class="card-body">
											<table class="table">
												@foreach($val as $name => $url)
												<tr>
													<td>
														<div class="checkbox">
															<div class="custom-control custom-checkbox">
																<input type="checkbox" class="custom-control-input"
																	name="permissions[]" value="{{ $name }}"
																	id="customCheck{{ $i + 1 }}"
																	{{ array_search($name,$permission_list) !== FALSE ? "checked" : "" }}>
																<label class="custom-control-label"
																	for="customCheck{{ $i + 1 }}">{{ str_replace("index","list",$name) }}</label>
															</div>
														</div>
													</td>
												</tr>
												@php $i++; @endphp
												@endforeach
											</table>
										</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>

                        <div class="col-md-12 mt-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg"><i class="icofont-check-circled"></i> {{ _lang('Save Permission') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
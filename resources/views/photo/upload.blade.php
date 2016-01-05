@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Upload</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" method="POST"
                              action="/photo" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <input name="userfile[]" type="file" accept="image/*" multiple/><br/>


                            <label for="gid">Group</label>
                            <select name="gid">
                                @foreach($group_ids as $gid)
                                    @if($gid == $photo_last_group_id)
                                        <option selected>{{$gid}}</option>
                                    @else
                                        <option>{{$gid}}</option>
                                    @endif
                                @endforeach
                            </select><br>
                            <label for="is_new_group">Or New Group</label>
                            <input type="checkbox" name="is_new_group"/>
                            <br><br>
                            <input type="submit" value="Send files"/>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

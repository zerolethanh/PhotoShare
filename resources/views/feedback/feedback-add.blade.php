<div class="col-md-4">
    <h3>
        Give us a feedback
    </h3>
    <div class="panel panel-default">
        <div class="panel-body">
            <form method="POST" action="/feedback">
                {{csrf_field()}}


                <div class="form-group {{$errors->has('you_want') ? 'has-error' : ''}}">
                    <label for="you_want">Please let us know what you want:</label>
                    <input type="text" class="form-control" id="you_want" name="you_want" value="{{old('you_want')}}">
                    {!! $errors->has('you_want') ? "<span class='help-block'>Please let us know what you want</span>" : '' !!}
                </div>

                <div class="form-group {{$errors->has('your_suggestion')? 'has-error' : ''}}">
                    <label for="your_suggestion">Please give us a suggestion:</label>

                    <textarea class="form-control" rows="5" name="your_suggestion">{{old('your_suggestion')}}</textarea>

                    {{--                    {!! $errors->first('your_suggestion',"<span class='help-block'>:message</span>") !!}--}}
                </div>

                <div class="form-group">
                    <button class="btn btn-primary">Send Feedback</button>
                </div>

            </form>
        </div>
    </div>
</div>
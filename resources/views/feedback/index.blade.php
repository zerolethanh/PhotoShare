@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row">
            <div class="col-md-8">

                <h1>Feedbacks</h1>

                <ul class="Links">

                </ul>


                @if(isset($feedbacks))

                    @foreach($feedbacks as $feedback)


                        <li class="list-group-item">
                            <a href="#">
                                {{str_limit($feedback->you_want,100)}}
                            </a>

                            <span class="pull-right">
                                <small>
                                    {{$feedback->updated_at->diffForHumans()}}
                                </small>
                            </span>
                            <br>

                            <span>
                                {{$feedback->your_suggestion}}
                            </span>
                        </li>

                    @endforeach
                @else

                    Please give us a feedback. <br>
                    Thanks for.
                    <br>
                    PhotoShare Team.


                @endif


                {{$feedbacks->links()}}
            </div>

            @include('feedback.feedback-add')


        </div>


    </div>
@stop
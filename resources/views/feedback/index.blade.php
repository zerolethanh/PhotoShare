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


                            <a href="#" class=" left">
                                {{str_limit($feedback->you_want,100)}}
                            </a>

                            <small>
                                {{$feedback->updated_at->diffForHumans()}}

                            </small>
                            <br>
                            <span>

                                {{$feedback->your_suggestion}}
                            </span>
                        </li>

                    @endforeach
                @else
                    No feedbacks yet.
                @endif


                {{$feedbacks->links()}}
            </div>

            @include('feedback.feedback-add')


        </div>


    </div>
@stop
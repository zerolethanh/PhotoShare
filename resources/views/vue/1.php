<? ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link href="/css/vue/1.css" rel="stylesheet" type="text/css">
    <!--    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600|Source+Code+Pro|Dosis:300,500'-->
    <!--          rel='stylesheet' type='text/css'>-->


</head>
<body>
<form id="form">
    <!-- text -->
    <p>
        <input type="text" v-model="msg">
        {{msg}}
    </p>
    <!-- checkbox -->
    <p>
        <input type="checkbox" v-model="checked">
        {{checked ? "yes" : "no"}}
    </p>
    <!-- radio buttons -->
    <p>
        <input type="radio" name="picked" value="one" v-model="picked">
        <input type="radio" name="picked" value="two" v-model="picked">
        {{picked}}
    </p>
    <!-- select -->
    <p>
        <select v-model="selected">
            <option>one</option>
            <option>two</option>
        </select>
        {{selected}}
    </p>
    <!-- multiple select -->
    <p>
        <select v-model="multiSelect" multiple>
            <option>one</option>
            <option>two</option>
            <option>three</option>
        </select>
        {{multiSelect}}
    </p>
    <p><pre>data: {{$data | json 2}}</pre></p>
</form>
<div id="demo">
<!--    <input type="text" v-model="post_search"/>-->
<!--    <ul>-->
<!--        <li v-repeat="post in posts | filterBy post_search | orderBy 'pub_at'">-->
<!--            {{post.title}} {{post.pub_at}}-->
<!--        </li>-->
<!--    </ul>-->
<!--    <ul>-->
<!--        <li v-repeat="user in users">{{user.name}}</li>-->
<!--    </ul>-->
    <!--    <h1>{{title | uppercase}}</h1>-->
    <!--    <input v-model="user_search" type="text"/>-->
    <!--    <ul>-->
    <!--        <li v-repeat="user in users | filterBy user_search | orderBy 'name'" v-on="click: upname(user)">-->
    <!--            {{user.name + " " + (user.phone || "")}}-->
    <!--        </li>-->
    <!--    </ul>-->
    <!--    <ul>-->
    <!--        <li-->
    <!--            v-repeat="todos"-->
    <!--            v-on="click: done = !done"-->
    <!--            class="{{done ? 'done' : ''}}">-->
    <!--            {{content}}-->
    <!--        </li>-->
    <!--        <li>{{date}}{{date | pluralize 'st' 'nd' 'rd' 'th'}}</li>-->
    <!--    </ul>-->

</div>

<script src="http://cdn.jsdelivr.net/vue/0.12.16/vue.min.js"></script>
<script src="/js/vue/1.js"></script>

</body>
</html>
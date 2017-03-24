/**
 * Created by ZE on 2015/10/22.
 */

new Vue({
    el: '#form',
    data: {
        msg: 'hi!',
        checked: true,
        picked: 'one',
        selected: 'two',
        multiSelect: ['one', 'three']
    }
});

var demo = new Vue({
    el: '#demo',
    data: {
        title: 'todos',
        posts: [
            {
                title: 'welcome',
                pub_at: 23
            },
            {
                title: 'こんんばんは',
                pub_at: 25
            },
            {
                title: '京都市',
                pub_at: 19
            }
        ],
        users: [
            {
                name: 'thanh',
                phone: '090 3702 5644'
            },
            {
                name: 'thanh akita',
                phone: '080 '
            },
            {
                name: 'レーバンタン',
                phone: '090 3777 928'
            },
            {
                name: ' bui trung kien'
            }
        ],
        todos: [
            {
                done: true,
                content: 'Learn JavaScript'
            },
            {
                done: false,
                content: 'Learn Vue.js'
            },
            {
                done: false,
                content: 'Learn Angular.js'
            }
        ],
        message: 'this is message',
        date: new Date().getDate()
    },
    methods: {
        changemsg: function () {
            this.$data.message = 'changed';
        },
        upname: function (user) {
            user.name = user.name.toUpperCase();
        }
    }
});
<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 14/10/2015
 * Time: 19:30
 */
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="_token" content="<?= csrf_token(); ?>"/>
    <title>jQuery File Upload Example</title>
</head>
<body>
<input id="fileupload" type="file" name="userfile[]" data-url="/photo" multiple>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="/js/vendor/jquery.ui.widget.js"></script>
<script src="/js/jquery.iframe-transport.js"></script>
<script src="/js/jquery.fileupload.js"></script>
<script>
    $(function () {
        $('#fileupload').fileupload({
            dataType: 'json',
            add: function (e, data) {
                data.context = $('<button/>').text('Upload')
                    .appendTo(document.body)
                    .click(function () {
                        data.context = $('<p/>').text('Uploading...').replaceAll($(this));
                        console.log($("meta[name=_token]").attr('content'));
//                        data._token = $('#_token').text();
                        data.submit();
                    });
            },
            formData: [
                {name: '_token', value: $("meta[name=_token]").attr('content')}
            ],
            done: function (e, data) {
                data.context.text('Upload finished.');
            }
        });
    });
</script>
</body>
</html>
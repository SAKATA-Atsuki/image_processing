<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $(document).on('change', '#upload_image', function(e) {
                // ロード画像表示
                $('.image_message').empty();
                $('.image_message').append($('<p>加工中</p>')).append($('<img>').attr('src', "{{ asset('ajax-loader.gif') }}"));

                // ファイルを取得
                var file = $('#upload_image').prop('files')[0];

                var fd = new FormData();
                fd.append('file', file);

                // Ajax保存処理をするファイルへ内容渡す
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                $.ajax({
                    url: "{{ route('image') }}",
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,           
                })
                .done(function(data) {
                    var info = data.split('/');

                    $('.image_message').empty();
                    $('.image_message').append($('<p>投稿する画像を選択してください。</p>'));
                    $('.image_box').empty();
                    $('.image_box').append($('<div>').addClass('image_element').append($('<img>').attr('src', "{{ asset('images') }}/" + info[0])).append($('<br>')).append($('<input>').attr('type', 'radio').attr('name', 'image').attr('id', 'radio_1').attr('value', info[0])).append($('<label for="radio_1">アップロード画像</label>')));
                    $('.image_box').append($('<div>').addClass('image_element').append($('<img>').attr('src', "{{ asset('images') }}/a" + info[0])).append($('<br>')).append($('<input>').attr('type', 'radio').attr('name', 'image').attr('id', 'radio_2').attr('value', 'a' + info[0])).append($('<label for="radio_2">彩度×'+info[1]+' 明度×'+info[2]+'</label>')));
                    $('.image_box').append($('<div>').addClass('image_element').append($('<img>').attr('src', "{{ asset('images') }}/b" + info[0])).append($('<br>')).append($('<input>').attr('type', 'radio').attr('name', 'image').attr('id', 'radio_3').attr('value', 'b' + info[0])).append($('<label for="radio_3">彩度×'+info[3]+' 明度×'+info[4]+'</label>')));

                    console.log(data);
                })
                .fail(function(data) {
                    $('.image_message').empty();
                    $('.image_message').append($('<p>加工に失敗しました。</p>'));
                    console.log(data.responseText);
                });
            })
        })
    </script>
    <title>自動画像加工掲示板</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>自動画像加工掲示板</h1>
    @foreach ($posts as $key => $post)
        @if ($post->image !== NULL || $post->message !== NULL)
            <div class="image_post">
                @if ($post->image !== NULL)
                    <img src="{{ asset('images') . '/' . $post->image }}">
                @endif
                @if ($post->message !== NULL)
                    <p>{{ $post->message }}</p>
                @endif
                <p>{{ $post->created_at }}</p>
            </div>
            <p>==========================================================================================</p>
        @endif
    @endforeach
    <form action="{{ route('store') }}" method="POST">
        @csrf
        <input type="file" name="upload_image" id="upload_image" accept="image/*">
        <div class="image_message"></div>
        <div class="image_box"></div>
        <br>
        <input type="text" name="message" size="60">
        <input type="submit" value="投稿">
    </form>
</body>
</html>
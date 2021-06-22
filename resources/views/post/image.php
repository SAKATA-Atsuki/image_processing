<?php
    $tmp_name = $_FILES['file']['tmp_name'];
    $explode_image_name = explode('.', $_FILES['file']['name']); // 拡張子を抽出
    $image_name = date('YmdHis') . '.' . end($explode_image_name); // 画像名を指定

    move_uploaded_file($tmp_name, 'images/' . $image_name);

    $command = '/Users/atsuki/.pyenv/versions/3.9.1/bin/python /Applications/MAMP/htdocs/image_processing/app/Python/ImageProcessing.py ' . $image_name;
    exec($command, $outputs);

    echo $outputs[0];
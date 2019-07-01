<?php

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>
    <title>Codepad</title>
</head>
<body>
<form id="codepad">
    <label for="code">PHP Version 7.3.6</label><br>
    <textarea name="code" id="code" cols="30" rows="10"></textarea>
    <br>
    <button type="submit">Submit</button>
</form>
<hr>
<div id="result"></div>
<script>
    $('#codepad').submit((e) => {
        e.preventDefault();
        let data = {code: btoa($('#code').val())};
        let res = $('#result');

        res.empty();
        $.ajax({
            type: "POST",
            url: "/index.php",
            data: data,
            success: (response) => {
                res.append(response);
            }
        })
    })
</script>
</body>
</html>

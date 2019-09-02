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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Codepad</title>
</head>
<body>
<div class="container my-4">
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <form id="codepad">
                <div class="form-group">
                    <label class="form-check-label"> PHP Version:
                        <select class="form-control" name="ver" id="ver">
                            <option value="7.3.6">7.3.60</option>
                            <option value="7.2.19">7.2.19</option>
                            <option value="7.1.30">7.1.30</option>
                            <option value="7.0.33">7.0.33</option>
                            <option value="5.6.40">5.6.40</option>
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label for="code">Code:</label>
                    <textarea class="form-control" name="code" id="code" cols="30" rows="10"></textarea>
                </div>
                <button class="btn btn-success" type="submit">Submit</button>
            </form>
        </div>
        <div class="col-md-6 col-xs-12">
            <div id="result" class="h-100">
                <div class="form-group">
                    <label for="data">Result:</label>
                    <pre class="form-control h-100" id="data"></pre>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#codepad').submit((e) => {
        e.preventDefault();
        let data = {
            code: btoa($('#code').val()),
            ver: btoa($('#ver').val())
        };
        let res = $('#result pre');
        console.log(data);
        res.empty();
        $.ajax({
            type: "POST",
            url: "/http/manager.php",
            data: data,
            success: (response) => {
                res.append(response);
            }
        })
    })
</script>
</body>
</html>
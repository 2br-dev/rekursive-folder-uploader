<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Rekursive Folder Uploader</title>
    <link href="css/dropzone.css" type="text/css" rel="stylesheet" />
</head>
<body>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="js/dropzone.js"></script>
    
    <!-- CONTENT -->

        <!-- DropZone -->
        <form action="upload.php" class="dropzone" id="uploadFile" name="uploadFile" method="POST">
            <span id="tmp-path"></span>
            <p class="flow-text" id='progress'></p>
        </form>

        <!-- Кнопка обработать -->
        <form action="process.php" id="process" name="process" method="POST">
            <button class='btn green waves-effect waves-light process' type='submit'>Обработать</button>
            <button class='btn red waves-effect waves-light clear'>очистить</button>
        </form>

        <!-- Айфрейм для загрузки результата -->
        <iframe id="result" style="display:none;"></iframe>

    <!-- CONTENT -->


    <script>
        var counter = 0;
        var length = 0;

        $(document).ready(function () {
            $('#process button').hide();
            Dropzone.autoDiscover = false;
            
            Dropzone.options.uploadFile = {
              init: function() {
                this.on("success", function(file, responseText) {
                    counter++;
                    length = myDropzone.files.length;
                    progress(counter, length);
                  /* file.previewTemplate.appendChild(document.createTextNode(responseText)); */
                });         

                this.on("sending", function(file) {
                    $("#tmp-path").html('<input type="hidden" name="path" value="'+file.fullPath+'" />')
                });            
              }
            }; 
            
            var myDropzone = new Dropzone("#uploadFile", { 
                url: "upload.php"
            });                               
        });

        function progress(counter, length) {
            if (counter === length) $('#process button').show();
            $('#progress').text('Загружено файлов: ' + counter + '/' + length);
        };

        function Download(url) {
            document.getElementById('result').src = url;
        };

        $('#process').submit(function(e) {
            e.preventDefault();            

            $.ajax({
                type: "POST",
                url: 'process.php',
                success: function(res) {
                    /* Download(JSON.parse(res).result); */
                    /* setTimeout(function() { window.location.reload() }, 2000); */
                },
                error: function(err) {
                    console.log(err);
                },
            });              
        });

        $('.clear').click(function() {
            window.location.reload();
        })
    </script>
    <style>
        #process {
            width: 280px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 50px auto 50px;
        }
        #progress {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 360px;
            margin: 0;
        }
    </style>
</body>
</html>
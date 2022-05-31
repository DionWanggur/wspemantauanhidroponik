<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pusher Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('a189c31a94dae4644a9f', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            //alert(JSON.stringify(data));
            loadDoc();
        });

        function loadDoc() {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                var JSONarray = JSON.parse(this.responseText);
                document.getElementById("namaHidroponik").innerHTML = JSONarray[0].namaHidroponik
                document.getElementById("lokasi").innerHTML = JSONarray[0].lokasi
                document.getElementById("namaNode").innerHTML = JSONarray[0].namaNode;
                document.getElementById("waktu").innerHTML = JSONarray[0].waktu;
                var content = "";

                for (let i in JSONarray) {
                    content += JSONarray[i].namaSensor + " : " + JSONarray[i].value + "\n";
                }

                document.getElementById("demo").innerHTML = content;
            }
            xhttp.open("GET", "http://localhost:8080/monitoring?namaNode=Node%20Satu", true);
            xhttp.responseText = "json";
            xhttp.send();
        }
    </script>
</head>

<body>
    <div class="container">
        <h1>Pusher Test</h1>
        <p>
            Try publishing an event to channel <code>my-channel</code>
            with event name <code>my-event</code>.
        </p>

        <h5 id="namaHidroponik"></h5>
        <h5 id="lokasi"></h5>
        <h5 id="namaNode"></h5>
        <h5>Waktu Pemantauan: </h5>
        <h5 id="waktu">Waktu Pemantauan: </h5>
        

        <p id="demo"></p>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
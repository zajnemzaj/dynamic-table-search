<!DOCTYPE html>
<html>

<head>
    <?php include 'included-phps/head.php';?>
</head>

<body>
    <div id="wrapper">
        <?php include 'included-phps/menu-visitors.php';?>
        <div class="container">
        </div>
        <!-- end of container -->
    </div>
    <!-- end of wrapper -->
    <script src="../../js/menu-selector.js"></script>
    <script>
        function splitText(allText) {
            // console.log(typeof allText);
            var lines = allText.split(/\n/);
            console.log(lines);
            return 0;
        }

        function readTextFile(file) {
            var rawFile = new XMLHttpRequest();
            rawFile.open("GET", file, true);
            rawFile.onreadystatechange = function() {
                if (rawFile.readyState === 4) {
                    if (rawFile.status === 200 || rawFile.status == 0) {
                        var allText = rawFile.responseText;
                        console.log(typeof allText);
                        splitText(allText);
                        return 0;
                    }
                }
            }
            rawFile.send(null);
        }

        readTextFile("used-items.csv");
    </script>
    <?php include 'included-phps/footer.php';?>
</body>

</html>

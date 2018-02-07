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
        function splitTextToLines(allText) {
            var finalText = "";
            var start = 0;
            // swap \n with <br>
            for (var i = 0; i < allText.length; i++) {
                if (allText[i] === "\n" && allText[i - 1] != '"') {
                    finalText += allText.substring(start, i);
                    finalText += "<br>";
                    start = i + 1;
                }
            }
            finalText += allText.substring(start, allText.length);
            var lines = finalText.split(/\n/);
            return lines;
        }

        function readTextFile(file) {
            var linesArray = [];
            var rawFile = new XMLHttpRequest();
            rawFile.open("GET", file, true);
            rawFile.onreadystatechange = function() {
                if (rawFile.readyState === 4) {
                    if (rawFile.status === 200 || rawFile.status == 0) {
                        var allText = rawFile.responseText;
                        var lines = splitTextToLines(allText);
                        // going through lines
                        for (var count = 1; count < lines.length; count++) {
                            var rowObject = {};
                            // initializing rowContent array to store each column as an array
                            var rowContent = lines[count].split("|");
                            // if it's not a empty row
                            if (rowContent != "") {
                                // going through columns
                                for (var i = 0; i < rowContent.length; i++) {
                                    // getting column names from first line
                                    var columnName = lines[0].split("|")[i];
                                    // adding new propery name (by column name) and value (from actual row) to rowObject
                                    rowObject[columnName] = rowContent[i].replace(/"/g, "");
                                }
                                // console.log(rowObject);
                                // pushing actual rowObject to linesArray
                                linesArray.push(rowObject);
                            }
                        }
                        console.table(linesArray);
                        //return linesArray;
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

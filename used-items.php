<!DOCTYPE html>
<html>

<head>
    <?php include 'included-phps/head.php';?>
    <link rel="stylesheet" href="used-items.css">
</head>

<body>
    <div id="wrapper">
        <?php include 'included-phps/menu-visitors.php';?>
        <div class="container">
            <div class="left-container">
                <input type="text" id="myInput" onkeyup="searchTable()" placeholder="Keresés gyártóra...">
            </div>
            <div class="right-container">
                <table id="myTable" class="main table table-hover table-condensed table-striped" border="1" cellspacing="0" bordercolor="#D4D4D4" frame="box" rules="all">
                    <thead>
                        <tr id="myTableHeadTr">
                        </tr>
                    </thead>
                    <tbody id="myTableBody">
                    </tbody>
                </table>
            </div>
        </div>
        <!-- end of container -->
    </div>
    <!-- end of wrapper -->
    <script src="../../js/menu-selector.js"></script>
    <script>

        function searchTable() {
            // Declare variables
            var input, filter, table, tr, td, i;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            th = table.getElementsByTagName("th");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                // for (var j = 0; th.length; j++)
                // {
                //     //td += tr[i].getElementsByTagName("td")[j];
                //     //console.log(tr[i].getElementsByTagName("td")[j]);
                // }
                td = tr[i].getElementsByTagName("td")[3];
                if (td) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function readTextFile(file) {

            function insertTable(linesArray) {
                var headerNames = Object.keys(linesArray[0]);
                var headerNamesHelper;
                for (var i = 0; i < headerNames.length; i++) {
                    document.getElementById('myTableHeadTr').innerHTML += `<th style="width:30%;">${headerNames[i]}</th>`;
                }

                var tableRef = document.getElementById('myTable').getElementsByTagName('tbody')[0];
                for (var j = 0; j < linesArray.length; j++) {
                    // Insert a row in the table at row index 0
                    var newRow   = tableRef.insertRow(tableRef.rows.length);
                    for (var k = headerNames.length-1; k >= 0; k--) {
                        // Insert a cell in the row at index 0
                        var newCell  = newRow.insertCell(0);
                        // Append a text node to the cell. Dot notaion not possible as now it can use variable as object property
                        var newText  = document.createTextNode(`${linesArray[j][headerNames[k]].replace(/\<br\>/g, "\n")}`);
                        newCell.appendChild(newText);
                        //document.getElementById('myTableBody').innerHTML += `<td style="width:30%;">${j} ${i}</td>`;
                    }
                }
            }

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
                                    var columnName = lines[0].split("|")[i].replace(/['"]+/g, '');
                                    // adding new propery name (by column name) and value (from actual row) to rowObject
                                    rowObject[columnName] = rowContent[i].replace(/"/g, "");
                                }
                                // console.log(rowObject);
                                // pushing actual rowObject to linesArray
                                linesArray.push(rowObject);
                            }
                        }
                        //console.table(linesArray);
                        insertTable(linesArray);
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

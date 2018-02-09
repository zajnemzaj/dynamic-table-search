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
            <div class="left-container col-sm-2">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-custom"><b>Szűrők</b></div>
                    <div class="panel-body filters">
                        <div class="panel-group">
                            <div class="panel panel-default noshadow">
                                <div class="panel-heading">
                                        <a data-toggle="collapse" href="#collapse-gyarto"><b>Gyártó</b></a>
                                </div>
                                <div id="collapse-gyarto" class="panel-collapse collapse in filter">
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Danfoss</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Eaton</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Komatsu</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Linde</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Rexroth</label>
                                    </div>
                                    <div class="panel-footer">Összes</div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-group">
                            <div class="panel panel-default noshadow">
                                <div class="panel-heading">
                                        <a data-toggle="collapse" href="#collapse-allapot"><b>Állapot</b></a>
                                </div>
                                <div id="collapse-allapot" class="panel-collapse collapse in filter">
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Használt, tesztelt</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Javított, tesztelt</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Új, tesztelt</label>
                                    </div>
                                    <div class="panel-footer">Összes</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="right-container col-sm-10">
                <input type="search" id="myInput" placeholder="Keresés...">
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
        // better to call here (than directly in input field) because of clear button on the right
        $('#myInput').on("input", function() {
            // update panel
            searchTable();
        });

        function searchTable() {
            // Declare variables
            var input, filter, table, tr, td, i;
            var searchInThis = "";
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            th = table.getElementsByTagName("th");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                // Loop through actual row's columns
                for (var j = 0; j < th.length; j++)
                 {
                    td = tr[i].getElementsByTagName("td")[j];
                    if (td) {
                        if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                            // if match, then display and set j index to last column so next row can be examined
                            tr[i].style.display = "";
                            j = th.length-1;
                        } else if (j === th.length-1) {
                            // if not match and it is last column than hide it
                            tr[i].style.display = "none";
                        }
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

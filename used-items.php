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
                    <div class="panel-body filters" id="divFilters">
                        // Filling up with insertCheckboxes()
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
        var headerNames;
        var linesArrayOfObjects = [];
        var htmlContent = "";
        var checkedFilters = [];





                function searchTable2(checkedFiltersArray) {
                    // Declare variables
                    var filter, table, tr, td, i;
                    var searchInThis = "";
                    filter = checkedFiltersArray[0].toUpperCase();
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

        function searchTable(checkedFiltersArray) {
            // Declare variables
            var filter, table, tr, td, i;
            var searchInThis = "";
            filter = checkedFiltersArray[0].toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            th = table.getElementsByTagName("th");

            // for (var i = 0; i < linesArrayOfObjects.length; i++) {
            //     for (var j = 0; j < checkedFiltersArray.length; j++) {
            //         checkedFiltersArray[i]
            //     }
            // }

            function getByValue4(arr, value) {
                var o;
                var isInTheseLines = [];

                for (var i=0, iLen=arr.length; i<iLen; i++) {
                    o = arr[i];

                    for (var p in o) {
                        if (o.hasOwnProperty(p) && o[p] == value) {
                            isInTheseLines.push(i);
                            // return i;
                        }
                    }
                }
                console.log(isInTheseLines);
                return isInTheseLines;
            }

            // TODO: iterate through checkedFiltersArray to get the indexes where we have match.
            // we store those indexes and later sorting them them have to make it a unique list.
            // than we print out the table only with the sorted unified indexes.

            getByValue4(linesArrayOfObjects,checkedFiltersArray[0]);
            getByValue4(linesArrayOfObjects,checkedFiltersArray[1]);

            // var empIds = getByValue4(linesArrayOfObjects,checkedFiltersArray[0]);
            // var filteredArray = linesArrayOfObjects.record.filter(function(itm){
            //     return empIds.indexOf(itm.empid) > -1;
            // });
            //
            // filteredArray = { records : filteredArray };
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

            function insertCheckboxes(inCategory) {
                htmlContent += `
                <div class="panel-group">
                    <div class="panel panel-default noshadow">
                        <div class="panel-heading">
                                <a data-toggle="collapse" href="#collapse-${inCategory}"><b>${inCategory}</b></a>
                        </div>
                        <div id="collapse-${inCategory}" class="panel-collapse collapse in">`;

                // Reading all the inCategory element into an array
                function unique(arr, prop) {
                    return arr.map(function(e) { return e[prop]; }).filter(function(e,i,a){
                        return i === a.indexOf(e);
                    });
                }
                var uniqueArray = (unique(linesArrayOfObjects,inCategory));

                // Creating checkboxes
                for (var i = 0; i < uniqueArray.length; i++) {
                    htmlContent += `
                            <div class="checkbox">
                                <label><input type="checkbox" name="${uniqueArray[i]}">${uniqueArray[i]}</label>
                            </div>
                            `;
                }

                // Adding footer of filter panel
                htmlContent += `
                        </div>
                    </div>
                </div>
                `;

                // Adding content to div
                document.getElementById('divFilters').innerHTML = htmlContent;
            }

            function insertTable(linesArrayOfObjects) {
                headerNames = Object.keys(linesArrayOfObjects[0]);
                for (var i = 0; i < headerNames.length; i++) {
                    document.getElementById('myTableHeadTr').innerHTML += `<th style="width:30%;">${headerNames[i]}</th>`;
                }

                var tableRef = document.getElementById('myTable').getElementsByTagName('tbody')[0];
                for (var j = 0; j < linesArrayOfObjects.length; j++) {
                    // Insert a row in the table at row index 0
                    var newRow   = tableRef.insertRow(tableRef.rows.length);
                    for (var k = headerNames.length-1; k >= 0; k--) {
                        // Insert a cell in the row at index 0
                        var newCell  = newRow.insertCell(0);
                        // Append a text node to the cell. Dot notaion not possible as now it can use variable as object property
                        var newText  = document.createTextNode(`${linesArrayOfObjects[j][headerNames[k]].replace(/\<br\>/g, "\n")}`);
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
                                // pushing actual rowObject to linesArrayOfObjects
                                linesArrayOfObjects.push(rowObject);
                            }
                        }
                        // console.table(linesArrayOfObjects);

                        //console.log(linesArrayOfObjects[0]);
                        insertTable(linesArrayOfObjects);
                        insertCheckboxes("Kategória");
                        insertCheckboxes("Állapot");
                        insertCheckboxes("Gyártó");
                        //return linesArrayOfObjects;
                    }
                }
            }
            rawFile.send(null);

        }

        readTextFile("used-items.csv");

        $(document).on('click', 'input[type="checkbox"]', function(){
            // console.log($(this).attr("name"));
            if ($(this).is(':checked')) {
                checkedFilters.push($(this).attr("name"));
                searchTable(checkedFilters);
            } else {
                checkedFilters = checkedFilters.filter(e => e !== $(this).attr("name"));
                searchTable(checkedFilters);
            }
        });

        // better to call here (than directly in input field) because of clear button on the right
        $('#myInput').on("input", function() {
            var input = [];
            // Get input
            input.push(document.getElementById("myInput").value);
            // update panel
            searchTable(input);
        });

    </script>
    <?php include 'included-phps/footer.php';?>
</body>

</html>

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
                        <!-- Filling up with insertCheckboxes() -->
                    </div>
                </div>
            </div>
            <div class="right-container col-sm-10">
                <input type="search" id="myInput" placeholder="Keresés...">
                <table id="myTable" class="main table table-hover table-condensed" border="1" cellspacing="0" bordercolor="#D4D4D4" frame="box" rules="all">
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
        var checkedFiltersByCategory = [];
        var isInTheseLines = [];



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

        function stripeTable() {
            $("tr:not(.hidden)").each(function (index) {
                $(this).toggleClass("stripe", !!(index & 1));
                });
        }

        function getByValue4(arr, value, isInTheseLines) {
            var o;


            for (var i=0, iLen=arr.length; i<iLen; i++) {
                o = arr[i];

                for (var p in o) {
                    if (o.hasOwnProperty(p) && o[p] == value) {
                        isInTheseLines.push(i+1);
                        // return i;
                    }
                }
            }
            // console.log(isInTheseLines);
            return 0;
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

            isInTheseLines = [];


            // TODO: iterate through checkedFiltersArray to get the indexes where we have match.
            // we store those indexes and later sorting them them have to make it a unique list.
            // than we print out the table only with the sorted unified indexes.
            for (var i = 0; i < checkedFiltersArray.length; i++) {
                getByValue4(linesArrayOfObjects,checkedFiltersArray[i],isInTheseLines);
            }

            let uniqueFilters = [...new Set(isInTheseLines)];
            let uniqueSortedFilters = uniqueFilters.sort(function(a, b){return a - b});

            // console.log(uniqueSortedFilters);

            // getByValue4(linesArrayOfObjects,checkedFiltersArray[0]);
            // getByValue4(linesArrayOfObjects,checkedFiltersArray[1]);

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
                            // tr[i].style.display = "";
                            tr[i].classList.remove("hidden");
                            j = th.length-1;
                        } else if (j === th.length-1) {
                            // if not match and it is last column than hide it
                            // tr[i].style.display = "none";
                            tr[i].classList.add("hidden");
                        }
                    }
                }
            }
            stripeTable();
        }

        function filterTableByIndex(linesArrayOfObjects,checkedGrouppedCategories) {


            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            for (i = 1; i < tr.length; i++) {
                if (checkedGrouppedCategories.includes(i)) {
                    tr[i].classList.remove("hidden");
                } else {
                    tr[i].classList.add("hidden");
                }
            }
            stripeTable();
        }

        function filterTableByCategory(checkedFiltersByCategory) {
            var objectForGroups = {};
            var isInTheseLines2 = [];
            var checkedGrouppedCategories = [];
            var arrayObjectKeysOfGroups = [];
            for (var i = 0; i < checkedFiltersByCategory.length; i++) {
                isInTheseLines2 = [];
                getByValue4(linesArrayOfObjects,checkedFiltersByCategory[i].checkboxName,isInTheseLines2);
                checkedFiltersByCategory[i].hitInRows = isInTheseLines2;
                var tmpCatName = checkedFiltersByCategory[i].categoryName;
                if (!objectForGroups[tmpCatName]) {
                    objectForGroups[tmpCatName] = [];
                }
                objectForGroups[tmpCatName] = objectForGroups[tmpCatName].concat(isInTheseLines2);
            }
            arrayObjectKeysOfGroups = Object.keys(objectForGroups);
            if (arrayObjectKeysOfGroups.length > 1) {
                checkedGrouppedCategories = objectForGroups[arrayObjectKeysOfGroups[0]];
                for (var i = 1; i < arrayObjectKeysOfGroups.length; i++) {
                    checkedGrouppedCategories = checkedGrouppedCategories.filter(function(val) {
                        return objectForGroups[arrayObjectKeysOfGroups[i]].indexOf(val) != -1;
                    });
                }
                console.log("Filtered rows:" + checkedGrouppedCategories);
                filterTableByIndex(linesArrayOfObjects,checkedGrouppedCategories);
            } else if (arrayObjectKeysOfGroups.length === 1) {
                checkedGrouppedCategories = objectForGroups[arrayObjectKeysOfGroups[0]];
                console.log("Only one group of filter selected: " + checkedGrouppedCategories);
                filterTableByIndex(linesArrayOfObjects,checkedGrouppedCategories);
            } else {
                console.log("There's nothing selected, so everything should appear.");
                var iterator = linesArrayOfObjects.keys();
                checkedGrouppedCategories = [];
                for (let key of iterator) {
                    checkedGrouppedCategories.push(key);
                }
                filterTableByIndex(linesArrayOfObjects,checkedGrouppedCategories);
            }

            // TODO We have the array of objects for checkboxes.
            // Have to join the hits to get which rows to show.
            // build up the logical connection between categories (and searchfield too!)
            // have to disable the checkboxes which are not relevant anymore
            // console.log(objectForGroups);
            // console.log(checkedFiltersByCategory);
        }

        function readTextFile(file) {

            function insertCheckboxes(inCategory) {
                htmlContent += `
                <div class="panel-group">
                    <div class="panel panel-default noshadow">
                        <div class="panel-heading">
                                <a data-toggle="collapse" href="#${inCategory}"><b>${inCategory}</b></a>
                        </div>
                        <div id="${inCategory}" class="panel-collapse collapse in">`;

                // Reading all the inCategory element into an array
                function unique(arr, prop) {
                    return arr.map(function(e) { return e[prop]; }).filter(function(e,i,a){
                        return i === a.indexOf(e);
                    });
                }
                var uniqueArrayForCheckboxes = (unique(linesArrayOfObjects,inCategory));

                // Creating checkboxes
                for (var i = 0; i < uniqueArrayForCheckboxes.length; i++) {
                    htmlContent += `
                            <div class="checkbox">
                                <label><input type="checkbox" name="${uniqueArrayForCheckboxes[i]}">${uniqueArrayForCheckboxes[i]}</label>
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
                stripeTable();
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
                    if (rawFile.status === 200 || rawFile.status === 0) {
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
            var clickedCheckboxName = $(this).attr("name");
            // console.log($(this).attr("name"));
            if ($(this).is(':checked')) {
                checkedFilters.push(clickedCheckboxName);
                // Get parent to see in which category is in it and store it in an object with property names as category
                var selectedObject = {};
                // Getting the id of collapse to identify in which category is click the checkbox
                selectedObject.categoryName = $(this).parent().parent().parent().attr('id');
                selectedObject.checkboxName = clickedCheckboxName;
                checkedFiltersByCategory.push(selectedObject);
                filterTableByCategory(checkedFiltersByCategory);
                // searchTable(checkedFilters);
            } else {
                checkedFilters = checkedFilters.filter(e => e !== clickedCheckboxName);

                checkedFiltersByCategory = checkedFiltersByCategory.filter(function( obj ) {
                    return obj.checkboxName !== clickedCheckboxName;
                });

                filterTableByCategory(checkedFiltersByCategory);
                // searchTable(checkedFilters);
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

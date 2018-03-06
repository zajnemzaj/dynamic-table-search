<!DOCTYPE html>
<html>

<head>
    <!-- <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script> -->
    <!-- <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <?php include 'included-phps/head.php';?>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->
    <!-- it has to be AFTER cutom font css -->
    <link rel="stylesheet" href="used-items.css">
</head>

<body>
    <div id="wrapper">
        <?php include 'included-phps/menu-visitors.php';?>
        <div class="container">
            <div class="left-container sm-hidden col-sm-2">
                <div>
                    <div id="panelFilter" class="panel panel-default">
                        <div class="panel-heading panel-heading-custom"><b>Szűrők</b></div>
                        <div class="panel-body filters" id="divFilters">
                            <!-- Filling up with insertCheckboxes() -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-container col-sm-10">
                <div class="input-group">
                    <span class="input-group-btn lg-hidden">
                        <button type="button" id="buttonsFilterAtSearch" class="btn btn-default">Szűrők</button>
                    </span>
                    <input type="search" class="form-control" id="myInput" placeholder="Keresés...">
                </div>
                <table id="myTable" class="main table table-hover table-condensed" border="1" cellspacing="0" bordercolor="#D4D4D4" frame="box" rules="all">
                    <thead>
                        <tr id="myTableHeadTr">
                        </tr>
                    </thead>
                    <tbody id="myTableBody">
                    </tbody>
                </table>
            </div>
        </div> <!-- end of .container -->
        <div id="divSideFilters">
            <div id="divBtnCloseSideFilters">
                <i id="btnCloseSideFilters" class="glyphicon glyphicon-arrow-left"></i>
            </div>
        </div>
        <div class="overlay"><div>
    </div> <!-- end of .wrapper -->








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

            isInTheseLines = [];

            for (var i = 0; i < checkedFiltersArray.length; i++) {
                getByValue4(linesArrayOfObjects,checkedFiltersArray[i],isInTheseLines);
            }

            let uniqueFilters = [...new Set(isInTheseLines)];
            let uniqueSortedFilters = uniqueFilters.sort(function(a, b){return a - b});

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
                        <div class="panel-heading filters-heading">
                            <div class="panel-title">
                                <a data-toggle="collapse" href="#${inCategory}" aria-expanded="true">
                                    <b>${inCategory}</b>
                                </a>
                            </div>
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
                                <label class="label-container">
                                    <input type="checkbox" name="${uniqueArrayForCheckboxes[i]}">
                                    ${uniqueArrayForCheckboxes[i]}
                                    <span class="checkmark"></span>
                                </label>
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
// TODO HANDLING SEARCH FIELD


                // if (document.getElementById("myInput").value !== selectedObject.checkboxName) {
                //     checkedFiltersByCategory = checkedFiltersByCategory.filter(function(el) {
                //         return el.categoryName !== "searchWord";
                //     });
                //     selectedObject = {};
                //     selectedObject.categoryName = "searchWord";
                //     selectedObject.checkboxName = document.getElementById("myInput").value;
                //     checkedFiltersByCategory.push(selectedObject);
                // }

                console.log(checkedFiltersByCategory);
                filterTableByCategory(checkedFiltersByCategory);
                // searchTable(checkedFilters);
            } else {
                checkedFilters = checkedFilters.filter(e => e !== clickedCheckboxName);

                checkedFiltersByCategory = checkedFiltersByCategory.filter(function( obj ) {
                    return obj.checkboxName !== clickedCheckboxName;
                });
// TODO HANDLING SEARCH FIELD
                                // if (document.getElementById("myInput").value !== selectedObject.checkboxName) {
                                //     checkedFiltersByCategory = checkedFiltersByCategory.filter(function(el) {
                                //         return el.categoryName !== "searchWord";
                                //     });
                                //     selectedObject = {};
                                //     selectedObject.categoryName = "searchWord";
                                //     selectedObject.checkboxName = document.getElementById("myInput").value;
                                //     checkedFiltersByCategory.push(selectedObject);
                                // }

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

        // Sidebar Filters button handler
        $('#btnCloseSideFilters, .overlay').on('click', function () {
            /* $('#sidebar').removeClass('active'); */
             $('.overlay').fadeOut();
             $('#divSideFilters').hide();
             $('#divFilters').detach().appendTo('#panelFilter');
             // $(".right-container").append();
        });

        $('#buttonsFilterAtSearch').on('click', function () {
            // $('.left-container').css("display","block");
            // above is equivalent to this:
            // $('.left-container').show();
            $('#divSideFilters').show();
            // var varFilter = $('#divFilters');
            // $("#divSideFilters").append($('#divFilters'));
            $('#divFilters').detach().appendTo('#divSideFilters');
            $('.overlay').fadeIn();
            /*$('#sidebar').addClass('active');
            $('.overlay').fadeIn();
            $('.collapse.in').toggleClass('in');
            $('a[aria-expanded=true]').prop('aria-expanded', 'false');*/
        });

    </script>
    <?php include 'included-phps/footer.php';?>
</body>

</html>

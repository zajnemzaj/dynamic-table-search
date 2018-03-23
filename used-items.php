<!DOCTYPE html>
<html>

<head>
    <!-- Including the head for Bootstrap, jQuery an others -->
    <?php include 'included-phps/head.php';?>
    <!-- Font needed only for arrows on filters -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <!-- Our css has to be after custom font css so it can use it -->
    <link rel="stylesheet" href="used-items.css">
</head>

<body>
    <div id="wrapper">
        <!-- Including the Bootstrap menu -->
        <?php include 'included-phps/menu-visitors.php';?>

        <div class="container">

            <!-- Div for sidebar filters on mobile view. Filling it up with JS -->
            <div id="divSideFilters">
                <div id="divBtnCloseSideFilters">
                    <i id="btnCloseSideFilters" class="glyphicon glyphicon-arrow-left"></i>
                </div>
            </div> <!-- End of #divSideFilters -->

            <!-- Left-container for filters on desktop view -->
            <div class="left-container sm-hidden col-sm-2">
                <!-- <div> Let's see if we can live without this div -->
                    <div id="panelFilter" class="panel panel-default">
                        <div class="panel-heading panel-heading-custom">
                            <b>Szűrők</b>
                        </div>
                        <div class="panel-body filters" id="divFilters">
                            <!-- Filling up with insertCheckboxes() -->
                        </div>
                    </div> <!-- End of #panelFilter -->
                <!-- </div> -->
            </div> <!-- End of .left-container -->



            <!-- Right-container for filtered table -->
            <div class="right-container col-sm-10">
                <div id="actualFilters" class="actual-filters">
                    <p>Aktív szűrők: </p>
                </div> <!-- End of .actual-filters -->
                <!-- Div for search input field and filters button -->
                <div class="input-group">
                    <!-- Button for filters. Only visible on mobile view -->
                    <span class="input-group-btn lg-hidden">
                        <button type="button" id="buttonsFilterAtSearch" class="btn btn-default">Szűrők</button>
                    </span>
                    <!-- Input field for searching by word -->
                    <input type="search" class="form-control" id="myInput" placeholder="Keresés...">
                </div> <!-- End of .input-group -->
                <!-- Table frame. We will fill it up with JS -->
                <table id="myTable" class="main table table-hover table-condensed" border="1" cellspacing="0" bordercolor="#D4D4D4" frame="box" rules="all">
                    <thead>
                        <tr id="myTableHeadTr"></tr>
                    </thead>
                    <tbody id="myTableBody">
                    </tbody>
                </table>
            </div> <!-- End of .right-container -->

        </div> <!-- End of .container -->

        <div class="overlay"></div>

    </div> <!-- End of .wrapper -->

    <script src="../../js/menu-selector.js"></script>

    <script>
        var headerNames;
        var linesArrayOfObjects = [];
        var htmlContent = "";
        var checkedFilters = [];
        var checkedFiltersByCategory = [];
        var isInTheseLines = [];

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
                        if (o[p].toUpperCase().includes(value.toUpperCase())) {
                            isInTheseLines.push(i+1);
                        }
                }
            }
            return 0;
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
            // Going through categories
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
                for (i = 1; i < arrayObjectKeysOfGroups.length; i++) {
                    checkedGrouppedCategories = checkedGrouppedCategories.filter(function(val) {
                        return objectForGroups[arrayObjectKeysOfGroups[i]].indexOf(val) != -1;
                    });
                }
                console.log(checkedFiltersByCategory);
                console.log("Filtered rows:" + checkedGrouppedCategories);
                filterTableByIndex(linesArrayOfObjects,checkedGrouppedCategories);
            } else if (arrayObjectKeysOfGroups.length === 1) {
                checkedGrouppedCategories = objectForGroups[arrayObjectKeysOfGroups[0]];
                console.log(checkedFiltersByCategory);
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
                    var clickedCheckboxNameId = uniqueArrayForCheckboxes[i].replace(/\s/g,'').replace(/\,/g,'').replace(/\(/g,'').replace(/\)/g,'').replace(/\//g,'');
                    console.log("|"+clickedCheckboxNameId+"|");
                    htmlContent += `
                            <div class="checkbox">
                                <label class="label-container">
                                    <input type="checkbox" name="${uniqueArrayForCheckboxes[i]}" id="${clickedCheckboxNameId}">${uniqueArrayForCheckboxes[i]}<span class="checkmark"></span>
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
                        // var newText  = document.createTextNode(`${linesArrayOfObjects[j][headerNames[k]].replace(/\<br\>/g, "\n")}`);
                        var newText  = document.createTextNode(`${linesArrayOfObjects[j][headerNames[k]].replace(/<br\s*[\/]?>/gi, "\n")}`);
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
                            // works only with != not with !== WHY?
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
            var clickedCheckboxNameId = clickedCheckboxName.replace(/\s/g,'').replace(/\,/g,'').replace(/\(/g,'').replace(/\)/g,'').replace(/\//g,'');
            var filtersHtmlContent = "";
            // If clicked on one of the checkboxes
            if ($(this).is(':checked')) {
                if (checkedFilters.length === 0) {
                    $('#actualFilters').fadeIn();
                }
                checkedFilters.push(clickedCheckboxName);
                // Get parent to see in which category is in it and store it in an object with property names as category
                var selectedObject = {};
                // Getting the id of collapse to identify in which category is click the checkbox
                selectedObject.categoryName = $(this).parent().parent().parent().attr('id');
                selectedObject.checkboxName = clickedCheckboxName;
                checkedFiltersByCategory.push(selectedObject);
                filterTableByCategory(checkedFiltersByCategory);
                // if any button exists already in Active filters list
                if ($('#'+clickedCheckboxNameId+'B, input[type="button"]').length > 0) {
                    // alert("van ilyen, újraadjuk");
                    // var seged = $('#'+clickedCheckboxNameId+'B');
                    // $('#actualFilters').append(seged);
                    // $('#'+clickedCheckboxNameId+'B, input[type="button"]').fadeIn();
                    $('#'+clickedCheckboxNameId+'B, input[type="button"]').appendTo('#actualFilters').fadeIn();
                } else {
                    // alert("nincs ilyen, létrehozzuk");
                    // Adding checked category to Active filters list
                    filtersHtmlContent = `<button type="button" id="${clickedCheckboxNameId}B" class="btn-filter btn btn-labeled btn-info btn-xs name="${clickedCheckboxName}">${clickedCheckboxName}<span class="btn-label"><i class="glyphicon glyphicon-remove"></i></span></button>`;
                    // document.getElementById('actualFilters').innerHTML += filtersHtmlContent;
                    $('#actualFilters').append(filtersHtmlContent).fadeIn();
                }

            // if clicked on an already checked checkbox
            } else {
                checkedFilters = checkedFilters.filter(e => e !== clickedCheckboxName);
                checkedFiltersByCategory = checkedFiltersByCategory.filter(function( obj ) {
                    return obj.checkboxName !== clickedCheckboxName;
                });
                // $('#'+clickedCheckboxNameId+ ', .btn-filter').fadeOut();
                $('#'+clickedCheckboxNameId+'B, input[type="button"]').fadeOut();
                if (checkedFilters.length === 0) {
                    $('#actualFilters').fadeOut();
                }

                // console.log("Clicked clickedCheckboxNameId: "+clickedCheckboxNameId);
                //.detach();
                filterTableByCategory(checkedFiltersByCategory);
            }
        });

        // better to call here (than directly in input field) because of clear button on the right
        $('#myInput').on("input", function() {
            var inputValue = document.getElementById("myInput").value;
            var selectedObject = {};
            // Deleting previous search word category
            checkedFiltersByCategory = checkedFiltersByCategory.filter(function(el) {
                return el.categoryName !== "searchWord";
            });
            if (inputValue !== "") {
                selectedObject.categoryName = "searchWord";
                selectedObject.checkboxName = inputValue;
                checkedFiltersByCategory.push(selectedObject);
            }
            filterTableByCategory(checkedFiltersByCategory);
        });

        // Sidebar filters close button handler (visible only on mobile view)
        $('#btnCloseSideFilters, .overlay').on('click', function () {
             $('.overlay').fadeOut();
             $('#divSideFilters').hide();
             $('#divFilters').detach().appendTo('#panelFilter');
        });

        // Sidebar filters search button handler (visible only on mobile view)
        $('#buttonsFilterAtSearch').on('click', function () {
            $('#divSideFilters').show();
            $('#divFilters').detach().appendTo('#divSideFilters');
            $('.overlay').fadeIn();
        });

        // Actual filters close buttons handler
        $("#actualFilters").on("click", ".btn-label", function() {
            // console.log("|"+$(this).parent().attr("id")+"|");
            // var tempName = $(this).parent().text();
            // Removing last B from the id
            $("input[id='"+$(this).parent().attr("id").slice(0,-1)+"']").click();
            // $("input[name='Orbitmotor']").click();  .detach()
            // $(this).parent().fadeOut();
        });


    </script>
    <?php include 'included-phps/footer.php';?>
</body>

</html>

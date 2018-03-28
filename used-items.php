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
            <div class="left-container sm-hidden col-sm-3">
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
            <div class="right-container col-sm-9">
                <!-- Div for displaying the actual filters -->
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

    <script src="used-items.js"></script>

    <?php include 'included-phps/footer.php';?>
</body>

</html>

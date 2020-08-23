<?php

namespace App;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class PaginationEngine extends Model
{
    public static function paginationStyle()
    {
        return '<style>
                    /* Dropdown Button */
                    .dropbtn {
                      background-color: white;
                      color: black;
                      padding: 5px 10px;
                      font-size: 16px;
                      border: 1px solid black;
                      cursor: pointer;
                      border-radius: 0.5rem;
                    }

                    /* Dropdown button on hover & focus */
                    .dropbtn:hover, .dropbtn:focus {
                      background-color: gold;
                    }

                    /* The container <div> - needed to position the dropdown content */
                    .dropdown {
                      position: relative;
                      display: inline-block;
                    }

                    /* Dropdown Content (Hidden by Default) */
                    .dropdown-content {
                      display: none;
                      position: absolute;
                      background-color: #f1f1f1;
                      min-width: 100%;
                      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                      overflow:auto;
                      z-index: 1;
                    }

                    /* Links inside the dropdown */
                    .dropdown-content a {
                      color: black;
                      padding: 12px 16px;
                      text-decoration: none;
                      display: block;
                    }

                    /* Links of navigator style */
                    .anchor-to-btn{
                        list-style-type: none!important;
                        display:flex;
                        justify-content: center;

                    }
                    .anchor-to-btn li{
                        width: 25px;
                        text-align: center;
                        background-color: white;
                        border:1px solid black;
                        margin: 5px;
                        box-shadow: 2px 2px 3px black;
                        transition: transform 0.3s ease-in-out;
                    }
                    .anchor-to-btn li:hover{
                        transform: scale(1.2)!important;
                        background: gold;
                        cursor: pointer;
                    }
                    .anchor-to-btn li a{
                        padding: 3px 9px;
                        color: black;
                        font-weight: 600;
                    }

                    /* Change color of dropdown links on hover */
                    .dropdown-content a:hover {background-color: #ddd}

                    /* Show the dropdown menu (use JS to add this class to the .dropdown-content container when the user clicks on the dropdown button) */
                    .show {display:block;}
                </style>';
    }
    //--------------------------------------------------------------------------------------------------------------
    public static function basicScript()
    {
        return '<script>
                /* When the user clicks on the button,
                toggle between hiding and showing the dropdown content */
                function dropDown() {
                  document.getElementById("myDropdown").classList.toggle("show");
                }

                // Close the dropdown menu if the user clicks outside of it
                window.onclick = function(event) {
                  if (!event.target.matches(\'.dropbtn\')) {
                    var dropdowns = document.getElementsByClassName("dropdown-content");
                    var i;
                    for (i = 0; i < dropdowns.length; i++) {
                      var openDropdown = dropdowns[i];
                      if (openDropdown.classList.contains(\'show\')) {
                        openDropdown.classList.remove(\'show\');
                      }
                    }
                  }
                }
                </script>';
    }
    //------------------------------------------------------------------------------------------------------------
    public static function ajaxScript($route)
    {
        return '<script>

                function getPage(pageNumber) {

                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "'.$route.'");
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.setRequestHeader("X-CSRF-TOKEN", "'.csrf_token().'");
                    xhr.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200)
                            document.getElementById("page").innerHTML = this.responseText;
                        else
                            document.getElementById("page").innerHTML = "Error Happened";
                    }
                    xhr.send("paginate=false&pageNumber=" + pageNumber);
                }

                function paginate(rowsPerPage) {
                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "'.$route.'");
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.setRequestHeader("X-CSRF-TOKEN", "'.csrf_token().'");
                    xhr.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200)
                            document.getElementById("paginate").innerHTML = this.responseText;
                        else
                            document.getElementById("paginate").innerHTML = "Error Happened";
                    }
                    xhr.send("paginate=true&rowsPerPage=" + rowsPerPage);
                }
                </script>';
    }
    //-----------------------------------------------------------------------------------------------------------
    public static function dropDownList($list, $dropName)
    {
        $dropDownList = ' <div class="dropdown">
                          <button onclick="dropDown()" class="dropbtn">'.$dropName.'</button>
                              <div id="myDropdown" class="dropdown-content">';
        foreach ($list as $name => $value)
        {
            $dropDownList .= '<a onclick="paginate('.$value.')">'.$name.'</a>';
        }

        $dropDownList .= '</div></div>';
        return $dropDownList;
    }
    //----------------------------------------------------------------------------------------------------------
    public static function getPage($request)
    {
        $tableArray = $request->session()->get('table_array');
        $operationsArray = $request->session()->get('operations_array');

        if(isset($request['paginate']))
        {
            if($request['paginate'] == 'false')
            {
                $rowsPerPage = $request->session()->get('rows_per_page');
                $pageNumber = (int)$request['pageNumber'];
                if(isset($pageNumber))
                    return self::buildPage($tableArray, $rowsPerPage, $pageNumber, $operationsArray,true);

                else return '<span style="color: red; background-color: white;">Can not get the target page number = '.$pageNumber.'</span>';
            }
            else
            {
                $rowsPerPage = $request['rowsPerPage'];
                if(isset($rowsPerPage))
                    return self::buildPage($tableArray, $rowsPerPage, 1, $operationsArray);

                else return '<span style="color: red; background-color: white;">Can not paginate with number of rows = '.$rowsPerPage.'</span>';
            }
        }

        else return '<span style="color: red; background-color: white;">Can not understand if it is a paginate or a getPage request</span>';
    }
    //----------------------------------------------------------------------------------------------------------
    public static function createInitialPage($tableArray, $rowsPerPage, $operationsArray = null)
    {
        return self::buildPage($tableArray, $rowsPerPage, 1, $operationsArray);
    }
    //-----------------------------------------------------------------------------------------------------------
    private static function buildPage($tableArray, $rowsPerPage, $currentPageNumber, $operationsArray = null, $justTable = false)
    {
        session(['table_array'=>$tableArray, 'operations_array'=>$operationsArray, 'rows_per_page'=>$rowsPerPage]);
        $page = '<div id="paginate" style="margin: 10px 0; padding: 0;">';
        $page .= '<div id="page" class="container-fluid box-body" style="margin: 15px 0; padding: 0;">';
        $page .= self::tabulator($tableArray, $rowsPerPage, $currentPageNumber, $operationsArray);
        $page .= '</div>';
        if(!$justTable)
            $page .= self::navigator($tableArray, $rowsPerPage);
        $page .= '</div>';
        return $page;
    }
    //-----------------------------------------------------------------------------------------------------------
    private static  function navigator($tableArray, $rowsPerPage)
    {
        $navigator = '<ul class="anchor-to-btn">';
        $totalNumberOfRows = count($tableArray) - 1;
        $totalNumberOfPages = ceil($totalNumberOfRows/$rowsPerPage);
        if($totalNumberOfPages == 1)
            return '';
        else
        {
            for ($i = 1; $i <= $totalNumberOfPages; $i++)
                $navigator .= '<li><a onclick="getPage('.$i.')">'.$i.'</a></li>';
        }

        $navigator .= '</ul>';
        return $navigator;
    }
    //-----------------------------------------------------------------------------------------------------------
    private static function tabulator($tableArray, $rowsPerPage, $currentPageNumber, $operationsArray)
    {
        $tableHeader = self::tableHeader($tableArray);
        $tableData = self::tableData($tableArray, $rowsPerPage, $currentPageNumber, $operationsArray);

        $table = '<table class="table table-bordered">';
        $table .= $tableHeader;
        $table .= $tableData;
        $table .= '</table>';

        return $table;
    }
    //-------------------------------------------------------------------------------------------------------------
    private static function tableHeader($tableArray)
    {
        $tableHeader = '<tr class="thead-dark"> <th scope="col">#</th>';
        foreach ($tableArray['headers'] as $columnName => $headerName)
        {
            $tableHeader .= '<th scope="col">'.$headerName.'</th>';
        }
        $tableHeader .= '</tr>';
        return $tableHeader;
    }
    //--------------------------------------------------------------------------------------------------------------
    private static function tableData($tableArray, $rowsPerPage, $currentPageNumber, $operationsArray)
    {
        $tableData = '';
        //check if the $rowsPerPage exceeds the tableArray length
        $numberOfRows = count($tableArray) - 1;
        if($rowsPerPage > $numberOfRows)
            $rowsPerPage = $numberOfRows;

        //count the start and the last index
        $startIndex = $rowsPerPage*($currentPageNumber - 1);
        $lastIndex = $startIndex + $rowsPerPage;

        //check if the last index exceeds the tableArray length
        if($lastIndex > $numberOfRows)
            $lastIndex = $numberOfRows;

        $tableHeaders = array_keys($tableArray['headers']);
        for($row = $startIndex; $row < $lastIndex; $row++)
        {
            $tableData .= '<tr class="bg-white"> <td>'.(string)($row + 1).'</td>';
            for($column = 0; $column < count($tableHeaders); $column++)
            {
                $tableData .= '<td>';
                $tableData .= $tableArray[(string)$row][$tableHeaders[(string)$column]];
                $tableData .= '</td>';
            }
            if($operationsArray != null) {
                $tableData .= '<td>';
                $tableData .= json_encode(self::parseOperations($operationsArray , $tableArray[(string)$row]));
                $tableData .= '</tr>';
            }
        }
        return $tableData;
    }
    //----------------------------------------------------------------------------------------------------------------
    private static function parseOperations($constrains, $tableRow)
    {
        $operations = '';
        for ($i = 0; $i < count($constrains); $i++)
        {
            $currentConstrainArray = $constrains[$i]['constrains'];
            $currentOperationsArray = $constrains[$i]['operations'];

            if((string)gettype($currentConstrainArray) == 'string' && $currentConstrainArray == 'false') {
                for ($j = 0; $j < count($currentOperationsArray); $j++)
                    $operations .= (string)$currentOperationsArray[$j];
            }
            else {
                for ($j = 0; $j < count($currentConstrainArray); $j++) {
                    $currentConstrain = preg_split("/(<>|=)/", $currentConstrainArray[$j], -1, PREG_SPLIT_DELIM_CAPTURE);
                    if (count($currentConstrain) != 3)
                        $currentConstrain = preg_split("/(<|>)/", $currentConstrainArray[$j], -1, PREG_SPLIT_DELIM_CAPTURE);

                    $splattedConstrains[$i] = $currentConstrain;
                }
            }
        }
        return $splattedConstrains;
    }
    //----------------------------------------------------------------------------------------------------------
    private static function matchValue($first, $second, $operator)
    {
        switch ($operator){
            case '<':
                if($first < $second)
                    return true;
                else return false;
            case '>':
                if($first > $second)
                    return true;
                else return false;
            case '=':
                if($first = $second)
                    return true;
                else return false;
            case '<>':
                if($first <> $second)
                    return true;
                else return false;
            case '<=':
                if($first <= $second)
                    return true;
                else return false;
            case '>=':
                if($first >= $second)
                    return true;
                else return false;
        }
    }
}

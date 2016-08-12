<?php
include_once "stem.php";
$query = $_POST['query'];


?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Search Result</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,600' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Mr+Dafoe' rel='stylesheet' type='text/css'>
    <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		 <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style type="text/css">
        button {
            background-color: #f2f2f2;
            border: 1px solid #f2f2f2;
            border-radius: 2px;
            color: #757575;
            cursor: default;
            font-family: arial, sans-serif;
            font-size: 13px;
            font-weight: bold;
            margin: 11px 4px;
            min-width: 54px;
            padding: 0 16px;
            text-align: center;
        }

        button:hover {
            border-color: dodgerblue;
        }

        table.sample {
            padding-left: 100px;
            margin: 0 auto;
            width: 100%;
            border-width: 5px;
            border-spacing: 2px;
            border-style: hidden;
            border-color: gray;
            border-collapse: collapse;
            background-color: rgb(255, 255, 240);
        }

        table.sample th {
            border-width: 1px;
            padding: 5px;
            border-style: none;
            border-color: gray;
            background-color: white;
            -moz-border-radius:;
        }

        table.sample td {

            border-width: 1px;
            padding: 5px;
            border-style: none;
            border-color: gray;
            background-color: white;
            -moz-border-radius:;
        }

        a {
            text-decoration: none;
        }

        #search_button {
            background-color: #f2f2f2;
            border: 1px solid #f2f2f2;
            border-radius: 2px;
            color: #757575;
            cursor: default;
            font-family: arial, sans-serif;
            font-size: 13px;
            font-weight: bold;
            margin: 11px 4px;
            min-width: 54px;
            padding: 0 16px;
            text-align: center;
        }

        #search_button:hover {
            border-color: dodgerblue;
        }

    </style>


</head>
<body style=" background-image: none;width: 90%; font-family: 'Open Sans', sans-serif; margin: 0 auto">
<div class="header"
     style="background-color: #ebf6fd;margin-top:10px;border-bottom:1px solid #666;border-color:#e5e5e5;height: 100px">
    <!--    <span style="font-family: 'Apple Color Emoji' ;font-size: 42px;margin: 20px 10px">SpiderMan</span>-->
    <!--    <br>-->
    <span style="display: inline-block;"><img src="img/hello1.png"
                                              style="margin-left:10px; margin-top: 10px;width: 120px;height: 50px; ">
											  </span>
    <span style="display: inline-block;margin-top: 15px"><form action='<?php echo $_SERVER['PHP_SELF']; ?>'
                                                               method="post" style="width: 150%;">
            <input id="search" style="border: #e8db69;margin-left: 3px;padding: 10px 1px;width: 115%;
    height: auto;z-index: auto;" name="query" placeholder=" <?php echo $query; ?>">
    <span style="display: inline-block"><input id="search_button" type="submit" value="Search"> </span></form></span>
    <div style="display: inline-block">
        <button onclick="PageRank()">PageRank</button>
        <button onclick="HITS()">HITS</button>
			
    </div>


</div>
<div class="container">
 
  <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-link" data-toggle="modal" data-target="#myModal">Help</button>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">How to Search</h4>
        </div>
        <div class="modal-body " style="float:left" >
          <h6>Learn a few tips and tricks to help you easily find information</h6>
		  <h3>1.Start with one keyword</h3>
		  <h6>Type one word to the query box. e.g summer. all search words will be processed by stem operator, which means if you type connections,
		  connects or connector, the search keyword will be connect</h6>
		   <h3>2.Use boolean operator</h3>
		   <h6>"AND": If you want to search pages containing both two words, use "AND". e.g summer AND employee </h6>
		   <h6>"OR": If you want to search pages containing whatever a word you want, use "OR". e.g summer OR employee </h6>
		    <h3>3.Multiple keywords search </h3>
			<h6>You can search multiple keywords. e.g. summer AND employee AND vacation</h6>
			 <h3>4.DO NOT mix "AND" and "OR" boolean operator</h3>
			 <h6>This search engine does not support mix boolean operators</h6>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
<div class="content" style="margin-left: 110px">

    <div>

        <?php
        $arr = search($query);
        function search($query)
        {
            $cox = connect_db();
            $sql = "Select doc, word from Inverted_file WHERE ";
            $a = explode(' ', $query);
            if (count($a) > 1) {
                if (strpos($query, 'OR') !== false) {
                    foreach ($a as $key => $value) {
                        if ($value != 'OR') {
							$stemmer = new Stemmer(); 
							$value1=$stemmer->stem($value); 
                            $sql .= " word=" . "'" . $value1 . "'";
                            if ($key < count($a) - 1) {
                                $sql .= ' OR';
                            }

                        }
                    }
                    $result = mysql_query($sql, $cox);

                    if (mysql_num_rows($result) > 0) {
                        $doc = '';
                        while ($row = mysql_fetch_array($result)) {
                            $doc .= $row['doc'];

                        }
                        preg_match_all("/([0-9]+,[0-9]+)/", $doc, $ss);
                        $arr = array();
                        foreach ($ss[0] as $row) {
                            $e = explode(',', $row);
                            $arr[$e[0]] = $e[1];

                        }
                        arsort($arr);
                        echo '<table class="sample">';

                        foreach ($arr as $key => $value) {
                            echo '<tr><td>';
                            find_doc($key);
                            echo '<td>Frequency: ' . $value . '</td>' . '</tr>';

                        }
                        echo '</table>';
                    } else {
                        echo '<h1>no result</h1>';
                    }

                } elseif (strpos($query, 'AND') !== false) {

                    foreach ($a as $key => $value) {
                        if ($value != 'AND') {
							$stemmer = new Stemmer(); 
							$value1=$stemmer->stem($value); 
                            $sql .= " word=" . "'" . $value1 . "'";
                            if ($key < count($a) - 1) {
                                $sql .= ' OR';
                            }
                        }
                    }
                    $result = mysql_query($sql, $cox);

                    if (mysql_num_rows($result) > 0) {
                        $doc = '';
                        while ($row = mysql_fetch_array($result)) {
                            $doc .= $row['doc'];

                        }
                        preg_match_all("/([0-9]+,[0-9]+)/", $doc, $ss);
                        $arr1 = array();
                        $arr = array();
                        foreach ($ss[0] as $row) {
                            $e = explode(',', $row);

                            if (array_key_exists($e[0], $arr1)) {
                                $arr[$e[0]] = $e[1];
                            } else {
                                $arr1[$e[0]] = $e[1];
                            }

                        }
                        arsort($arr);
                        if (count($arr) > 0) {
                            echo '<table class="sample">';

                            foreach ($arr as $key => $value) {
                                echo '<tr><td>';
                                find_doc($key);
                                echo '<td>Frequency: ' . $value . '</td>' . '</tr>';

                            }
                            echo '</table>';
                        } else {
                            echo '<h1>no result</h1>';
                        }


                    }


                } else {
                    echo '<h1>no result</h1>';
                }
            } else {
				$stemmer = new Stemmer(); 
				$query1=$stemmer->stem($query); 
                $sql = "Select doc, word from Inverted_file WHERE word='$query1'";
                $result = mysql_query($sql, $cox);

                if (mysql_num_rows($result) > 0) {
                    $doc = '';
                    while ($row = mysql_fetch_array($result)) {
                        $doc .= $row['doc'];

                    }


                    preg_match_all("/([0-9]+,[0-9]+)/", $doc, $ss);

                    $arr = array();
                    foreach ($ss[0] as $row) {
                        $e = explode(',', $row);
                        $arr[$e[0]] = $e[1];

                    }
                    arsort($arr);

                    echo '<table class="sample">';

                    foreach ($arr as $key => $value) {
                        echo '<tr><td>';
                        find_doc($key);
                        echo '<td>Frequency: ' . $value . '</td>' . '</tr>';

                    }
                    echo '</table>';


                } else {
                    echo '<h1>no result</h1>';
                }

            }
            return $arr;
        }


        function connect_db()
        {
            $con = mysql_connect('mysql.dur.ac.uk', 'znjv53', '');
            if (!$con) {
                die("Could not connect: " . mysql_error());
                return $con;
            }
            mysql_select_db("Xznjv53_search");
            return $con;
        }

        function find_doc($id)
        {
            $cox = connect_db();
            $sql = "Select url,created,title from urls WHERE Doc_ID='$id'";
            $result = mysql_query($sql, $cox);
            while ($row = mysql_fetch_array($result)) {
                echo '<a href=' . "'" . $row['url'] . "'" . '>' . $row['title'] . '</a>';
                echo '</td><td>';
                echo $row['created'];
                echo '</td>';

            }

            return $row[0];

        }


        ?>
    </div>
</div>
<div class="footer" style=" width: 100%;

    margin: 0 auto;
    height: 50px;
    background: #ebf6fd;
    position: relative;">
    <a type="button" href="index.html">Back Home</a>

    <?php


    $totalPage = ceil(count($arr) / 10);
    echo $totalPage;
    $pagLink = "<div class='pagination'>";
    for ($i = 1; $i <= $totalPage; $i++) {
        $pagLink .= "<a href='load.php?page=" . $i . "'>" . $i . "</a>";
    };
    echo $pagLink . "</div>";

    ?>


</div>
<script>
    function PageRank() {
        var array = [];

        array.push( <?php
            $p = '';
            foreach ($arr as $key => $value) {
                $p .= "'" . $key . "'" . ",";
            }
            print rtrim($p, ',')
            ?>);

        $('.sample').load('pageRank.php', {query: array});
    }

    function HITS() {
//        var query = '<?php //echo $query?>//';
        var array = [];

        array.push( <?php
            $p = '';
            foreach ($arr as $key => $value) {
                $p .= "'" . $key . "'" . ",";
            }
            print rtrim($p, ',')
            ?>);

        $('.sample').load('HITS.php', {query: array});
    }
</script>
</body>
</html>

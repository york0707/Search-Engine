<?php

$index = $_POST['query'];
$arr=array();
//$cox = connect_db();
//
//$sql = "Select doc, word from Inverted_file WHERE word='$query'";
//
//$result = mysql_query($sql, $cox);
//
//if (mysql_num_rows($result) > 0) {
//    while ($row = mysql_fetch_array($result)) {
//        $doc = $row['doc'];
//    }
//
//    preg_match_all("/([0-9]+,[0-9]+)/", $doc, $ss);
//
//    $arr = array();
//    foreach ($ss[0] as $row) {
//        $e = explode(',', $row);
//        $arr[$e[0]] = $e[1];
//
//    }
//    arsort($arr);

    foreach ($index as $key => $value) {

        $cox = connect_db();
        $sql = "Select inlink,outlink from urls WHERE Doc_ID='$value'";
        $result = mysql_query($sql, $cox);
        while ($row = mysql_fetch_array($result)) {

            $a = count(explode(',', $row['inlink']));
            $h = (int)$row['outlink'];
            $hits = ($a + $h) / 2;
            $arr[$value] = $hits;
        }

    }
    arsort($arr);

    echo '<table class="sample">';

    foreach ($arr as $key => $value) {
        echo '<tr><td>';
        find_doc($key);
        echo '<td>Average HITS: ' . $value . '</td>' . '</tr>';

    }
    echo '</table>';

//
// else {
//    echo '<h1>no result</h1>';
//}


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
        echo '<a href='."'" . $row['url'] ."'". '>' . $row['title'] . '</a>';
        echo '</td><td>';
        echo $row['created'];
        echo '</td>';


    }


}


?>
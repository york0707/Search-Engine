<?php

$arr = $_POST['query'];
$index = array();
$cox = connect_db();
foreach ($arr as $key => $value) {
//   array_push($index, $value);
    $sql = "Select PR from urls WHERE Doc_ID='$value'";
    $result = mysql_query($sql, $cox);
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $index[$value] = $row['PR'];
        }
    }
}
arsort($index);


foreach ($index as $key => $value) {
    echo '<tr><td>';

    find_doc($key);
    echo '<td>PageRank Score: ' . $value . '</td>' . '</tr>';

}
echo '</table>';


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

    return $row[0];

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


?>
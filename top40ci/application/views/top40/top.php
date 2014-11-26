<h1>Top 40 UK singles</h1>
<div class="container-fluid">

    <?php
    //script to get data from bbc top 40
    $output = json_decode($top40, true);

    echo '<table class="bordered"><thead><tr>' . '
<th>Position</th>' . '<th>Popularity</th>' . '<th>Artist</th>' . '<th>Title</th>' . '<th>Download</th>' . '</th>' . '</tr></thead>';
    foreach ($output as $item) {
        echo '<tbody><tr>';
        echo '<td class="td-center">' . $item['position'] . '</td>';
        switch ($item['status_st']) {
            case 'new':
                echo "<td class=\"td-center\"><img src=\"img/new.png\" /></td>";
                break;
            case 'down':
            if(isset($item["status_num"]))
                echo "<td class=\"td-center\"><img src=\"img/down.png\"  />   " . $item['status_num'] . "</td>";
            else
                echo "<td class=\"td-center\"><img src=\"img/up.png\" /></td>";
                break;
            case 'up':
            default:
            if(isset($item["status_num"]))
                echo "<td class=\"td-center\"><img src=\"img/up.png\" />    " . $item['status_num'] . "</td>";
            else
                echo "<td class=\"td-center\"><img src=\"img/up.png\" /></td>";
                break;
        }
        echo '<td>' . $item['artist'] . '</td>';
        echo '<td>' . $item['title'] . '</td>';
        if(isset($offer))
        echo '<td class="td-center">' . '<a href='.$offer.'><img src="img/download.png" /></a>' . '</td>';
    else
        echo '<td class="td-center"><a href="#"><img src="img/download.png" /></a></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
    ?>
</div>
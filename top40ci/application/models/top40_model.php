<?php
class Top40_model extends CI_Model
{
    public function __construct()
    {

    }

    public function get_top($mode)
    {
        // First of all, define the URLs for the various requests we might make:
        #http://www.bbc.co.uk/radio1/chart/updatesingles/print
        //TODO: test this links
        define("SINGLE_CHART_URL", "http://www.bbc.co.uk/radio1/chart/singles/print");
        define("ALBUMS_CHART_URL", "http://www.bbc.co.uk/radio1/chart/albums/print");


        // This is an array of elements to remove from the content before stripping it:
        $newlines = array("\t", "\n", "\r", "\x20\x20", "\0", "\x0B");

        switch ($mode) {
            // They want the Singles chart, or haven't specified what they want:
            case 'singles':
            case '':
            default:
                $content = file_get_contents(SINGLE_CHART_URL);
                $start_search = '<table border="1" cellpadding="3" cellspacing="0" width="100%" style="font-family: Arial; font-size: 70%;"><tr><th>Pos.</th><th>Status</th><th>Prev.</th><th>Wks</th><th>Artist</th><th>Title</th></tr>';
                break;

            // They want the Album chart:
            case 'albums':
                $content = file_get_contents(ALBUMS_CHART_URL);
                $start_search = '<table border="1" cellpadding="3" cellspacing="0" width="100%" style="font-family: Arial; font-size: 70%;"><tr><th>Pos.</th><th>Status</th><th>Prev.</th><th>Wks</th><th>Artist</th><th>Title</th></tr>';
                break;
        }

        $content = str_replace($newlines, "", html_entity_decode($content));

// Work out where we need to start the scrape from:
        $scrape_start = strpos($content, $start_search);
        $scrape_end = strpos($content, '</table>', $scrape_start);
        $the_table = substr($content, $scrape_start, ($scrape_end - $scrape_start));
        $the_table = str_replace(' style="font-family: Arial; font-size: 70%;"', '', $the_table);

// Now loop through the rows and get the data we need:
        preg_match_all("|<tr(.*)</tr>|U", $the_table, $rows);

        $count = 0;
        $listtop40 = array();
        foreach ($rows[0] as $row) {
            // Check it's OK:
            if (!strpos($row, '<th')) {
                // Get the cells:
                preg_match_all("|<td(.*)</td>|U", $row, $cells);
                $cells = $cells[0];

                $position = strip_tags($cells[0]);
                $status = strip_tags($cells[1]);
                $prev_pos = strip_tags($cells[2]);
                $weeks = strip_tags($cells[3]);
                $artist = strip_tags($cells[4]);
                $title = strip_tags($cells[5]);

                $status = explode(" ", $status);

                $prev_pos_int = ($prev_pos == 'none') ? 0 : $prev_pos;
                $position_change = $prev_pos - $position;
                $position_string = "";

                if ($position_change < 0) $position_string = 'down';
                if ($position_change == 0) $position_string = 'new';
                if ($position_change > 0) $position_string = 'up';

                $move_val = $this->signed2unsigned($position_change);

                $item = array(
                    'position' => $position,
                    'previousPosition' => $prev_pos_int,
                    'noWeeks' => $weeks,
                    'artist' => $artist,
                    'title' => $title,
                    'status_st' => $status[0],
                    'change' => "",
                    'directions' => $position_string,
                    'amount' => $move_val,
                    'actual' => $position_change
                );
                if (sizeof($status) > 1)
                    $item['status_num'] = $status[1];

                array_push($listtop40, $item);
                $count++;
            }
        }
        return json_encode($listtop40);
    }


// Function to convert a signed integer to an unsigned integer:
    public function signed2unsigned($integer)
    {
        return ($integer < 0) ? ($integer - $integer) - $integer : $integer;
    }
}
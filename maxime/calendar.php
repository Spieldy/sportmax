<?php

function getCurlRequest($URL) {
    // Get cURL resource
    $ch = curl_init();

    // Set url
    curl_setopt($ch, CURLOPT_URL, $URL);

    // Set method
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    // Set options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Set compression
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");

    // Set headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Basic " . base64_encode("Itymax" . ":" . "pentio91@/MSF")
    ]);

    // Send the request & save response to $resp
    $resp= curl_exec($ch);
    curl_close($ch);
    return $resp;
}

function getSpecificMatch($ID,$seasonName){
    $urlRequest="https://api.mysportsfeeds.com/v1.1/pull/nhl/$seasonName/game_boxscore.json?gameid=$ID&teamstats=none&playerstats=none";
    $resp=getCurlRequest($urlRequest);

    if (!$resp) {
        //print curl_error($ch);
        //die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
    } else {
        //echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //echo "\nResponse HTTP Body : " . $resp;
        $resp = (array)json_decode($resp);
        $resp = (array)$resp['gameboxscore'];
        $score = (array)((array)$resp['periodSummary'])['periodTotals']);
        $match = (array)$resp['game'];
        $date = date_create_from_format('Y-m-d', $match['date']);
        $prettyDate = $date->format("l, jS F Y");

        $homeTeam = ((array)$match['homeTeam'])['Name'];
        $awayTeam = ((array)$match['awayTeam'])['Name'];
        echo "<div class=\"col-sm-4\">
                <div class=\"card\">
                  <div class=\"card-body\">
                    <h4 class=\"card-title\">$homeTeam @ $awayTeam </h4>
                    <h6 class=\"card-subtitle mb-2 text-muted\">$prettyDate</h6>
                    <ul class=\"list-group list-group-flush\">
                      <li class=\"list-group-item\">$homeTeam:$score[homeScore]</li>
                      <li class=\"list-group-item\">$awayTeam:$score[awayScore]</li>
                      <li class=\"list-group-item\"><a href=\"<?=BASEURL?>index.php/hockey/classment\" class=\"card-link\">Classment</a></li>
                    </ul>
                  </div>
                </div>
              </div>";
    }
}


function getSpecificDate($date, $seasonName){

    $dateFormatee=date("Ymd",$date);
    //$urlRequest="https://api.mysportsfeeds.com/v1.1/pull/nhl/$seasonName/daily_game_schedule.json?fordate=$dateFormatee";
    $urlRequest= "https://api.mysportsfeeds.com/v1.1/pull/nhl/$seasonName/scoreboard.json?fordate=$dateFormatee";
    //print $urlRequest;

    $resp = getCurlRequest($urlRequest);

    if (!$resp) {
        //print curl_error($ch);
        //die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
    } else {
       // echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //echo "\nResponse HTTP Body : " . $resp;

        $prettyDate = date("l, jS F Y", $date);

        $resp = (array)json_decode($resp);
        $resp = (array)$resp['scoreboard'];
        $resp = (array)$resp['gameScore'];

        foreach($resp as $gameScore){
            $gameScore = (array)$gameScore;
            $match = (array)$gameScore["game"];

            $homeTeam = ((array)$match['homeTeam'])['Name'];
            $awayTeam = ((array)$match['awayTeam'])['Name'];

            if($gameScore['isCompleted'] == "true"){
                $homeScore = $gameScore["homeScore"];
                $awayScore = $gameScore["awayScore"];
            }else{
                $homeScore = "N/A";
                $awayScore = "N/A";
            }

            echo "<div class=\"col-sm-4\">
                <div class=\"card\">
                  <div class=\"card-body\">
                    <h4 class=\"card-title\">$homeTeam @ $awayTeam </h4>
                    <h6 class=\"card-subtitle mb-2 text-muted\">$prettyDate</h6>
                    <ul class=\"list-group list-group-flush\">
                      <li class=\"list-group-item\">$homeTeam:$homeScore</li>
                      <li class=\"list-group-item\">$awayTeam:$awayScore</li>
                      <li class=\"list-group-item\"><a href=\"<?=BASEURL?>index.php/hockey/classment\" class=\"card-link\">Classment</a></li>
                    </ul>
                  </div>
                </div>
              </div>";

        }

    }
}


function afficherCalendrier($dateDebut =  NULL, $dateFin = NULL )
{
    if($dateDebut == NULL || $dateFin == NULL){
        $dateDebut = mktime(0,0,0,date("m"),date("d")-2,date("Y"));
        $dateFin = mktime(0,0,0,date("m"),date("d")+2,date("Y"));
    }

    $anneeDebut = date("Y", $dateDebut);
    $anneeFin = date("Y", $dateDebut)+1;

    //echo date("d-m-Y", $dateDebut) . date("d-m-Y", $dateFin) . "\n";
    //echo "$anneeDebut $anneeFin \n";

    //Ici on prend que regular TODO faire 2 sections ?
    $seasonName = date("Y", $dateDebut) . "-" . (date("Y", $dateDebut) +1) . "-regular";

    $date = $dateDebut;
    while($date <= $dateFin){
        getSpecificDate($date, $seasonName);
        $date = mktime(0,0,0,date("m",$date),date("d",$date)+1,date("Y"));
        //break;
    }

}

//afficherCalendrier();



?>

<div class="row">
    <?= afficherCalendrier(); ?>
</div>
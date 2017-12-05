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

function getSpecificDate($date, $seasonName){

    $dateFormatee=date("Ymd",$date);
    //$urlRequest="https://api.mysportsfeeds.com/v1.1/pull/nhl/$seasonName/daily_game_schedule.json?fordate=$dateFormatee";
    $urlRequest= "https://api.mysportsfeeds.com/v1.1/pull/nfl/$seasonName/scoreboard.json?fordate=$dateFormatee";
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

            $temp_homeTeam = json_decode(json_encode($match['homeTeam']), true);
            $temp_awayTeam = json_decode(json_encode($match['awayTeam']), true);
            $homeTeam = $temp_homeTeam['Name'];
            $awayTeam = $temp_awayTeam['Name'];

            if($gameScore['isCompleted'] == "true"){
                $homeScore = $gameScore["homeScore"];
                $awayScore = $gameScore["awayScore"];
                echo "<div class=\"col-sm-4\">
                <div class=\"card\">
                  <div class=\"card-body\">
                    <h4 class=\"card-title\">$homeTeam @ $awayTeam </h4>
                    <h6 class=\"card-subtitle mb-2 text-muted\">$prettyDate</h6>
                    <ul class=\"list-group list-group-flush\">
                      <li class=\"list-group-item\">$homeTeam:$homeScore</li>
                      <li class=\"list-group-item\">$awayTeam:$awayScore</li>
                      <li class=\"list-group-item\"><a href=\"<?=BASEURL?>index.php/hockey/standing\" class=\"card-link\">Standing</a></li>
                    </ul>
                  </div>
                </div>
              </div>";
            }else{
                $time = $match['time'];
                
                echo "<div class=\"col-sm-4\">
                <div class=\"card\">
                  <div class=\"card-body\">
                    <h4 class=\"card-title\">$homeTeam @ $awayTeam </h4>
                    <h6 class=\"card-subtitle mb-2 text-muted\">$prettyDate</h6>
                    <ul class=\"list-group list-group-flush\">
                      <li class=\"list-group-item\">Time : $time East </li>
                      <li class=\"list-group-item\"><a href=\"<?=BASEURL?>index.php/hockey/standing\" class=\"card-link\">Standing</a></li>
                    </ul>
                  </div>
                </div>
              </div>";
            }

            

        }

    }
}
function afficherCalendrier($dateDebut =  NULL, $dateFin = NULL )
{
    if($dateDebut == NULL){
        $dateDebut = mktime(0,0,0,date("m"),date("d"),date("Y"));
    }
    if($dateFin == NULL){
        $dateFin = mktime(0,0,0,date("m",$dateDebut),date("d",$dateDebut)+4,date("Y",$dateDebut));
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

?>


<div class="row">
    <?= afficherCalendrier(); ?>
</div>
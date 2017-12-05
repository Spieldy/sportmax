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
    $urlRequest= "https://api.mysportsfeeds.com/v1.1/pull/nba/$seasonName/scoreboard.json?fordate=$dateFormatee";
    //print $urlRequest;

    $resp = getCurlRequest($urlRequest);

    if (!$resp) {
        //print curl_error($ch);
        //die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
    } else {
       //echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //echo "\nResponse HTTP Body : " . $resp;

        $prettyDate = date("l, jS F Y", $date);

        $resp = json_decode($resp, true);
        //echo $resp;
        $resp = $resp['scoreboard'];
        $resp = $resp['gameScore'];
        
        echo "<h6>$prettyDate</h6>
                <table class=\"table table-striped\">
                    <thead>
                        <tr>
                          <th class=\"text-right\">Team away</th>
                          <th class=\"text-center\">Score</th>
                          <th class=\"text-left\">Team home</th>
                        </tr>
                  </thead><tbody>";

        foreach($resp as $gameScore){
            $gameScore = $gameScore;
            $match = $gameScore["game"];

            $homeTeam = $match['homeTeam']['Name'];
            $awayTeam = $match['awayTeam']['Name'];

            if($gameScore['isCompleted'] == "true"){
                $homeScore = $gameScore["homeScore"];
                $awayScore = $gameScore["awayScore"];
                echo "   <tr>
                          <td class=\"text-right\">$awayTeam</td>
                          <td class=\"text-center\">$awayScore - $homeScore</td>
                          <td class=\"text-left\">$homeTeam</td>
                        </tr>";
            }

            

        }
        
        echo "  </tbody>
             </table>";

    }
}
function afficherResultats($dateDebut =  NULL, $dateFin = NULL )
{
    if($dateDebut == NULL){
        $dateDebut = mktime(0,0,0,date("m"),date("d")-7,date("Y"));
    }
    if($dateFin == NULL){
        $dateFin = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
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

afficherResultats();


?>
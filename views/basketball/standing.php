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

function getByConference(){
    
    
    
     $date = mktime(0,0,0,date("m"),date("d"),date("Y"));
    
    $anneeDebut = date("Y",$date);
    $anneeFin = date("Y",$date)+1; //<--------------------------------------- BUG : fonctionnera plus quand on sera en 2018 - pareil sur les autres pages

    //Ici on prend que regular TODO faire 2 sections ?
    $seasonName = $anneeDebut . "-" . $anneeFin . "-regular";
    
    
    $urlRequest= "https://api.mysportsfeeds.com/v1.1/pull/nba/$seasonName/conference_team_standings.json?teamstats=W,L,PTS,PTSA";
    //print $urlRequest;

    $resp = getCurlRequest($urlRequest);
    
    if (!$resp) {
        //print curl_error($ch);
        //die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
        echo "EEEERRRREUR";
    } else {
       //echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //echo "\nResponse HTTP Body : " . $resp;

        //$prettyDate = date("l, jS F Y", $date);

        $resp = json_decode($resp, true);
        //echo $resp;
        $conferences = $resp['conferenceteamstandings']['conference'];
        
        foreach($conferences as $conference){
            $conferenceName = $conference["@name"];
            $teamEntry = $conference['teamentry'];
            
            echo "<div class=\"row\">
                <h3>$conferenceName Conference</h3>
            <table class=\"table table-hover\">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Team</th>
                  <th>Game Played</th>
                  <th>Victory</th>
                  <th>Lost</th>
                </tr>
              </thead>
              <tbody>";
        
        
                    foreach($teamEntry as $entry){
                        $team = $entry['team']['Name'];
                        $rank = $entry['rank'];
                        $gamePlayed = $entry['stats']['GamesPlayed']['#text'];
                        $gameWin = $entry['stats']['Wins']['#text'];
                        $gameLosse = $entry['stats']['Losses']['#text'];

                        echo "  <tr>
                                  <th scope=\"row\">$rank</th>
                                  <td>$team</td>
                                  <td>$gamePlayed</td>
                                  <td>$gameWin</td>
                                  <td>$gameLosse</td>
                                </tr>";
                    }
        
                    echo "</tbody>
                </table>
            </div>";

        }
        
        
}
}

function getOverAll(){
    
     $date = mktime(0,0,0,date("m"),date("d"),date("Y"));
    
    $anneeDebut = date("Y",$date);
    $anneeFin = date("Y",$date)+1; //<--------------------------------------- BUG : fonctionnera plus quand on sera en 2018 - pareil sur les autres pages

    //Ici on prend que regular TODO faire 2 sections ?
    $seasonName = $anneeDebut . "-" . $anneeFin . "-regular";
    
    
    $urlRequest= "https://api.mysportsfeeds.com/v1.1/pull/nba/$seasonName/overall_team_standings.json?teamstats=W,L,PTS,PTSA";
    //print $urlRequest;

    $resp = getCurlRequest($urlRequest);
    
    if (!$resp) {
        //print curl_error($ch);
        //die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
        echo "EEEERRRREUR";
    } else {
       //echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //echo "\nResponse HTTP Body : " . $resp;

        //$prettyDate = date("l, jS F Y", $date);

        $resp = json_decode($resp, true);
        //echo $resp;
        $resp = $resp['overallteamstandings']['teamstandingsentry'];
        
        
        echo "<div class=\"row\">
				<table class=\"table table-hover\">
				  <thead>
				    <tr>
				      <th>#</th>
				      <th>Team</th>
				      <th>Game Played</th>
				      <th>Victory</th>
				      <th>Lost</th>
				    </tr>
				  </thead>
				  <tbody>";
        
        
        foreach($resp as $entry){
            $team = $entry['team']['Name'];
            $rank = $entry['rank'];
            $gamePlayed = $entry['stats']['GamesPlayed']['#text'];
            $gameWin = $entry['stats']['Wins']['#text'];
            $gameLosse = $entry['stats']['Losses']['#text'];
            
            echo "  <tr>
                      <th scope=\"row\">$rank</th>
                      <td>$team</td>
                      <td>$gamePlayed</td>
                      <td>$gameWin</td>
                      <td>$gameLosse</td>
                    </tr>";
        }
        
        echo "</tbody>
	</table>
</div>";
        
    }
}

getByConference();

?>

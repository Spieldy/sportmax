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
    
    
    $urlRequest= "https://api.mysportsfeeds.com/v1.1/pull/nfl/$seasonName/division_team_standings.json?teamstats=W,L,PF,PA";
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
        $divisions = $resp['divisionteamstandings']['division'];
        
        foreach($divisions as $division){
        	$division_explode = explode('/', $division["@name"]);
            $divisionName = $division_explode[1];
            $teamEntry = $division['teamentry'];
            
            echo "<div class=\"row\">
                <h3>$divisionName Conference</h3>
            <table class=\"table table-hover\">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Team</th>
                  <th>Game Played</th>
                  <th>Wins</th>
                  <th>Lost</th>
                  <th>PointFor</th>
                  <th>PointsAgainst</th>
                </tr>
              </thead>
              <tbody>";
        
        
                    foreach($teamEntry as $entry){
                        $team = $entry['team']['Name'];
                        $rank = $entry['rank'];
                        $gamePlayed = $entry['stats']['GamesPlayed']['#text'];
                        $gameWin = $entry['stats']['Wins']['#text'];
                        $gameLosse = $entry['stats']['Losses']['#text'];
                        $overTimeWins = $entry['stats']['PointsFor']['#text'];
                        $overTimeLosses = $entry['stats']['PointsAgainst']['#text'];

                        echo "  <tr>
                                  <th scope=\"row\">$rank</th>
                                  <td>$team</td>
                                  <td>$gamePlayed</td>
                                  <td>$gameWin</td>
                                  <td>$gameLosse</td>
                                  <td>$overTimeWins</td>
                                  <td>$overTimeLosses</td>
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
    
    
    $urlRequest= "https://api.mysportsfeeds.com/v1.1/pull/nfl/$seasonName/overall_team_standings.json?teamstats=W,L,PF,PA";
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
	      <th>Wins</th>
	      <th>Lost</th>
	      <th>PointsFor</th>
          <th>PointsAgainst</th>
	    </tr>
	  </thead>
	  <tbody>";
        
        
        foreach($resp as $entry){
            $team = $entry['team']['Name'];
            $rank = $entry['rank'];
            $gamePlayed = $entry['stats']['GamesPlayed']['#text'];
            $gameWin = $entry['stats']['Wins']['#text'];
            $gameLosse = $entry['stats']['Losses']['#text'];
            $overTimeWins = $entry['stats']['PointsFor']['#text'];
            $overTimeLosses = $entry['stats']['PointsAgainst']['#text'];
            
            echo "  <tr>
                      <th scope=\"row\">$rank</th>
                      <td>$team</td>
                      <td>$gamePlayed</td>
                      <td>$gameWin</td>
                      <td>$gameLosse</td>
                      <td>$overTimeWins</td>
                      <td>$overTimeLosses</td>
                    </tr>";
        }
        
        echo "</tbody>
	</table>
</div>";
        
    }
}

getByConference();

?>




<!-- <h1>AFC</h1>
<div class="row">
	<h3>North Division</h3>
	<table class="table table-hover">
	  <thead>
	    <tr>
	      <th>#</th>
	      <th>Team</th>
	      <th>Game Played</th>
	      <th>Victory</th>
	      <th>Lost</th>
	      <th>Overtime</th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <th scope="row">1</th>
	      <td>Tampa Bay</td>
	      <td>20</td>
	      <td>15</td>
          <td>3</td>
          <td>2</td>
	    </tr>
	    <tr>
	      <th scope="row">2</th>
	      <td>Tonronto</td>
	      <td>22</td>
	      <td>14</td>
          <td>8</td>
          <td>0</td>
	    </tr>
	    <tr>
	      <th scope="row">3</th>
	      <td>New Jersey</td>
	      <td>20</td>
	      <td>12</td>
          <td>5</td>
          <td>3</td>
	    </tr>
          <tr>
	      <th scope="row">4</th>
	      <td>Columbus</td>
	      <td>21</td>
	      <td>13</td>
          <td>7</td>
          <td>1</td>
	    </tr>
	  </tbody>
	</table>
</div>
<div class="row">
	<h3>East Division</h3>
	<table class="table table-hover">
	  <thead>
	    <tr>
	      <th>#</th>
	      <th>Team</th>
	      <th>Game Played</th>
	      <th>Victory</th>
	      <th>Lost</th>
	      <th>Overtime</th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <th scope="row">1</th>
	      <td>Tampa Bay</td>
	      <td>20</td>
	      <td>15</td>
          <td>3</td>
          <td>2</td>
	    </tr>
	    <tr>
	      <th scope="row">2</th>
	      <td>Tonronto</td>
	      <td>22</td>
	      <td>14</td>
          <td>8</td>
          <td>0</td>
	    </tr>
	    <tr>
	      <th scope="row">3</th>
	      <td>New Jersey</td>
	      <td>20</td>
	      <td>12</td>
          <td>5</td>
          <td>3</td>
	    </tr>
          <tr>
	      <th scope="row">4</th>
	      <td>Columbus</td>
	      <td>21</td>
	      <td>13</td>
          <td>7</td>
          <td>1</td>
	    </tr>
	  </tbody>
	</table>
</div>
<div class="row">
	<h3>South Division</h3>
	<table class="table table-hover">
	  <thead>
	    <tr>
	      <th>#</th>
	      <th>Team</th>
	      <th>Game Played</th>
	      <th>Victory</th>
	      <th>Lost</th>
	      <th>Overtime</th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <th scope="row">1</th>
	      <td>Tampa Bay</td>
	      <td>20</td>
	      <td>15</td>
          <td>3</td>
          <td>2</td>
	    </tr>
	    <tr>
	      <th scope="row">2</th>
	      <td>Tonronto</td>
	      <td>22</td>
	      <td>14</td>
          <td>8</td>
          <td>0</td>
	    </tr>
	    <tr>
	      <th scope="row">3</th>
	      <td>New Jersey</td>
	      <td>20</td>
	      <td>12</td>
          <td>5</td>
          <td>3</td>
	    </tr>
          <tr>
	      <th scope="row">4</th>
	      <td>Columbus</td>
	      <td>21</td>
	      <td>13</td>
          <td>7</td>
          <td>1</td>
	    </tr>
	  </tbody>
	</table>
</div>
<div class="row">
	<h3>West Division</h3>
	<table class="table table-hover">
	  <thead>
	    <tr>
	      <th>#</th>
	      <th>Team</th>
	      <th>Game Played</th>
	      <th>Victory</th>
	      <th>Lost</th>
	      <th>Overtime</th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <th scope="row">1</th>
	      <td>Tampa Bay</td>
	      <td>20</td>
	      <td>15</td>
          <td>3</td>
          <td>2</td>
	    </tr>
	    <tr>
	      <th scope="row">2</th>
	      <td>Tonronto</td>
	      <td>22</td>
	      <td>14</td>
          <td>8</td>
          <td>0</td>
	    </tr>
	    <tr>
	      <th scope="row">3</th>
	      <td>New Jersey</td>
	      <td>20</td>
	      <td>12</td>
          <td>5</td>
          <td>3</td>
	    </tr>
          <tr>
	      <th scope="row">4</th>
	      <td>Columbus</td>
	      <td>21</td>
	      <td>13</td>
          <td>7</td>
          <td>1</td>
	    </tr>
	  </tbody>
	</table>
</div>

<h1>NFC</h1>
<div class="row">
	<h3>North Conference</h3>
	<table class="table table-hover">
	  <thead>
	    <tr>
	      <th>#</th>
	      <th>Team</th>
	      <th>Game Played</th>
	      <th>Victory</th>
	      <th>Lost</th>
	      <th>Overtime</th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <th scope="row">1</th>
	      <td>St. Louis</td>
	      <td>21</td>
	      <td>15</td>
          <td>5</td>
          <td>1</td>
	    </tr>
          <tr>
	      <th scope="row">2</th>
	      <td>Winnipeg</td>
	      <td>20</td>
	      <td>12</td>
          <td>5</td>
          <td>3</td>
	    </tr>
          <tr>
	      <th scope="row">3</th>
	      <td>Nashville</td>
	      <td>22</td>
	      <td>12</td>
          <td>6</td>
          <td>2</td>
	    </tr>
          <tr>
	      <th scope="row">4</th>
	      <td>Los Angeles</td>
	      <td>21</td>
	      <td>12</td>
          <td>7</td>
          <td>2</td>
	    </tr>
	  </tbody>
	</table>
</div>
<div class="row">
	<h3>East Conference</h3>
	<table class="table table-hover">
	  <thead>
	    <tr>
	      <th>#</th>
	      <th>Team</th>
	      <th>Game Played</th>
	      <th>Victory</th>
	      <th>Lost</th>
	      <th>Overtime</th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <th scope="row">1</th>
	      <td>St. Louis</td>
	      <td>21</td>
	      <td>15</td>
          <td>5</td>
          <td>1</td>
	    </tr>
          <tr>
	      <th scope="row">2</th>
	      <td>Winnipeg</td>
	      <td>20</td>
	      <td>12</td>
          <td>5</td>
          <td>3</td>
	    </tr>
          <tr>
	      <th scope="row">3</th>
	      <td>Nashville</td>
	      <td>22</td>
	      <td>12</td>
          <td>6</td>
          <td>2</td>
	    </tr>
          <tr>
	      <th scope="row">4</th>
	      <td>Los Angeles</td>
	      <td>21</td>
	      <td>12</td>
          <td>7</td>
          <td>2</td>
	    </tr>
	  </tbody>
	</table>
</div>
<div class="row">
	<h3>South Conference</h3>
	<table class="table table-hover">
	  <thead>
	    <tr>
	      <th>#</th>
	      <th>Team</th>
	      <th>Game Played</th>
	      <th>Victory</th>
	      <th>Lost</th>
	      <th>Overtime</th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <th scope="row">1</th>
	      <td>St. Louis</td>
	      <td>21</td>
	      <td>15</td>
          <td>5</td>
          <td>1</td>
	    </tr>
          <tr>
	      <th scope="row">2</th>
	      <td>Winnipeg</td>
	      <td>20</td>
	      <td>12</td>
          <td>5</td>
          <td>3</td>
	    </tr>
          <tr>
	      <th scope="row">3</th>
	      <td>Nashville</td>
	      <td>22</td>
	      <td>12</td>
          <td>6</td>
          <td>2</td>
	    </tr>
          <tr>
	      <th scope="row">4</th>
	      <td>Los Angeles</td>
	      <td>21</td>
	      <td>12</td>
          <td>7</td>
          <td>2</td>
	    </tr>
	  </tbody>
	</table>
</div>
<div class="row">
	<h3>West Conference</h3>
	<table class="table table-hover">
	  <thead>
	    <tr>
	      <th>#</th>
	      <th>Team</th>
	      <th>Game Played</th>
	      <th>Victory</th>
	      <th>Lost</th>
	      <th>Overtime</th>
	    </tr>
	  </thead>
	  <tbody>
	    <tr>
	      <th scope="row">1</th>
	      <td>St. Louis</td>
	      <td>21</td>
	      <td>15</td>
          <td>5</td>
          <td>1</td>
	    </tr>
          <tr>
	      <th scope="row">2</th>
	      <td>Winnipeg</td>
	      <td>20</td>
	      <td>12</td>
          <td>5</td>
          <td>3</td>
	    </tr>
          <tr>
	      <th scope="row">3</th>
	      <td>Nashville</td>
	      <td>22</td>
	      <td>12</td>
          <td>6</td>
          <td>2</td>
	    </tr>
          <tr>
	      <th scope="row">4</th>
	      <td>Los Angeles</td>
	      <td>21</td>
	      <td>12</td>
          <td>7</td>
          <td>2</td>
	    </tr>
	  </tbody>
	</table>
</div>

 -->
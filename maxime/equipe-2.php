<?php
    //www.wikidata.org/w/api.php?action=wbsearchentities&search=Tampa%20Bay&language=en

$team = array(
					"ID"=> "13",
					"City"=> "Florida",//"Ottawa",
					"Name"=> "Panthers",//"Senators",
					"Abbreviation"=> "OTT"
				);


function getEquipe($team){
    $city = $team['City'];
    $name = $team['Name'];
    $url = "https://www.wikidata.org/w/api.php?action=wbsearchentities&search=" . urlencode($city) ."%20" . urlencode($name) ."&language=en&format=json";
    
    //echo $url;
    // Get cURL resource
    $curl = curl_init();
   // Set url
    curl_setopt($curl, CURLOPT_URL, $url);
    // Set method
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

    // Set options
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    if (!$resp) {
        print curl_error($curl);
        die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
    } else {
        /*echo "Response HTTP Status Code : " . curl_getinfo($curl, CURLINFO_HTTP_CODE);
        echo "\nResponse HTTP Body : " . $resp;*/
        // Close request to clear up some resources
        curl_close($curl);
        
        //On a le resultat, on va prendre le premier
        $resp=json_decode($resp,true);
        
        $searchResults = $resp["search"];
        //var_dump($searchResults);
        $wikidataID = NULL;
        $wikiLabel = NULL;
        foreach( $searchResults as $resultat){
            
            if(array_key_exists('description', $resultat) && strpos($resultat['description'],'hockey') !== false){ //<<<<----------------------TEST HOCKEY
                //var_dump($resultat);
                $wikidataID = $resultat["id"];
                $wikiLabel = $resultat["label"]; //<<<<<------------------------------------ Peut être prendre le title du enWiki plus bas.
                break;
            }
        }
        if($wikidataID == NULL) { 
            echo "Erreur, team non trouvée";
            return;
        }
        $urlWikidata = "https://www.wikidata.org/wiki/Special:EntityData/$wikidataID.json";
        

             // Get cURL resource
        $curl = curl_init();
       // Set url
        curl_setopt($curl, CURLOPT_URL, $urlWikidata);
        // Set method
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

        // Set options
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // Send the request & save response to $resp
        $resp2 = curl_exec($curl);
        if (!$resp2) {
            print curl_error($curl);
            die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
        } else {
            /*echo "Response HTTP Status Code : " . curl_getinfo($curl, CURLINFO_HTTP_CODE);
            echo "\nResponse HTTP Body : " . $resp;*/
            // Close request to clear up some resources
            curl_close($curl);

            //On a le resultat, on va prendre le premier
            $resp2=json_decode($resp2,true);
            //var_dump($resp2);
            
            $urlwikipedia = $resp2["entities"]["$wikidataID"]["sitelinks"]["frwiki"]["url"];
            
            echo  "URL wikipedia : " . $urlwikipedia  . "<br>";
        }
        
            $urlImage = "https://en.wikipedia.org/w/api.php?action=query&prop=pageimages&format=json&piprop=original&titles=". urlencode($wikiLabel) . "&pilicense=any";
             // Get cURL resource
            $curl = curl_init();
           // Set url
            curl_setopt($curl, CURLOPT_URL, $urlImage);
            // Set method
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

            // Set options
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            // Send the request & save response to $resp
            $resp3 = curl_exec($curl);
        
        $wikipageID = NULL;
        
            if (!$resp3) {
                print curl_error($curl);
                die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
            } else {
                /*echo "Response HTTP Status Code : " . curl_getinfo($curl, CURLINFO_HTTP_CODE);
                echo "\nResponse HTTP Body : " . $resp;*/
                // Close request to clear up some resources
                curl_close($curl);

                //On a le resultat, on va prendre le premier
                $resp3=json_decode($resp3,true);
                //var_dump($resp3);
                
                $wikipageID = key($resp3["query"]["pages"]);
                
                $urlImage = $resp3["query"]["pages"][$wikipageID]["original"]["source"];

                echo  "<img src=\"$urlImage\" alt=\"$wikiLabel\">";
            }
        
        
        
        //https://en.wikipedia.org/w/api.php?action=query&pageids=22705&prop=revisions&rvprop=content&format=jsonfm
        
        if($wikipageID != NULL){
        
            //$urlGetContent = "https://en.wikipedia.org/w/api.php?action=query&pageids=$wikipageID&prop=revisions&rvprop=content&format=json";
            //https://en.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&exintro=&pageids=22705  --> Introduction
            //https://en.wikipedia.org/wiki/Template:Ottawa_Senators_roster
            //https://en.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&exintro=&label=Template:Ottawa_Senators_roster
            //$urlGetContent = "https://en.wikipedia.org/w/api.php?action=parse&pageid=$wikipageID&format=json&prop=text&section=0&contentformat=text/css";
            
            $urlGetContent = "https://en.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&exintro=&pageids=$wikipageID";
             // Get cURL resource
            $curl = curl_init();
           // Set url
            curl_setopt($curl, CURLOPT_URL, $urlGetContent);
            // Set method
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

            // Set options
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            // Send the request & save response to $resp
            $resp4 = curl_exec($curl);
            if (!$resp4) {
                print curl_error($curl);
                die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
            } else {
                /*echo "Response HTTP Status Code : " . curl_getinfo($curl, CURLINFO_HTTP_CODE);
                echo "\nResponse HTTP Body : " . $resp;*/
                // Close request to clear up some resources
                curl_close($curl);

                //On a le resultat, on va prendre le premier
                $resp4=json_decode($resp4,true);
                //var_dump($resp4);
                
                //echo $resp4["query"]["pages"][$wikipageID]["revisions"][0]["*"];
                //echo $resp4["parse"]["text"];
                echo $resp4["query"]["pages"][$wikipageID]["extract"]; ///<<<<<------------------introduction
                
                echo "<a href=\"$urlwikipedia\">See more on Wikipedia ...</a>";
    }
        }
    }
}



//Image d'un label : https://en.wikipedia.org/w/api.php?action=query&prop=pageimages&format=json&piprop=original&titles=Ottawa%20Senators&pilicense=any
//Image d'une pageID


//HTML page wikipedia
//getEquipe($team);
?>


<!DOCTYPE html>
<html>
 <head>
  <title>
   Exemple de HTML
  </title>
 </head>
 <body>
  <?php getEquipe($team); ?>
 </body>
</html>
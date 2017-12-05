<?php
    //www.wikidata.org/w/api.php?action=wbsearchentities&search=Tampa%20Bay&language=en

$team = array(
					"ID"=> "13",
					"City"=> "Ottawa",
					"Name"=> "Senators",
					"Abbreviation"=> "OTT"
				);


function getEquipe($team){
    $city = $team['City'];
    $name = $team['Name'];
    $url = "https://www.wikidata.org/w/api.php?action=wbsearchentities&search=" . urlencode($city) ."%20" . urlencode($name) ."&language=en&format=json";
 
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
        foreach( $searchResults as $resultat){
            if(array_key_exists('description', $resultat) && strpos($resultat['description'],'hockey') != false){ //<<<<----------------------TEST HOCKEY
                //var_dump($resultat);
                $wikidataID = $resultat["id"];
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
            
            echo $urlwikipedia;
        }
        
        
        
    }
    }

getEquipe($team);
?>
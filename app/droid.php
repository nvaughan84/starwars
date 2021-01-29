<?php

namespace Droid; 
use Starwars\CliPrinter;


class Droid
{

    private $directions = ['f','r','l'];
    private $client;
    private $trip;
    private $printer;
    private $droidName;
    private $alligiance;


    public function __construct($alligiance, $name)
    {
        $this->client = new \GuzzleHttp\Client(['base_uri' =>'https://deathstar.dev-tests.vp-ops.com/']);
        $this->printer = new CliPrinter();
        $this->droidName = $name;
        $this->alligiance = $alligiance;
    }


    /**
    * Recursive function that loops through the directions array
    * 410 (lost) will update the trip with the new path
    * 417 (crash) will attempt a new path
    * 200 (succeed) will end the function and output the successful path
    * 
    */
    public function engageThruters()
    {

        foreach($this->directions as $move)
        {
            //set a temporary path to probe the area
            $path = $this->trip.$move;

            $attemptMove = $this->checkMove($path);

            if($attemptMove == 410) // lost communication
            {
                $this->trip = $path; //update the trip with the new path
                $this->engageThruters();
            }
            if($attemptMove == 200) // successfully reached goal
            {
                $this->trip = $path; //update the trip with the new path
                $this->getPrinter()->display($this->trip);
                exit();
            }

        }

        // All moves resulted in a crash. Therefore, step back in the trip and continue voyage  

        //remove last move from trip
        $this->trip = $this->reverseThrusters($this->trip);

        //switch left/right movement position in directions array. Along with reverseThrusters, this will prevent in endless dead end scenario
        $this->directions = $this->switchLateralMovement($this->directions);
        
        //continue voyage
        $this->engageThruters();

    }

    /**
    * Determine the status of the next move. Crash, Lost or Successful
    *
    * @param string $path
    * @return int 
    */
    public function checkMove($path)
    {
        //Query the API for the next move to determine the response
        $request = $this->client->request('GET',$this->alligiance, 
                    ['http_errors' => false, 'query' => ['name' => $this->droidName, 'path'=>$path]]);

        return $request->getStatusCode();
   
    }

    /**
    * Remove the last movement from the path
    *
    * @param string $currentPath
    * @return string 
    */
    public function reverseThrusters($currentPath)
    {
        return substr($currentPath, 0, -1);
    }

    /**
    * Switch the l and r values in the $directions array
    *
    * @param array $directions
    * @return array 
    */
    public function switchLateralMovement($directions)
    {

        $d1 = array_slice($directions, 0, 1);
        $d2 = array_slice($directions, 1, 2);

        $directions = array_merge($d1, array_reverse($d2));

        return $directions;
    }

    private function getPrinter()
    {
        return $this->printer;
    }


}
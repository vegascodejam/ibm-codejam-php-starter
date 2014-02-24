<?php
/*******************************************************************************
 * COPYRIGHT LICENSE: This information contains sample code provided in source 
 * code form. You may copy, modify, and distribute these sample programs in any 
 * form without payment to IBM® for the purposes of developing, using, marketing 
 * or distributing application programs conforming to the application programming 
 * interface for the operating platform for which the sample code is written. 
 * Notwithstanding anything to the contrary, IBM PROVIDES THE SAMPLE SOURCE CODE 
 * ON AN "AS IS" BASIS AND IBM DISCLAIMS ALL WARRANTIES, EXPRESS OR IMPLIED, 
 * INCLUDING, BUT NOT LIMITED TO, ANY IMPLIED WARRANTIES OR CONDITIONS OF 
 * MERCHANTABILITY, SATISFACTORY QUALITY, FITNESS FOR A PARTICULAR PURPOSE, TITLE, 
 * AND ANY WARRANTY OR CONDITION OF NON-INFRINGEMENT. IBM SHALL NOT BE LIABLE 
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL OR CONSEQUENTIAL DAMAGES ARISING 
 * OUT OF THE USE OR OPERATION OF THE SAMPLE SOURCE CODE. IBM HAS NO OBLIGATION 
 * TO PROVIDE MAINTENANCE, SUPPORT, UPDATES, ENHANCEMENTS OR MODIFICATIONS 
 * TO THE SAMPLE SOURCE CODE.
 ******************************************************************************/
/**
 * MyVehicleAI - Your code to implement your vehicle goes here!
 * 
 */

namespace CodeRally;

class MyVehicleAI implements VehicleListener
{
	private $vehicle;
	private $track;
	private $race;
	private $extra;

	function __construct(Vehicle $vehicle, Track $track, Race $race, $extraData)
	{
		$this->vehicle = $vehicle;
		$this->track = $track;
		$this->race = $race;
		$this->extra = $extraData;
	}

	/**
	 * onRaceStart - Called when a race first begins.
	 * 
	 * @return $response
	 */
	public function onRaceStart()
	{
		// Begin implementation here!


		return $this->getResponse();
	}

	/**
	 * onCheckpointUpdated - Called when the car updates it's current checkpoint target
	 * 
	 * @return $response
	 */
	public function onCheckpointUpdated()
	{

		return $this->getResponse();
	}

	/**
	 * onOffTrack - Called when the car goes off track
	 * 
	 * @return $response
	 */
	public function onOffTrack()
	{
		return $this->getResponse();
	}

	/**
	 * onStalled - Called when the cars velocity drops to zero
	 * @return $response
	 */
	public function onStalled()
	{
		return array('status' => 'noop');		
	}

	/**
	 * onOpponentInProximity - Called when an opponent is in proximity of the car
	 * 
	 * @return $response
	 */
	public function onOpponentInProximity()
	{	
		$otherVehicle = new Vehicle($this->extra);

		return $this->getResponse();
	}

	/**
	 * onCarCollision - Called when the car collides with another car
	 * 
	 * @return $response
	 */
	public function onCarCollision()
	{
		$otherVehicle = new Vehicle($this->extra);


		return array('status' => 'noop');
	}

	/**
	 * onObstacleInProximity - Called when an obstacle is in proximity of the car
	 * 
	 * @param  Obstacle $obstacle
	 * @return $response
	 */
	public function onObstacleInProximity() {
		$obstacle = new Obstacle($this->extra);

		return array('status' => 'noop');
	}

	/**
	 * onObstacleCollision - Called when the car collides with an obstacle
	 * 
	 * @param  Obstacle $obstacle
	 * @return $response
	 */
	public function onObstacleCollision() {
		$obstacle = new Obstacle($this->extra);

		return array('status' => 'noop');		
	}


	/**
	 * onTimeStep - Called once for each timestep of the race
	 * 
	 * @return $response
	 */
	public function onTimeStep() {
		return array('status' => 'noop');		
	}

	private function getResponse() {
		return array(
			'status' => 'success',
			'vehicle' => $this->vehicle->getState()
		);
	}
}


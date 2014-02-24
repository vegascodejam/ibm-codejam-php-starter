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
 * VehicleListener - Interface to implement your AI for
 * 
 * @package CodeRally
 * @version 1.0
 */

namespace CodeRally;

interface VehicleListener {

	/**
	 * onRaceStart - Called when a race first begins.
	 * 
	 * @return $response
	 */
	public function onRaceStart();

	/**
	 * onCheckpointUpdated - Called when the car updates it's current checkpoint target
	 * 
	 * @return $response
	 */
	public function onCheckpointUpdated();

	/**
	 * onOffTrack - Called when the car goes off track
	 * 
	 * @return $response
	 */
	public function onOffTrack();

	/**
	 * onOpponentInProximity - Called when an opponent is in proximity of the car
	 * 
	 * @param  Vehicle $otherVehicle - Instantiated from $extra included in payload
	 * @return $response
	 */
	public function onOpponentInProximity();

	/**
	 * onCarCollision - Called when the car collides with another car
	 * 
	 * @param  Vehicle $otherVehicle - Instantiated from $extra included in payload
	 * @return $response
	 */
	public function onCarCollision();

	/**
	 * onObstacleInProximity - Called when an obstacle is in proximity of the car
	 * 
	 * @param  Obstacle $obstacle - Instantiated from $extra included in payload
	 * @return $response
	 */
	public function onObstacleInProximity();

	/**
	 * onObstacleCollision - Called when the car collides with an obstacle
	 * 
	 * @param  Obstacle $obstacle - Instantiated from $extra included in payload
	 * @return $response
	 */
	public function onObstacleCollision();

	/**
	 * onStalled - Called when the cars velocity drops to zero
	 * 
	 * @return $response
	 */
	public function onStalled();

	/**
	 * onTimeStep - Called once for each timestep of the race
	 * 
	 * @return $response
	 */
	function onTimeStep();
}

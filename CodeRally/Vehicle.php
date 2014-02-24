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
 * Vehicle - Represents a vehicle on a track
 * 
 * @package CodeRally
 * @version 1.0
 */

namespace CodeRally;

class Vehicle
{
	public $brakePercent = 0;			// Currently set brake percentage
	public $accelerationPercent = 0;	// Currently set acceleration percentage
	public $target;					// Current target coordinate

	private $state;						// Retrieved vehicle JSON game state

	private $attributes;				// Vehicle JSON attributes
	private $attributePoints;			// Vehicle JSON allocated points - these are the denormalized attributes

	public function __construct($state = null)
	{
		if ($state != null) {
			$this->attributes = $state->attributes;
			$this->attributePoints = $state->attributePoints;
			$this->update($state);
		} else {
			$this->target = new Point(0, 0);
		}
	}

    /**
     * setAccelerationPercent - Adjusts the pressure you are applying to your acceleration pedal.
     * 
     * @param int $percent Values may be between 100 for full throttle and 0 for no pressure.
     */
	public function setAccelerationPercent($percent)
	{
		if ($percent < 0 || $percent > 100) {
			throw new \InvalidArgumentException('function only accepts percentages 0-100. Input was: '.$int);
		}

		$this->accelerationPercent = $percent;
	}

	/**
	 * getAccelerationPercent Returns the last value you set the Acceleration Percent to
	 * 
	 * @return int Percentage of acceleration.
	 */
	public function getAccelerationPercent()
	{
		return $this->accelerationPercent;
	}

	/**
	 * getAcceleration - Return vehicle's acceleration vector
	 * 
	 * @return Vector Current acceleration vector
	 */
	private function getAcceleration()
	{
		return new Vector($this->state->acceleration->x, $this->state->acceleration->y);
	}

	/**
	 * setBrakePercent - Set how much pressure you are applying to your break pedal.
	 * 
	 * @param int $percent [Values may be between 100 for full break and 0 for no pressure.
	 */
	public function setBrakePercent($percent)
	{
		if ($percent < 0 || $percent > 100) {
			throw new \InvalidArgumentException('function only accepts percentages 0-100. Input was: '.$int);
		}

		$this->brakePercent = $percent;
	}

	/**
	 * getBrakePercent - Return the last value you set Brake Percent to
	 * 
	 * @return Vector Percentage of brake
	 */
	private function getBrakePercent()
	{
		return $this->brakePercent;
	}

	/**
	 * setTarget - Specify a 2D point (x,y) on the game track, that your vehicle will head towards.
	 * 
	 * @param Point $p Coordinate to move towards.
	 */
	public function setTarget(Point $p)
	{
		$this->target = $p;
	}

	/**
	 * getTarget - Returns the point you are currently headed towards.
	 * 
	 * @return Point Current destination point of vehicle.
	 */
	public function getTarget()
	{
		return $this->target;
	}

	/**
	 * getVelocity - Returns how fast you are currently moving in x and y directions
	 * 
	 * @return Vector Contains x and y velocity indicators
	 */
	public function getVelocity()
	{
		return new Vector($this->state->velocity->x, $this->state->velocity->y);
	}

	public function getVelocityNormalized()
	{
		return $this->state->velocityNormalized;
	}

	/**
	 * getPosition() - Returns the current position of this vehicle.
	 * 
	 * @return Point Current vehicle position
	 */
	public function getPosition()
	{
		return new Point($this->state->position->x, $this->state->position->y);
	}

	/**
	 * getRotation - Returns the current rotation of the vehicle
	 * 
	 * @return int degrees of rotation
	 */
	public function getRotation()
	{
		return $this->state->rotation;
	}

	/**
	 * getLap - Get the current lap of the race for this vehicle
	 * 
	 * @return int Current lap count
	 */
	public function getLap()
	{
		return $this->state->lap;
	}

	/**
	 * getCheckpoint Get the next Checkpoint on the Track in relation to your vehicle
	 * 
	 * @return Checkpoint Next checkpoint.
	 */
	public function getCheckpoint()
	{
		return new Checkpoint($this->state->checkpoint);
	}

	/**
	 * calculateMaximumTurning - Calculates the maximum degrees the car can turn with the given acceleration percentage in one second
	 * 
	 * @param  int $acceleration Possible acceleration percentage
	 * 
	 * @return float Maximum degrees of turning per second at the provided acceleration
	 */
	public function calculateMaximumTurning($acceleration)
	{
		if ($acceleration > 0) {
			//100% acceleration reduces turning by 50%
			return $this->getTurningDegrees() * ((-$acceleration) / 2 + 100) / 100;
		} else {
			//100% braking (-100% acceleration) increases turning by 100%
			return $this->getTurningDegrees() * (- $acceleration + 100) / 100;
		}
	}

	/**
	 * calculateHeading - Calculates the amount of degrees the entity must turn to 
	 * head towards the given point
	 * 
	 * @param  Point  $point Point to turn to
	 * @return int Degrees the vehicle must turn
	 */
	public function calculateHeading(Point $point)
	{
		//+90 to represent the image axis offset
		$desiredHeading = ($this->getPosition->getHeadingTo($point) + 90) % 360;
		$currentHeading = $this->getRotation();
		$degrees = $desiredHeading - $currentHeading;

		//Faster to turn the other way
		if ($degrees > 180) {
			$degrees -= 360;
		} else if ($degrees < -180) {
			$degrees += 360;
		}
		return $degrees;
	}

	/**
	 * recalculateHeading description]
	 * @param  integer $bias
	 * @return void
	 */
	public function recalculateHeading($bias = 1)
	{
		$target = $this->getTarget();
		
		//Predicts how far the car can turn in 1 second
		$turn = abs($this->calculateHeading($target));
		$degreesPerSecond = $this->getTurningDegrees();
		
		//Predicts how many seconds to reach the checkpoint
		$distance = $this->getPosition()->getDistance($target);
		$velocity = $this->getVelocity();
		$acceleration = $this->getAcceleration();

		$predictedVelocity = pow(sqrt($velocity->magnitude) + 
			sqrt($acceleration->magnitude), 2.4) / 7;
		$seconds = $distance / ($predictedVelocity * 5280 / 3600);
		
		//Adjust the time according to the bias
		$seconds *= $bias;
		
		//Predicts how many degrees the car can turn in the time to reach the checkpoint
		$predictedTurn = $degreesPerSecond * $seconds;

		if ($predictedTurn * 7 < $turn) {
			$this->setBrakePercent(100);
			$this->setAccelerationPercent(0);
		} else if ($predictedTurn * 6 < $turn) {
			$this->setBrakePercent(80);
			$this->setAccelerationPercent(0);
		} else if ($predictedTurn * 5 < $turn) {
			$this->setBrakePercent(60);
		} else if ($predictedTurn * 4 < $turn) {
			$this->setBrakePercent(40);
			$this->setAccelerationPercent(0);
		} else if ($predictedTurn * 3 < $turn) {
			$this->setBrakePercent(20);
			$this->setAccelerationPercent(0);
		} else if ($predictedTurn * 2 < $turn) {
			$this->setAccelerationPercent(20);
			$this->setBrakePercent(0);
		} else if  ($predictedTurn * 1.5 < $turn) {
			$this->setAccelerationPercent(50);
			$this->setBrakePercent(0);
		} else if  ($predictedTurn < $turn) {
			$this->setAccelerationPercent(70);
			$this->setBrakePercent(0);
		} else {
			$this->setAccelerationPercent(100);
			$this->setBrakePercent(0);
		}
	}


	/* Attribute methods */

	/**
	 * getMaxMph - Get vehicle acceleration attribute.  Note: This is not the cars current acceleration
	 * 
	 * @return float Vehicle acceleration attribute
	 */
	public function getMaxMph()
	{
		return $this->attributes->acceleration;
	}

	/**
	 * getWeight - Vehicle weight
	 * 
	 * @return float Weight in kilograms
	 */
	public function getWeight()
	{
		return $this->attributes->weight;
	}

	/**
	 * getTractionCoefficient - Retrieve the vehicle's traction coefficient
	 * 
	 * @return float Traction coefficient
	 */
	public function getTractionCoefficient()
	{
		return $this->attributes->traction;
	}

	/**
	 * getTurningDegrees - The amount of turning a vehicle can perform
	 * 
	 * @return float Max turning capability in degrees per second
	 */
	public function getTurningDegrees()
	{
		return $this->attributes->turning;
	}



	function update($vehicleState)
	{
    	$this->state = $vehicleState;

		$this->accelerationPercent = $this->state->accelerationPercent;
		$this->brakePercent = $this->state->brakePercent;
		$this->target = new Point($this->state->target->x, $this->state->target->y);
	}

	public function getState()
	{
		return array(
			'brakePercent' => $this->brakePercent,
			'accelerationPercent' => $this->accelerationPercent,
			'target' => $this->target->get()
		);
	}

}

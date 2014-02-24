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
 * Point - Helper class for points on the race grid.
 * 
 * @package CodeRally
 * @version 1.0
 */

namespace CodeRally;

class Vector 
{
	public var $x = 0;				// Normalized X direction
	public var $y = 0; 			// Normalized Y direction
	public var $magnitude = 0;		// Magnitude

	/**
	 * Point constructor
	 * 
	 * @param int $dx Change in X
	 * @param int $dy Change in Y
	 */
	function __construct($dx, $dy, $magnitude = null)
	{

		if ($magnitude == null) {
			// Create vector from its change in the X and Y directions.
			$this->magnitude = sqrt(($dx*$dx) + ($dy*$dy));
			$this->x = $dx/$this->magnitude;
			$this->y = $dy/$this->magnitude;
		} else {
			// Create vector from its normalized vector (direction) and magnitude.
			$this->magnitude = $magnitude;
			$this->x = $dx;
			$this->y = $dy;
		}
	}


	/**
	 * getX - Change in X direction
	 * @return float
	 */
	public function getX()
	{
		return $this->x * $this->magnitude;
	}
	
	/**
	 * getX - Change in Y direction
	 * @return float
	 */
	public function getY()
	{
		return $this->y * $this->magnitude;
	}

	/**
	 * normalize - Get the normalized vector
	 * @return Vector
	 */
	public function normalize()
	{
		return new Vector($this->x, $this->y, 1);
	}

	/**
	 * toPoint - Retrieve a point representing this vector without magnitude
	 * @return Point
	 */
	public function toPoint()
	{
		return new Point($this->getX(), $this->getY());
	}


}

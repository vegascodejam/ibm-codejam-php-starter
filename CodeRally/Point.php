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

class Point 
{
	public $x = 0;
	public $y = 0;

	/**
	 * Point constructor
	 * 
	 * @param int $x X coordinate
	 * @param int $y Y coordinate
	 */
	function __construct($x, $y)
	{
		$this->x = $x;
		$this->y = $y;
	}

	/**
	 * Get the point data as an array
	 * 
	 * @return Array containing the coordinates.
	 */
	function get()
	{
		return array('x' => $this->x, 'y' => $this->y);
	}

	/**
	 * midpoint - Get the midpoint between $p and this
	 * 
	 * @param  Point  $p Location to compare against
	 * @return Point Location equadistant between the points
	 */
	public function midpoint(Point $p)
	{
		return new Point(($this->x + $p->x) / 2, ($this->y + $p->y) / 2);
	}

	/**
	 * getDistance - Get distance between $p and this
	 * 
	 * @param  Point  $p Location to compare against
	 * @return float  Distance on the grid
	 */
	public function getDistance(Point $p)
	{
		return sqrt($this->getDistanceSquared($p));
	}

	/**
	 * getDistanceSquared - Get distance between $p and this squared
	 * 
	 * @param  Point  $p Location to compare against
	 * @return int  Distance squared on the grid
	 */
	public function getDistanceSquared(Point $p)
	{
		return (($this->x - $p->x) * ($this->x - $p->x)) + (($this->y - $p->y) * ($this->y - $p->y)); 
	}

	/**
	 * getHeadingTo - Get a heading between $p and this
	 * 
	 * @param  Point  $p Location to compare against
	 * @return float Heading in degrees
	 */
	public function getHeadingTo(Point $p)
	{
		$degrees = rad2deg(atan2($p->y - $this->y, $p->x - $this->x));

		if ($degrees < 0) {
			$degrees += 360;
		}
		return $degrees;
	}

	/**
	 * intersection - Get intersection point between this point based on a rotation and
	 * the line formed by the top and bottom points.
	 * 
	 * @param  int $rotation Rotation in degrees
	 * @param  Point  $top      Top coordinate of intersecting line
	 * @param  Point  $bottom   Bottom coordinate of intersecting line
	 * 
	 * @return Point           Intersecting point.
	 */
	public function intersection($rotation, Point $top, Point $bottom)
	{
		$o2x = (int) cos( deg2rad($rotation) *100);
		$o2y = (int) sin( deg2rad($rotation) *100);

		return AIUtils::getLineSegmentIntersection(
				$this->x, $this->y, $o2x, $o2y,
				$top->x, $top->y, $bottom->x, $bottom->y
			);
	}

}

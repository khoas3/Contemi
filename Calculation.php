<?php 

namespace Interno\Calculation;
class VocationCalculation {
	const WORKDAYS_PER_WEEK = 5;
	const VOCATION_FULL_YEAR = 24;
	const VOCATION_PER_MONTH = 2;
	const PROBATION_MONTH_PERIOD = 6;		
	
	private $start_date;
	private $calc_date;	
	private $remaining_vocation_days = 0;
	
	public function __construct(\DateTime $start_date, \DateTime $calc_date)
	{
		if ($start_date > $calc_date) {
			throw new InvalidArgumentException('Start date cannot be greater than calculation date.');
		}
		$this->start_date = $start_date;
		$this->calc_date = $calc_date;
	}	
	
	/**
     * Void main
     */
    public function calculate()
    {
        echo $this->numberOfVocation($this->start_date, $this->calc_date);
    }

    /**
     * @param \DateTime $start_date
     * @param \DateTime $calc_date
     * @return int
     */
    private function numberOfVocation(\DateTime $start_date, \DateTime $calc_date)
    {
        $number_of_m = $this->workedMonth($start_date, $calc_date);
        if($number_of_m >= self::PROBATION_MONTH_PERIOD){
            $this->remaining_vocation_days = self::VOCATION_FULL_YEAR;
            // Plus additional previous vocation if has.
            $start_year = intval($start_date->format('Y'));
            $calc_year = intval($calc_date->format('Y'));
            if($calc_year - $start_year > 0) {
                $vm_in_previous_year = $this->accumulatedVocation($start_date, $calc_year);					
                // Plus here.
                $this->remaining_vocation_days += $vm_in_previous_year;
            }
        } else {
            $this->remaining_vocation_days = $number_of_m * self::VOCATION_PER_MONTH;
        }

        return $this->remaining_vocation_days;
    }

    /**
     * @param \DateTime $start_date
     * @param $calc_year
     * @return int
     */
    private function accumulatedVocation(\DateTime $start_date, $calc_year)
    {
        // The last day in year.
        $lastday_in_year = ($calc_year - 1).'-12-31';
        $py = new \DateTime($lastday_in_year);
        $m_in_previous_year = $this->workedMonth($start_date, $py);        
        if($m_in_previous_year >= self::PROBATION_MONTH_PERIOD){
            $vm_in_previous_year = self::VOCATION_FULL_YEAR;
        } else {
            $vm_in_previous_year = $m_in_previous_year * self::VOCATION_PER_MONTH;
        }
		if($m_in_previous_year < 12){
			return $vm_in_previous_year;
        }
		
        return $vm_in_previous_year + $this->accumulatedVocation($start_date, $calc_year - 1);
    }

    /**
     * @param \DateTime $start_date
     * @param \DateTime $calc_date
     * @return int
     */
    private function workedMonth(\DateTime $start_date, \DateTime $calc_date)
    {
        $interval = $start_date->diff($calc_date);
        $m = intval($interval->format('%m'));
        $y = intval($interval->format('%y'));
        if($y > 0){
            $m += $y*12;
        }
        return $m;
    }

    /**
     * @param \DateTime $start_date
     * @param \DateTime $calc_date
     * @return float|mixed
     */
    private function weekdays(\DateTime $start_date, \DateTime $calc_date)
    {
        $start_timestamp = $start_date->getTimestamp();
        $calc_timestamp = $calc_date->getTimestamp();
        // Find day of week for 2 dates
        $start_d = date('N', $start_timestamp);
        $calc_d = date('N', $calc_timestamp);
        // Calculate the number of time span in terms of weeks.
        $w = floor(($calc_timestamp - $start_timestamp)/(86400*7));
        // Deduct the first week from the number of weeks.
        if($calc_d >=$start_d)
        {
            $w--;
        }
        // Calculate the days in the first week.
        $wd = max(6 - $start_d,0);
        // Calculate the days in the last week.
        $wd += min($calc_d, 5);
        $wd += $w*self::WORKDAYS_PER_WEEK;
        return $wd;
    }
}

$s = new \DateTime('2015-10-29');
$e = new \DateTime('2015-12-29');
$c = new VocationCalculation($s, $e);
$c->calculate();


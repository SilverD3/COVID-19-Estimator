<?php 

/**
* Computations 
*/

class Computations
{
	function normalizeDuration($periodType, $timeToElapse){
		if ($periodType == "days") {
			$days = $timeToElapse;
		}else if($periodType == "weeks"){
			$days = $timeToElapse * 7;
		}else if($periodType == "months"){
			$days = $timeToElapse * 30;
		}

		return $days;
	}
	
	function currentlyInfected($reportedCases, $infections_estimation){
		return $reportedCases * $infections_estimation;
	}

	function infectionsByRequestedTime($currentlyInfected, $data){
		$factor = round(($this->normalizeDuration($data->periodType, $data->timeToElapse)/3), 0);
		$infectionsByRequestedTime = $currentlyInfected * pow(2, $factor);
		return (int)number_format($infectionsByRequestedTime, 0, '.', '');
	}

	function severeCasesByRequestedTime($infectionsByRequestedTime){
		$severeCasesByRequestedTime = $infectionsByRequestedTime * 0.15;
		return (int)number_format($severeCasesByRequestedTime, 0, '.', '');
	}

	function hospitalBedsByRequestedTime($totalHospitalBeds, $severeCasesByRequestedTime){
		$hospitalBedsBRT = ($totalHospitalBeds * 0.35) - $severeCasesByRequestedTime;
		return (int)number_format($hospitalBedsBRT, 0, '.', '');
	}

	function casesForICUByRequestedTime($infectionsByRequestedTime){
		return (int)number_format($infectionsByRequestedTime * 0.05, 0, '.', '');
	}

	function casesForVentilatorsByRequestedTime($infectionsByRequestedTime){
		return (int)number_format($infectionsByRequestedTime * 0.02, 0, '.', '');
	}

	function dollarsInFlight($infectionsByRequestedTime, $data){
		$dollarsInFlight = $infectionsByRequestedTime * ($data->region->avgDailyIncomeInUSD / $this->normalizeDuration($data->periodType, $data->timeToElapse))* $data->region->avgDailyIncomePopulation;

		(int)return number_format($dollarsInFlight, 0, '.', '');
	}
	
}
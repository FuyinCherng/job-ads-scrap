<?php
echo "JobApis Start collecting Job Ads"."\n";

// --- Include source files from JobApis
require("./vendor/autoload.php");
require_once("./src/JobsMulti.php");
require_once("./vendor/jobapis/jobs-adzuna/src/Providers/AdzunaProvider.php");
require_once("./vendor/jobapis/jobs-adzuna/src/Queries/AdzunaQuery.php");


// -- Set query and from which platform
$Source = 'CareerCast'; // Stackoverflow, Github, Indeed, Dice, CareerCast, Adzuna
$SearchWord = 'job'; //if searchWord == job means approximate exhuasted search
$pnum = 0;

echo $Source.' '.$SearchWord."\n";

// Some special and mandatory parameter for Aszuna and CareerCast
if($Source == 'Adzuna'){
	
	// parameter: category of jobs
	// value of category: scientific-qa-jobs consultancy-jobs pr-advertising-marketing-jobs engineering-jobs it-jobs accounting-finance-jobs
	$AdzuCate = 'engineering-jobs';


	// parameter: Country
	// value of country (ISO 8601 country code): gb, au, at, br, ca: Canada, de: Germany, fr, in, it, nl, nz, pl, ru, sg, us, za
	$country = 'gb'; 

	echo 'Category of Job: '.$AdzuCate.'; Country:'.$country."\n";

}
if($Source == 'CareerCast'){

	// parameter: category of jobs
	// vlaue of category: Accounting, Administrative / Clerical, Automotive, Biotechnology / Science, Business, Construction / Skilled Trades, Customer Service, Education,
	//Engineering, Executive, Facilities, Financial Services, Government, Healthcare, Hospitality, Human Resources, Information Technology, Legal, Management
	//Manufacturing / Production, Marketing, Real Estate, Retail / Wholesale, Sales / Business Development, Telecommunications, Transportation / Warehouse
	$careercastCate = 'Information Technology'; 

	echo 'Category of Job: '.$careercastCate."\n";
	
}

// --- Create directory for downloaded data if not exist
date_default_timezone_set('GMT');
$date = getdate();
$dir = 'result/'.$Source.'_'.$date['mon'].'_'.$date['year'];

if(!file_exists($dir)){
	mkdir($dir , 0777, true);
}


// --- insert customed query to APIs
$jobcount = 1;

while($jobcount != 0){
	$pnum = $pnum + 1;

	switch($Source){

		case "Github":
			$query = new JobApis\Jobs\Client\Queries\GithubQuery([
			    'search' => $SearchWord
			]);
			$query->set('page', $pnum);
			$client = new JobApis\Jobs\Client\Providers\GithubProvider($query);
			$jobs = $client->getJobs();
			break;

		//Indeed, 
		//error: date formate (Mon, 02 Aug 2010 16:21:00 GMT) is different from PHP date formate
		case "Indeed":
			// Add parameters to the query via the constructor
			date_default_timezone_set('GMT');
			$query = new JobApis\Jobs\Client\Queries\IndeedQuery([
			    'publisher' => 5613804311321314
			]);
			$query->set('q', $SearchWord)
				  ->set('userip', '1.2.3.4')
				  ->set('v','2')
				  ->set('useragent', 'Mozilla')
				  ->set('fromage', '60')
				  ->set('limit','25')
				  ->set('start',(string)(25*$pnum)); //limit*pnum
			$client = new JobApis\Jobs\Client\Providers\IndeedProvider($query);
			$jobs = $client->getJobs();
			break;
		

		case "Dice":
			date_default_timezone_set('GMT');
			$query = new JobApis\Jobs\Client\Queries\DiceQuery([
		    	'text' => $SearchWord
			]);
			$query ->set('pgcnt','100') // num of result per page
				   ->set('age','360') // days
				   ->set('page', (string)$pnum);
			//$query->set('skill', 'soldering')
			//		->set('state', 'Illinois')
			//		->set('country', 'United States');

			$client = new JobApis\Jobs\Client\Providers\DiceProvider($query);
			$jobs = $client->getJobs();
			break;
		
		case "CareerCast":
			date_default_timezone_set('GMT');
			$query = new JobApis\Jobs\Client\Queries\CareercastQuery([
		    	'keyword' => $SearchWord,
		    	'category' => $careercastCate
			]);
			$query->set('page', (string)$pnum);
				
		    $client = new JobApis\Jobs\Client\Providers\CareercastProvider($query);
		    $jobs = $client->getJobs();
		    break;
		
		case "Adzuna":
			date_default_timezone_set('GMT');
			$query = new JobApis\Jobs\Client\Queries\AdzunaQuery([
			    'app_key' => '7f3450a2fa41b3afb906e7a3205666d5',
			    'app_id' => f877420d,
			    'country' => $country
			]);
			
			$query //->set('what', 'engineering')
					->set('category',$AdzuCate) //if don't set category -> get all category of jobs;
					->set('results_per_page','100') //max 50 per page
					->set('max_days_old','60')
					->set('page', (string)$pnum);

			$client = new JobApis\Jobs\Client\Providers\AdzunaProvider($query);
			$jobs = $client->getJobs();
			break;
		
		case "Stackoverflow"; //only provide RSS feeds
			date_default_timezone_set('GMT');
			$query = new JobApis\Jobs\Client\Queries\StackoverflowQuery();
			//$query//->set('q', 'engineering')->set('pg', (string)$pnum);

			$client = new JobApis\Jobs\Client\Providers\StackoverflowProvider($query);
			$jobs = $client->getJobs();
			break;

		default:
			echo "No matching name of given platform.";
		
	}


	//------ Extract Information from returns
	$jobcount = $jobs->count();
	if($jobcount <= 0){
		echo "No more job ads for given query avaliable.";
		break;
	}
	else{
		echo "Number of job ads: ".$jobcount.' page num: '.$pnum."\n";
	}

	
	$fp = fopen($dir.'/'.$jobs->get(1)->getSource().'_'.$SearchWord.'_p'.$pnum.'.json','w');

	fwrite($fp,'[');
	for ($i = 0; $i < $jobs->count(); $i++){
		$j = $jobs->get($i);

		//Search Info
		$fout_Query = $j->getQuery();
		$fout_Source = $j->getSource();
		$fout_DatePosted = $j->getDatePosted()->format(DateTime::ISO8601);
		$fout_Url = $j->getUrl();

		//Company Info
		$fout_CompanyDescription = $j->getCompanyDescription();
		$fout_CompanyEmail = $j->getCompanyEmail();
		$fout_CompanyLogo = $j->getCompanyLogo();
		$fout_CompanyName = $j->getCompanyName();
		$fout_CompanyUrl = $j->getCompanyUrl();


		//Location Info
		$fout_Country = $j->getCountry();
		$fout_City = $j->getCity();
		$fout_Latitude = $j->getLatitude();
		$fout_Longitude = $j->getLongitude();
		$fout_Location = $j->getLocation();
		$fout_State = $j->getState();
		$fout_PostalCode = $j->getPostalCode();


		//Job Info
		$fout_MinSalary = $j->getMinimumSalary();	
		$fout_BaseSalary = $j->getBaseSalary();
		$fout_SalaryCurrency = $j->getSalaryCurrency();
		$fout_Description = $j->getDescription();
		$fout_JobBenefits = $j->getJobBenefits();
		$fout_EducationReq = $j->getEducationRequirements();
		$fout_EmploymentType = $j->getEmploymentType();
		$fout_ExperienceRequirements = $j->getExperienceRequirements();
		$fout_IncentiveCompensation = $j->getIncentiveCompensation();
		$fout_Industry = $j->getIndustry();
		$fout_OccupationalCategory = $j->getOccupationalCategory();
		$fout_Qualifications = $j->getQualifications();
		$fout_Responsibilities = $j->getResponsibilities();
		$fout_Skills = $j->getSkills();
		$fout_SpecialCommitments = $j->getSpecialCommitments();
		$fout_Title = $j->getTitle();
		$fout_WorkHours = $j->getWorkHours();		
		$fout_alternateName = $j->getAlternateName();
		$fout_Name = $j->getName();

		// --- save job info as json formate
		$array = array(
			'Query' => $fout_Query,
			'Source' => $fout_Source,
			'Title' => $fout_Title,
			'Url' => $fout_Url,
			'Industry' => $fout_Industry,
			'OccupationalCategory' => $fout_OccupationalCategory,
			'Country' => $fout_Country,
			'Location' => $fout_Location,
			'DatePosted' => $fout_DatePosted,
			'Company' => array(
					'CompanyDescription' => $fout_CompanyDescription,
					'CompanyEmail' => $fout_CompanyEmail,
					'CompanyLogo' => $fout_CompanyLogo,
					'CompanyName' => $fout_CompanyName,
					'CompanyUrl' => $fout_CompanyUrl,
					'Telephone' => $fout_Telephone
				),
			'Adress' => array(
				'City' => $fout_City,
				'Latitude' => $fout_Latitude,
				'Longitude' => $fout_Longitude,
				'State' => $fout_State,
				'PostalCode' => $fout_PostalCode
				),
			'Salary' => array(
				'MinSalary' => $fout_MinSalary,
				'BaseSalary' => $fout_BaseSalary,
				'SalaryCurrency' => $fout_SalaryCurrency
				),
			'Description' => array(
				'JobBenefits' => $fout_JobBenefits,
				'EducationReq' => $fout_EducationReq,
				'EmploymentType' => $fout_EmploymentType,
				'ExperienceRequirements' => $fout_ExperienceRequirements,
				'IncentiveCompensation' => $fout_IncentiveCompensation,
				'Qualifications' => $fout_Qualifications,
				'Responsibilities' => $fout_Responsibilities,
				'Skills' => $fout_Skills,
				'SpecialCommitments' => $fout_SpecialCommitments,
				'WorkHours' => $fout_WorkHours,
				'Description' => $fout_Description
				),
			'Other' => array(
				'alternateName' => $fout_alternateName,
				'Name' => $fout_Name
				)
			);

		// --- save json file
		fwrite($fp,json_encode($array));
		if($i != ($jobs->count()-1)){
			fwrite($fp,',');
		}
	}
	fwrite($fp,']');
	fclose($fp);
}
echo " FINISH ";

?>
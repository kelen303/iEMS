<?php
$textnum = rand(1,16);
$text = randomtext($textnum);

function randomtext($num){
	switch($num){
		case 1:echo "Is a Clean Tech Company that enables \"Conservation Through Technology\""; break;
		case 2:echo "Manages over 535 Megawatts of demand response resources at more than 1,000 end use customer facilities; the equivalent of a medium-size power plant!"; break;
		case 3:echo "Revenue has grown by more than 400% since 2005."; break;
		case 4:echo "Is 100% management-owned with sustained profitability and a solid balance sheet since Q2 of our inception."; break;
		case 5:echo "Customers include some of the largest utilities, power system operators and competitive energy market participants in North America."; break;
		case 6:echo "Supports some of the most well respected industrial, commercial and institutional consumers in the world."; break;
		case 7:echo "Has over 99% meter solution uptime!"; break;
		case 8:echo "Is an ISO New England (ISO-NE) certified Internet-Based Communication System (IBCS) provider in the six-state 33,000 Megawatt New England bulk power system."; break;
		case 9:echo "Management was involved in the design and implementation of the First Real-Time Demand Response Pilot for a bulk power system in the U.S."; break;
		case 10:echo "Enables customer participation in reliability/reserves driven demand response, dynamic price response, day-ahead demand response bidding and local distribution company targeted demand response offerings."; break;
		case 11:echo "Provides direct CT metering for stand-by emergency generator assets."; break;
		case 12:echo "Implements metering solutions to monitor and manage water consumption for environmental compliance."; break;
		case 13:echo "Meters steam consumption to control operating costs."; break;
		case 14:echo "Can assist pipelines and natural gas distribution companies to manage peak demand more effectively during capacity constraints."; break;
		case 15:echo "Is one of six Prestige Level members of Georgia`s fast growing Cumming-Forsyth County Chamber of Commerce."; break;
		case 16:echo "Serves on the Board and Co-Chairs the Energy and Technology Committee of the New England-Canada Business Council."; break;
	}
}

?>

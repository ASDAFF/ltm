<?

$aMenuLinks = array();


$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);

if($exhibCode)
{
    $aMenuLinks = array(
        array(
            "Deadlines & info",
            "/cabinet/" . $exhibCode . "/deadline/",
            array(),
            array()
        ),
        array(
            "Morning session",
            "/cabinet/" . $exhibCode . "/morning/",
            array(),
            array()
        ),
        array(
            "Hosted Buyers Session",
            "/cabinet/" . $exhibCode . "/hb/",
            array(),
            array()
        ),
		array(
            "Evening session",
            "/cabinet/" . $exhibCode . "/evening/",
            array(),
            array()
	   ),
        array(
            "Messages",
            "/cabinet/" . $exhibCode . "/messages/",
            array(),
            array()
        ),
    );
}
?>
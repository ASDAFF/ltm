<?

$aMenuLinks = array();


$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);

if($exhibCode)
{
    $aMenuLinks = array(
        array(
            "Программа и инфо",
            "/cabinet/" . $exhibCode . "/deadline/",
            array(),
            array()
        ),
        array(
            "2 марта",
            "/cabinet/" . $exhibCode . "/hb/",
            array(),
            array()
        ),
        array(
            "3 марта",
            "/cabinet/" . $exhibCode . "/morning/",
            array(),
            array()
        ),
        array(
            "Мои сообщения",
            "/cabinet/" . $exhibCode . "/messages/",
            array(),
            array()
        ),
        array(
            "Каталог участников",
            "/members/",
            array(),
            array()
        ),
    );
}
?>
<?

$aMenuLinks = array();


$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);

if($exhibCode)
{
    $aMenuLinks = array(
        array(
            "��������� � ����",
            "/cabinet/" . $exhibCode . "/deadline/",
            array(),
            array()
        ),
        array(
            "13 �����",
            "/cabinet/" . $exhibCode . "/morning/",
            array(),
            array()
        ),
        /*array(
            "�������� ������",
            "/cabinet/" . $exhibCode . "/morning/",
            array(),
            array()
        ),*/
        array(
            "��� ���������",
            "/cabinet/" . $exhibCode . "/messages/",
            array(),
            array()
        ),
        array(
            "������� ����������",
            "/members/",
            array(),
            array()
        ),
    );
}
?>
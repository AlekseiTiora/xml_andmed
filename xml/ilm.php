<?php
$ilmatemperatuur=simplexml_load_file('ilm.xml');
//otsing nimetuse järgi

function search_ByName($query){
    global $ilmatemperatuur;
    $result=array();
    foreach ($ilmatemperatuur->ilm as $ilm) {


        if (substr(strtolower($ilm->temperatuur), 0,strlen($query))==
            strtolower($query)){
            array_push($result, $ilm);
        }
    }
    return $result;
}
//andmebaas lisamine xml-1
if (isset($_POST['submit'])){
    $xmlDoc=new DOMDocument("1.0", "UTF-8");


    $temperatuur=$_POST['temperatuur'];
    $kuupaev=$_POST['kuupaev'];
    $maakonna=$_POST['maakonna'];
    $maakonnakeskus=$_POST['maakonnakeskus'];

    $xml_ilm=$ilmatemperatuur->addChild('ilm');
    $xml_ilm->addChild('temperatuur', $temperatuur);
    //addchild('xml struktuur', $nimi - tekst väli)
    $xml_ilm->addChild('kuupaev', $kuupaev);

    $xml_ilmatemperatuur=$xml_ilm->addChild('maakonnad');
    $xml_ilmatemperatuur->addChild('maakonna',$maakonna );
    $xml_ilmatemperatuur->addChild('maakonnakeskus',$maakonnakeskus );


    $xmlDoc->loadXML($ilmatemperatuur->asXML(), LIBXML_NOBLANKS);
    $xmlDoc -> preserveWhiteSpace=false;
    $xmlDoc->formatOutput = true;
    $xmlDoc->save('ilm.xml');
    header("refresh: 0;");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XML andmed</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>XML failist andmed</h1>
<form action="?" method="post">
    <label for="otsing">Otsing</label>
    <input type="text" name="otsing" id="otsing" placeholder="nimetus">
    <input type="submit" value="otsi" name="otsi">
</form>
<?php
if (!empty($_POST['otsing'])){
    $result=search_ByName($_POST['otsing']);
    foreach ($result as $ilm){
        echo"<li>".$ilm->temperatuur.", ";
        echo $ilm->kuupaev.", ";
        echo $ilm->maakonnad->maakonna.", ";
        echo $ilm->maakonnad->maakonnakeskus."</li>";

    }
}
?>
<table class="table">
    <tr>
        <th>temperatuur</th>
        <th>kuupaev</th>
        <th>maakonna</th>
        <th>maakonnakeskus</th>
    </tr>
    <?php
    foreach ($ilmatemperatuur->ilm as $ilm){
        echo"<tr>";
        echo"<td>". ($ilm->temperatuur). "</td>";
        echo"<td>". ($ilm->kuupaev). "</td>";
        echo"<td>". ($ilm->maakonnad->maakonna). "</td>";
        echo"<td>". ($ilm->maakonnad->maakonnakeskus). "</td>";

        echo"</tr>";
    }
    ?>
</table class="table">
<form action="" method="post">
    <h1>Andmete lisamine xml faili sisse</h1>
    <table>
        <tr>
            <td><label for="temperatuur">temperatuur</label></td>
            <td><input type="text" id="temperatuur" name="temperatuur"></td>

        </tr>
        <tr>
            <td><label for="kuupaev">kuupaev</label></td>
            <td><input type="Date" id="kuupaev" name="kuupaev"></td>

        </tr>
        <tr>
            <td><label for="maakonna">maakonna</label></td>
            <td><input type="text" id="maakonna" name="maakonna"></td>

        </tr>
        <tr>
            <td><label for="maakonnakeskus">maakonnakeskus</label></td>
            <td><input type="text" id="maakonnakeskus" name="maakonnakeskus"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="lisa" name="submit">
            </td>
        </tr>
    </table>
</form>
</body>
</html>
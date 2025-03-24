
<?php
function execPrint($command) {
$result = array();
exec($command, $result);
foreach ($result as $line) {
print($line . "<br>");
}
}

print("<pre>" . execPrint("git pull https://anandbrightcode:367195a2a486711fc30766dad6678b9044fca96c@github.com/anandbrightcode/kanodia_factory.git main") . "</pre>");


?>

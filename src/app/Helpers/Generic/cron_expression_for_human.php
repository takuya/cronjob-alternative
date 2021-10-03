<?php


use Cron\CronExpression;
use App\Exceptions\InvalidCronExpression;
use Symfony\Component\Process\Process;


if ( !function_exists( 'cron_expression_for_human' ) ) {

  
  
  function cron_expression_for_human ( $cron_expression ) {
    if ( !CronExpression::isValidExpression( $cron_expression ) ) {
      throw new InvalidCronExpression( "Invalid expression '{$cron_expression}'" );
    }
    $src =<<<'EOS'
      #!/usr/bin/env node
      const cronstrue = require('cronstrue/i18n').default;
      // Override locales
      cronstrue.locales.ja_custom =  Object.create(cronstrue.locales.ja)
      cronstrue.locales.ja_custom.use24HourTimeFormatByDefault= ()=>true;
      cronstrue.locales.ja_custom.everyHour= ()=>"毎時"
      cronstrue.locales.ja_custom.anErrorOccuredWhenGeneratingTheExpressionD= ()=>"式の記述を生成する際にエラーが発生しました。Cron 式の構文を確認してください。"
      cronstrue.locales.ja_custom.atSpace= ()=>"毎"
      cronstrue.locales.ja_custom.everyMinuteBetweenX0AndX1= ()=>"%s分から%s分まで毎分"
      cronstrue.locales.ja_custom.at= ()=>""
      cronstrue.locales.ja_custom.spaceAnd= ()=>"と"
      cronstrue.locales.ja_custom.everySecond= ()=>"毎秒"
      cronstrue.locales.ja_custom.everyX0Seconds= ()=>"%s秒おき"
      cronstrue.locales.ja_custom.secondsX0ThroughX1PastTheMinute= ()=>"毎分%s秒から%s秒まで"
      cronstrue.locales.ja_custom.atX0SecondsPastTheMinute= ()=>"%s秒"
      cronstrue.locales.ja_custom.everyX0Minutes= ()=>"%s分毎"
      cronstrue.locales.ja_custom.minutesX0ThroughX1PastTheHour= ()=>"毎時%s分から%s分まで"
      cronstrue.locales.ja_custom.atX0MinutesPastTheHour= ()=> "毎時%s分"
      cronstrue.locales.ja_custom.everyX0Hours= ()=>"%s時間おき"
      cronstrue.locales.ja_custom.betweenX0AndX1= ()=>"%s-%s時分まで"
      cronstrue.locales.ja_custom.atX0= ()=>"毎%s"
      cronstrue.locales.ja_custom.commaEveryDay= ()=>",毎日"
      cronstrue.locales.ja_custom.commaEveryX0DaysOfTheWeek= ()=>",週のうち%s日おき"
      cronstrue.locales.ja_custom.commaX0ThroughX1= ()=>",%sから%sまで"
      cronstrue.locales.ja_custom.first= ()=>"月の1 番目の"
      cronstrue.locales.ja_custom.second= ()=>"月の2 番目の"
      cronstrue.locales.ja_custom.third= ()=>"月の3 番目の"
      cronstrue.locales.ja_custom.fourth= ()=>"月の4 番目の"
      cronstrue.locales.ja_custom.fifth= ()=>"月の5 番目の"
      cronstrue.locales.ja_custom.commaOnThe= ()=>","
      cronstrue.locales.ja_custom.spaceX0OfTheMonth= ()=>"%s"
      cronstrue.locales.ja_custom.commaOnTheLastX0OfTheMonth= ()=>",%s"
      cronstrue.locales.ja_custom.commaEveryX0Months= ()=>"毎年%sの月ごと"
      cronstrue.locales.ja_custom.commaOnlyInX0= ()=>",%s"
      cronstrue.locales.ja_custom.commaOnTheLastDayOfTheMonth= ()=>",月の最終日"
      cronstrue.locales.ja_custom.commaOnTheLastWeekdayOfTheMonth= ()=>",月の最後の平日"
      cronstrue.locales.ja_custom.firstWeekday= ()=>"最初の平日"
      cronstrue.locales.ja_custom.weekdayNearestDayX0= ()=>"%s日の直近の平日"
      cronstrue.locales.ja_custom.commaOnTheX0OfTheMonth= ()=>",%s"
      cronstrue.locales.ja_custom.commaEveryX0Days= ()=>"毎月%sの日ごと"
      cronstrue.locales.ja_custom.commaBetweenDayX0AndX1OfTheMonth= ()=>",月の%s日から%s日の間"
      cronstrue.locales.ja_custom.commaOnDayX0OfTheMonth= ()=>",毎月%s日"
      cronstrue.locales.ja_custom.spaceAndSpace= ()=>" と"
      cronstrue.locales.ja_custom.commaEveryMinute= ()=>",毎分"
      cronstrue.locales.ja_custom.commaEveryHour= ()=>",毎時"
      cronstrue.locales.ja_custom.commaEveryX0Years= ()=>",%s年おき"
      cronstrue.locales.ja_custom.commaStartingX0= ()=>",%sに開始"
      cronstrue.locales.ja_custom.commaOnlyOnX0 =()=>"%s"
      cronstrue.locales.ja_custom.aMPeriod= ()=>"午前"
      cronstrue.locales.ja_custom.pMPeriod= ()=>"午後"
      cronstrue.locales.ja_custom.commaDaysBeforeTheLastDayOfTheMonth= ()=>",月末から%s日前"
      cronstrue.locales.ja_custom.commaOnlyInYearX0= ()=>",%s"
      cronstrue.locales.ja_custom.daysOfTheWeek= ()=> ["日曜", "月曜", "火曜", "水曜", "木曜", "金曜", "土曜"]
      cronstrue.locales.ja_custom.commaBetweenDayX0AndX1OfTheMonth= ()=>",毎月%s-%s日"
      cronstrue.locales.ja_custom.commaAndOnX0 = () =>"の%s"
      //opts={locale:'ja'};
      opts={locale:'ja_custom'};
      EOS;
    $src = $src. " process.stdout.write(cronstrue.toString('${cron_expression}',opts) );";
    $proc = new Process(['node']);
    $proc->setInput($src);
    $proc->mustRun();
    
    $ret = $proc->getOutput();
    // どうしても日本語表記に合わない A,B,C and D をなんとかする。
    $ret = str_replace(',と','と', $ret);
    $ret = str_replace('と ','と', $ret);
    $ret = str_replace(', ',',', $ret);
    return $ret;
  }
  
}

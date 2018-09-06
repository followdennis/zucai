<?php

namespace App\Http\Controllers;

use App\Models\AnalogueInjection;
use App\Models\SourceWangyiCaipiao;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class TestController extends Controller
{
    //
    public function index(){

        $str = '<div class="ie-fix"><p class="reader-word-layer reader-word-s1-2" style="width:677px;height:169px;line-height:169px;top:1199px;left:1410px;z-index:0;false">转正申请</p><p class="reader-word-layer reader-word-s1-5" style="width:7518px;height:169px;line-height:169px;top:1199px;left:2087px;z-index:1;false">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-4" style="width:339px;height:169px;line-height:169px;top:1451px;left:1410px;z-index:2;false">&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-6" style="width:1013px;height:169px;line-height:169px;top:1451px;left:1749px;z-index:3;false">尊敬的领导：</p><p class="reader-word-layer reader-word-s1-7" style="width:5489px;height:169px;line-height:169px;top:1451px;left:2763px;z-index:4;false">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-4" style="width:339px;height:169px;line-height:169px;top:1702px;left:1410px;z-index:5;false">&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-8" style="width:1351px;height:169px;line-height:169px;top:1702px;left:1749px;z-index:6;false">首先感谢您给我到</p><p class="reader-word-layer reader-word-s1-9" style="width:251px;height:169px;line-height:169px;top:1702px;left:3144px;z-index:7;false">xxx</p><p class="reader-word-layer reader-word-s1-2" style="width:677px;height:169px;line-height:169px;top:1702px;left:3438px;z-index:8;false">有限公司</p><p class="reader-word-layer reader-word-s1-4" style="width:169px;height:169px;line-height:169px;top:1702px;left:4156px;z-index:9;false">xx</p><p class="reader-word-layer reader-word-s1-1" style="width:2029px;height:169px;line-height:169px;top:1702px;left:4366px;z-index:10;letter-spacing:-0.79px;false">部从事技术员工作的机会，</p><p class="reader-word-layer reader-word-s1-12" style="width:507px;height:169px;line-height:169px;top:1702px;left:6352px;z-index:11;false">对此，</p><p class="reader-word-layer reader-word-s1-8" style="width:1351px;height:169px;line-height:169px;top:1702px;left:6817px;z-index:12;false">我感到无比的荣幸</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:1953px;left:1410px;z-index:13;false">和感谢。我一定会珍惜这来之不易的机会，在今后的工作中，好好表现自己，全身心地投入
</p><p class="reader-word-layer reader-word-s1-14" style="width:1691px;height:169px;line-height:169px;top:2204px;left:1410px;z-index:14;false">到技术员工作中去，为</p><p class="reader-word-layer reader-word-s1-9" style="width:168px;height:169px;line-height:169px;top:2204px;left:3144px;z-index:15;false">xx</p><p class="reader-word-layer reader-word-s1-15" style="width:3045px;height:169px;line-height:169px;top:2204px;left:3353px;z-index:16;false">公司明天的发展，贡献自己全部的力量。</p><p class="reader-word-layer reader-word-s1-4" style="width:3211px;height:169px;line-height:169px;top:2204px;left:6396px;z-index:17;letter-spacing:-0.42000000000000004px;false">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-4" style="width:339px;height:169px;line-height:169px;top:2455px;left:1410px;z-index:18;false">&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-16" style="width:337px;height:169px;line-height:169px;top:2455px;left:1749px;z-index:19;false">我于</p><p class="reader-word-layer reader-word-s1-17" style="width:337px;height:169px;line-height:169px;top:2455px;left:2130px;z-index:20;false">2012</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:2455px;left:2508px;z-index:21;">年</p><p class="reader-word-layer reader-word-s1-4" style="width:84px;height:169px;line-height:169px;top:2455px;left:2721px;z-index:22;">7</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:2455px;left:2846px;z-index:23;">月</p><p class="reader-word-layer reader-word-s1-4" style="width:169px;height:169px;line-height:169px;top:2455px;left:3059px;z-index:24;false">23</p><p class="reader-word-layer reader-word-s1-13" style="width:4903px;height:169px;line-height:169px;top:2455px;left:3270px;z-index:25;false">日成为公司的试用员工，到今天三个月试用期已满，根据公司的规
</p><p class="reader-word-layer reader-word-s1-18" style="width:2707px;height:169px;line-height:169px;top:2706px;left:1410px;z-index:26;false">章制度，现申请转为公司正式员工。</p><p class="reader-word-layer reader-word-s1-19" style="width:5491px;height:169px;line-height:169px;top:2706px;left:4115px;z-index:27;false">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-4" style="width:339px;height:169px;line-height:169px;top:2957px;left:1410px;z-index:28;false">&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-20" style="width:6423px;height:169px;line-height:169px;top:2957px;left:1749px;z-index:29;false">在此期间，除了对原理知识的学习外，主要是实践的学习，现在自己可以处理一些简单</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:3208px;left:1410px;z-index:30;false">的故障问题，如加粉、更换色带、更换鼓粉、取卡纸、不能打印、设置共享打印、网络打印</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:3459px;left:1410px;z-index:31;false">等简单的问题，这些问题自己都亲自进行过处理，不懂得地方也问过同事。自己也在原理知</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:3710px;left:1410px;z-index:32;false">识的基础上学到了好多东西。在实践期间除了对简单的故障问题有了深入的了解外，还对各</p><p class="reader-word-layer reader-word-s1-1" style="width:3719px;height:169px;line-height:169px;top:3961px;left:1410px;z-index:33;letter-spacing:-0.8200000000000001px;false">类机型的型号以及耗材的型号有了更深入的了解，</p><p class="reader-word-layer reader-word-s1-2" style="width:677px;height:169px;line-height:169px;top:3961px;left:5102px;z-index:34;false">如美能达</p><p class="reader-word-layer reader-word-s1-17" style="width:337px;height:169px;line-height:169px;top:3961px;left:5823px;z-index:35;false">7616</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:3961px;left:6159px;z-index:36;">、</p><p class="reader-word-layer reader-word-s1-22" style="width:422px;height:169px;line-height:169px;top:3961px;left:6304px;z-index:37;false">7616v</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:3961px;left:6725px;z-index:38;">、</p><p class="reader-word-layer reader-word-s1-17" style="width:337px;height:169px;line-height:169px;top:3961px;left:6869px;z-index:39;false">7622</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:3961px;left:7208px;z-index:40;">、</p><p class="reader-word-layer reader-word-s1-23" style="width:253px;height:169px;line-height:169px;top:3961px;left:7353px;z-index:41;false">283</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:3961px;left:7606px;z-index:42;">、</p><p class="reader-word-layer reader-word-s1-4" style="width:254px;height:169px;line-height:169px;top:3961px;left:7748px;z-index:43;false">363</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:3961px;left:8001px;z-index:44;">、</p><p class="reader-word-layer reader-word-s1-4" style="width:254px;height:169px;line-height:169px;top:4213px;left:1410px;z-index:45;false">423</p><p class="reader-word-layer reader-word-s1-20" style="width:6423px;height:169px;line-height:169px;top:4213px;left:1749px;z-index:46;false">等机型的所用的耗材型号以及一些相关的寿命，结合着原理知识的基础上，对这些机器</p><p class="reader-word-layer reader-word-s1-14" style="width:6257px;height:169px;line-height:169px;top:4464px;left:1410px;z-index:47;false">有了更深的了解，有助于我们在以后处理问题上，能较快较准确的找出问题的所在。</p><p class="reader-word-layer reader-word-s1-4" style="width:169px;height:169px;line-height:169px;top:4464px;left:7667px;z-index:48;false">&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-16" style="width:337px;height:169px;line-height:169px;top:4464px;left:7835px;z-index:49;false">三个</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:4715px;left:1410px;z-index:50;false">月的时间，虽然学到了很多知识，但是有些地方还很不足。首先，自己的专业技能知识还需</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:4966px;left:1410px;z-index:51;false">要增强。在实践期间，分析机器故障的时候，会出现细节上的错误，导致故障得不到很快的</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:5217px;left:1410px;z-index:52;false">解决，会让用户觉得不够专业，影响公司的形象。其次，产品知识认识得不够全面。在给用</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:5468px;left:1410px;z-index:53;false">户推荐产品的时候，或者在用户咨询产品信息的时候，不能考虑全面，只会照着数据给用户</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:5719px;left:1410px;z-index:54;false">介绍机型，而忽视了产品的一些优点及良好的性能能给用户带来什么好处。再次，销售能力
</p><p class="reader-word-layer reader-word-s1-1" style="width:5581px;height:169px;line-height:169px;top:5970px;left:1410px;z-index:55;letter-spacing:-0.76px;false">需要锻炼以及加强。对于不善于沟通的我，销售对于我来说确实有点困难。</p><p class="reader-word-layer reader-word-s1-19" style="width:2618px;height:169px;line-height:169px;top:5970px;left:6989px;z-index:56;false">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-4" style="width:339px;height:169px;line-height:169px;top:6221px;left:1410px;z-index:57;false">&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-20" style="width:6423px;height:169px;line-height:169px;top:6221px;left:1749px;z-index:58;false">经过了这些的学习以及自己的不足，以后还要加强自己的专业技能知识的学习，把学习</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:6472px;left:1410px;z-index:59;false">的原理知识很好的运用到实践当中，遇到不会的问题多问、多想、多观察，积极的向各位师</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:6723px;left:1410px;z-index:60;false">傅学习自己所欠缺的技能知识。多看产品的报价书，以及一些相关信息，全面的了解产品，</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:6974px;left:1410px;z-index:61;false">以便推荐产品的时候能很好的应对以及把产品推销出去。说到销售，多看学习期间所给的资</p><p class="reader-word-layer reader-word-s1-1" style="width:6763px;height:169px;line-height:169px;top:7225px;left:1410px;z-index:62;letter-spacing:-0.81px;false">料，然后结合自己的实际情况运用到现实当中，提高与人沟通的能力，处变不惊的能力以及</p><p class="reader-word-layer reader-word-s1-1 reader-word-s1-21" style="width:847px;height:169px;line-height:169px;top:7476px;left:1410px;z-index:63;font-family:\'宋体\',\'ae837ac3195f312b3169a5b00010001\',\'宋体\';false">抗压能力。</p><p class="reader-word-layer reader-word-s1-5" style="width:5491px;height:169px;line-height:169px;top:7476px;left:2255px;z-index:64;false">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
</p><p class="reader-word-layer reader-word-s1-4" style="width:339px;height:169px;line-height:169px;top:7728px;left:1410px;z-index:65;false">&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-20" style="width:6423px;height:169px;line-height:169px;top:7728px;left:1749px;z-index:66;false">在此我提出转正申请，恳请领导给我继续锻炼自己、实现理想的机会。我会用谦虚的态
</p><p class="reader-word-layer reader-word-s1-14" style="width:6257px;height:169px;line-height:169px;top:7979px;left:1410px;z-index:67;false">度和饱满的热情做好我的本职工作，为公司创造价值，同公司一起展望美好的未来！</p><p class="reader-word-layer reader-word-s1-4" style="width:1942px;height:169px;line-height:169px;top:7979px;left:7667px;z-index:68;letter-spacing:-0.47px;false">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-4" style="width:339px;height:169px;line-height:169px;top:8230px;left:1410px;z-index:69;false">&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-24" style="width:675px;height:169px;line-height:169px;top:8230px;left:1749px;z-index:70;false">申请人：</p><p class="reader-word-layer reader-word-s1-19" style="width:3547px;height:169px;line-height:169px;top:8230px;left:2425px;z-index:71;false">xxx&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;2012</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:8230px;left:6024px;z-index:72;">年</p><p class="reader-word-layer reader-word-s1-4" style="width:169px;height:169px;line-height:169px;top:8230px;left:6244px;z-index:73;false">10</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:8230px;left:6464px;z-index:74;">月</p><p class="reader-word-layer reader-word-s1-4" style="width:169px;height:169px;line-height:169px;top:8230px;left:6682px;z-index:75;false">28</p><p class="reader-word-layer reader-word-s1-24" style="width:675px;height:169px;line-height:169px;top:8230px;left:6902px;z-index:76;false">日篇二：</p><p class="reader-word-layer reader-word-s1-23" style="width:253px;height:169px;line-height:169px;top:8230px;left:7578px;z-index:77;false">it&ensp;</p><p class="reader-word-layer reader-word-s1-1 reader-word-s1-25" style="width:337px;height:169px;line-height:169px;top:8230px;left:7831px;z-index:78;font-family:\'宋体\',\'ae837ac3195f312b3169a5b00010001\',\'宋体\';false">转正</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:8481px;left:1410px;z-index:79;">申</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:8481px;left:2352px;z-index:80;">请</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:8481px;left:3292px;z-index:81;">书</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:8481px;left:4234px;z-index:82;">与</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:8481px;left:5174px;z-index:83;">工</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:8481px;left:6116px;z-index:84;">作</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:8481px;left:7057px;z-index:85;">汇</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:8481px;left:7999px;z-index:86;">报</p><p class="reader-word-layer reader-word-s1-22" style="width:1436px;height:169px;line-height:169px;top:8481px;left:8169px;z-index:87;false">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-1" style="width:509px;height:169px;line-height:169px;top:8732px;left:1410px;z-index:88;false">篇三：</p><p class="reader-word-layer reader-word-s1-4" style="width:169px;height:169px;line-height:169px;top:8732px;left:1917px;z-index:89;false">it</p><p class="reader-word-layer reader-word-s1-12" style="width:507px;height:169px;line-height:169px;top:8732px;left:2128px;z-index:90;false">员工和</p><p class="reader-word-layer reader-word-s1-17" style="width:337px;height:169px;line-height:169px;top:8732px;left:2678px;z-index:91;false">java</p><p class="reader-word-layer reader-word-s1-14" style="width:1691px;height:169px;line-height:169px;top:8732px;left:3059px;z-index:92;false">软件工程师转正申请书</p><p class="reader-word-layer reader-word-s1-5" style="width:4900px;height:169px;line-height:169px;top:8732px;left:4749px;z-index:93;false">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-4" style="width:339px;height:169px;line-height:169px;top:8983px;left:1410px;z-index:94;false">&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-6" style="width:1013px;height:169px;line-height:169px;top:8983px;left:1749px;z-index:95;false">尊敬的领导：</p><p class="reader-word-layer reader-word-s1-7" style="width:5489px;height:169px;line-height:169px;top:8983px;left:2763px;z-index:96;false">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-4" style="width:339px;height:169px;line-height:169px;top:9234px;left:1410px;z-index:97;false">&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-16" style="width:337px;height:169px;line-height:169px;top:9234px;left:1749px;z-index:98;false">我于</p><p class="reader-word-layer reader-word-s1-9" style="width:167px;height:169px;line-height:169px;top:9234px;left:2130px;z-index:99;false">xx</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:9234px;left:2340px;z-index:100;">年</p><p class="reader-word-layer reader-word-s1-4" style="width:169px;height:169px;line-height:169px;top:9234px;left:2551px;z-index:101;false">xx</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:9234px;left:2761px;z-index:102;">月</p><p class="reader-word-layer reader-word-s1-4" style="width:169px;height:169px;line-height:169px;top:9234px;left:2974px;z-index:103;false">xx</p><p class="reader-word-layer reader-word-s1-1" style="width:2027px;height:169px;line-height:169px;top:9234px;left:3185px;z-index:104;letter-spacing:-0.95px;false">号成为公司的试用员工，到</p><p class="reader-word-layer reader-word-s1-4" style="width:169px;height:169px;line-height:169px;top:9234px;left:5253px;z-index:105;false">xx</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:9234px;left:5465px;z-index:106;">年</p><p class="reader-word-layer reader-word-s1-4 reader-word-s1-25" style="width:167px;height:169px;line-height:169px;top:9234px;left:5678px;z-index:107;font-family:\'宋体\',\'ae837ac3195f312b3169a5b00020001\',\'宋体\';false">xx</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:9234px;left:5888px;z-index:108;">月</p><p class="reader-word-layer reader-word-s1-4" style="width:169px;height:169px;line-height:169px;top:9234px;left:6099px;z-index:109;false">xx</p><p class="reader-word-layer reader-word-s1-12" style="width:1859px;height:169px;line-height:169px;top:9234px;left:6309px;z-index:110;false">号试用期已满，根据公司
</p><p class="reader-word-layer reader-word-s1-15" style="width:3045px;height:169px;line-height:169px;top:9485px;left:1410px;z-index:111;false">的规章制度，现申请转为公司正式员工。</p><p class="reader-word-layer reader-word-s1-22" style="width:5154px;height:169px;line-height:169px;top:9485px;left:4453px;z-index:112;false">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-4" style="width:339px;height:169px;line-height:169px;top:9736px;left:1410px;z-index:113;false">&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-8" style="width:1351px;height:169px;line-height:169px;top:9736px;left:1749px;z-index:114;false">本人在试用期间，</p><p class="reader-word-layer reader-word-s1-12" style="width:1183px;height:169px;line-height:169px;top:9736px;left:3080px;z-index:115;false">作为公司的一名</p><p class="reader-word-layer reader-word-s1-17" style="width:337px;height:169px;line-height:169px;top:9736px;left:4307px;z-index:116;false">java</p><p class="reader-word-layer reader-word-s1-18" style="width:1015px;height:169px;line-height:169px;top:9736px;left:4685px;z-index:117;false">软件工程师，</p><p class="reader-word-layer reader-word-s1-12" style="width:845px;height:169px;line-height:169px;top:9736px;left:5678px;z-index:118;false">工作认真，</p><p class="reader-word-layer reader-word-s1-12" style="width:1521px;height:169px;line-height:169px;top:9736px;left:6502px;z-index:119;false">按时完成分配任务，</p><p class="reader-word-layer reader-word-s1-1" style="width:169px;height:169px;line-height:169px;top:9736px;left:8003px;z-index:120;">工</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:9987px;left:1410px;z-index:121;false">作技能和技术不算很优秀，但也非常努力的学习，和同事之间能够通力合作，关系相处融洽</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:10238px;left:1410px;z-index:122;false">而和睦。并积极学习一些在工作中用到的内容，并很好的运用到实际开发中去。在项目的开</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:10489px;left:1410px;z-index:123;false">发过程中遇到错误时，能够及时的与项目组其他成员沟通，并找到解决问题的办法，以保证</p><p class="reader-word-layer reader-word-s1-1" style="width:1353px;height:169px;line-height:169px;top:10740px;left:1410px;z-index:124;letter-spacing:-0.74px;false">项目的开发效率。</p><p class="reader-word-layer reader-word-s1-7" style="width:5489px;height:169px;line-height:169px;top:10740px;left:2763px;z-index:125;false">&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-4" style="width:339px;height:169px;line-height:169px;top:10991px;left:1410px;z-index:126;false">&ensp;&ensp;&ensp;&ensp;</p><p class="reader-word-layer reader-word-s1-20" style="width:6423px;height:169px;line-height:169px;top:10991px;left:1749px;z-index:127;false">在这里作为项目组中的一员，当我从踏进公司面试开始，我相信公司是一个能让人发挥</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:11242px;left:1410px;z-index:128;false">聪明和才智的地方，在公司里，项目经理有着丰富的项目开发经验，见多识广，工作中能够</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:11494px;left:1410px;z-index:129;false">对我进行正确的指导，让我在开发的过程中避免了很多的错误，少走了很多的弯路，从中我</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:11745px;left:1410px;z-index:130;false">能学到很多的知识，同时也积累了开发经验。在这两个月来我学到了很多，看到公司的迅速</p><p class="reader-word-layer reader-word-s1-13" style="width:6763px;height:169px;line-height:169px;top:11996px;left:1410px;z-index:131;false">发展，我深深地感到骄傲和自豪，也更加迫切的希望以一名正式员工的身份在这里工作，实
</p></div>';

        $content = strip_tags($str,'<p>');
        echo $content;die;
        $data = AnalogueInjection::orderBy('id','desc')->get();
        foreach($data as $k => $v){
            echo $v->match_id;
            foreach($v->host_history_score as $kk => $vv){
               echo  $vv->host_team_name;die;
            }
die;
        }
       die;
        $client = new Client();
        $response = $client->request('get','http://caipiao.163.com/order/jczq-hunhe/#from=leftnav');
        $html =  $response->getBody();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $dom = $crawler->filterXPath('//div[@class="gameSelect"]//dl[contains(@gamedate,"20180721")]/dd[contains(@matchcode,"2018")]');
        foreach($dom as $i => $node){
            $c = new Crawler($node);
            //编号
            echo $c->filterXpath('//span[@class="co1"]')->text();
            echo "&nbsp;&nbsp;";
            //赛事
            echo $c->filterXPath('//span[@class="co2"]')->text();
            echo "&nbsp;&nbsp;";
            //时间
            echo $c->filterXPath('//span[contains(@class,"co3")]')->text();
            echo "&nbsp;&nbsp;";
            // 交战队伍
            echo $c->filterXPath('//span[contains(@class,"co4")]//em[@class="hostTeam"]')->text();
            echo "&nbsp;";
            echo $c->filterXPath('//span[contains(@class,"co4")]//em[@class="guestTeam"]')->text();
            echo "&nbsp;&nbsp;";
            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line1"]/em[@class="rq"]')->text();
            echo "赔率";
            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line1"]/em[2]')->text();
            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line1"]/em[2]')->text();
            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line1"]/em[3]')->text();

            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line2"]/em[contains(@class,"rq")]')->text();
            echo "赔率";
            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line2"]/em[2]')->text();
            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line2"]/em[2]')->text();
            echo $c->filterXPath('//span[contains(@class,"co6_1")]/div[@class="line2"]/em[3]')->text();

            echo "&nbsp;&nbsp;";
            echo $c->filterXPath('//span[contains(@class,"co6_2")]')->text();
            echo "&nbsp;&nbsp;";
            echo $c->filterXPath('//span[contains(@class,"co7")]')->text();

            echo $i."<br/>";
        }
    }
    public function score(){
        $url = 'http://zx.caipiao.163.com/library/football/match.html?mId=1368658&hId=2228&vId=2233';
        $cache = time();
        $modelId = 'data_recCase';
        $matchId = '';
        $league = '';
        $field = '';
        echo $url;
        $client = new Client();
        $response = $client->request('get',$url);
        echo "<pre>";
        $html = $response->getBody();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $script = $crawler->filter('.docBody  script')->text();
        $input = $crawler->filter('#data_recCase')->filter('dd.list')->filter('input')
        ->extract(['value']); //获取联盟id
        $crawler->clear();
        //获取matchId


        preg_match('/Core.pageData\(\'matchId\', \'(\d+)\'\)/',$script,$match);
        preg_match('/Core.pageData\(\'hostId\', \'(\d+)\'\)/',$script,$host);
        preg_match('/Core.pageData\(\'guestId\', \'(\d+)\'\);/',$script,$guest);
        $matchId = $match[1];
        $realHostId = $host[1];
        $realGuestId = $guest[1];

        $league = implode(',',$input);
        $url2 = 'http://bisai.caipiao.163.com/match/data.html?cache='.$cache.'&modelId='.$modelId.'&matchId='.$matchId.'&league='.urlencode($league).'&field=10';

        $html2 = $client->request('get',$url2)->getBody();
        echo $html2;
        $crawler = new Crawler();

        //修改比赛表  match_id
        $crawler->addHtmlContent($html2);
        //历史数据
        $dom = $crawler->filter('.u-tb-s02 ')->each(function(Crawler $node ,$i) use($realHostId,$realGuestId,$matchId){

            $tr = $node->filter('tr')->each(function(Crawler $node2 ,$j) use($i,$realHostId,$realGuestId,$matchId){


               if($j > 0){
                $league_name = $node2->filter('td')->eq(0)->text();

                $date = trim($node2->filter('th')->text());
                $hostIdStr = $node2->filter('td')->eq(1)->attr('data-fid');
                $hostId = explode(';',$hostIdStr)[1];


                $host_name = $node2->filter('td')->eq(1)->filter('a')->text();
                $scoresStr = $node2->filter('td')->eq(2)->text();
                $hostScore = explode(':',$scoresStr)[0];
                $guestScore = explode(':',$scoresStr)[1];

                $guestIdStr = $node2->filter('td')->eq(3)->attr('data-fid');
                $guestId = explode(';',$guestIdStr)[1];

                $guest_name = $node2->filter('td')->eq(3)->filter('a')->text();
                $result = $node2->filter('td')->eq(4)->text();


               $res_int = 0;
               if($result == '胜'){
                   $res_int = 1;
               }elseif($result == '平'){
                   $res_int = 2;
               }elseif($result == '负'){
                   $res_int = 3;
               }
                //主队数据
                if($i == 0){
                    $aim_team_id = $realHostId;
                    //判断目标队伍的位置
                    if($realHostId == $hostId){
                        $is_host = 0;
                    }else{
                        $is_host = 1;
                    }
                    //写入主队历史数据库
                }
                //客队
                if($i == 1){

                    $aim_team_id = $realGuestId;
                    if($realGuestId == $hostId){
                        $is_host = 0;
                    }else{
                        $is_host = 1;
                    }
                    //写入客队历史数据库
                }


//                echo $league_name.'-'.$date.'-'.$hostId.'-'.$host_name.'-'.$hostScore.':'.$guestScore.'-'.$guestId.'-'.$guest_name.'-'.$result.'-'.$aim_team_id.'-'.$is_host .'-'.$res_int;

               }



            });
        });
        //统计数据
        $host_average = $crawler->filter('.u-tb-s01')->filter('tr')->eq(2)->filter('td')->eq(0)->text();
        $guest_average = $crawler->filter('.u-tb-s01')->filter('tr')->eq(2)->filter('td')->eq(3)->text();

        //修改比赛数据平均值和状态
        echo $host_average .'-' . $guest_average;die;
    }
    public function getNumber(){
        $str = 'aaabbbv321';
        preg_match('/\d+/',$str,$out);
        print_r($out);
        $a = [
            0 => 'aaa',
            1 => 'bbb'
        ];
        $b = [
            2 => 'cc',
            3 => 'dd'
        ];
        $t1 = '2008-02-12 11:33:20';
        $today = Carbon::parse($t1)->startOfDay();

        $start = Carbon::parse($today)->addHours(12);
        if(strtotime($t1) > strtotime($start)){
            echo $today;
        }else{
            echo Carbon::parse($today)->subDay();
        }
        $a = '2.3';
        $b = '1.2';
        $c = '3.8';
        $arr = [
            1 =>$a,
            2 =>$b,
            3 =>$c
        ];

        $new = $this->hope($arr,2);
        echo "<br/>";
        print_r($new);
        echo '<hr/>';
        $data = SourceWangyiCaipiao::where('status',2)->first();
        echo $data->color1;
        echo "<br/>";
        echo $data->id;
        echo $data->competition_name;
        echo $data->match_time;

    }
    public function aoke(){
        $client = new Client();
        $response = $client->request('get','http://www.okooo.com/jingcai/');
        $html =  $response->getBody();
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $dom = $crawler->filter('div.cont');
        foreach($dom as $node){
            $c1 = new Crawler($node);

            $content = $c1->filter('.cont_1 .riqi .time')->html();
            echo $content;
            die;
        }

    }
    public function hope($arr,$pos = 1){
        asort($arr);
        $i = 1;
        $color = 0;
        foreach($arr as $k => $v){

            if($k == $pos){

                $color = $i;
                break;

            }
            $i++;
        }
        return $color;
    }

    /**
     * 常用内容测试
     */
    public function test(){
        echo "test<br/>";

        $client = new Client();
        $response = $client->request('get','http://bisai.caipiao.163.com/match/data.html?cache=1535184705081&modelId=data_recHis&matchId=2721965&league=110%2C577%2C109&field=10');
        $response = $client->request('get','http://zx.caipiao.163.com/library/football/match.html?mId=1398732&hId=290&vId=216');

//        $response = $client->request('get','http://bisai.caipiao.163.com/match/data.html?cache='.time().)
        $url = '';

            echo microtime(true);
//        $html = $response->getBody();
//        echo $html;
    }
}

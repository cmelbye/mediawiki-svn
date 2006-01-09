<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesSr = array(
	NS_MEDIA            => "Медија",
	NS_SPECIAL          => "Посебно",
	NS_MAIN             => "",
	NS_TALK             => "Разговор",
	NS_USER             => "Корисник",
	NS_USER_TALK        => "Разговор_са_корисником",
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => ($wgMetaNamespaceTalk ? $wgMetaNamespaceTalk : "Разговор_о_".$wgMetaNamespace ),
	NS_IMAGE            => "Слика",
	NS_IMAGE_TALK       => "Разговор_о_слици",
	NS_MEDIAWIKI        => "МедијаВики",
	NS_MEDIAWIKI_TALK   => "Разговор_о_МедијаВикију",
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Разговор_о_шаблону',
	NS_HELP             => 'Помоћ',
	NS_HELP_TALK        => 'Разговор_о_помоћи',
	NS_CATEGORY         => 'Категорија',
	NS_CATEGORY_TALK    => 'Разговор_о_категорији',
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsSr = array(
 "Никаква", "Причвршћена лево", "Причвршћена десно", "Плутајућа лево"
);

/* private */ $wgSkinNamesSr = array(
 "Обична", "Носталгија", "Келнско плаво", "Педингтон", "Монпарнас"
) + $wgSkinNamesEn;

/* private */ $wgDateFormatsSr = array(
	'Није битно',
	'06:12, 5. јануар 2001.',
	'06:12, 5 јануар 2001',
	'06:12, 05.01.2001.',
	'06:12, 5.1.2001.',
	'06:12, 5. јан 2001.',
	'06:12, 5 јан 2001',
	'6:12, 5. јануар 2001.',
	'6:12, 5 јануар 2001',
	'6:12, 05.01.2001.',
	'6:12, 5.1.2001.',
	'6:12, 5. јан 2001.',
	'6:12, 5 јан 2001',
);

/* NOT USED IN STABLE VERSION */
/* private */ $wgMagicWordsSr = array(
#	ID                                CASE SYNONYMS
	MAG_REDIRECT             => array( 0, '#Преусмери', '#redirect', '#преусмери', '#ПРЕУСМЕРИ' ),
	MAG_NOTOC                => array( 0, '__NOTOC__', '__БЕЗСАДРЖАЈА__' ),
	MAG_FORCETOC             => array( 0, '__FORCETOC__', '__ФОРСИРАНИСАДРЖАЈ__' ),
	MAG_TOC                  => array( 0, '__TOC__', '__САДРЖАЈ__' ),
	MAG_NOEDITSECTION        => array( 0, '__NOEDITSECTION__', '__БЕЗ_ИЗМЕНА__', '__БЕЗИЗМЕНА__' ),
	MAG_START                => array( 0, '__START__', '__ПОЧЕТАК__' ),
	MAG_END                  => array( 0, '__END__', '__КРАЈ__' ),
	MAG_CURRENTMONTH         => array( 1, 'CURRENTMONTH', 'ТРЕНУТНИМЕСЕЦ' ),
	MAG_CURRENTMONTHNAME     => array( 1, 'CURRENTMONTHNAME', 'ТРЕНУТНИМЕСЕЦИМЕ' ),
	MAG_CURRENTMONTHNAMEGEN  => array( 1, 'CURRENTMONTHNAMEGEN', 'ТРЕНУТНИМЕСЕЦРОД' ),
	MAG_CURRENTMONTHABBREV   => array( 1, 'CURRENTMONTHABBREV', 'ТРЕНУТНИМЕСЕЦСКР' ),
	MAG_CURRENTDAY           => array( 1, 'CURRENTDAY', 'ТРЕНУТНИДАН' ),
	MAG_CURRENTDAYNAME       => array( 1, 'CURRENTDAYNAME', 'ТРЕНУТНИДАНИМЕ' ),
	MAG_CURRENTYEAR          => array( 1, 'CURRENTYEAR', 'ТРЕНУТНАГОДИНА' ),
	MAG_CURRENTTIME          => array( 1, 'CURRENTTIME', 'ТРЕНУТНОВРЕМЕ' ),
	MAG_NUMBEROFARTICLES     => array( 1, 'NUMBEROFARTICLES', 'БРОЈЧЛАНАКА' ),
	MAG_NUMBEROFFILES        => array( 1, 'NUMBEROFFILES', 'БРОЈДАТОТЕКА', 'БРОЈФАЈЛОВА' ),
	MAG_PAGENAME             => array( 1, 'PAGENAME', 'СТРАНИЦА' ),
	MAG_PAGENAMEE            => array( 1, 'PAGENAMEE', 'СТРАНИЦЕ' ),
	MAG_NAMESPACE            => array( 1, 'NAMESPACE', 'ИМЕНСКИПРОСТОР' ),
	MAG_NAMESPACEE           => array( 1, 'NAMESPACEE', 'ИМЕНСКИПРОСТОРИ' ),
	MAG_FULLPAGENAME         => array( 1, 'FULLPAGENAME', 'ПУНОИМЕСТРАНЕ' ),
	MAG_FULLPAGENAMEE        => array( 1, 'FULLPAGENAMEE', 'ПУНОИМЕСТРАНЕЕ' ),
	MAG_MSG                  => array( 0, 'MSG:', 'ПОР:' ),
	MAG_SUBST                => array( 0, 'SUBST:', 'ЗАМЕНИ:' ),
	MAG_MSGNW                => array( 0, 'MSGNW:', 'НВПОР:' ),
	MAG_IMG_THUMBNAIL        => array( 1, 'thumbnail', 'thumb', 'мини' ),
	MAG_IMG_MANUALTHUMB      => array( 1, 'thumbnail=$1', 'thumb=$1', 'мини=$1' ),
	MAG_IMG_RIGHT            => array( 1, 'right', 'десно', 'д' ),
	MAG_IMG_LEFT             => array( 1, 'left', 'лево', 'л' ),
	MAG_IMG_NONE             => array( 1, 'none', 'н', 'без' ),
	MAG_IMG_WIDTH            => array( 1, '$1px', '$1пискел' , '$1п' ),
	MAG_IMG_CENTER           => array( 1, 'center', 'centre', 'центар', 'ц' ),
	MAG_IMG_FRAMED           => array( 1, 'framed', 'enframed', 'frame', 'оквир', 'рам' ),
	MAG_INT                  => array( 0, 'INT:', 'ИНТ:' ),
	MAG_SITENAME             => array( 1, 'SITENAME', 'ИМЕСАЈТА' ),
	MAG_NS                   => array( 0, 'NS:', 'ИП:' ),
	MAG_LOCALURL             => array( 0, 'LOCALURL:', 'ЛОКАЛНААДРЕСА:' ),
	MAG_LOCALURLE            => array( 0, 'LOCALURLE:', 'ЛОКАЛНЕАДРЕСЕ:' ),
	MAG_SERVER               => array( 0, 'SERVER', 'СЕРВЕР' ),
	MAG_SERVERNAME           => array( 0, 'SERVERNAME', 'ИМЕСЕРВЕРА' ),
	MAG_SCRIPTPATH           => array( 0, 'SCRIPTPATH', 'СКРИПТА' ),
	MAG_GRAMMAR              => array( 0, 'GRAMMAR:', 'ГРАМАТИКА:' ),
	MAG_NOTITLECONVERT       => array( 0, '__NOTITLECONVERT__', '__NOTC__', '__БЕЗТЦ__' ),
	MAG_NOCONTENTCONVERT     => array( 0, '__NOCONTENTCONVERT__', '__NOCC__', '__БЕЗЦЦ__' ),
	MAG_CURRENTWEEK          => array( 1, 'CURRENTWEEK', 'ТРЕНУТНАНЕДЕЉА' ),
	MAG_CURRENTDOW           => array( 1, 'CURRENTDOW', 'ТРЕНУТНИДОВ' ),
	MAG_REVISIONID           => array( 1, 'REVISIONID', 'ИДРЕВИЗИЈЕ' ),
	MAG_PLURAL               => array( 0, 'PLURAL:', 'МНОЖИНА:' ),
	MAG_FULLURL              => array( 0, 'FULLURL:', 'ПУНУРЛ:' ),
	MAG_FULLURLE             => array( 0, 'FULLURLE:', 'ПУНУРЛЕ:' ),
	MAG_LCFIRST              => array( 0, 'LCFIRST:', 'ЛЦПРВИ:' ),
	MAG_UCFIRST              => array( 0, 'UCFIRST:', 'УЦПРВИ:' ),
	MAG_LC                   => array( 0, 'LC:', 'ЛЦ:' ),
	MAG_UC                   => array( 0, 'UC:', 'УЦ:' ),
);

$wgAllMessagesSr = array(
'1movedto2' => 'чланку [[$1]] је промењено име у [[$2]]',
'1movedto2_redir' => 'чланку [[$1]] је промењено име у [[$2]] путем преусмерења',
'Monobook.css' => '/*
*/',
'Monobook.js' => '
/* tooltips and access keys */
ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Моја корисничка страница\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'Корисничка страна за ИП коју мењате као\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Моја страна за разговор\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Разговор о прилозима са ове ИП адресе\');
ta[\'pt-preferences\'] = new Array(\'\',\'Моја корисничка подешавања\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'Списак чланака које надгледате.\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Списак мојих прилога\');
ta[\'pt-login\'] = new Array(\'o\',\'Пријава није обавезна, али доноси много користи.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Пријава није обавезна, али доноси много користи.\');
ta[\'pt-logout\'] = new Array(\'o\',\'Одјава са Википедије\');
ta[\'ca-talk\'] = new Array(\'t\',\'Разговори о садржају чланка\');
ta[\'ca-edit\'] = new Array(\'e\',\'Можете да уређујете овај чланак!\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Додајте свој коментар.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Овај чланак је закључан.\');
ta[\'ca-history\'] = new Array(\'h\',\'Претходне верзије садржаја чланка.\');
ta[\'ca-protect\'] = new Array(\'=\',\'Заштити страницу од будућих измена\');
ta[\'ca-delete\'] = new Array(\'d\',\'Брисање странице\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Враћање измена које су начињене пре брисања странице.\');
ta[\'ca-move\'] = new Array(\'m\',\'Померање странице\');
ta[\'ca-nomove\'] = new Array(\'\',\'Немате дозволу за померање ове странице\');
ta[\'ca-watch\'] = new Array(\'w\',\'Додавање странице на Ваш списак надгладања.\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Уклањање ове странице са Вашег списка надгледања.\');
ta[\'search\'] = new Array(\'f\',\'Претраживање Википедије\');
ta[\'p-logo\'] = new Array(\'\',\'Главна страна\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Посетите главну страну\');
ta[\'n-portal\'] = new Array(\'\',\'Разговор о било чему што се тиче Википедије.\');
ta[\'n-currentevents\'] = new Array(\'\',\'Подаци о ономе на чему се тренутно ради.\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'Списак скорашњих измена на Википедији\');
ta[\'n-randompage\'] = new Array(\'x\',\'Учитавање случајне странице\');
ta[\'n-help\'] = new Array(\'\',\'Научите да уређујете Википедију!\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Подржите нас\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Списак свих чланака који су повезани са овим\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Скорашње измене чланака на којима се налази линк ка овом чланку \');
ta[\'feed-rss\'] = new Array(\'\',\'RSS за ову страницу\');
ta[\'feed-atom\'] = new Array(\'\',\'Atom за ову страницу\');
ta[\'t-contributions\'] = new Array(\'\',\'Списак прилога овог корисника\');
ta[\'t-emailuser\'] = new Array(\'\',\'Слање електронског писма овом кориснику\');
ta[\'t-upload\'] = new Array(\'u\',\'Слање слика и медија фајлова\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Списак свих специјалних страница\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Видети садржај чланка\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Видети корисничку страницу\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Видети медија фајл\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Ово је специјална страница и зато је не можете самостално уређивати.\');
ta[\'ca-nstab-wp\'] = new Array(\'c\',\'Видети пројекат страницу\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Видети страницу слике\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Видети системску поруку\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Видети шаблон\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Видети страницу за помоћ\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Видети страницу категорије\');',
'about' => 'О...',
'aboutpage' => '{{ns:4}}:О',
'aboutsite' => 'О пројекту {{ns:4}}',
'accmailtext' => 'Лозинка за налог \'$1\' је послата на адресу $2.',
'accmailtitle' => 'Лозинка је послата.',
'acct_creation_throttle_hit' => 'Жао нам је, већ сте направили $1 корисничка имена. Више није дозвољено.',
'actioncomplete' => 'Акција завршена',
'addedwatch' => 'Додато списку надгледања',
'addedwatchtext' => 'Страница "[[:$1]]" је додата вашем [[{{ns:-1}}:Watchlist|списку надгледања]] .
Будуће промене ове странице и њој придружене странице за разговор ће бити наведене овде, и страница ће бити <b>подебљана</b> у [[{{ns:-1}}:Recentchanges|списку]] скорашњих измена да би се лакше уочила.</p>

<p>Ако касније желите да уклоните страницу са вашег списка надгледања, кликните на "прекини надгледање" на бочној палети.',
'addgroup' => 'Додај групу',
'addgrouplogentry' => 'Додата група $2',
'administrators' => '{{ns:4}}:Администратори',
'allarticles' => 'Сви чланци',
'allinnamespace' => 'Све странице ($1 именски простор)',
'alllogstext' => 'Комбиновани приказ слања, брисања, заштите, блокирања, и администраторских записа.',
'allmessages' => 'Системске поруке',
'allmessagescurrent' => 'Тренутно',
'allmessagesdefault' => 'Стандардно',
'allmessagesname' => 'Име',
'allmessagesnotsupportedDB' => '[[{{ns:-1}}:AllMessages|Системске поруке]] нису подржане зато што је <i>wgUseDatabaseMessages</i> искључен.',
'allmessagesnotsupportedUI' => 'Ваш тренутни језик интерфејса <b>$1</b> није подржан у [[{{ns:-1}}:AllMessages|системским порукама]] на овој вики.',
'allmessagestext' => 'Ово је списак свих порука које су у {{ns:8}}: именском простору',
'allnonarticles' => 'Све странице које нису чланци',
'allnotinnamespace' => 'Све странице (које нису у $1 именском простору)',
'allpages' => 'Све странице',
'allpagesfrom' => 'Прикажи странице почетно са:',
'allpagesnext' => 'Следећа',
'allpagesprev' => 'Претходна',
'allpagessubmit' => 'Иди',
'alphaindexline' => '$1 у $2',
'already_bureaucrat' => 'Овај корисник је већ бирократа',
'already_steward' => 'Овај корисник је већ домаћин',
'already_sysop' => 'Овај корисник је већ администратор',
'alreadyloggedin' => '<strong>Корисниче $1, већ сте пријављени!</strong><br />',
'alreadyrolled' => 'Не могу да вратим последњу измену [[$1]]
од корисника [[{{ns:2}}:$2|$2]] ([[{{ns:3}}:$2|разговор]]); неко други је већ изменио или вратио чланак.

Последња измена од корисника [[{{ns:2}}:$3|$3]] ([[{{ns:3}}:$3|разговор]]). ',
'ancientpages' => 'Најстарији чланци',
'and' => 'и',
'anontalk' => 'Разговор за ову ИП адресу',
'anontalkpagetext' => '---- Ово је страница за разговор за анонимног корисника који још није направио налог или га не користи. Због тога морамо да користимо бројчану [[ИП адреса|ИП адресу]] како бисмо идентификовали њега или њу. Такву адресу може делити више корисника. Ако сте анонимни корисник и мислите да су вам упућене небитне примедбе, молимо вас да [[{{ns:-1}}:Userlogin|направите налог или се пријавите]] да бисте избегли будућу забуну са осталим анонимним корисницима.',
'anonymous' => 'Анонимни корисник {{ns:4}}',
'apr' => 'апр',
'april' => 'април',
'article' => 'Чланак',
'articleexists' => 'Страница под тим именом већ постоји, или је
име које сте изабрали неисправно.
Молимо изаберите друго име.',
'articlepage' => 'Погледај чланак',
'aug' => 'авг',
'august' => 'август',
'autoblocker' => 'Аутоматски сте блокирани јер делите ИП адресу са "$1". Разлог "$2".',
'badaccess' => 'Грешка при одобрењу',
'badaccesstext' => 'Акција коју сте тражили је ограничена на кориснике са "$2" дозволама. Погледајте $1.',
'badarticleerror' => 'Ова акција не може бити извршена на овој страници.',
'badfilename' => 'Име слике је промењено у "$1".',
'badfiletype' => '".$1" није препоручени формат слике.',
'badipaddress' => 'Не постоји ни један корисник који се тако зове',
'badquery' => 'Лоше обликован упит за претрагу',
'badquerytext' => 'Нисмо могли да обрадимо ваш упит.
Ово је вероватно због тога што сте покушали да тражите
реч краћу од три слова, што тренутно није подржано.
Такође је могуће да сте погрешно укуцали израз, на
пример "риба ии крљушти".
Молимо вас покушајте неким другим упитом.',
'badretype' => 'Лозинке које сте унели се не поклапају.',
'badtitle' => 'Лош наслов',
'badtitletext' => 'Захтевани наслов странице је био неисправан, празан или
неисправно повезан међујезички или интервики наслов.',
'blanknamespace' => '(Главно)',
'blockedtext' => 'Ваше корисничко име или ИП адреса је блокирана од стране $1.
Дати разлог је следећи:<br />\'\'$2\'\'<p>Можете се обратити $1 или неком другом
[[{{ns:4}}:администратори|администратору]] да бисте разговарали о блокади.',
'blockedtitle' => 'Корисник је блокиран',
'blockip' => 'Блокирај корисника',
'blockipsuccesssub' => 'Блокирање је успело',
'blockipsuccesstext' => '"$1" је блокиран.
<br />Погледајте [[{{ns:-1}}:Ipblocklist|ИП списак блокираних корисника]] за преглед блокирања.',
'blockiptext' => 'Употребите доњи упитник да бисте уклонили право писања
са одређене ИП адресе или корисничког имена.
Ово би требало да буде урађено само да би се спречио вандализам, и у складу
са [[{{ns:4}}:Смернице|смерницама]].
Унесите конкретан разлог испод (на пример, наводећи које
странице су вандализоване).',
'blocklink' => 'блокирај',
'blocklistline' => '$1, $2 блокирао корисника [[Корисник:$3|$3]], (истиче $4)',
'blocklogentry' => 'је блокирао "$1" са временом истицања блокаде од $2',
'blocklogpage' => 'историја блокирања',
'blocklogtext' => 'Ово је историја блокирања и деблокирања корисника. Аутоматски
блокиране ИП адресе нису исписане овде. Погледајте [[{{ns:-1}}:Ipblocklist|блокиране ИП адресе]] за списак тренутних забрана и блокирања.',
'bold_sample' => 'подебљан текст',
'bold_tip' => 'подебљан текст',
'booksources' => 'Штампани извори',
'booksourcetext' => 'Испод је списак веза на друге сајтове који
продају нове и коришћене књиге, и такође могу имати даљње информације
о књигама које тражите.
{{SITENAME}} не сарађује ни се једним од ових предузећа, и
овај списак не треба да се схвати као потврда њиховог квалитета.',
'brokenredirects' => 'Покварена преусмерења',
'brokenredirectstext' => 'Следећа преусмерења су повезана на непостојећи чланак.',
'bugreports' => 'Пријаве грешака',
'bugreportspage' => '{{ns:4}}:Пријаве_грешака',
'bureaucratlog' => 'историја администраторских права',
'bureaucratlogentry' => 'Права за корисника "[[{{ns:2}}:$1|$1]]" постављена',
'bydate' => 'по датуму',
'byname' => 'по имену',
'bysize' => 'по величини',
'cachederror' => 'Ово је кеширана копија захтеване странице, и можда није најновија.',
'cancel' => 'Поништи',
'cannotdelete' => 'Не могу да обришем наведену страницу или слику. (Могуће је да ју је неко други већ обрисао.)',
'cantrollback' => 'Не могу да вратим измену; последњи аутор је уједно и једини.',
'categories' => 'Категорије страница',
'categoriespagetext' => 'Следеће категорије већ постоје у {{ns:4}}',
'category' => 'категорија',
'category_header' => 'Чланака у категорији: "$1"',
'categoryarticlecount' => 'У овој категорији се налази $1 чланака.',
'categoryarticlecount1' => 'У овој категорији се налази $1 чланака.',
'changed' => 'промењен',
'changegrouplogentry' => 'Промењена група $2',
'changepassword' => 'Промени лозинку',
'changes' => 'измене',
'clearyourcache' => '\'\'\'Запамтите:\'\'\' Након снимања, морате очистити кеш вашег веб читача да бисте видели промене: \'\'\'Mozilla/Safari/Konqueror:\'\'\' држите \'\'SHIFT\'\' док кликћете \'\'Reload\'\' (или притисните  \'\'Shift+Ctrl+R\'\'), \'\'\'IE:\'\'\' притисните \'\'Ctrl-F5\'\', \'\'\'Оpera\'\'\' притисните \'\'F5\'\'.',
'columns' => 'Колона',
'compareselectedversions' => 'Упоређивање означених верзија',
'confirm' => 'Потврди',
'confirmdelete' => 'Потврди брисање',
'confirmdeletetext' => 'На путу сте да трајно обришете страницу
или слику заједно са њеном историјом из базе података.
Молимо вас потврдите да намеравате да урадите ово, да разумете
последице, и да ово радите у складу са
[[{{ns:4}}:Правила и смернице|правилима]] {{ns:4}}.',
'confirmemail' => 'Потврдите адресу е-поште',
'confirmemail_body' => 'Неко, вероватно ви, је са ИП адресе $1 регистровао налог "$2" са овом адресом е-поште на {{SITENAME}}.

Да потврдите да овај налог стварно припада вама и да активирате могућност е-поште на {{SITENAME}}, отворите ову повезницу у вашем браузеру:

$3

Ако ово нисте ви, не пратите повезницу. Овај код за потврду ће истећи у $4.',
'confirmemail_error' => 'Нешто је пошло по злу приликом снимања ваше потврде.',
'confirmemail_invalid' => 'Нетачан код за потврду. Могуће је да је код истекао.',
'confirmemail_loggedin' => 'Адреса ваше е-поште је сада потврђена.',
'confirmemail_send' => 'Пошаљи код за потврду',
'confirmemail_sendfailed' => 'Пошта за потврђивање није послата. Проверита адресу због неправилних карактера.',
'confirmemail_sent' => 'Е-пошта за потврђивање послата.',
'confirmemail_subject' => '{{SITENAME}} адреса е-поште за потврђивање',
'confirmemail_success' => 'Адреса ваше е-поште је потврђена. Можете сада да се пријавите и уживате у вики.',
'confirmemail_text' => 'Ова вики захтева да потврдите адресу ваше е-поште пре него што користите могућности е-поште. Активирајте дугме испод како бисте послали пошту за потврду на вашу адресу. Пошта укључује повезницу која садржи код; учитајте повезницу у ваш браузер да бисте потврдили да је адреса ваше е-поште валидна.',
'confirmprotect' => 'Потврдите заштиту',
'confirmprotecttext' => 'Да ли заиста желите да заштитите ову страницу?',
'confirmrecreate' => 'Корисник [[{{ns:2}}:$1|$1]] ([[{{ns:3}}:$1|разговор]]) је обрисао овај чланак пошто сте почели уређивање са разлогом:
: \'\'$2\'\'

Молимо потврдите да стварно желите да поново направите овај чланак.',
'confirmunprotect' => 'Потврдите скидање заштите',
'confirmunprotecttext' => 'Да ли заиста желите да скинете заштиту са ове странице?',
'contextchars' => 'Карактера контекста по линији',
'contextlines' => 'Линија по поготку',
'contribs-showhideminor' => '$1 малих измена',
'contribslink' => 'прилози',
'contribsub' => 'За $1',
'contributions' => 'Прилози корисника',
'copyright' => 'Садржај је објављен под $1.',
'copyrightpage' => '[[{{ns:4}}:Ауторска права|Ауторска права]]',
'copyrightpagename' => '{{SITENAME}} ауторска права',
'copyrightwarning' => '
\'\'\'Ваше измене ће одмах бити видљиве.\'\'\'<br />
* За тестирање, молимо Вас користите страницу \'\'\'[[{{ns:4}}:песак|песак]]\'\'\' или <span class=plainlinks>[http://crash.vikimedija.org/ пројекат посебно намењен за тестирање]</span>..
* Молимо вас да обратите пажњу да се за сваки допринос {{ns:4}} сматра да је објављен под ГНУ лиценцом за слободну документацију (погледајте [[{{ns:4}}:ауторска права|ауторска права]] за детаље).
* Ако не желите да се ваше писање мења и редистрибуира без ограничења, онда га немојте слати овде.<br />
* Такође нам обећавате да сте га сами написали, или прекопирали из извора који је у јавном власништву или сличног слободног извора.
----
\'\'\'НЕ ШАЉИТЕ РАДОВЕ ЗАШТИЋЕНЕ АУТОРСКИМ ПРАВИМА БЕЗ ДОЗВОЛЕ!\'\'\'

</div>',
'copyrightwarning2' => 'Напомена: Сви доприноси {{ns:4}} могу да се мењају или уклоне од стране других корисника. Ако не желите да се ваши доприноси немилосрдно мењају, не шаљите их овде.<br />
Такође нам обећавате да сте ово сами написали или прекопирали из извора у јавном власништву или сличног слободног извора (видите $1 за детаље).
<strong>НЕ ШАЉИТЕ РАДОВЕ ЗАШТИЋЕНЕ АУТОРСКИМ ПРАВИМА БЕЗ ДОЗВОЛЕ!</strong>',
'couldntremove' => 'Не могу да уклоним \'$1\'...',
'createaccount' => 'Направи налог',
'createaccountmail' => 'e-поштом',
'createarticle' => 'Направи чланак',
'created' => 'направљен',
'creditspage' => 'Заслуге за страну',
'cur' => 'трен',
'currentevents' => 'Тренутни догађаји',
'currentevents-url' => 'Тренутни догађаји',
'currentrev' => 'Тренутна ревизија',
'currentrevisionlink' => 'прикажи тренутни преглед',
'data' => 'Подаци',
'databaseerror' => 'Грешка у бази',
'dateformat' => 'Формат датума',
'datedefault' => 'Није битно',
'dberrortext' => 'Десила се синтаксна грешка упита базе.
Ово је могуће због илегалног упита,
или могуће грешке у софтверу.
Последњи покушани упит је био:
<blockquote><tt>$1</tt></blockquote>
из функције "<tt>$2</tt>".
MySQL је вратио грешку "<tt>$3: $4</tt>".',
'dberrortextcl' => 'Десила се синтаксна грешка упита базе.
Последњи покушани упит је био:
"$1"
из функције "$2".
MySQL је вратио грешку "$3: $4".',
'deadendpages' => 'Странице без интерних веза',
'debug' => 'Исправи грешке',
'dec' => 'дец',
'december' => 'децембар',
'default' => 'стандард',
'defaultns' => 'Уобичајено тражи у овим именским просторима:',
'defemailsubject' => '{{SITENAME}} е-пошта',
'delete' => 'обриши',
'delete_and_move' => 'Обриши и премести',
'delete_and_move_reason' => 'Обрисан како би се направило место за премештање',
'delete_and_move_text' => '==Потребно брисање==

Циљани чланак "[[$1]]" већ постоји. Да ли желите да га обришете да бисте направили место за премештање?',
'deletecomment' => 'Разлог за брисање',
'deletedarticle' => 'обрисан "$1"',
'deletedrev' => '[обрисан]',
'deletedrevision' => 'Обрисана стара ревизија $1.',
'deletedtext' => 'Чланак "$1" је обрисан.
Погледајте $2 за запис о скорашњим брисањима.',
'deletedwhileediting' => 'Упозорење: Ова страна је обрисана пошто сте почели уређивање!',
'deleteimg' => 'обр',
'deleteimgcompletely' => 'обр',
'deletepage' => 'Обриши страницу',
'deletesub' => '(Бришем "$1")',
'deletethispage' => 'Обриши ову страницу',
'deletionlog' => 'историја брисања',
'dellogpage' => 'историја брисања',
'dellogpagetext' => 'Испод је списак најскоријих брисања.
Сва приказана времене су серверска (UTC).
<ul>
</ul>',
'destfilename' => 'Циљано име фајла',
'developertext' => 'Акцију коју сте затражили могу
извести само корисници са "девелопер" статусом.
Погледајте $1.',
'developertitle' => 'Неопходан је девелоперски приступ',
'diff' => 'разл',
'difference' => '(Разлика између ревизија)',
'disambiguations' => 'Странице за вишезначне одреднице',
'disambiguationspage' => '{{ns:10}}:Вишезначна одредница',
'disambiguationstext' => 'Следећи чланци се повезују са <i>вишезначном одредницом</i>. Уместо тога, они би требало да се повезују са одговарајућом темом.<br />Страница се третира да је вишезначна одредница ако је повезана са $1.<br />Повезнице из осталих именских простора <i>нису</i> наведене овде.',
'disclaimerpage' => '{{ns:4}}:Услови коришћења, правне напомене и одрицање одговорности',
'disclaimers' => 'Одрицање одговорности',
'doubleredirects' => 'Двострука преусмерења',
'doubleredirectstext' => '<b>Пажња:</b> Овај списак може да садржи лажне резултате. То обично значи да постоји додатни текст са везама испод првог #Redirect.<br />
Сваки ред садржи везе на прво и друго преусмерење, као и на прву линију текста другог преусмерења, што обично даје "прави" циљни чланак, на који би прво преусмерење и требало да показује.',
'download' => 'Преузми',
'eauthentsent' => 'Е-пошта за потврду је послата на номиновану адресу е-поште. Пре него што се било која друга е-пошта пошаље на налог, мораћете да пратите упутства у е-пошти, да бисте потврдили да је налог заиста ваш.',
'edit' => 'Уреди',
'edit-externally' => 'Измените овај фајл користећи спољашњу апликацију',
'edit-externally-help' => 'Погледајте [http://meta.wikimedia.org/wiki/Help:External_editors упутство за подешавање] за више информација.',
'editcomment' => 'Коментар измене је: "<i>$1</i>".',
'editconflict' => 'Сукобљене измене: $1',
'editcurrent' => 'Измени тренутну верзију ове странице',
'editgroup' => 'Мењај групу',
'edithelp' => 'Како се мења страна?',
'edithelppage' => '{{ns:4}}:Како се мења страна',
'editing' => 'Мењате $1',
'editingcomment' => 'Мењате $1 (коментар)',
'editingold' => '<strong>ПАЖЊА: Ви мењате старију
ревизију ове странице.
Ако је снимите, све промене учињене од ове ревизије биће изгубљене.</strong>',
'editingsection' => 'Мењате $1 (део)',
'editsection' => 'уреди',
'editthispage' => 'Уреди ову страницу',
'editusergroup' => 'Мењај групе корисника',
'email' => 'Е-пошта',
'emailauthenticated' => 'Ваша адреса е-поште је проверена на $1.',
'emailconfirmlink' => 'Потврдите вашу адресу е-поште',
'emailforlost' => '* Уношење адресе е-поште није обавезно. Међутим, унос ће омогућити људима да Вас контатирају кроз сајт, а да не морате да им откријете своју адресу. Такође ће Вам помоћи уколико заборавите вашу лозинку.',
'emailfrom' => 'Од',
'emailmessage' => 'Порука',
'emailnotauthenticated' => 'Ваша адреса е-поште <strong>још увек није потврђена</strong>. Е-пошта неће бити послата ни за једну од следећих могућности.',
'emailpage' => 'Пошаљи е-писмо кориснику',
'emailpagetext' => 'Ако је овај корисник унео исправну адресу е-поште у
своја корисничка подешавања, упитник испод ће послати једну поруку.
Адреса е-поште коју сте ви унели у своја корисничка подешавања ће се појавити
као "From" адреса поруке, тако да ће прималац моћи да одговори.',
'emailsend' => 'Пошаљи',
'emailsent' => 'Порука послата',
'emailsenttext' => 'Ваша порука је послата електронском поштом.',
'emailsubject' => 'Тема',
'emailto' => 'За',
'emailuser' => 'Пошаљи е-пошту овом кориснику',
'emptyfile' => 'Фајл који сте послали делује да је празан. Ово је могуће због грешке у имену фајла. Молимо проверите да ли стварно желите да пошаљете овај фајл.',
'enotif_body' => 'Драги $WATCHINGUSERNAME,

{{SITENAME}} страна $PAGETITLE је била $CHANGEDORCREATED $PAGEEDITDATE од стране $PAGEEDITOR,
погледајте {{SERVER}}{{localurl:$PAGETITLE_RAWURL}} за тренутну верзију.

$NEWPAGE

Резиме едитора: $PAGESUMMARY $PAGEMINOREDIT

Контактирајте едитора:
пошта {{SERVER}}{{localurl:Special:Emailuser|target=$PAGEEDITOR_RAWURL}}
вики {{SERVER}}{{localurl:User:$PAGEEDITOR_RAWURL}}

Неће бити других обавештења у случају даљих промена уколико не посетите ову страну.
Такође можете да ресетујете заставице за обавештења за све ваше надгледане стране на вашем списку надгледања.

             Ваш пријатељски {{SITENAME}} систем обавештавања

--
Да промените подешавања везана за списак надгледања посетите
{{SERVER}}{{localurl:Special:Watchlist|edit=yes}}

Фидбек и даља помоћ:
{{SERVER}}{{localurl:{{ns:12}}:Садржај}}',
'enotif_lastvisited' => 'Погледајте {{SERVER}}{{localurl:$PAGETITLE_RAWURL|diff=0&oldid=$OLDID}} за све промене од ваше последње посете.',
'enotif_mailer' => '{{SITENAME}} обавештење о пошти',
'enotif_newpagetext' => 'Ово је нови чланак.',
'enotif_reset' => 'Означи све стране као посећене',
'enotif_subject' => '{{SITENAME}} страна $PAGETITLE је била $CHANGEDORCREATED од стране $PAGEEDITOR',
'enterlockreason' => 'Унесите разлог за закључавање, укључујући процену
времена откључавања',
'error' => 'Грешка',
'errorpagetitle' => 'Грешка',
'exbeforeblank' => 'садржај пре брисања је био: \'$1\'',
'exblank' => 'страница је била празна',
'excontent' => 'садржај је био: \'$1\'',
'excontentauthor' => 'садржај је био: \'$1\' (а једину измену је направио \'$2\')',
'exif-aperturevalue' => 'Отвор бленде',
'exif-artist' => 'Аутор',
'exif-bitspersample' => 'Битова по компоненти',
'exif-brightnessvalue' => 'Светлост',
'exif-cfapattern' => 'CFA шаблон',
'exif-colorspace' => 'Простор боје',
'exif-componentsconfiguration' => 'Значење сваке од компоненти',
'exif-componentsconfiguration-0' => 'не постоји',
'exif-compressedbitsperpixel' => 'Мод компресије слике',
'exif-compression' => 'Шема компресије',
'exif-compression-1' => 'Некомпресован',
'exif-compression-6' => 'ЈПЕГ',
'exif-contrast' => 'Контраст',
'exif-contrast-0' => 'Нормално',
'exif-contrast-1' => 'Меко',
'exif-contrast-2' => 'Тврдо',
'exif-copyright' => 'Носилац права',
'exif-customrendered' => 'Додатна обрада слике',
'exif-customrendered-0' => 'Нормални процес',
'exif-customrendered-1' => 'Нестадардни процес',
'exif-datetime' => 'Датум последње промене фајла',
'exif-datetimedigitized' => 'Датум и време дигитализације',
'exif-datetimeoriginal' => 'Датум и време сликања',
'exif-devicesettingdescription' => 'Опис подешавања уређаја',
'exif-digitalzoomratio' => 'Однос дигиталног зума',
'exif-exifversion' => 'Exif верзија',
'exif-exposurebiasvalue' => 'Компензација експозиције',
'exif-exposureindex' => 'Индекс експозиције',
'exif-exposuremode' => 'Режим избора експозиције',
'exif-exposuremode-0' => 'Аутоматски',
'exif-exposuremode-1' => 'Ручно',
'exif-exposuremode-2' => 'Аутоматски са задатим распоном',
'exif-exposureprogram' => 'Програм експозиције',
'exif-exposureprogram-0' => 'Непознато',
'exif-exposureprogram-1' => 'Ручно',
'exif-exposureprogram-2' => 'Нормални програм',
'exif-exposureprogram-3' => 'Приоритет отвора бленде',
'exif-exposureprogram-4' => 'Приоритет затварача',
'exif-exposureprogram-5' => 'Уметнички програм (на бази нужне дубине поља)',
'exif-exposureprogram-6' => 'Спортски програм (на бази што бржег затварача)',
'exif-exposureprogram-7' => 'Портретни режим (за крупне кадрове са неоштром позадином)',
'exif-exposureprogram-8' => 'Режим пејзажа (за слике пејзажа са оштром позадином)',
'exif-exposuretime' => 'Експозиција',
'exif-filesource' => 'Изворни фајл',
'exif-filesource-3' => 'Дигитални фотоапарат',
'exif-flash' => 'Блиц',
'exif-flashenergy' => 'Енергија блица',
'exif-flashpixversion' => 'Подржана верзија Флешпикса',
'exif-fnumber' => 'F број отвора бленде',
'exif-focallength' => 'Фокусна даљина сочива',
'exif-focallengthin35mmfilm' => 'Еквивалент фокусне даљине за 35 mm филм',
'exif-focalplaneresolutionunit' => 'Јединица резолуције фокусне равни',
'exif-focalplaneresolutionunit-2' => 'инчи',
'exif-focalplanexresolution' => 'Водоравна резолуција фокусне равни',
'exif-focalplaneyresolution' => 'Хоризонатлна резолуција фокусне равни',
'exif-gaincontrol' => 'Контрола осветљености',
'exif-gaincontrol-0' => 'Нема',
'exif-gaincontrol-1' => 'Мало повећање',
'exif-gaincontrol-2' => 'Велико повећање',
'exif-gaincontrol-3' => 'Мало смањење',
'exif-gaincontrol-4' => 'Велико смањење',
'exif-gpsaltitude' => 'Висина',
'exif-gpsaltituderef' => 'Висина испод или изнад мора',
'exif-gpsareainformation' => 'Име ГПС подручја',
'exif-gpsdatestamp' => 'ГПС датум',
'exif-gpsdestbearing' => 'Азимут објекта',
'exif-gpsdestbearingref' => 'Индекс азимута објекта',
'exif-gpsdestdistance' => 'Удаљеност објекта',
'exif-gpsdestdistanceref' => 'Мерне јединице удаљености објекта',
'exif-gpsdestlatitude' => 'Географска ширина објекта',
'exif-gpsdestlatituderef' => 'Индекс географске ширине објекта',
'exif-gpsdestlongitude' => 'Географска дужина објекта',
'exif-gpsdestlongituderef' => 'Индекс географске дужине објекта',
'exif-gpsdifferential' => 'ГПС диференцијална корекција',
'exif-gpsdirection-m' => 'Магнетни правац',
'exif-gpsdirection-t' => 'Прави правац',
'exif-gpsdop' => 'Прецизност мерења',
'exif-gpsimgdirection' => 'Азимут слике',
'exif-gpsimgdirectionref' => 'Тип азимута слике (прави или магнетни)',
'exif-gpslatitude' => 'Ширина',
'exif-gpslatitude-n' => 'Север',
'exif-gpslatitude-s' => 'Југ',
'exif-gpslatituderef' => 'Северна или јужна ширина',
'exif-gpslongitude' => 'Дужина',
'exif-gpslongitude-e' => 'Исток',
'exif-gpslongitude-w' => 'Запад',
'exif-gpslongituderef' => 'Источна или западна дужина',
'exif-gpsmapdatum' => 'Коришћени геодетски координатни систем',
'exif-gpsmeasuremode' => 'Режим мерења',
'exif-gpsmeasuremode-2' => 'Дводимензионално мерење',
'exif-gpsmeasuremode-3' => 'Тродимензионално мерење',
'exif-gpsprocessingmethod' => 'Име методе обраде ГПС података',
'exif-gpssatellites' => 'Употребљени сателити',
'exif-gpsspeed' => 'Брзина ГПС пријемника',
'exif-gpsspeed-k' => 'Километри на час',
'exif-gpsspeed-m' => 'Миље на час',
'exif-gpsspeed-n' => 'Чворови',
'exif-gpsspeedref' => 'Јединица брзине',
'exif-gpsstatus' => 'Статус пријемника',
'exif-gpsstatus-a' => 'Мерење у току',
'exif-gpsstatus-v' => 'Спреман за пренос',
'exif-gpstimestamp' => 'Време по ГПС-у (атомски сат)',
'exif-gpstrack' => 'Азимут пријемника',
'exif-gpstrackref' => 'Тип азимута пријемника (прави или магнетни)',
'exif-gpsversionid' => 'Верзија блока ГПС-информације',
'exif-imagedescription' => 'Име слике',
'exif-imagelength' => 'Висина',
'exif-imageuniqueid' => 'Јединствени идентификатор слике',
'exif-imagewidth' => 'Ширина',
'exif-isospeedratings' => 'ИСО вредност',
'exif-jpeginterchangeformat' => 'Удаљеност ЈПЕГ прегледа од почетка фајла',
'exif-jpeginterchangeformatlength' => 'Количина бајтова ЈПЕГ прегледа',
'exif-lightsource' => 'Извор светлости',
'exif-lightsource-0' => 'Непознато',
'exif-lightsource-1' => 'Дневна светлост',
'exif-lightsource-10' => 'Облачно време',
'exif-lightsource-11' => 'Сенка',
'exif-lightsource-12' => 'Флуоресцентна светлост (D 5700 – 7100K)',
'exif-lightsource-13' => 'Флуоресцентна светлост (N 4600 – 5400K)',
'exif-lightsource-14' => 'Флуоресцентна светлост (W 3900 – 4500K)',
'exif-lightsource-15' => 'Бела флуоресценција (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Стандардно светло А',
'exif-lightsource-18' => 'Стандардно светло Б',
'exif-lightsource-19' => 'Стандардно светло Ц',
'exif-lightsource-2' => 'Флуоресцентно',
'exif-lightsource-24' => 'ИСО студијски волфрам',
'exif-lightsource-255' => 'Други извор светла',
'exif-lightsource-3' => 'Волфрам (светло)',
'exif-lightsource-4' => 'Блиц',
'exif-lightsource-9' => 'Лепо време',
'exif-make' => 'Произвођач камере',
'exif-makernote' => 'Напомене произвођача',
'exif-maxaperturevalue' => 'Минимални број отвора бленде',
'exif-meteringmode' => 'Режим мерача времена',
'exif-meteringmode-0' => 'Непознато',
'exif-meteringmode-1' => 'Просек',
'exif-meteringmode-2' => 'Просек са тежиштем на средини',
'exif-meteringmode-255' => 'Друго',
'exif-meteringmode-3' => 'Тачка',
'exif-meteringmode-4' => 'Више тачака',
'exif-meteringmode-5' => 'Матрични',
'exif-meteringmode-6' => 'Делимични',
'exif-model' => 'Модел камере',
'exif-oecf' => 'Оптоелектронски фактор конверзије',
'exif-orientation' => 'Оријентација кадра',
'exif-orientation-1' => 'Нормално',
'exif-orientation-2' => 'Обрнуто по хоризонтали',
'exif-orientation-3' => 'Заокренуто 180°',
'exif-orientation-4' => 'Обрнуто по вертикали',
'exif-orientation-5' => 'Заокренуто 90° супротно од смера казаљке на сату и обрнуто по вертикали',
'exif-orientation-6' => 'Заокренуто 90° у смеру казаљке на сату',
'exif-orientation-7' => 'Заокренуто 90° у смеру казаљке на сату и обрнуто по вертикали',
'exif-orientation-8' => 'Заокренуто 90° супротно од смера казаљке на сату',
'exif-photometricinterpretation' => 'Колор модел',
'exif-pixelxdimension' => 'Пуна ширина слике',
'exif-pixelydimension' => 'Пуна висина слике',
'exif-planarconfiguration' => 'Принцип распореда података',
'exif-planarconfiguration-1' => 'делимични формат',
'exif-planarconfiguration-2' => 'планарни формат',
'exif-primarychromaticities' => 'Хромацитет примарних боја',
'exif-referenceblackwhite' => 'Место беле и црне тачке',
'exif-relatedsoundfile' => 'Повезани звучни запис',
'exif-resolutionunit' => 'Јединица резолуције',
'exif-rowsperstrip' => 'Број редова у блоку',
'exif-samplesperpixel' => 'Број колор компонената',
'exif-saturation' => 'Сатурација',
'exif-saturation-0' => 'Нормално',
'exif-saturation-1' => 'Ниска сатурација',
'exif-saturation-2' => 'Висока сатурација',
'exif-scenecapturetype' => 'Тип сцене на снимку',
'exif-scenecapturetype-0' => 'Стандардно',
'exif-scenecapturetype-1' => 'Пејзаж',
'exif-scenecapturetype-2' => 'Портрет',
'exif-scenecapturetype-3' => 'Ноћно',
'exif-scenetype' => 'Тип сцене',
'exif-scenetype-1' => 'Директно фотографисана слика',
'exif-sensingmethod' => 'Тип сензора',
'exif-sensingmethod-1' => 'Недефинисано',
'exif-sensingmethod-2' => 'Једнокристални матрични сензор',
'exif-sensingmethod-3' => 'Двокристални матрични сензор',
'exif-sensingmethod-4' => 'Трокристални матрични сензор',
'exif-sensingmethod-5' => 'Секвенцијални матрични сензор',
'exif-sensingmethod-7' => 'Тробојни линеарни сензор',
'exif-sensingmethod-8' => 'Секвенцијални линеарни сензор',
'exif-sharpness' => 'Оштрина',
'exif-sharpness-0' => 'Нормално',
'exif-sharpness-1' => 'Меко',
'exif-sharpness-2' => 'Тврдо',
'exif-shutterspeedvalue' => 'Брзина затварача',
'exif-software' => 'Коришћен софтвер',
'exif-spatialfrequencyresponse' => 'Просторна фреквенцијска карактеристика',
'exif-spectralsensitivity' => 'Спектрална осетљивост',
'exif-stripbytecounts' => 'Величина компресованог блока',
'exif-stripoffsets' => 'Положај блока података',
'exif-subjectarea' => 'Положај и површина објекта снимка',
'exif-subjectdistance' => 'Удаљеност до објекта',
'exif-subjectdistance-value' => '$1 метара',
'exif-subjectdistancerange' => 'Распон удаљености субјеката',
'exif-subjectdistancerange-0' => 'Непознато',
'exif-subjectdistancerange-1' => 'Крупни кадар',
'exif-subjectdistancerange-2' => 'Блиски кадар',
'exif-subjectdistancerange-3' => 'Далеки кадар',
'exif-subjectlocation' => 'Положај субјекта',
'exif-subsectime' => 'Део секунде у којем је сликано',
'exif-subsectimedigitized' => 'Део секунде у којем је дигитализовано',
'exif-subsectimeoriginal' => 'Део секунде у којем је фотографисано',
'exif-transferfunction' => 'Функција преобликовања колор простора',
'exif-usercomment' => 'Кориснички коментар',
'exif-whitebalance' => 'Баланс беле боје',
'exif-whitebalance-0' => 'Аутоматски',
'exif-whitebalance-1' => 'Ручно',
'exif-whitepoint' => 'Хромацитет беле тачке',
'exif-xresolution' => 'Хоризонатална резолуција',
'exif-ycbcrcoefficients' => 'Матрични коефицијенти трансформације колор простора',
'exif-ycbcrpositioning' => 'Размештај компонената Y и C',
'exif-ycbcrsubsampling' => 'Однос компоненте Y према C',
'exif-yresolution' => 'Вертикална резолуција',
'expiringblock' => 'истиче $1',
'explainconflict' => 'Неко други је променио ову страницу откад сте ви почели да је мењате.
Горње текстуално поље садржи текст странице какав тренутно постоји.
Ваше измене су приказане у доњем тексту.
Мораћете да унесете своје промене у постојећи текст.
<b>Само</b> текст у горњем текстуалном пољу ће бити снимљен када
притиснете "Сними страницу".<br />',
'export' => 'Извези странице',
'exportcuronly' => 'Укључи само тренутну ревизију, не целу историју',
'exporttext' => 'Можете извести текст и историју промена одређене
странице или групе страница у XML формату; ово онда може бити увезено у други
вики који користи МедијаВики софтвер, трансформисано, или коришћено за ваше личне
потребе.',
'externaldberror' => 'Дошло је или до грешке при спољашњој аутентификацији базе података или вам није дозвољено да ажурирате свој спољашњи налог.',
'extlink_sample' => 'http://www.adresa.com опис адресе',
'extlink_tip' => 'спољашња повезница (запамти префикс http://)',
'faq' => 'НПП',
'faqpage' => '{{ns:4}}:НПП',
'feb' => 'феб',
'february' => 'фебруар',
'feedlinks' => 'Фид:',
'filecopyerror' => 'Не могу да ископирам фајл "$1" на "$2".',
'filedeleteerror' => 'Не могу да обришем фајл "$1".',
'filedesc' => 'Опис',
'fileexists' => 'Фајл са овим именом већ постоји. Молимо проверите $1 ако нисте сигурни да ли желите да га промените.',
'fileexists-forbidden' => 'Фајл са овим именом већ постоји; молимо вратите се и пошаљите овај фајл под новим именом. [[{{ns:6}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Фајл са овим именом већ постоји у заједничкој остави; молимо вратите се и пошаљите овај фајл под новим именом. [[{{ns:6}}:$1|thumb|center|$1]]',
'fileinfo' => '$1KB, МИМЕ тип: <code>$2</code>',
'filemissing' => 'Недостаје фајл',
'filename' => 'Име фајла',
'filenotfound' => 'Не могу да нађем фајл "$1".',
'filerenameerror' => 'Не могу да променим име фајла "$1" у "$2".',
'files' => 'Фајлови',
'filesource' => 'Извор',
'filestatus' => 'Статус ауторских права',
'fileuploaded' => 'Фајл "$1" је успешно послат.
Молим пратите ову везу: ($2) до странице за описивање и унесите
информације о фајлу, као одакле је, када и
ко га је направио, и било шта друго што знате о њему.',
'fileuploadsummary' => 'Опис:',
'formerror' => 'Грешка: не могу да пошаљем упитник',
'friday' => 'петак',
'getimagelist' => 'прибављам списак слика',
'go' => 'Иди',
'group-admin-desc' => 'Корисници којима се верује, могу да блокирају кориснике и бришу чланке',
'group-admin-name' => 'администратор',
'group-anon-desc' => 'Непознати корисници',
'group-anon-name' => 'Непознати',
'group-bureaucrat-desc' => 'Група бирократа може да додељује администраторска права',
'group-bureaucrat-name' => 'бирократа',
'group-loggedin-desc' => 'Пријављени корисници',
'group-loggedin-name' => 'Корисник',
'group-steward-desc' => 'Потпуни приступ',
'group-steward-name' => 'домаћин',
'groups' => 'Корисничке групе',
'groups-addgroup' => 'Додај групу',
'groups-already-exists' => 'Група тог имена већ постоји',
'groups-editgroup' => 'Мењај групу',
'groups-editgroup-description' => 'Опис групе (максимално 255 карактера):<br />',
'groups-editgroup-name' => 'Име групе:',
'groups-editgroup-preamble' => 'Ако име описа почиње са две тачке, остатак ће бити третиран као име поруке, па ће и текст да се локализује користећи МедијаВики именски простор',
'groups-existing' => 'Постојеће групе',
'groups-group-edit' => 'Постојеће групе:',
'groups-lookup-group' => 'Управљај правима група',
'groups-noname' => 'Молимо одредите правилно име групе',
'groups-tableheader' => 'ID || Име || Опис || Права',
'guesstimezone' => 'Попуни из браузера',
'headline_sample' => 'Наслов',
'headline_tip' => 'Поднаслов',
'help' => 'Помоћ',
'helppage' => '{{ns:12}}:Садржај',
'hide' => 'сакриј',
'hidetoc' => 'сакриј',
'hist' => 'ист',
'histfirst' => 'Најраније',
'histlast' => 'Последње',
'histlegend' => 'Објашњење: (трен) = разлика са тренутном верзијом,
(посл) = разлика са претходном верзијом, М = мала измена',
'history' => 'Историја странице',
'history_short' => 'историја',
'historywarning' => 'Пажња: страница коју желите да обришете има историју: ',
'hr_tip' => 'Хоризонтална линија',
'illegalfilename' => 'Фајл "$1" садржи карактере који нису дозвољени на овој страници. Молимо Вас промените име фајла и поново га пошаљите.',
'ilsubmit' => 'Тражи',
'image_sample' => 'име_слике.jpg',
'image_tip' => 'Уклопљена слика',
'imagelinks' => 'Употреба слике',
'imagelist' => 'Списак слика',
'imagelistall' => 'све',
'imagelisttext' => 'Испод је списак $1 слика поређаних $2.',
'imagemaxsize' => 'Ограничи слике на странама за разговор о сликама на:',
'imagepage' => 'Погледај страну слике',
'imagereverted' => 'Враћање на ранију верзију је успешно.',
'imgdelete' => 'обр',
'imgdesc' => 'опис',
'imghistlegend' => 'Објашњење: (трен) = ово је тренутна слика, (обр) = обриши
ову стару верзију, (врт) = врати на ову стару верзију.
<br /><i>Кликните на датум да видите слику послату тог датума</i>.',
'imghistory' => 'Историја слике',
'imglegend' => 'Објашњење: (опис) = прикажи/измени опис слике.',
'immobile_namespace' => 'Циљани назив је посебног типа; не могу да преместим стране у тај именски простор.',
'import' => 'Увоз страница',
'importfailed' => 'Увоз није успео: $1',
'importhistoryconflict' => 'Постоји конфликтна историја ревизија',
'importinterwiki' => 'Трансвики увожење',
'importnosources' => 'Није дефинисан ниједан извор трансвики увожења и директна слања историја су онемогућена.',
'importnotext' => 'Страница је празна или без текста.',
'importsuccess' => 'Успешно сте увезли страницу!',
'importtext' => 'Молимо извезите фајл из изворног викија користећи [[{{ns:-1}}:Export|извоз]], сачувајте га код себе и пошаљите овде.',
'infiniteblock' => 'бесконачан',
'info_short' => 'Информације',
'infosubtitle' => 'Информације за страницу',
'internalerror' => 'Интерна грешка',
'intl' => 'Међујезичке везе',
'invalidemailaddress' => 'Адреса е-поште не може да се прими јер није правилног формата. Молимо унесите добро-форматирану адресу или испразните то поље.',
'invert' => 'Обрни селекцију',
'ip_range_invalid' => 'Нетачан распон ИП адреса.',
'ipaddress' => 'ИП адреса/корисничко име',
'ipadressorusername' => 'ИП адреса или корисничко име',
'ipb_expiry_invalid' => 'Погрешно време трајања.',
'ipbexpiry' => 'Трајање',
'ipblocklist' => 'Списак блокираних ИП адреса и корисника',
'ipblocklistempty' => 'Списак блокова је празан.',
'ipboptions' => '2 сата:2 hours,1 дан:1 day,3 дана:3 days,1 недеља:1 week,2 недеље:2 weeks,1 месец:1 month,3 месеца:3 months,6 месеци:6 months,1 година:1 year,бесконачно:infinite',
'ipbother' => 'Остало време',
'ipbotheroption' => 'остало',
'ipbreason' => 'Разлог',
'ipbsubmit' => 'Обуздај овог корисника',
'ipusubmit' => 'Отпусти ову адресу',
'ipusuccess' => '"$1" отпуштен',
'isredirect' => 'Преусмеривач',
'italic_sample' => 'курзиван текст',
'italic_tip' => 'курзиван текст',
'iteminvalidname' => 'Проблем са \'$1\', неисправно име...',
'jan' => 'јан',
'january' => 'јануар',
'jul' => 'јул',
'july' => 'јул',
'jun' => 'јун',
'june' => 'јун',
'laggedslavemode' => 'Упозорење: Могуће је да страна није скоро ажурирана.',
'largefile' => 'Препоручује се да слике не пређу величину од 100К.',
'largefileserver' => 'Овај фајл је већи него што је подешено да сервер дозволи.',
'last' => 'посл',
'lastmodified' => 'Ова страница је последњи пут измењена $1.',
'lastmodifiedby' => 'Ову страницу је последњи пут променио $2, дана $1.',
'license' => 'Лиценца',
'lineno' => 'Линија $1:',
'link_sample' => 'наслов повезнице',
'link_tip' => 'унутрашља повезница',
'linklistsub' => '(списак веза)',
'linkshere' => 'Следеће странице су повезане овде:',
'linkstoimage' => 'Следеће странице користе ову слику:',
'linktrail' => "/^([абвгдђежзијклљмнњопрстћуфхцчџш]+)(.*)$/usD",
'listform' => 'списак',
'listingcontinuesabbrev' => ' наст.',
'listusers' => 'Списак корисника',
'loadhist' => 'Учитавам историју странице',
'loadingrev' => 'учитавам ревизију за разлику',
'localtime' => 'Приказ локалног времена',
'lockbtn' => 'Закључај базу',
'lockconfirm' => 'Да, заиста желим да закључам базу.',
'lockdb' => 'Закључај базу',
'lockdbsuccesssub' => 'База је закључана',
'lockdbsuccesstext' => '{{ns:4}} база података је закључана.
<br />Сетите се да је откључате када завршите са одржавањем.',
'lockdbtext' => 'Закључавање базе ће свим корисницима укинути могућност измене страница,
промене корисничких подешавања, измене списка надгледања, и свега осталог
што захтева промене у бази.
Молим потврдите да је ово заиста оно што намеравате да урадите, и да ћете
откључати базу када завршите посао око њеног одржавања.',
'locknoconfirm' => 'Нисте потврдили своју намеру.',
'log' => 'Протоколи',
'login' => 'Пријави се',
'loginend' => '\'\'\'Регистровање бесплатних налога вам одузима само неколико секунди, и има многе предности\'\'\'

*\'\'\'За регистрацију, изаберите корисничко име и шифру и кликните "направи налог".\'\'\'
*Избегавајте корисничка имена која су неприкладна или збуњујућа.
*Молимо Вас изаберите читка имена, а не бројеве.
*Корисничка имена морају почињати великим словом.
*Избегавајте корисничка имена која су име вашег политичког вође, партије, славне личности и других.
</div>

\'\'\'Регистровани корисници морају само попунити корисничко име и шифру.\'\'\'

*Морате имати одобрене [[HTTP cookie|колачиће]] (\'\'\'cookies\'\'\') да би сте приступили на пројекат {{ns:4}}.
*[[{{ns:12}}:Како да се региструјем|Погледајте више о регистрацији]].

Адреса е-поште није обавезна. Уколико одаберете да унесете адресу е-поште, то вам омогућва да остали корисници могу да вам шаљу поруке без знања ваше праве адресе е-поште, и дозвољава вам да уколико заборавите лозинку можете да је повратите. \'\'\'Нико не може да види адресу ваше е-поште.\'\'\'',
'loginerror' => 'Грешка при пријављивању',
'loginpagetitle' => 'Пријављивање',
'loginproblem' => '<b>Било је проблема са вашим пријављивањем.</b><br />Пробајте поново!',
'loginprompt' => 'Морате да имате омогућене колачиће (\'\'\'cookies\'\'\') да бисте се пријавили на {{SITENAME}}.',
'loginreqlink' => 'пријава',
'loginreqpagetext' => 'Морате $1 да бисте видели остале стране.',
'loginreqtitle' => 'Потребно [[Special:Userlogin|пријављивање]]',
'loginsuccess' => 'Сада сте пријављени на {{SITENAME}} као "$1".',
'loginsuccesstitle' => 'Пријављивање успешно',
'logout' => 'Одјави се',
'logouttext' => 'Сада сте одјављени. Можете да наставите да користите пројекат {{SITENAME}} анонимно, или се поново пријавити као други корисник. Обратите пажњу да неке странице могу наставити да се приказују као да сте још увек пријављени, док не очистите кеш свог браузера.',
'logouttitle' => 'Одјави се',
'lonelypages' => 'Сирочићи',
'longpages' => 'Дугачке странице',
'longpagewarning' => '\'\'\'ПАЖЊА:\'\'\' Ова страница има $1 килобајта. Молимо вас да размотрите разбијање странице на мање делове.',
'mailerror' => 'Грешка при слању е-поште: $1',
'mailmypassword' => 'Пошаљи ми нову лозинку',
'mailnologin' => 'Нема адресе за слање',
'mailnologintext' => 'Морате бити [[Special:Userlogin|пријављени]]
и имати исправну адресу е-поште у вашим [[Special:Preferences|подешавањима]]
да бисте слали електронску пошту другим корисницима.',
'mainpage' => 'Главна страна',
'mainpagedocfooter' => 'Молимо видите [http://meta.wikimedia.org/wiki/MediaWiki_i18n документацију о подешавању интерфејса вашим потребама] и [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide кориснички водич] за коришћење и помоћ при конфигурисању.',
'mainpagetext' => 'Вики софтвер је успешно инсталиран.',
'maintenance' => 'Страница за одржавање',
'maintenancebacklink' => 'Назад на страницу за одржавање',
'maintnancepagetext' => 'Ова страница садржи неколико згодних алатки за свакодневно одржавање. Неке од њих могу заморити базу, па вас молимо да не учитавате поново после сваке ставке коју сте средили ;-)',
'makesysop' => 'Давање администраторских овлашћења кориснику',
'makesysopfail' => '<b>Корисник "$1" не може да постане администратор. (Да ли сте правилно унели име?)</b>',
'makesysopname' => 'Име корисника:',
'makesysopok' => '<b>Корисник "$1" је сада администратор</b>',
'makesysopsubmit' => 'Додајте овом кориснику администраторска овлашћења',
'makesysoptext' => 'Овај формулар се користи од стране бирократа да се обични корисници претворе у администраторе. Унесите име корисника у кутију и притисните дугме да би корисник постао администратор',
'makesysoptitle' => 'Претворите корисника у администратора',
'mar' => 'мар',
'march' => 'март',
'markaspatrolleddiff' => 'Означи као патролирано',
'markaspatrolledtext' => 'Означи овај чланак као патролиран',
'markedaspatrolled' => 'Означи као патролирано',
'markedaspatrolledtext' => 'Изабрана ревизија је означена као патролирана.',
'matchtotals' => 'Упит "$1" је нађен у $2 наслова чланака
и текст $3 чланака.',
'math' => 'Приказивање математике',
'math_bad_output' => 'Не могу да напишем или направим директоријум за math извештај.',
'math_bad_tmpdir' => 'Не могу да напишем или направим привремени math директоријум',
'math_failure' => 'Неуспех при парсирању',
'math_image_error' => 'PNG конверзија неуспешна; проверите тачну инсталацију latex-а, dvips-а, gs-а и convert-а',
'math_lexing_error' => 'речничка грешка',
'math_notexvc' => 'Недостаје извршно texvc; молимо погледајте math/README да бисте подесили.',
'math_sample' => 'Овде унесите формулу',
'math_syntax_error' => 'синтаксна грешка',
'math_tip' => 'Математичка формула (LaTeX)',
'math_unknown_error' => 'непозната грешка',
'math_unknown_function' => 'непозната функција ',
'may' => 'мај',
'may_long' => 'мај',
'media_sample' => 'име_медија_фајла.mp3',
'media_tip' => 'Путања ка мултимедијалном фајлу',
'mediawarning' => '\'\'\'Упозорење\'\'\': Овај фајл садржи лош код, његовим извршавањем можете да угрозите ваш систем.
<hr>',
'metadata' => 'Метаподаци',
'metadata_page' => '{{ns:project}}:Метаподаци',
'mimesearch' => 'МИМЕ претрага',
'mimetype' => 'МИМЕ тип:',
'minlength' => 'Имена слика морају имати бар три слова.',
'minoredit' => 'Ово је мала измена',
'minoreditletter' => 'М',
'mispeelings' => 'Странице са грешкама у куцању',
'mispeelingspage' => 'Списак честих грешака у куцању',
'mispeelingstext' => 'Следеће странице садрже честе грешке у куцању, које су наведене на $1. Исправне речи могу бити дате (овако).',
'missingarticle' => 'База није нашла текст странице
који је требало, назван "$1".

<p>Ово је обично изазвано праћењем застарелог "разл" или везе ка историји
странице која је обрисана.

<p>Ако ово није случај, можда сте пронашли грешку у софтверу.
Молимо вас пријавите ово једном од [[{{ns:4}}:Администратори|администратора]], заједно са УРЛ-ом.',
'missingimage' => '<b>Овде недостаје слика</b><br /><i>$1</i>',
'missinglanguagelinks' => 'Недостајући језичке везе',
'missinglanguagelinksbutton' => 'Нађи недостајуће језичке везе за',
'missinglanguagelinkstext' => 'Ови чланци <i>нису</i> повезани са њима одговарајућим у $1. Преусмерења и подстранице <i>нису</i> приказани.',
'monday' => 'понедељак',
'moredotdotdot' => 'Још...',
'mostlinked' => 'Највише повезане стране',
'move' => 'премести',
'movearticle' => 'Премести страницу',
'movedto' => 'премештена на',
'movelogpage' => 'историја премештања',
'movelogpagetext' => 'Испод је списак премештених чланака.',
'movenologin' => 'Нисте улоговани',
'movenologintext' => 'Морате бити регистровани корисник и [[Special:Userlogin|пријављени]]
да бисте преместили страницу.',
'movepage' => 'Премештање странице',
'movepagebtn' => 'премести страницу',
'movepagetalktext' => 'Одговарајућа страница за разговор, ако постоји, ће бити аутоматски премештена истовремено \'\'\'осим:\'\'\'
*Ако премештате страницу преко именских простора,
*Непразна страница за разговор већ постоји под новим именом, или
*Одбележите доњу кућицу.

У тим случајевима, мораћете ручно да преместите страницу уколико то желите.',
'movepagetext' => 'Доњи упитник ће преименовати страницу, премештајући сву
њену историју на ново име.
Стари наслов ће постати преусмерење на нови наслов.
Повезнице према старом наслову неће бити промењене; обавезно
потражите [[{{ns:-1}}:DoubleRedirects|двострука]] или [[{{ns:-1}}:BrokenRedirects|покварена преусмерења]].
На вама је одговорност да везе и даље иду тамо где би и требало да иду.

Обратите пажњу да страница \'\'\'неће\'\'\' бити померена ако већ постоји
страница са новим насловом, осим ако је она празна или преусмерење и нема
историју промена. Ово значи да не можете преименовати страницу на оно име
са кога сте је преименовали ако погрешите, и не можете преписати
постојећу страницу.

<b>ПАЖЊА!</b>
Ово може бити драстична и неочекивана промена за популарну страницу;
молимо да будете сигурни да разумете последице овога пре него што
наставите.',
'movereason' => 'Разлог',
'movetalk' => 'Премести "страницу за разговор" такође, ако је могуће.',
'movethispage' => 'премести ову страницу',
'mw_math_html' => 'HTML ако је могуће, иначе PNG',
'mw_math_mathml' => 'MathML ако је могуће (експериментално)',
'mw_math_modern' => 'Препоручено за савремене браузере',
'mw_math_png' => 'Увек прикажи PNG',
'mw_math_simple' => 'HTML ако је врло једноставно, иначе PNG',
'mw_math_source' => 'Остави као ТеХ (за текстуалне браузере)',
'mycontris' => 'Моји прилози',
'mypage' => 'Моја страница',
'mytalk' => 'Мој разговор',
'namespace' => 'Именски простор:',
'namespacesall' => 'сви',
'nbytes' => '$1 бајтова',
'nchanges' => '$1 промена',
'newarticle' => '(Нови)',
'newarticletext' => '\'\'\'{{SITENAME}} још увек нема {{NAMESPACE}} чланак под именом {{PAGENAME}}.\'\'\'
* Да бисте започели страницу, почните да куцате у пољу испод. Ако сте овде дошли грешком, само притисните \'\'\'back\'\'\' дугме вашег браузера.<br /> \'\'Погледајте [[Помоћ:Садржај|\'\'\'\'\'помоћ\'\'\'\'\']] за више информација.\'\'',
'newbies' => 'новајлије',
'newimages' => 'Галерија нових слика',
'newmessageslink' => 'нових порука',
'newpage' => 'Нова страница',
'newpageletter' => 'Н',
'newpages' => 'Нове странице',
'newpassword' => 'Нова шифра',
'newtitle' => 'Нови наслов',
'newusersonly' => '(само за нове кориснике)',
'newwindow' => '(нови прозор)',
'next' => 'след',
'nextdiff' => 'Следећа измена →',
'nextn' => 'следећих $1',
'nextpage' => 'Следећа страница ($1)',
'nextrevision' => 'Следећа ревизија →',
'nlinks' => '$1 веза',
'noarticletext' => '\'\'\'{{SITENAME}} још увек нема чланак под тим именом.\'\'\'
* \'\'\'[{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} Почни {{PAGENAME}} чланак]\'\'\'
* [[{{ns:-1}}:Search/{{PAGENAME}}|Претражи {{PAGENAME}}]] у осталим чланцима
* [[{{ns:-1}}:Whatlinkshere/{{NAMESPACE}}:{{PAGENAME}}|Странице које су повезане за]] {{PAGENAME}} чланак
----
* \'\'\'Уколико сте направили овај чланак у последњих неколико минута и још се није појавио, постоји погућност да је сервер у застоју због освежавања базе података.\'\'\' Молимо Вас пробајте са [{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=purge}} освежавањем] или сачекајте и проверите касније поново пре поновног прављења чланка.',
'noconnect' => 'Жалимо! Вики има неке техничке потешкоће, и не може да се повеже се сервером базе.',
'nocontribs' => 'Нису нађене промене које задовољавају ове услове.',
'nocookieslogin' => '{{SITENAME}} користи колачиће (\'\'cookies\'\') да би се корисници пријавили. Ви сте онемогућили колачиће на Вашем рачунару. Молимо омогућите их и покушајте поново са пријавом.',
'nocookiesnew' => 'Кориснички налог је направљен, али нисте пријављени. {{SITENAME}} користи колачиће (\'\'cookies\'\') да би се корисници пријавили. Ви сте онемогућили колачиће на свом рачунару. Молимо омогућите их, а онда се пријавите са својим новим корисничким именом и лозинком.',
'nocreativecommons' => 'Creative Commons RDF метаподаци онемогућени за овај сервер.',
'nocredits' => 'Нису доступне информације о заслугама за ову страну.',
'nodb' => 'Не могу да изаберем базу $1',
'nodublincore' => 'Dublin Core RDF метаподаци онемогућени за овај сервер.',
'noemail' => 'Не постоји адреса е-поште за корисника "$1".',
'noemailprefs' => '<strong>Није дата ни једна адреса е-поште</strong>, наредна опција
неће радити.',
'noemailtext' => 'Овај корисник није навео исправну адресу е-поште,
или је изабрао да не прима е-пошту од других корисника.',
'noemailtitle' => 'Нема адресе е-поште',
'nogomatch' => 'Не постоји страница са оваквим насловом.

Можете [[$1|написати чланак]] са овим насловом.

Молимо Вас претражите Википедију, пре креирања чланка да бисмо избегли дуплирање већ постојећег.',
'nohistory' => 'Не постоји историја измена за ову страницу.',
'noimage' => 'Не постоји фајл са овим именом, $1',
'noimage-linktext' => 'пошаљи га',
'noimages' => 'Нема ништа да се види',
'nolicense' => 'Нема',
'nolinkshere' => 'Ни једна страница није повезана овде.',
'nolinkstoimage' => 'Нема страница које користе ову слику.',
'noname' => 'Нисте изабрали исправно корисничко име.',
'nonefound' => '<strong>Пажња</strong>: неуспешне претраге су
често изазване тражењем честих речи као "је" или "од",
које нису индексиране, или навођењем више од једног израза за тражење (само странице
које садрже све изразе који се траже ће се појавити у резултату).',
'nonunicodebrowser' => '<strong>УПОЗОРЕЊЕ: Ваш интернет претраживач не подржава уникод. Молимо промените га пре него што почнете са уређивањем чланка.</strong>',
'nospecialpagetext' => 'Тражили сте посебну страницу, коју {{SITENAME}} софтвер није препознао.',
'nosuchaction' => 'Нема такве акције',
'nosuchactiontext' => 'Акција наведена у УРЛ-у није
препозната од стране {{SITENAME}} софтвера.',
'nosuchspecialpage' => 'Нема такве посебне странице',
'nosuchuser' => 'Не постоји корисник са именом "$1".
Проверите ваше куцање, или употребите доњи упитник да направите нови кориснички налог.',
'nosuchusershort' => 'Не постоји корисник "$1". Проверите да ли сте добро написали.',
'notacceptable' => 'Вики сервер не може да пружи податке у оном формату који ваш клијент може да их прочита.',
'notanarticle' => 'Није чланак',
'notargettext' => 'Нисте навели циљну страницу или корисника
на коме би се извела ова функција.',
'notargettitle' => 'Нема циља',
'note' => '<strong>Пажња:</strong> ',
'notextmatches' => 'Ниједан текст чланка не одговара',
'notitlematches' => 'Ниједан наслов чланка не одговара',
'notloggedin' => 'Нисте пријављени',
'nov' => 'нов',
'november' => 'новембар',
'nowatchlist' => 'Немате ништа на свом списку надгледања.',
'nowiki_sample' => 'Додај неформатирани текст овде',
'nowiki_tip' => 'Игнориши Вики форматирање текст',
'nstab-category' => 'Категорија',
'nstab-help' => 'Помоћ',
'nstab-image' => 'Слика',
'nstab-main' => 'Чланак',
'nstab-media' => 'Медиј',
'nstab-mediawiki' => 'Порука',
'nstab-special' => 'Посебна',
'nstab-template' => 'Шаблон',
'nstab-user' => 'Корисничка страна',
'nstab-wp' => 'Чланак',
'numauthors' => 'Број различитих аутора (чланак): $1',
'number_of_watching_users_pageview' => '[$1 корисник/а који надгледа/ју]',
'numedits' => 'Број промена (чланак): $1',
'numtalkauthors' => 'Број различитих аутора (страница за разговор): $1',
'numtalkedits' => 'Број промена (страница за разговор): $1',
'numwatchers' => 'Број посматрача: $1',
'nviews' => '$1 пута погледано',
'oct' => 'окт',
'october' => 'октобар',
'ok' => 'да',
'oldpassword' => 'Стара лозинка',
'orig' => 'ориг',
'orphans' => 'Сирочићи',
'othercontribs' => 'Базирано на раду од стране корисника $1.',
'otherlanguages' => 'Остали језици',
'others' => 'остали',
'pagemovedsub' => 'Премештање успело',
'pagemovedtext' => 'Страница "[[$1]]" премештена је на "[[$2]]".',
'passwordremindertext' => 'Неко (вероватно ви, са ИП адресе $1) је захтевао да вам пошаљемо нову шифру за пријављивање на {{SITENAME}}. Шифра за корисника "$2" је сада "$3". Сада треба да се пријавите и промените своју шифру.',
'passwordremindertitle' => '{{SITENAME}} подсетник за шифру',
'passwordsent' => 'Нова шифра је послата на адресу е-поште корисника "$1".
Молимо вас да се пријавите пошто је примите.',
'passwordtooshort' => 'Ваша шифра је превише кратка. Мора да има бар $1 карактера.',
'perfcached' => 'Следећи подаци су кеширани и не морају бити у потпуности ажурирани:',
'perfdisabled' => 'Жалимо! Ова могућност је привремено онемогућена јер успорава базу до те мере да више нико не може да користи вики.',
'perfdisabledsub' => 'Овде је снимљена копија $1:',
'permalink' => 'Пермалинк',
'personaltools' => 'Лични алати',
'popularpages' => 'Популарне странице',
'portal' => 'Трг',
'portal-url' => 'Project:Трг',
'postcomment' => 'Пошаљи коментар',
'poweredby' => '{{SITENAME}} је омогућена од стране [http://www.mediawiki.org/ МедијаВикија], вики машине слободног кода.',
'powersearch' => 'Тражи',
'powersearchtext' => 'Претрага у именским просторима:<br />
$1<br />
$2 Излистај преусмерења &nbsp; Тражи $3 $9',
'preferences' => 'Подешавања',
'prefixindex' => 'Списак префикса',
'prefs-help-email' => '² Е-пошта (опционо): Омогућује осталима да вас контактирају преко ваше корисничке стране или стране разговора са корисником без потребе да одајете свој идентитет.',
'prefs-help-email-enotif' => 'Ова адреса се такође користи да вам се шаљу обавештења преко е-поште ако сте омогућили ту опцију.',
'prefs-help-realname' => '¹ Право име (опционо): ако изаберете да дате име, ово ће бити коришћено за приписивање за ваш рад.',
'prefs-misc' => 'Разна подешавања',
'prefs-personal' => 'Корисничка подешавања',
'prefs-rc' => 'Подешавање скорашњих измена',
'prefsnologin' => 'Нисте пријављени',
'prefsnologintext' => 'Морате бити [[{{ns:-1}}:Userlogin|пријављени]]
да бисте подешавали корисничка подешавања.',
'prefsreset' => 'Враћена су ускладиштена подешавања.',
'preview' => 'Претпреглед',
'previewconflict' => 'Овај претпреглед осликава како ће текст у
текстуалном пољу изгледати ако се одлучите да га снимите.',
'previewnote' => 'Запамтите да је ово само претпреглед, и да још није снимљен!',
'previousdiff' => '← Претходна измена',
'previousrevision' => '← Претходна ревизија',
'prevn' => 'претходних $1',
'print' => 'Штампа',
'printableversion' => 'Верзија за штампу',
'printsubtitle' => '(Са {{SERVER}})',
'protect' => 'заштити',
'protectcomment' => 'Разлог заштите',
'protectedarticle' => 'заштићено $1',
'protectedpage' => 'Заштићена страница',
'protectedpagewarning' => '\'\'\'ПАЖЊА:\'\'\' Ова страница је закључана тако да само корисници са
администраторским привилегијама могу да је мењају. Уверите се
да пратите [[{{ns:4}}:Правила о заштити страница|правила о заштити страница]].',
'protectedtext' => 'Можете гледати и копирати садржај ове стране:',
'protectlogpage' => 'историја закључавања',
'protectlogtext' => 'Испод је списак заштићених страница. <br />
Погледајте [[{{ns:4}}:Правила о заштити страница|правила о заштити страница]] за више информација.',
'protectmoveonly' => 'Заштићено само од померања',
'protectpage' => 'Заштити страницу',
'protectsub' => '(Заштићујем "$1")',
'protectthispage' => 'Заштити ову страницу',
'proxyblocker' => 'Блокер проксија',
'proxyblockreason' => 'Ваша ИП адреса је блокирана јер је она отворени прокси. Молимо контактирајте вашег Интернет сервис провајдера или техничку подршку и обавестите их о овом озбиљном сигурносном проблему.',
'proxyblocksuccess' => 'Прокси успешно блокиран.',
'qbbrowse' => 'Прелиставај',
'qbedit' => 'Измени',
'qbfind' => 'Пронађи',
'qbmyoptions' => 'Моје опције',
'qbpageinfo' => 'Информације о страници',
'qbpageoptions' => 'Опције странице',
'qbsettings' => 'Подешавања брзе палете',
'qbspecialpages' => 'Посебне странице',
'randompage' => 'Случајна страница',
'randompage-url' => '{{ns:-1}}:Random',
'range_block_disabled' => 'Администраторска могућност да блокира ИП групе је искључена.',
'rchide' => 'у $4 облику; $1 мале измене; $2 секундарни именски простори; $3 вишеструке измене.',
'rclinks' => 'Покажи последњих $1 промена у последњих $2 дана; $3 мале измене',
'rclistfrom' => 'Покажи нове промене почев од $1',
'rcliu' => '; $1 измена од пријављених корисника',
'rcloaderr' => 'Учитавам скорашње измене',
'rclsub' => '(на странице повезане од "$1")',
'rcnote' => 'Испод је последњих <strong>$1</strong> промена у последњих <strong>$2</strong> дана.',
'rcnotefrom' => 'Испод су промене од <b>$2</b> (до <b>$1</b> приказано).',
'rcpatroldisabled' => 'Патрола скорашњих измена онемогућена',
'rcpatroldisabledtext' => 'Патрола скорашњих измена је тренутно онемогућена.',
'readonly' => 'База је закључана',
'readonly_lag' => 'База података је аутоматски закључана док слејв сервери не сустигну мастер',
'readonlytext' => 'Википедијина база је тренутно закључана за нове
уносе и остале измене, вероватно због рутинског одржавања,
после чега ће бити враћена у уобичајено стање.
Администратор који ју је закључао понудио је ово објашњење:
<p>$1',
'readonlywarning' => '\'\'\'ПАЖЊА:\'\'\' База је управо закључана због одржавања,
тако да нећете моћи да снимите своје измене управо сада. Можда желите да ископирате и налепите
текст у текст едитор и снимите га за касније.',
'recentchanges' => 'Скорашње измене',
'recentchanges-url' => '{{ns:-1}}:Recentchanges',
'recentchangesall' => 'све',
'recentchangescount' => 'Број наслова у скорашњим променама',
'recentchangeslinked' => 'Сродне промене',
'recentchangestext' => '[[{{ns:4}}:Добродошли|Добродошли]]!<br />
Овде пратите најскорије измене на Википедији.<br />
Википедија на српском језику тренутно има [[{{ns:-1}}:Statistics|\'\'\'{{NUMBEROFARTICLES}}\'\'\']] чланака.<br /> {{{{ns:4}}:Recentchanges}}',
'recreate' => 'Поново направи',
'redirectedfrom' => '(Преусмерено са $1)',
'remembermypassword' => 'Запамти ме',
'removechecked' => 'Уклони обележене уносе из списка надгледања',
'removedwatch' => 'Уклоњено из списка надгледања',
'removedwatchtext' => 'Страница "$1" је уклоњена из вашег списка надгледања.',
'removingchecked' => 'Уклањам обележене ствари са списка надгледања...',
'renamegrouplogentry' => 'Група $2 преименована у $3',
'resetprefs' => 'Врати подешавања',
'restorelink' => '$1 обрисаних измена',
'restorelink1' => 'једну обрисану измену',
'restrictedpheading' => 'Заштићене посебне странице',
'resultsperpage' => 'Погодака по страници',
'retrievedfrom' => 'Добављено из "$1"',
'returnto' => 'Повратак на $1.',
'retypenew' => 'Поново откуцајте нову лозинку',
'reupload' => 'Поново пошаљи',
'reuploaddesc' => 'Врати се на упитник за слање.',
'reverted' => 'Враћено на ранију ревизију',
'revertimg' => 'врт',
'revertmove' => 'врати',
'revertpage' => 'Враћено на последњу измену од корисника $1',
'revhistory' => 'Историја измена',
'revisionasof' => 'Ревизија од $1',
'revisionasofwithlink' => 'Ревизија од $1; $2<br />$3 | $4',
'revnotfound' => 'Ревизија није пронађена',
'revnotfoundtext' => 'Старија ревизија ове странице коју сте затражили није нађена.
Молимо вас да проверите УРЛ који сте употребили да бисте приступили овој страници.',
'rights' => 'Права:',
'rightslogtext' => 'Ово је историја измена корисничких права.',
'rollback' => 'Врати измене',
'rollback_short' => 'Врати',
'rollbackfailed' => 'Враћање није успело',
'rollbacklink' => 'врати',
'rows' => 'Редова',
'saturday' => 'субота',
'savearticle' => 'Сними страницу',
'savedprefs' => 'Ваша подешавања су снимљена.',
'savefile' => 'Сними фајл',
'savegroup' => 'Сними групу',
'saveprefs' => 'Сними подешавања',
'saveusergroups' => 'Сачувај корисничке групе',
'scarytranscludedisabled' => '[Интервики трансклузија је онемогућена]',
'scarytranscludefailed' => '[Доношење шаблона неуспешно; жао нам је]',
'scarytranscludetoolong' => '[УРЛ је предугачак; жао нам је]',
'search' => 'Тражи',
'searchdisabled' => '<p>Извињавамо се! Пуна претрага текста је привремено онемогућена, због бржег рада Википедије. У међувремену, можете користити Гугл претрагу испод, која може бити застарела.</p>',
'searchfulltext' => 'Претражи цео текст',
'searchquery' => 'Тражили сте "<a href="/wiki/$1">$1</a>" <a href="/wiki/Special:Allpages/$1">[Садржај]</a>',
'searchresults' => 'Резултати претраге',
'searchresultshead' => 'Подешавања резултата претраге',
'searchresulttext' => '<!--
За више информација о претраживању Википедије, погледајте [[{{ns:4}}:Тражење|Претраживање Википедије]].
-->',
'selectnewerversionfordiff' => 'Изабери новију верзију за упоређивање',
'selectolderversionfordiff' => 'Изабери старију верзију за упоређивање',
'selflinks' => 'Странице са самовезама',
'selflinkstext' => 'Следеће странице садрже везе на саме себе, што не би требало.',
'selfmove' => 'Изворни и циљани назив су исти; страна не може да се премести преко саме себе.',
'sep' => 'сеп',
'september' => 'септембар',
'servertime' => 'Време на серверу је сада',
'sessionfailure' => 'Изгледа да постоји проблем са вашом сеансом пријаве;
ова акција је прекинута као предострожност против преотимања сеанси.
Молимо кликните "back" и поново учитајте страну одакле сте дошли, а онда покушајте поново.',
'set_rights_fail' => '<b>Корисничка права за "$1" нису могла да се подесе. (Да ли сте правилно унели име?)</b>',
'set_user_rights' => 'Постави права корисника',
'setbureaucratflag' => 'Постави права бирократе',
'setstewardflag' => 'Постави заставицу домаћина',
'sharedupload' => '<br clear=both>Ова слика је са оставе.<br />',
'shareduploadwiki' => 'Молимо погледајте [страницу описа $1 фајла] за даље информације.',
'shareduploadwiki-linktext' => 'страна за опис фајла',
'shortpages' => 'Кратке странице',
'show' => 'покажи',
'showbigimage' => 'Прикажи слику веће резолуције ($1x$2, $3 Kb)',
'showdiff' => 'Прикажи промене',
'showhidebots' => '($1 ботове)',
'showhideminor' => '$1 мале измене | $2 ботове | $3 пријављене кориснике',
'showingresults' => 'Приказујем <b>$1</b> резултата почев од <b>$2</b>.',
'showingresultsnum' => 'Приказујем <b>$3</b> резултате почев од <b>$2</b>.',
'showlast' => 'Прикажи последњих $1 слика поређаних по $2.',
'showpreview' => 'Прикажи претпреглед',
'showtoc' => 'прикажи',
'sig_tip' => 'Ваш потпис са тренутним временом',
'sitestats' => 'Статистике сајта',
'sitestatstext' => 'Википедија тренутно има \'\'\'$2\'\'\' чланака.</p>
Овај број искључује редиректе, странице за разговор, странице са описом слике, корисничке странице, шаблоне, странице за помоћ, чланке без иједне повезнице, и странице о Википедији. Укључујући ове, имамо \'\'\'$1\'\'\' страница.</p>

Корисници су направили \'\'\'$4\'\'\' измена од јула 2002 године; у просеку \'\'\'$5\'\'\' измена по страници.',
'sitesubtitle' => '',
'sitesupport' => 'Донације',
'sitesupport-url' => 'Project:Fundraising',
'siteuser' => '{{ns:4}} корисник $1',
'siteusers' => '{{ns:4}} корисник (корисници) $1',
'skin' => 'Кожа',
'skinpreview' => '(Преглед)',
'sorbs_create_account_reason' => 'Ваша ИП адреса се налази на списку као отворени прокси на [http://www.sorbs.net SORBS] DNSBL. Не можете да направите налог',
'sorbsreason' => 'Ваша ИП адреса је на списку као отворен прокси на [http://www.sorbs.net SORBS] DNSBL.',
'sourcefilename' => 'Име фајла извора',
'spamprotectionmatch' => 'Следећи текст је изазвао наш филтер за нежељене поруке: $1',
'spamprotectiontext' => 'Страна коју желите да сачувате је блокирана од стране филтера за нежељене поруке. Ово је вероватно изазвано везом ка спољашњем сајту.',
'spamprotectiontitle' => 'Филтер за заштиту од нежељених порука',
'speciallogtitlelabel' => 'Наслов:',
'specialloguserlabel' => 'Корисник:',
'specialpage' => 'Посебна страница',
'specialpages' => 'Посебне странице',
'spheading' => 'Посебне странице за све кориснике',
'sqlhidden' => '(SQL претрага сакривена)',
'statistics' => 'Статистике',
'storedversion' => 'Ускладиштена верзија',
'stubthreshold' => 'Граница за приказивање клица',
'subcategories' => 'Поткатегорије',
'subcategorycount' => '$1 поткатегорија су у овој категорији.',
'subcategorycount1' => '$1 поткатегорија су у овој категорији.',
'subject' => 'Тема/наслов',
'subjectpage' => 'Погледај тему',
'successfulupload' => 'Успешно слање',
'summary' => 'Уопштено',
'sunday' => 'недеља',
'sysoptext' => 'Акцију коју сте затражили могу
извести само корисници са статусом администратора.
Погледајте $1.',
'sysoptitle' => 'Неопходан је администраторски приступ',
'tableform' => 'табела',
'tagline' => 'Из Википедије, слободне енциклопедије.',
'talk' => 'Разговор',
'talkexists' => 'Сама страница је успешно премештена, али
страница за разговор није могла бити премештена јер таква већ постоји на новом наслову. Молимо вас да их спојите ручно.',
'talkpage' => 'Разговор о овој страници',
'talkpagemoved' => 'Одговарајућа страница за разговор је такође премештена.',
'talkpagenotmoved' => 'Одговарајућа страница за разговор <strong>није</strong> премештена.',
'templatesused' => 'Шаблони који се користе на овој страници:',
'textboxsize' => 'Величине текстуалног поља',
'textmatches' => 'Текст чланка одговара',
'thisisdeleted' => 'Погледај или врати $1?',
'thumbnail-more' => 'увећај',
'thumbsize' => 'Величина умањеног приказа :',
'thursday' => 'четвртак',
'timezonelegend' => 'Временска зона',
'timezoneoffset' => 'Одступање',
'timezonetext' => 'Унесите број сати за који се ваше локално време
разликује од серверског времена (UTC).',
'titlematches' => 'Наслов чланка одговара',
'toc' => 'Садржај',
'tog-editondblclick' => 'Мењај странице двоструким кликом (захтева JavaScript)',
'tog-editsection' => 'Омогући измену делова [мењај] везама',
'tog-editsectiononrightclick' => 'Омогући измену делова десним кликом<br /> на њихове наслове (захтева JavaScript)',
'tog-editwidth' => 'Поље за измене има пуну ширину',
'tog-enotifminoredits' => 'Пошаљи ми е-пошту такође за мале измене страна',
'tog-enotifrevealaddr' => 'Откриј адресу моје е-поште у поштама обавештења',
'tog-enotifusertalkpages' => 'Пошаљи ми е-пошту када се промени моја корисничка страна за разговор',
'tog-enotifwatchlistpages' => 'Пошаљи ми е-пошту када се промене стране',
'tog-externaldiff' => 'Користи спољашњи разл по подразумеваним подешавањима',
'tog-externaleditor' => 'Користи спољашњи едитор по подразумеваним подешавањима',
'tog-fancysig' => 'Чист потпис (без аутоматског повезивања)',
'tog-hideminor' => 'Сакриј мале измене у списку скорашњих променама',
'tog-highlightbroken' => 'Форматирај покварене везе <a href="" class="new">овако</a> (алтернатива: овако<a href="" class="internal">?</a>).',
'tog-justify' => 'Уравнај пасусе',
'tog-minordefault' => 'Означи све измене малим испрва',
'tog-nocache' => 'Онемогући кеширање страница',
'tog-numberheadings' => 'Аутоматски нумериши поднаслове',
'tog-previewonfirst' => 'Прикажи изглед при првој промени',
'tog-previewontop' => 'Покажи претпреглед пре поља за измену а не после њега',
'tog-rememberpassword' => 'Памти шифру кроз више сеанси',
'tog-shownumberswatching' => 'Прикажи број корисника који надгледају',
'tog-showtoc' => 'Прикажи садржај<br />(у свим чланцима са више од три поднаслова)',
'tog-showtoolbar' => 'Прикажи дугмиће за измене',
'tog-underline' => 'Подвуци везе',
'tog-usenewrc' => 'Побољшан списак скорашњих измена (није за све браузере)',
'tog-watchdefault' => 'Додај странице које мењам у мој списак надгледања',
'toolbox' => 'алати',
'tooltip-compareselectedversions' => 'Погледаj разлике између две селектоване верзије ове странице. [alt-v]',
'tooltip-diff' => 'Прикажи које промене сте направили на тексту. [alt-d]',
'tooltip-minoredit' => 'Назначите да се ради о малој измени [alt-i]',
'tooltip-preview' => 'Претпреглед Ваших измена, молимо Вас користите ово пре снимања! [alt-p]',
'tooltip-save' => 'Снимите Ваше измене [alt-s]',
'tooltip-search' => 'Претражите Вики',
'tooltip-watch' => 'Додајте ову страницу на Ваш списак надгледања [alt-w]',
'trackback' => '<div id=\'mw_trackbacks\'> Враћања за овај чланак:<br/> $1 </div>',
'trackbackbox' => '; $4$5 : [$2 $1]',
'trackbackdeleteok' => 'Враћање је успешно обрисано.',
'trackbackexcerpt' => '; $4$5 : [$2 $1]: <nowiki>$3</nowiki>',
'trackbacklink' => 'Враћање',
'trackbackremove' => '([$1 Брисање])',
'tryexact' => 'Покушај тачно',
'tuesday' => 'уторак',
'uclinks' => 'Гледај последњих $1 промена; гледај последњих $2 дана.',
'ucnote' => 'Испод је последњих <b>$1</b> промена у последњих <b>$2</b> дана.',
'uctop' => ' (врх)',
'unblockip' => 'Отпусти корисника',
'unblockiptext' => 'Употребите доњи упитник да бисте вратили право писања
раније обузданој ИП адреси или корисничком имену.',
'unblocklink' => 'деблокирај',
'unblocklogentry' => 'деблокиран "$1"',
'uncategorizedcategories' => 'Категорије без категорија',
'uncategorizedpages' => 'Странице без категорије',
'undelete' => 'Врати обрисану страницу',
'undelete_short' => 'врати $1 обрисаних измена',
'undelete_short1' => 'Врати једну обрисану измену',
'undeletearticle' => 'Врати обрисани чланак',
'undeletebtn' => 'Врати!',
'undeletedarticle' => 'враћено "$1"',
'undeletedrevisions' => '$1 ревизија враћено',
'undeletedtext' => 'Чланак [[:$1|$1]] је успешно враћен.
Погледајте [[{{ns:-1}}:Log/delete]] за запис о скорашњим брисањима и враћањима.',
'undeletehistory' => 'Ако вратите страницу, све ревизије ће бити враћене њеној историји.
Ако је нова страница истог имена направљена од брисања, враћене
ревизије ће се појавити у ранијој историји, а тренутна ревизија садашње странице
неће бити аутоматски замењена.',
'undeletehistorynoadmin' => 'Ова страна је обрисана. Испод се налази део историје брисања и историја ревизија обрисане стране. Питајте [[{{ns:4}}:Администратори|администратора]] ако желите да се страница врати.',
'undeletepage' => 'Погледај и врати обрисане странице',
'undeletepagetext' => 'Следеће странице су обрисане али су још увек у архиви и
могу бити враћене. Архива може бити периодично чишћена.',
'undeleterevision' => 'Обрисана ревизија од $1',
'undeleterevisions' => '$1 ревизија архивирано',
'underline-always' => 'Увек',
'underline-default' => 'По подешавањима браузера',
'underline-never' => 'Никад',
'unexpected' => 'Неочекивана вредност: "$1"="$2".',
'unlockbtn' => 'Откључај базу',
'unlockconfirm' => 'Да, заиста желим да откључам базу.',
'unlockdb' => 'Откључај базу',
'unlockdbsuccesssub' => 'База је откључана',
'unlockdbsuccesstext' => 'Википедијина база података је откључана.',
'unlockdbtext' => 'Откључавање базе ће свим корисницима вратити могућност измене страница,
промене корисничких подешавања, измене списка надгледања, и свега осталог
што захтева промене у бази.
Молимо потврдите да је ово заиста оно што намеравате да урадите.',
'unprotect' => 'Скини заштиту',
'unprotectcomment' => 'Разлог за скидање заштите',
'unprotectedarticle' => 'заштита скинута $1',
'unprotectsub' => '(скидање заштите "$1")',
'unprotectthispage' => 'Уклони заштиту са ове странице',
'unusedcategories' => 'Неискоришћене категорије',
'unusedcategoriestext' => 'Наредне стране категорија постоје иако их ни један други чланак или категорија не користе.',
'unusedimages' => 'Неупотребљене слике',
'unusedimagestext' => '<p>Обратите пажњу да се други веб сајтови
као што су међународне Википедије могу повезивати на слику
директним УРЛ-ом, и тако могу још увек бити приказани овде упркос
активној употреби.',
'unwatch' => 'Прекини надгледање',
'unwatchthispage' => 'Прекини надгледање',
'updated' => '(Освежено)',
'updatedmarker' => 'ажурирано од моје последње посете',
'upload' => 'Пошаљи фајл',
'upload_directory_read_only' => 'На директоријум за слање ($1) вебсервер не може да пише.',
'uploadbtn' => 'Пошаљи фајл',
'uploadcorrupt' => 'Фајл је неисправан или има нетачну екстензију. Молимо проверите фајл и пошаљите га поново.',
'uploaddisabled' => 'Извињавамо се, слање фајлова је искључено.',
'uploadedfiles' => 'Послати фајлови',
'uploadedimage' => 'послато "[[$1]]"',
'uploaderror' => 'Грешка при слању',
'uploadlink' => 'Пошаљи слике',
'uploadlog' => 'лог слања',
'uploadlogpage' => 'историја слања',
'uploadlogpagetext' => 'Испод је списак најскоријих слања.
Сва времена су серверска времена (UTC).
<ul>
</ul>',
'uploadnewversion' => '[$1 Пошаљите новију верзију ове датотеке]',
'uploadnologin' => 'Нисте пријављени',
'uploadnologintext' => 'Морате бити [[{{ns:-1}}:Userlogin|пријављени]]
да бисте слали фајлове.',
'uploadscripted' => 'Овај фајл садржи ХТМЛ или код скрипте које интернет браузер може са грешком да интерпретира.',
'uploadtext' => '

Молимо, придржавајте се следећих смерница при постављању датотека:
*Назначите у текстуалном пољу (Опис) \'\'детаљне\'\' податке о извору датотеке; ову информацију систем ће одмах поставити и на описну страну датотеке. Ако сте датотеку набавили негде на Интернету, молимо укључите и УРЛ (интернет адресу) одакле.
*Наведите \'\'лиценцу\'\' датотеке додавањем одговарајуће налепнице, нпр. <tt><nowiki>{</nowiki>{гфдл}}</tt>, <tt><nowiki>{</nowiki>{јв}}</tt>, итд. Погледајте [[{{ns:4}}: Налепнице за ауторска права над сликама|Налепнице за ауторска права над сликама]], где ћете наћи и списак свих налепница које се могу користити.
*Слику или други садржај додајете у погодне чланке користећи синтаксу <tt><nowiki>[[{{ns:6}}:File.jpg|thumb|Натпис под сликом]]</nowiki></tt> за слике, односно <tt><nowiki>[[{{ns:-2}}:File.ogg]]</nowiki></tt> за друге медије. За даља упутства, погледајте [[{{ns:4}}:Проширена синтакса за слике|Проширена синтакса за слике]].
*Користите јасна, описна имена (нпр. "Ајфелов торањ у Паризу ноћу.jpg") како бисте избегли преклапања са постојећим датотекама.

Ако желите да сазнате више, погледајте [[{{ns:4}}:Постављање датотека]] и [[{{ns:4}}:Звук]]. Списак свих већ постављених датотека можете видети на [[{{ns:-1}}:Imagelist|списку слика]].

<p>\'\'\'Ако желите да поставите датотеку над којом <i>Ви</i> поседујете ауторска права,<br /> морате је лиценцирати под [[ГНУ-ова ЛСД|ГНУ-овом Лиценцом за слободну документацијом]]<br /> или предати у [[јавно власништво]].\'\'\'</p>',
'uploadvirus' => 'Фајл садржи вирус! Детаљи: $1',
'uploadwarning' => 'Упозорење при слању',
'usenewcategorypage' => '1

Поставите први карактер на "0" да онемогућите распоред стране за нову категорију.',
'user_rights_set' => '<b>Права за корисника "$1" промењена</b>',
'usercssjsyoucanpreview' => '<strong>Пажња:</strong> Кориситите \'Прикажи претпреглед\' дугме да тестирате свој нови CSS/JS пре снимања.',
'usercsspreview' => '\'\'\'Запамтите ово је само претпреглед вашег CSS, још увек није снимљен!\'\'\'',
'userexists' => 'Корисничко име које сте унели већ је у употреби. Молимо Вас изаберите друго име.',
'userjspreview' => '\'\'\'Запамтите ово је само претпреглед ваше JavaScript-е, још увек није снимљен!\'\'\'',
'userlogin' => 'Региструј се/Пријави се',
'userlogout' => 'Одјави се',
'usermailererror' => 'Објекат поште је вратио грешку:',
'userpage' => 'Погледај корисничку страну',
'userrights' => 'Управљање корисничким правима',
'userrights-editusergroup' => 'Промени корисничке групе',
'userrights-groupsavailable' => 'Доступне групе:',
'userrights-groupshelp' => 'Одабране групе од којих желите да се уклони корисник или да се дода.
Неодабране групе неће бити промењене. Можете да деселектујете групу користећи CTRL + леви клик',
'userrights-groupsmember' => 'Члан:',
'userrights-logcomment' => 'Промењено чланство групе из $1 у $2',
'userrights-lookup-user' => 'Управљај корисничким групама',
'userrights-user-editname' => 'Унеси корисничко име:',
'userstats' => 'Статистике корисника',
'userstatstext' => 'Постоји \'\'\'$1\'\'\' регистрованих корисника, од којих су \'\'\'$2\'\'\' (или $4%) [[{{ns:4}}:Администратори|администратори]].',
'version' => 'Верзија',
'versionrequired' => 'Верзија $1 МедијаВикија је потребна',
'versionrequiredtext' => 'Верзија $1 МедијаВикија је потребна да би се користила ова страна. Погледајте [[{{ns:-1}}:Version|верзију]]',
'viewcount' => 'Овој страници је приступљено $1 пута.',
'viewdeleted' => 'Погледај $1?',
'viewdeletedpage' => 'Погледај обрисане стране',
'viewprevnext' => 'Погледај ($1) ($2) ($3).',
'views' => 'Прегледи',
'viewsource' => 'погледај код',
'viewtalkpage' => 'Погледај расправу',
'wantedpages' => 'Тражене странице',
'watch' => 'надгледај',
'watchdetails' => '($1 страница надгледано не рачунајући странице за разговор;
$2 укупно страница измењено од одсецања;
$3...)<br />
[$4 прикажи и мењај потпуни списак]',
'watcheditlist' => 'Овде је азбучни списак страница
које надгледате. Обележите кућице страница које желите да уклоните
са свог списка надгледања и кликните на дугме \'уклони изабране\'
на дну екрана.',
'watchlist' => 'Мој списак надгледања',
'watchlistall1' => 'све',
'watchlistall2' => 'све',
'watchlistcontains' => 'Ваш списак надгледања садржи $1 страница.',
'watchlistsub' => '(за корисника "$1")',
'watchmethod-list' => 'проверавам има ли скорашњих измена у надгледаним страницама',
'watchmethod-recent' => 'проверавам има ли надгледаних страница у скорашњим изменама',
'watchnochange' => 'Ништа што надгледате није промењено у приказаном времену.',
'watchnologin' => 'Нисте пријављени',
'watchnologintext' => 'Морате бити [[{{ns:-1}}:Userlogin|пријављени]] да бисте мењали списак надгледања.',
'watchthis' => 'Надгледај овај чланак',
'watchthispage' => 'Надгледај ову страницу',
'wednesday' => 'среда',
'welcomecreation' => '<h2>Добродошли, $1!</h2><p>Ваш налог је креиран.
Не заборавите да прилагодите себи своја {{ns:4}} подешавања.',
'whatlinkshere' => 'Шта је повезано овде',
'whitelistacctext' => 'Да би вам било дозвољено да направите налоге на овом Викију морате да се [[{{ns:-1}}:Userlogin|пријавите]] и имате одговарајућа овлашћења.',
'whitelistacctitle' => 'Није вам дозвољено да направите налог',
'whitelistedittext' => 'Морате да се [[{{ns:-1}}:Userlogin|пријавите]] да бисте мењали чланке.',
'whitelistedittitle' => 'Обавезно је [[{{ns:-1}}:Userlogin|пријављивање]] за мењање',
'whitelistreadtext' => 'Морате да се [[{{ns:-1}}:Userlogin|пријавите]] да бисте читали чланке.',
'whitelistreadtitle' => 'Обавезно је пријављивање за читање',
'wikipediapage' => 'Погледај страну о овој страни',
'wlheader-enotif' => '* Обавештавање е-поштом је омогућено.',
'wlheader-showupdated' => '* Стране које су измењене од када сте их последњи пут посетили су приказане \'\'\'масним словима\'\'\'',
'wlhide' => 'Сакриј',
'wlhideshowbots' => '$1 измена ботова.',
'wlhideshowown' => '$1 мојe изменe.',
'wlnote' => 'Испод је последњих $1 измена у последњих <b>$2</b> сати.',
'wlsaved' => 'Ово је сачувана верзија вашег списка надгледања.',
'wlshow' => 'Прикажи',
'wlshowlast' => 'Прикажи последњих $1 сати $2 дана $3',
'wrong_wfQuery_params' => 'Нетачни параметри за wfQuery()<br />
Функција: $1<br />
Претрага: $2',
'wrongpassword' => 'Лозинка коју сте унели је неисправна. Молимо покушајте поново.',
'yourdiff' => 'Разлике',
'yourdomainname' => 'Ваш домен',
'youremail' => 'Адреса ваше е-поште*',
'yourlanguage' => 'Језик изгледа Википедије',
'yourname' => 'Корисничко име',
'yournick' => 'Ваш надимак (за потписе)',
'yourpassword' => 'Ваша лозинка',
'yourpasswordagain' => 'Поновите лозинку',
'yourrealname' => 'Ваше право име*',
'yourtext' => 'Ваш текст',
'yourvariant' => 'Варијанта језика',
);

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageSr extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesSr;
		return $wgNamespaceNamesSr;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsSr;
		return $wgQuickbarSettingsSr;
	}

	function getSkinNames() {
		global $wgSkinNamesSr;
		return $wgSkinNamesSr;
	}

	function getDateFormats() {
		global $wgDateFormatsSr;
		return $wgDateFormatsSr;
	}

	function getMessage( $key ) {
		global $wgAllMessagesSr;
		if(array_key_exists($key, $wgAllMessagesSr))
			return $wgAllMessagesSr[$key];
		else
			return parent::getMessage($key);
	}

	/**
	* Exports $wgMagicWordsSr
	* @return array
	*/
	function getMagicWords()  {
		global $wgMagicWordsSr;
		return $wgMagicWordsSr;
	}

	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
	}

	/**
	 * @access public
	 * @param mixed  $ts the time format which needs to be turned into a
	 *               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	 * @param bool   $adj whether to adjust the time output according to the
	 *               user configured offset ($timecorrection)
	 * @param mixed  $format what format to return, if it's false output the
	 *               default one.
	 * @param string $timecorrection the time offset as returned by
	 *               validateTimeZone() in Special:Preferences
	 * @return string
	 */
	function date( $ts, $adj = false, $format = true, $timecorrection = false ) {

		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }

		$mm = substr( $ts, 4, 2 );
		$m = 0 + $mm;
		$mmmm = $this->getMonthName( $mm );
		$mmm = $this->getMonthAbbreviation( $mm );
		$dd = substr( $ts, 6, 2 );
		$d = 0 + $dd;
		$yyyy =  substr( $ts, 0, 4 );
		$yy =  substr( $ts, 2, 2 );

		switch( $format ) {
			case '2':
			case '8':
				return "$d $mmmm $yyyy";
			case '3':
			case '9':
				return "$dd.$mm.$yyyy.";
			case '4':
			case '10':
				return "$d.$m.$yyyy.";
			case '5':
			case '11':
				return "$d. $mmm $yyyy.";
			case '6':
			case '12':
				return "$d $mmm $yyyy";
			default:
				return "$d. $mmmm $yyyy.";
		}

	}

	/**
	* @access public
	* @param mixed  $ts the time format which needs to be turned into a
	*               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	* @param bool   $adj whether to adjust the time output according to the
	*               user configured offset ($timecorrection)
	* @param mixed  $format what format to return, if it's false output the
	*               default one (default true)
	* @param string $timecorrection the time offset as returned by
	*               validateTimeZone() in Special:Preferences
	* @return string
	*/
	function time( $ts, $adj = false, $format = true, $timecorrection = false ) {

		if ( $adj ) { $ts = $this->userAdjust( $ts, $timecorrection ); }
		$hh = substr( $ts, 8, 2 );
		$h = 0 + $hh;
		$mm = substr( $ts, 10, 2 );
		switch( $format ) {
			case '7':
			case '8':
			case '9':
			case '10':
			case '11':
			case '12':
				return "$h:$mm";
			default:
				return "$hh:$mm";
		}
	}

	/**
	* @access public
	* @param mixed  $ts the time format which needs to be turned into a
	*               date('YmdHis') format with wfTimestamp(TS_MW,$ts)
	* @param bool   $adj whether to adjust the time output according to the
	*               user configured offset ($timecorrection)
	* @param mixed  $format what format to return, if it's false output the
	*               default one (default true)
	* @param string $timecorrection the time offset as returned by
	*               validateTimeZone() in Special:Preferences
	* @return string
	*/
	function timeanddate( $ts, $adj = false, $format = true, $timecorrection = false) {
		$datePreference = $this->dateFormat($format);
		return $this->time( $ts, $adj, $datePreference, $timecorrection ) . ', ' . $this->date( $ts, $adj, $datePreference, $timecorrection );

	}

	function convertPlural( $count, $wordform1, $wordform2, $wordform3) {
		$count = str_replace ('.', '', $count);
		if ($count > 10 && floor(($count % 100) / 10) == 1) {
			return $wordform3;
		} else {
			switch ($count % 10) {
				case 1: return $wordform1;
				case 2:
				case 3:
				case 4: return $wordform2;
				default: return $wordform3;
			}
		}
	}

}

?>

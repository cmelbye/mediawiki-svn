<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

$namespaceNames = array(
	NS_MEDIA            => "Medija",
	NS_SPECIAL          => "Posebno",
	NS_MAIN             => "",
	NS_TALK             => "Razgovor",
	NS_USER             => "Korisnik",
	NS_USER_TALK        => "Razgovor_sa_korisnikom",
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => "Razgovor_o_$1",
	NS_IMAGE            => "Slika",
	NS_IMAGE_TALK       => "Razgovor_o_slici",
	NS_MEDIAWIKI        => "MedijaViki",
	NS_MEDIAWIKI_TALK   => "Razgovor_o_MedijaVikiju",
	NS_TEMPLATE         => 'Šablon',
	NS_TEMPLATE_TALK    => 'Razgovor_o_šablonu',
	NS_HELP             => 'Pomoć',
	NS_HELP_TALK        => 'Razgovor_o_pomoći',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Razgovor_o_kategoriji',
);

$quickbarSettings = array(
 "Nikakva", "Pričvršćena levo", "Pričvršćena desno", "Plutajuća levo"
);

$skinNames = array(
 "Obična", "Nostalgija", "Kelnsko plavo", "Pedington", "Monparnas"
);

$extraUserToggles = array(
	'nolangconversion',
);

$datePreferenceMigrationMap = array(
	'default',
	'hh:mm d. month y.',
	'hh:mm d month y',
	'hh:mm dd.mm.yyyy',
	'hh:mm d.m.yyyy',
	'hh:mm d. mon y.',
	'hh:mm d mon y',
	'h:mm d. month y.',
	'h:mm d month y',
	'h:mm dd.mm.yyyy',
	'h:mm d.m.yyyy',
	'h:mm d. mon y.',
	'h:mm d mon y',
);

$datePreferences = array(
	'default',
	'hh:mm d. month y.',
	'hh:mm d month y',
	'hh:mm dd.mm.yyyy',
	'hh:mm d.m.yyyy',
	'hh:mm d. mon y.',
	'hh:mm d mon y',
	'h:mm d. month y.',
	'h:mm d month y',
	'h:mm dd.mm.yyyy',
	'h:mm d.m.yyyy',
	'h:mm d. mon y.',
	'h:mm d mon y',
);

$defaultDateFormat = 'hh:mm d. month y.';

$dateFormats = array(
	/*
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
	 */
	
	'hh:mm d. month y. time'    => 'H:i',
	'hh:mm d month y time'      => 'H:i',
	'hh:mm dd.mm.yyyy time'     => 'H:i',
	'hh:mm d.m.yyyy time'       => 'H:i',
	'hh:mm d. mon y. time'      => 'H:i',
	'hh:mm d mon y time'        => 'H:i',
	'h:mm d. month y. time'     => 'G:i',
	'h:mm d month y time'       => 'G:i',
	'h:mm dd.mm.yyyy time'      => 'G:i',
	'h:mm d.m.yyyy time'        => 'G:i',
	'h:mm d. mon y. time'       => 'G:i',
	'h:mm d mon y time'         => 'G:i',

	'hh:mm d. month y. date'    => 'j. F Y.',
	'hh:mm d month y date'      => 'j F Y',  
	'hh:mm dd.mm.yyyy date'     => 'd.m.Y',  
	'hh:mm d.m.yyyy date'       => 'j.n.Y',  
	'hh:mm d. mon y. date'      => 'j. M Y.',
	'hh:mm d mon y date'        => 'j M Y',  
	'h:mm d. month y. date'     => 'j. F Y.',
	'h:mm d month y date'       => 'j F Y',  
	'h:mm dd.mm.yyyy date'      => 'd.m.Y',  
	'h:mm d.m.yyyy date'        => 'j.n.Y',  
	'h:mm d. mon y. date'       => 'j. M Y.',
	'h:mm d mon y date'         => 'j M Y',  

	'hh:mm d. month y. both'    =>'H:i, j. F Y.', 
	'hh:mm d month y both'      =>'H:i, j F Y',   
	'hh:mm dd.mm.yyyy both'     =>'H:i, d.m.Y',   
	'hh:mm d.m.yyyy both'       =>'H:i, j.n.Y',   
	'hh:mm d. mon y. both'      =>'H:i, j. M Y.', 
	'hh:mm d mon y both'        =>'H:i, j M Y',   
	'h:mm d. month y. both'     =>'G:i, j. F Y.', 
	'h:mm d month y both'       =>'G:i, j F Y',   
	'h:mm dd.mm.yyyy both'      =>'G:i, d.m.Y',   
	'h:mm d.m.yyyy both'        =>'G:i, j.n.Y',   
	'h:mm d. mon y. both'       =>'G:i, j. M Y.', 
	'h:mm d mon y both'         =>'G:i, j M Y',   
);


/* NOT USED IN STABLE VERSION */
$magicWords = array(
#	ID                                CASE SYNONYMS
	'redirect'               => array( 0, '#Preusmeri', '#redirect', '#preusmeri', '#PREUSMERI' ),
	'notoc'                  => array( 0, '__NOTOC__', '__BEZSADRŽAJA__' ),
	'forcetoc'               => array( 0, '__FORCETOC__', '__FORSIRANISADRŽAJ__' ),
	'toc'                    => array( 0, '__TOC__', '__SADRŽAJ__' ),
	'noeditsection'          => array( 0, '__NOEDITSECTION__', '__BEZ_IZMENA__', '__BEZIZMENA__' ),
	'start'                  => array( 0, '__START__', '__POČETAK__' ),
	'end'                    => array( 0, '__END__', '__KRAJ__' ),
	'currentmonth'           => array( 1, 'CURRENTMONTH', 'TRENUTNIMESEC' ),
	'currentmonthname'       => array( 1, 'CURRENTMONTHNAME', 'TRENUTNIMESECIME' ),
	'currentmonthnamegen'    => array( 1, 'CURRENTMONTHNAMEGEN', 'TRENUTNIMESECROD' ),
	'currentmonthabbrev'     => array( 1, 'CURRENTMONTHABBREV', 'TRENUTNIMESECSKR' ),
	'currentday'             => array( 1, 'CURRENTDAY', 'TRENUTNIDAN' ),
	'currentdayname'         => array( 1, 'CURRENTDAYNAME', 'TRENUTNIDANIME' ),
	'currentyear'            => array( 1, 'CURRENTYEAR', 'TRENUTNAGODINA' ),
	'currenttime'            => array( 1, 'CURRENTTIME', 'TRENUTNOVREME' ),
	'numberofarticles'       => array( 1, 'NUMBEROFARTICLES', 'BROJČLANAKA' ),
	'numberoffiles'          => array( 1, 'NUMBEROFFILES', 'BROJDATOTEKA', 'BROJFAJLOVA' ),
	'pagename'               => array( 1, 'PAGENAME', 'STRANICA' ),
	'pagenamee'              => array( 1, 'PAGENAMEE', 'STRANICE' ),
	'namespace'              => array( 1, 'NAMESPACE', 'IMENSKIPROSTOR' ),
	'namespacee'             => array( 1, 'NAMESPACEE', 'IMENSKIPROSTORI' ),
	'fullpagename'           => array( 1, 'FULLPAGENAME', 'PUNOIMESTRANE' ),
	'fullpagenamee'          => array( 1, 'FULLPAGENAMEE', 'PUNOIMESTRANEE' ),
	'msg'                    => array( 0, 'MSG:', 'POR:' ),
	'subst'                  => array( 0, 'SUBST:', 'ZAMENI:' ),
	'msgnw'                  => array( 0, 'MSGNW:', 'NVPOR:' ),
	'img_thumbnail'          => array( 1, 'thumbnail', 'thumb', 'mini' ),
	'img_manualthumb'        => array( 1, 'thumbnail=$1', 'thumb=$1', 'mini=$1' ),
	'img_right'              => array( 1, 'right', 'desno', 'd' ),
	'img_left'               => array( 1, 'left', 'levo', 'l' ),
	'img_none'               => array( 1, 'none', 'n', 'bez' ),
	'img_width'              => array( 1, '$1px', '$1piskel' , '$1p' ),
	'img_center'             => array( 1, 'center', 'centre', 'centar', 'c' ),
	'img_framed'             => array( 1, 'framed', 'enframed', 'frame', 'okvir', 'ram' ),
	'int'                    => array( 0, 'INT:', 'INT:' ),
	'sitename'               => array( 1, 'SITENAME', 'IMESAJTA' ),
	'ns'                     => array( 0, 'NS:', 'IP:' ),
	'localurl'               => array( 0, 'LOCALURL:', 'LOKALNAADRESA:' ),
	'localurle'              => array( 0, 'LOCALURLE:', 'LOKALNEADRESE:' ),
	'server'                 => array( 0, 'SERVER', 'SERVER' ),
	'servername'             => array( 0, 'SERVERNAME', 'IMESERVERA' ),
	'scriptpath'             => array( 0, 'SCRIPTPATH', 'SKRIPTA' ),
	'grammar'                => array( 0, 'GRAMMAR:', 'GRAMATIKA:' ),
	'notitleconvert'         => array( 0, '__NOTITLECONVERT__', '__NOTC__', '__BEZTC__' ),
	'nocontentconvert'       => array( 0, '__NOCONTENTCONVERT__', '__NOCC__', '__BEZCC__' ),
	'currentweek'            => array( 1, 'CURRENTWEEK', 'TRENUTNANEDELjA' ),
	'currentdow'             => array( 1, 'CURRENTDOW', 'TRENUTNIDOV' ),
	'revisionid'             => array( 1, 'REVISIONID', 'IDREVIZIJE' ),
	'plural'                 => array( 0, 'PLURAL:', 'MNOŽINA:' ),
	'fullurl'                => array( 0, 'FULLURL:', 'PUNURL:' ),
	'fullurle'               => array( 0, 'FULLURLE:', 'PUNURLE:' ),
	'lcfirst'                => array( 0, 'LCFIRST:', 'LCPRVI:' ),
	'ucfirst'                => array( 0, 'UCFIRST:', 'UCPRVI:' ),
	'lc'                     => array( 0, 'LC:', 'LC:' ),
	'uc'                     => array( 0, 'UC:', 'UC:' ),
);

$separatorTransformTable = array(',' => '.', '.' => ',' );


$messages = array(
'1movedto2' => 'članku [[$1]] je promenjeno ime u [[$2]]',
'1movedto2_redir' => 'članku [[$1]] je promenjeno ime u [[$2]] putem preusmerenja',
'Monobook.css' => '/*
*/',
'Monobook.js' => '
/* tooltips and access keys */
var ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Moja korisnička stranica\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'Korisnička strana za IP koju menjate kao\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Moja strana za razgovor\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Razgovor o prilozima sa ove IP adrese\');
ta[\'pt-preferences\'] = new Array(\'\',\'Moja korisnička podešavanja\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'Spisak članaka koje nadgledate.\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Spisak mojih priloga\');
ta[\'pt-login\'] = new Array(\'o\',\'Prijava nije obavezna, ali donosi mnogo koristi.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Prijava nije obavezna, ali donosi mnogo koristi.\');
ta[\'pt-logout\'] = new Array(\'o\',\'Odjava sa Vikipedije\');
ta[\'ca-talk\'] = new Array(\'t\',\'Razgovori o sadržaju članka\');
ta[\'ca-edit\'] = new Array(\'e\',\'Možete da uređujete ovaj članak!\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Dodajte svoj komentar.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Ovaj članak je zaključan.\');
ta[\'ca-history\'] = new Array(\'h\',\'Prethodne verzije sadržaja članka.\');
ta[\'ca-protect\'] = new Array(\'=\',\'Zaštiti stranicu od budućih izmena\');
ta[\'ca-delete\'] = new Array(\'d\',\'Brisanje stranice\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Vraćanje izmena koje su načinjene pre brisanja stranice.\');
ta[\'ca-move\'] = new Array(\'m\',\'Pomeranje stranice\');
ta[\'ca-nomove\'] = new Array(\'\',\'Nemate dozvolu za pomeranje ove stranice\');
ta[\'ca-watch\'] = new Array(\'w\',\'Dodavanje stranice na Vaš spisak nadgladanja.\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Uklanjanje ove stranice sa Vašeg spiska nadgledanja.\');
ta[\'search\'] = new Array(\'f\',\'Pretraživanje Vikipedije\');
ta[\'p-logo\'] = new Array(\'\',\'Glavna strana\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Posetite glavnu stranu\');
ta[\'n-portal\'] = new Array(\'\',\'Razgovor o bilo čemu što se tiče Vikipedije.\');
ta[\'n-currentevents\'] = new Array(\'\',\'Podaci o onome na čemu se trenutno radi.\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'Spisak skorašnjih izmena na Vikipediji\');
ta[\'n-randompage\'] = new Array(\'x\',\'Učitavanje slučajne stranice\');
ta[\'n-help\'] = new Array(\'\',\'Naučite da uređujete Vikipediju!\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Podržite nas\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Spisak svih članaka koji su povezani sa ovim\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Skorašnje izmene članaka na kojima se nalazi link ka ovom članku \');
ta[\'feed-rss\'] = new Array(\'\',\'RSS za ovu stranicu\');
ta[\'feed-atom\'] = new Array(\'\',\'Atom za ovu stranicu\');
ta[\'t-contributions\'] = new Array(\'\',\'Spisak priloga ovog korisnika\');
ta[\'t-emailuser\'] = new Array(\'\',\'Slanje elektronskog pisma ovom korisniku\');
ta[\'t-upload\'] = new Array(\'u\',\'Slanje slika i medija fajlova\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Spisak svih specijalnih stranica\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Videti sadržaj članka\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Videti korisničku stranicu\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Videti medija fajl\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Ovo je specijalna stranica i zato je ne možete samostalno uređivati.\');
ta[\'ca-nstab-project\'] = new Array(\'c\',\'Videti projekat stranicu\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Videti stranicu slike\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Videti sistemsku poruku\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Videti šablon\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Videti stranicu za pomoć\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Videti stranicu kategorije\');',
'about' => 'O...',
'aboutpage' => '{{ns:4}}:O',
'aboutsite' => 'O projektu {{ns:4}}',
'accmailtext' => 'Lozinka za nalog \'$1\' je poslata na adresu $2.',
'accmailtitle' => 'Lozinka je poslata.',
'acct_creation_throttle_hit' => 'Žao nam je, već ste napravili $1 korisnička imena. Više nije dozvoljeno.',
'actioncomplete' => 'Akcija završena',
'addedwatch' => 'Dodato spisku nadgledanja',
'addedwatchtext' => 'Stranica "[[:$1]]" je dodata vašem [[{{ns:-1}}:Watchlist|spisku nadgledanja]] .
Buduće promene ove stranice i njoj pridružene stranice za razgovor će biti navedene ovde, i stranica će biti <b>podebljana</b> u [[{{ns:-1}}:Recentchanges|spisku]] skorašnjih izmena da bi se lakše uočila.</p>

<p>Ako kasnije želite da uklonite stranicu sa vašeg spiska nadgledanja, kliknite na "prekini nadgledanje" na bočnoj paleti.',
'administrators' => '{{ns:4}}:Administratori',
'allarticles' => 'Svi članci',
'allinnamespace' => 'Sve stranice ($1 imenski prostor)',
'alllogstext' => 'Kombinovani prikaz slanja, brisanja, zaštite, blokiranja, i administratorskih zapisa.',
'allmessages' => 'Sistemske poruke',
'allmessagescurrent' => 'Trenutno',
'allmessagesdefault' => 'Standardno',
'allmessagesname' => 'Ime',
'allmessagesnotsupportedDB' => '[[{{ns:-1}}:AllMessages|Sistemske poruke]] nisu podržane zato što je <i>wgUseDatabaseMessages</i> isključen.',
'allmessagesnotsupportedUI' => 'Vaš trenutni jezik interfejsa <b>$1</b> nije podržan u [[{{ns:-1}}:AllMessages|sistemskim porukama]] na ovoj viki.',
'allmessagestext' => 'Ovo je spisak svih poruka koje su u {{ns:8}}: imenskom prostoru',
'allnotinnamespace' => 'Sve stranice (koje nisu u $1 imenskom prostoru)',
'allpages' => 'Sve stranice',
'allpagesfrom' => 'Prikaži stranice početno sa:',
'allpagesnext' => 'Sledeća',
'allpagesprev' => 'Prethodna',
'allpagessubmit' => 'Idi',
'alphaindexline' => '$1 u $2',
'already_bureaucrat' => 'Ovaj korisnik je već birokrata',
'already_steward' => 'Ovaj korisnik je već domaćin',
'already_sysop' => 'Ovaj korisnik je već administrator',
'alreadyloggedin' => '<strong>Korisniče $1, već ste prijavljeni!</strong><br />',
'alreadyrolled' => 'Ne mogu da vratim poslednju izmenu [[$1]]
od korisnika [[{{ns:2}}:$2|$2]] ([[{{ns:3}}:$2|razgovor]]); neko drugi je već izmenio ili vratio članak.

Poslednja izmena od korisnika [[{{ns:2}}:$3|$3]] ([[{{ns:3}}:$3|razgovor]]).',
'ancientpages' => 'Najstariji članci',
'and' => 'i',
'anontalk' => 'Razgovor za ovu IP adresu',
'anontalkpagetext' => '---- Ovo je stranica za razgovor za anonimnog korisnika koji još nije napravio nalog ili ga ne koristi. Zbog toga moramo da koristimo brojčanu [[IP adresa|IP adresu]] kako bismo identifikovali njega ili nju. Takvu adresu može deliti više korisnika. Ako ste anonimni korisnik i mislite da su vam upućene nebitne primedbe, molimo vas da [[{{ns:-1}}:Userlogin|napravite nalog ili se prijavite]] da biste izbegli buduću zabunu sa ostalim anonimnim korisnicima.',
'anonymous' => 'Anonimni korisnik {{ns:4}}',
'apr' => 'apr',
'april' => 'april',
'article' => 'Članak',
'articleexists' => 'Stranica pod tim imenom već postoji, ili je
ime koje ste izabrali neispravno.
Molimo izaberite drugo ime.',
'articlepage' => 'Pogledaj članak',
'aug' => 'avg',
'august' => 'avgust',
'autoblocker' => 'Automatski ste blokirani jer delite IP adresu sa "$1". Razlog "$2".',
'badaccess' => 'Greška pri odobrenju',
'badaccesstext' => 'Akcija koju ste tražili je ograničena na korisnike sa "$2" dozvolama. Pogledajte $1.',
'badarticleerror' => 'Ova akcija ne može biti izvršena na ovoj stranici.',
'badfilename' => 'Ime slike je promenjeno u "$1".',
'badfiletype' => '".$1" nije preporučeni format slike.',
'badipaddress' => 'Ne postoji ni jedan korisnik koji se tako zove',
'badquery' => 'Loše oblikovan upit za pretragu',
'badquerytext' => 'Nismo mogli da obradimo vaš upit.
Ovo je verovatno zbog toga što ste pokušali da tražite
reč kraću od tri slova, što trenutno nije podržano.
Takođe je moguće da ste pogrešno ukucali izraz, na
primer "riba ii krljušti".
Molimo vas pokušajte nekim drugim upitom.',
'badretype' => 'Lozinke koje ste uneli se ne poklapaju.',
'badtitle' => 'Loš naslov',
'badtitletext' => 'Zahtevani naslov stranice je bio neispravan, prazan ili
neispravno povezan međujezički ili interviki naslov.',
'blanknamespace' => '(Glavno)',
'blockedtext' => 'Vaše korisničko ime ili IP adresa je blokirana od strane $1.
Dati razlog je sledeći:<br />\'\'$2\'\'<p>Možete se obratiti $1 ili nekom drugom
[[{{ns:4}}:administratori|administratoru]] da biste razgovarali o blokadi.',
'blockedtitle' => 'Korisnik je blokiran',
'blockip' => 'Blokiraj korisnika',
'blockipsuccesssub' => 'Blokiranje je uspelo',
'blockipsuccesstext' => '"$1" je blokiran.
<br />Pogledajte [[{{ns:-1}}:Ipblocklist|IP spisak blokiranih korisnika]] za pregled blokiranja.',
'blockiptext' => 'Upotrebite donji upitnik da biste uklonili pravo pisanja
sa određene IP adrese ili korisničkog imena.
Ovo bi trebalo da bude urađeno samo da bi se sprečio vandalizam, i u skladu
sa [[{{ns:4}}:Smernice|smernicama]].
Unesite konkretan razlog ispod (na primer, navodeći koje
stranice su vandalizovane).',
'blocklink' => 'blokiraj',
'blocklistline' => '$1, $2 blokirao korisnika [[Korisnik:$3|$3]], (ističe $4)',
'blocklogentry' => 'je blokirao "$1" sa vremenom isticanja blokade od $2',
'blocklogpage' => 'istorija blokiranja',
'blocklogtext' => 'Ovo je istorija blokiranja i deblokiranja korisnika. Automatski
blokirane IP adrese nisu ispisane ovde. Pogledajte [[{{ns:-1}}:Ipblocklist|blokirane IP adrese]] za spisak trenutnih zabrana i blokiranja.',
'bold_sample' => 'podebljan tekst',
'bold_tip' => 'podebljan tekst',
'booksources' => 'Štampani izvori',
'booksourcetext' => 'Ispod je spisak veza na druge sajtove koji
prodaju nove i korišćene knjige, i takođe mogu imati daljnje informacije
o knjigama koje tražite.
{{SITENAME}} ne sarađuje ni se jednim od ovih preduzeća, i
ovaj spisak ne treba da se shvati kao potvrda njihovog kvaliteta.',
'brokenredirects' => 'Pokvarena preusmerenja',
'brokenredirectstext' => 'Sledeća preusmerenja su povezana na nepostojeći članak.',
'bugreports' => 'Prijave grešaka',
'bugreportspage' => '{{ns:4}}:Prijave_grešaka',
'bydate' => 'po datumu',
'byname' => 'po imenu',
'bysize' => 'po veličini',
'cachederror' => 'Ovo je keširana kopija zahtevane stranice, i možda nije najnovija.',
'cancel' => 'Poništi',
'cannotdelete' => 'Ne mogu da obrišem navedenu stranicu ili sliku. (Moguće je da ju je neko drugi već obrisao.)',
'cantrollback' => 'Ne mogu da vratim izmenu; poslednji autor je ujedno i jedini.',
'categories' => 'Kategorije stranica',
'categoriespagetext' => 'Sledeće kategorije već postoje u {{ns:4}}',
'category' => 'kategorija',
'category_header' => 'Članaka u kategoriji: "$1"',
'categoryarticlecount' => 'U ovoj kategoriji se nalazi $1 članaka.',
'changed' => 'promenjen',
'changepassword' => 'Promeni lozinku',
'changes' => 'izmene',
'clearyourcache' => '\'\'\'Zapamtite:\'\'\' Nakon snimanja, morate očistiti keš vašeg veb čitača da biste videli promene: \'\'\'Mozilla/Safari/Konqueror:\'\'\' držite \'\'SHIFT\'\' dok klikćete \'\'Reload\'\' (ili pritisnite  \'\'Shift+Ctrl+R\'\'), \'\'\'IE:\'\'\' pritisnite \'\'Ctrl-F5\'\', \'\'\'Opera\'\'\' pritisnite \'\'F5\'\'.',
'columns' => 'Kolona',
'compareselectedversions' => 'Upoređivanje označenih verzija',
'confirm' => 'Potvrdi',
'confirmdelete' => 'Potvrdi brisanje',
'confirmdeletetext' => 'Na putu ste da trajno obrišete stranicu
ili sliku zajedno sa njenom istorijom iz baze podataka.
Molimo vas potvrdite da nameravate da uradite ovo, da razumete
posledice, i da ovo radite u skladu sa
[[{{ns:4}}:Pravila i smernice|pravilima]] {{ns:4}}.',
'confirmemail' => 'Potvrdite adresu e-pošte',
'confirmemail_body' => 'Neko, verovatno vi, je sa IP adrese $1 registrovao nalog "$2" sa ovom adresom e-pošte na {{SITENAME}}.

Da potvrdite da ovaj nalog stvarno pripada vama i da aktivirate mogućnost e-pošte na {{SITENAME}}, otvorite ovu poveznicu u vašem brauzeru:

$3

Ako ovo niste vi, ne pratite poveznicu. Ovaj kod za potvrdu će isteći u $4.',
'confirmemail_error' => 'Nešto je pošlo po zlu prilikom snimanja vaše potvrde.',
'confirmemail_invalid' => 'Netačan kod za potvrdu. Moguće je da je kod istekao.',
'confirmemail_loggedin' => 'Adresa vaše e-pošte je sada potvrđena.',
'confirmemail_send' => 'Pošalji kod za potvrdu',
'confirmemail_sendfailed' => 'Pošta za potvrđivanje nije poslata. Proverita adresu zbog nepravilnih karaktera.',
'confirmemail_sent' => 'E-pošta za potvrđivanje poslata.',
'confirmemail_subject' => '{{SITENAME}} adresa e-pošte za potvrđivanje',
'confirmemail_success' => 'Adresa vaše e-pošte je potvrđena. Možete sada da se prijavite i uživate u viki.',
'confirmemail_text' => 'Ova viki zahteva da potvrdite adresu vaše e-pošte pre nego što koristite mogućnosti e-pošte. Aktivirajte dugme ispod kako biste poslali poštu za potvrdu na vašu adresu. Pošta uključuje poveznicu koja sadrži kod; učitajte poveznicu u vaš brauzer da biste potvrdili da je adresa vaše e-pošte validna.',
'confirmprotect' => 'Potvrdite zaštitu',
'confirmprotecttext' => 'Da li zaista želite da zaštitite ovu stranicu?',
'confirmrecreate' => 'Korisnik [[{{ns:2}}:$1|$1]] ([[{{ns:3}}:$1|razgovor]]) je obrisao ovaj članak pošto ste počeli uređivanje sa razlogom:
: \'\'$2\'\'

Molimo potvrdite da stvarno želite da ponovo napravite ovaj članak.',
'confirmunprotect' => 'Potvrdite skidanje zaštite',
'confirmunprotecttext' => 'Da li zaista želite da skinete zaštitu sa ove stranice?',
'contextchars' => 'Karaktera konteksta po liniji',
'contextlines' => 'Linija po pogotku',
'contribslink' => 'prilozi',
'contribsub' => 'Za $1',
'contributions' => 'Prilozi korisnika',
'copyright' => 'Sadržaj je objavljen pod $1.',
'copyrightpage' => '{{ns:4}}:Autorska prava',
'copyrightpagename' => '{{SITENAME}} autorska prava',
/*'copyrightwarning' => '
\'\'\'Vaše izmene će odmah biti vidljive.\'\'\'<br />
* Za testiranje, molimo Vas koristite stranicu \'\'\'[[{{ns:4}}:pesak|pesak]]\'\'\' ili <span class=plainlinks>[http://crash.vikimedija.org/ projekat posebno namenjen za testiranje]</span>..
* Molimo vas da obratite pažnju da se za svaki doprinos {{ns:4}} smatra da je objavljen pod GNU licencom za slobodnu dokumentaciju (pogledajte [[{{ns:4}}:autorska prava|autorska prava]] za detalje).
* Ako ne želite da se vaše pisanje menja i redistribuira bez ograničenja, onda ga nemojte slati ovde.<br />
* Takođe nam obećavate da ste ga sami napisali, ili prekopirali iz izvora koji je u javnom vlasništvu ili sličnog slobodnog izvora.
----
\'\'\'NE ŠALjITE RADOVE ZAŠTIĆENE AUTORSKIM PRAVIMA BEZ DOZVOLE!\'\'\'

</div>',*/
'copyrightwarning2' => 'Napomena: Svi doprinosi {{ns:4}} mogu da se menjaju ili uklone od strane drugih korisnika. Ako ne želite da se vaši doprinosi nemilosrdno menjaju, ne šaljite ih ovde.<br />
Takođe nam obećavate da ste ovo sami napisali ili prekopirali iz izvora u javnom vlasništvu ili sličnog slobodnog izvora (vidite $1 za detalje).
<strong>NE ŠALjITE RADOVE ZAŠTIĆENE AUTORSKIM PRAVIMA BEZ DOZVOLE!</strong>',
'couldntremove' => 'Ne mogu da uklonim \'$1\'...',
'createaccount' => 'Napravi nalog',
'createaccountmail' => 'e-poštom',
'createarticle' => 'Napravi članak',
'created' => 'napravljen',
'creditspage' => 'Zasluge za stranu',
'cur' => 'tren',
'currentevents' => 'Trenutni događaji',
'currentevents-url' => 'Trenutni događaji',
'currentrev' => 'Trenutna revizija',
'currentrevisionlink' => 'prikaži trenutni pregled',
'data' => 'Podaci',
'databaseerror' => 'Greška u bazi',
'dateformat' => 'Format datuma',
'datedefault' => 'Nije bitno',
'dberrortext' => 'Desila se sintaksna greška upita baze.
Ovo je moguće zbog ilegalnog upita,
ili moguće greške u softveru.
Poslednji pokušani upit je bio:
<blockquote><tt>$1</tt></blockquote>
iz funkcije "<tt>$2</tt>".
MySQL je vratio grešku "<tt>$3: $4</tt>".',
'dberrortextcl' => 'Desila se sintaksna greška upita baze.
Poslednji pokušani upit je bio:
"$1"
iz funkcije "$2".
MySQL je vratio grešku "$3: $4".',
'deadendpages' => 'Stranice bez internih veza',
'dec' => 'dec',
'december' => 'decembar',
'default' => 'standard',
'defaultns' => 'Uobičajeno traži u ovim imenskim prostorima:',
'defemailsubject' => '{{SITENAME}} e-pošta',
'delete' => 'obriši',
'delete_and_move' => 'Obriši i premesti',
'delete_and_move_reason' => 'Obrisan kako bi se napravilo mesto za premeštanje',
'delete_and_move_text' => '==Potrebno brisanje==

Ciljani članak "[[$1]]" već postoji. Da li želite da ga obrišete da biste napravili mesto za premeštanje?',
'deletecomment' => 'Razlog za brisanje',
'deletedarticle' => 'obrisan "$1"',
'deletedrev' => '[obrisan]',
'deletedrevision' => 'Obrisana stara revizija $1.',
'deletedtext' => 'Članak "$1" je obrisan.
Pogledajte $2 za zapis o skorašnjim brisanjima.',
'deletedwhileediting' => 'Upozorenje: Ova strana je obrisana pošto ste počeli uređivanje!',
'deleteimg' => 'obr',
'deleteimgcompletely' => 'obr',
'deletepage' => 'Obriši stranicu',
'deletesub' => '(Brišem "$1")',
'deletethispage' => 'Obriši ovu stranicu',
'deletionlog' => 'istorija brisanja',
'dellogpage' => 'istorija brisanja',
'dellogpagetext' => 'Ispod je spisak najskorijih brisanja.
Sva prikazana vremene su serverska (UTC).
<ul>
</ul>',
'destfilename' => 'Ciljano ime fajla',
'developertext' => 'Akciju koju ste zatražili mogu
izvesti samo korisnici sa "developer" statusom.
Pogledajte $1.',
'developertitle' => 'Neophodan je developerski pristup',
'diff' => 'razl',
'difference' => '(Razlika između revizija)',
'disambiguations' => 'Stranice za višeznačne odrednice',
'disambiguationspage' => '{{ns:10}}:Višeznačna odrednica',
'disambiguationstext' => 'Sledeći članci se povezuju sa <i>višeznačnom odrednicom</i>. Umesto toga, oni bi trebalo da se povezuju sa odgovarajućom temom.<br />Stranica se tretira da je višeznačna odrednica ako je povezana sa $1.<br />Poveznice iz ostalih imenskih prostora <i>nisu</i> navedene ovde.',
'disclaimerpage' => '{{ns:4}}:Uslovi korišćenja, pravne napomene i odricanje odgovornosti',
'disclaimers' => 'Odricanje odgovornosti',
'doubleredirects' => 'Dvostruka preusmerenja',
'doubleredirectstext' => '<b>Pažnja:</b> Ovaj spisak može da sadrži lažne rezultate. To obično znači da postoji dodatni tekst sa vezama ispod prvog #Redirect.<br />
Svaki red sadrži veze na prvo i drugo preusmerenje, kao i na prvu liniju teksta drugog preusmerenja, što obično daje "pravi" ciljni članak, na koji bi prvo preusmerenje i trebalo da pokazuje.',
'download' => 'Preuzmi',
'eauthentsent' => 'E-pošta za potvrdu je poslata na nominovanu adresu e-pošte. Pre nego što se bilo koja druga e-pošta pošalje na nalog, moraćete da pratite uputstva u e-pošti, da biste potvrdili da je nalog zaista vaš.',
'edit' => 'Uredi',
'edit-externally' => 'Izmenite ovaj fajl koristeći spoljašnju aplikaciju',
'edit-externally-help' => 'Pogledajte [http://meta.wikimedia.org/wiki/Help:External_editors uputstvo za podešavanje] za više informacija.',
'editcomment' => 'Komentar izmene je: "<i>$1</i>".',
'editconflict' => 'Sukobljene izmene: $1',
'editcurrent' => 'Izmeni trenutnu verziju ove stranice',
'edithelp' => 'Kako se menja strana?',
'edithelppage' => '{{ns:4}}:Kako se menja strana',
'editing' => 'Menjate $1',
'editingcomment' => 'Menjate $1 (komentar)',
'editingold' => '<strong>PAŽNjA: Vi menjate stariju
reviziju ove stranice.
Ako je snimite, sve promene učinjene od ove revizije biće izgubljene.</strong>',
'editingsection' => 'Menjate $1 (deo)',
'editsection' => 'uredi',
'editold' => 'uredi',
'editthispage' => 'Uredi ovu stranicu',
'editusergroup' => 'Menjaj grupe korisnika',
'email' => 'E-pošta',
'emailauthenticated' => 'Vaša adresa e-pošte je proverena na $1.',
'emailconfirmlink' => 'Potvrdite vašu adresu e-pošte',
'emailfrom' => 'Od',
'emailmessage' => 'Poruka',
'emailnotauthenticated' => 'Vaša adresa e-pošte <strong>još uvek nije potvrđena</strong>. E-pošta neće biti poslata ni za jednu od sledećih mogućnosti.',
'emailpage' => 'Pošalji e-pismo korisniku',
'emailpagetext' => 'Ako je ovaj korisnik uneo ispravnu adresu e-pošte u
svoja korisnička podešavanja, upitnik ispod će poslati jednu poruku.
Adresa e-pošte koju ste vi uneli u svoja korisnička podešavanja će se pojaviti
kao "From" adresa poruke, tako da će primalac moći da odgovori.',
'emailsend' => 'Pošalji',
'emailsent' => 'Poruka poslata',
'emailsenttext' => 'Vaša poruka je poslata elektronskom poštom.',
'emailsubject' => 'Tema',
'emailto' => 'Za',
'emailuser' => 'Pošalji e-poštu ovom korisniku',
'emptyfile' => 'Fajl koji ste poslali deluje da je prazan. Ovo je moguće zbog greške u imenu fajla. Molimo proverite da li stvarno želite da pošaljete ovaj fajl.',
'enotif_body' => 'Dragi $WATCHINGUSERNAME,

{{SITENAME}} strana $PAGETITLE je bila $CHANGEDORCREATED $PAGEEDITDATE od strane $PAGEEDITOR,
pogledajte {{SERVER}}{{localurl:$PAGETITLE_RAWURL}} za trenutnu verziju.

$NEWPAGE

Rezime editora: $PAGESUMMARY $PAGEMINOREDIT

Kontaktirajte editora:
pošta {{SERVER}}{{localurl:Special:Emailuser|target=$PAGEEDITOR_RAWURL}}
viki {{SERVER}}{{localurl:User:$PAGEEDITOR_RAWURL}}

Neće biti drugih obaveštenja u slučaju daljih promena ukoliko ne posetite ovu stranu.
Takođe možete da resetujete zastavice za obaveštenja za sve vaše nadgledane strane na vašem spisku nadgledanja.

             Vaš prijateljski {{SITENAME}} sistem obaveštavanja

--
Da promenite podešavanja vezana za spisak nadgledanja posetite
{{SERVER}}{{localurl:Special:Watchlist|edit=yes}}

Fidbek i dalja pomoć:
{{SERVER}}{{localurl:{{ns:12}}:Sadržaj}}',
'enotif_lastvisited' => 'Pogledajte {{SERVER}}{{localurl:$PAGETITLE_RAWURL|diff=0&oldid=$OLDID}} za sve promene od vaše poslednje posete.',
'enotif_mailer' => '{{SITENAME}} obaveštenje o pošti',
'enotif_newpagetext' => 'Ovo je novi članak.',
'enotif_reset' => 'Označi sve strane kao posećene',
'enotif_subject' => '{{SITENAME}} strana $PAGETITLE je bila $CHANGEDORCREATED od strane $PAGEEDITOR',
'enterlockreason' => 'Unesite razlog za zaključavanje, uključujući procenu
vremena otključavanja',
'error' => 'Greška',
'errorpagetitle' => 'Greška',
'exbeforeblank' => 'sadržaj pre brisanja je bio: \'$1\'',
'exblank' => 'stranica je bila prazna',
'excontent' => 'sadržaj je bio: \'$1\'',
'excontentauthor' => 'sadržaj je bio: \'$1\' (a jedinu izmenu je napravio \'$2\')',
'exif-aperturevalue' => 'Otvor blende',
'exif-artist' => 'Autor',
'exif-bitspersample' => 'Bitova po komponenti',
'exif-brightnessvalue' => 'Svetlost',
'exif-cfapattern' => 'CFA šablon',
'exif-colorspace' => 'Prostor boje',
'exif-componentsconfiguration' => 'Značenje svake od komponenti',
'exif-componentsconfiguration-0' => 'ne postoji',
'exif-compressedbitsperpixel' => 'Mod kompresije slike',
'exif-compression' => 'Šema kompresije',
'exif-compression-1' => 'Nekompresovan',
'exif-compression-6' => 'JPEG',
'exif-contrast' => 'Kontrast',
'exif-contrast-0' => 'Normalno',
'exif-contrast-1' => 'Meko',
'exif-contrast-2' => 'Tvrdo',
'exif-copyright' => 'Nosilac prava',
'exif-customrendered' => 'Dodatna obrada slike',
'exif-customrendered-0' => 'Normalni proces',
'exif-customrendered-1' => 'Nestadardni proces',
'exif-datetime' => 'Datum poslednje promene fajla',
'exif-datetimedigitized' => 'Datum i vreme digitalizacije',
'exif-datetimeoriginal' => 'Datum i vreme slikanja',
'exif-devicesettingdescription' => 'Opis podešavanja uređaja',
'exif-digitalzoomratio' => 'Odnos digitalnog zuma',
'exif-exifversion' => 'Exif verzija',
'exif-exposurebiasvalue' => 'Kompenzacija ekspozicije',
'exif-exposureindex' => 'Indeks ekspozicije',
'exif-exposuremode' => 'Režim izbora ekspozicije',
'exif-exposuremode-0' => 'Automatski',
'exif-exposuremode-1' => 'Ručno',
'exif-exposuremode-2' => 'Automatski sa zadatim rasponom',
'exif-exposureprogram' => 'Program ekspozicije',
'exif-exposureprogram-0' => 'Nepoznato',
'exif-exposureprogram-1' => 'Ručno',
'exif-exposureprogram-2' => 'Normalni program',
'exif-exposureprogram-3' => 'Prioritet otvora blende',
'exif-exposureprogram-4' => 'Prioritet zatvarača',
'exif-exposureprogram-5' => 'Umetnički program (na bazi nužne dubine polja)',
'exif-exposureprogram-6' => 'Sportski program (na bazi što bržeg zatvarača)',
'exif-exposureprogram-7' => 'Portretni režim (za krupne kadrove sa neoštrom pozadinom)',
'exif-exposureprogram-8' => 'Režim pejzaža (za slike pejzaža sa oštrom pozadinom)',
'exif-exposuretime' => 'Ekspozicija',
'exif-filesource' => 'Izvorni fajl',
'exif-filesource-3' => 'Digitalni fotoaparat',
'exif-flash' => 'Blic',
'exif-flashenergy' => 'Energija blica',
'exif-flashpixversion' => 'Podržana verzija Flešpiksa',
'exif-fnumber' => 'F broj otvora blende',
'exif-focallength' => 'Fokusna daljina sočiva',
'exif-focallengthin35mmfilm' => 'Ekvivalent fokusne daljine za 35 mm film',
'exif-focalplaneresolutionunit' => 'Jedinica rezolucije fokusne ravni',
'exif-focalplaneresolutionunit-2' => 'inči',
'exif-focalplanexresolution' => 'Vodoravna rezolucija fokusne ravni',
'exif-focalplaneyresolution' => 'Horizonatlna rezolucija fokusne ravni',
'exif-gaincontrol' => 'Kontrola osvetljenosti',
'exif-gaincontrol-0' => 'Nema',
'exif-gaincontrol-1' => 'Malo povećanje',
'exif-gaincontrol-2' => 'Veliko povećanje',
'exif-gaincontrol-3' => 'Malo smanjenje',
'exif-gaincontrol-4' => 'Veliko smanjenje',
'exif-gpsaltitude' => 'Visina',
'exif-gpsaltituderef' => 'Visina ispod ili iznad mora',
'exif-gpsareainformation' => 'Ime GPS područja',
'exif-gpsdatestamp' => 'GPS datum',
'exif-gpsdestbearing' => 'Azimut objekta',
'exif-gpsdestbearingref' => 'Indeks azimuta objekta',
'exif-gpsdestdistance' => 'Udaljenost objekta',
'exif-gpsdestdistanceref' => 'Merne jedinice udaljenosti objekta',
'exif-gpsdestlatitude' => 'Geografska širina objekta',
'exif-gpsdestlatituderef' => 'Indeks geografske širine objekta',
'exif-gpsdestlongitude' => 'Geografska dužina objekta',
'exif-gpsdestlongituderef' => 'Indeks geografske dužine objekta',
'exif-gpsdifferential' => 'GPS diferencijalna korekcija',
'exif-gpsdirection-m' => 'Magnetni pravac',
'exif-gpsdirection-t' => 'Pravi pravac',
'exif-gpsdop' => 'Preciznost merenja',
'exif-gpsimgdirection' => 'Azimut slike',
'exif-gpsimgdirectionref' => 'Tip azimuta slike (pravi ili magnetni)',
'exif-gpslatitude' => 'Širina',
'exif-gpslatitude-n' => 'Sever',
'exif-gpslatitude-s' => 'Jug',
'exif-gpslatituderef' => 'Severna ili južna širina',
'exif-gpslongitude' => 'Dužina',
'exif-gpslongitude-e' => 'Istok',
'exif-gpslongitude-w' => 'Zapad',
'exif-gpslongituderef' => 'Istočna ili zapadna dužina',
'exif-gpsmapdatum' => 'Korišćeni geodetski koordinatni sistem',
'exif-gpsmeasuremode' => 'Režim merenja',
'exif-gpsmeasuremode-2' => 'Dvodimenzionalno merenje',
'exif-gpsmeasuremode-3' => 'Trodimenzionalno merenje',
'exif-gpsprocessingmethod' => 'Ime metode obrade GPS podataka',
'exif-gpssatellites' => 'Upotrebljeni sateliti',
'exif-gpsspeed' => 'Brzina GPS prijemnika',
'exif-gpsspeed-k' => 'Kilometri na čas',
'exif-gpsspeed-m' => 'Milje na čas',
'exif-gpsspeed-n' => 'Čvorovi',
'exif-gpsspeedref' => 'Jedinica brzine',
'exif-gpsstatus' => 'Status prijemnika',
'exif-gpsstatus-a' => 'Merenje u toku',
'exif-gpsstatus-v' => 'Spreman za prenos',
'exif-gpstimestamp' => 'Vreme po GPS-u (atomski sat)',
'exif-gpstrack' => 'Azimut prijemnika',
'exif-gpstrackref' => 'Tip azimuta prijemnika (pravi ili magnetni)',
'exif-gpsversionid' => 'Verzija bloka GPS-informacije',
'exif-imagedescription' => 'Ime slike',
'exif-imagelength' => 'Visina',
'exif-imageuniqueid' => 'Jedinstveni identifikator slike',
'exif-imagewidth' => 'Širina',
'exif-isospeedratings' => 'ISO vrednost',
'exif-jpeginterchangeformat' => 'Udaljenost JPEG pregleda od početka fajla',
'exif-jpeginterchangeformatlength' => 'Količina bajtova JPEG pregleda',
'exif-lightsource' => 'Izvor svetlosti',
'exif-lightsource-0' => 'Nepoznato',
'exif-lightsource-1' => 'Dnevna svetlost',
'exif-lightsource-10' => 'Oblačno vreme',
'exif-lightsource-11' => 'Senka',
'exif-lightsource-12' => 'Fluorescentna svetlost (D 5700 – 7100K)',
'exif-lightsource-13' => 'Fluorescentna svetlost (N 4600 – 5400K)',
'exif-lightsource-14' => 'Fluorescentna svetlost (W 3900 – 4500K)',
'exif-lightsource-15' => 'Bela fluorescencija (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Standardno svetlo A',
'exif-lightsource-18' => 'Standardno svetlo B',
'exif-lightsource-19' => 'Standardno svetlo C',
'exif-lightsource-2' => 'Fluorescentno',
'exif-lightsource-24' => 'ISO studijski volfram',
'exif-lightsource-255' => 'Drugi izvor svetla',
'exif-lightsource-3' => 'Volfram (svetlo)',
'exif-lightsource-4' => 'Blic',
'exif-lightsource-9' => 'Lepo vreme',
'exif-make' => 'Proizvođač kamere',
'exif-makernote' => 'Napomene proizvođača',
'exif-maxaperturevalue' => 'Minimalni broj otvora blende',
'exif-meteringmode' => 'Režim merača vremena',
'exif-meteringmode-0' => 'Nepoznato',
'exif-meteringmode-1' => 'Prosek',
'exif-meteringmode-2' => 'Prosek sa težištem na sredini',
'exif-meteringmode-255' => 'Drugo',
'exif-meteringmode-3' => 'Tačka',
'exif-meteringmode-4' => 'Više tačaka',
'exif-meteringmode-5' => 'Matrični',
'exif-meteringmode-6' => 'Delimični',
'exif-model' => 'Model kamere',
'exif-oecf' => 'Optoelektronski faktor konverzije',
'exif-orientation' => 'Orijentacija kadra',
'exif-orientation-1' => 'Normalno',
'exif-orientation-2' => 'Obrnuto po horizontali',
'exif-orientation-3' => 'Zaokrenuto 180°',
'exif-orientation-4' => 'Obrnuto po vertikali',
'exif-orientation-5' => 'Zaokrenuto 90° suprotno od smera kazaljke na satu i obrnuto po vertikali',
'exif-orientation-6' => 'Zaokrenuto 90° u smeru kazaljke na satu',
'exif-orientation-7' => 'Zaokrenuto 90° u smeru kazaljke na satu i obrnuto po vertikali',
'exif-orientation-8' => 'Zaokrenuto 90° suprotno od smera kazaljke na satu',
'exif-photometricinterpretation' => 'Kolor model',
'exif-pixelxdimension' => 'Puna širina slike',
'exif-pixelydimension' => 'Puna visina slike',
'exif-planarconfiguration' => 'Princip rasporeda podataka',
'exif-planarconfiguration-1' => 'delimični format',
'exif-planarconfiguration-2' => 'planarni format',
'exif-primarychromaticities' => 'Hromacitet primarnih boja',
'exif-referenceblackwhite' => 'Mesto bele i crne tačke',
'exif-relatedsoundfile' => 'Povezani zvučni zapis',
'exif-resolutionunit' => 'Jedinica rezolucije',
'exif-rowsperstrip' => 'Broj redova u bloku',
'exif-samplesperpixel' => 'Broj kolor komponenata',
'exif-saturation' => 'Saturacija',
'exif-saturation-0' => 'Normalno',
'exif-saturation-1' => 'Niska saturacija',
'exif-saturation-2' => 'Visoka saturacija',
'exif-scenecapturetype' => 'Tip scene na snimku',
'exif-scenecapturetype-0' => 'Standardno',
'exif-scenecapturetype-1' => 'Pejzaž',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Noćno',
'exif-scenetype' => 'Tip scene',
'exif-scenetype-1' => 'Direktno fotografisana slika',
'exif-sensingmethod' => 'Tip senzora',
'exif-sensingmethod-1' => 'Nedefinisano',
'exif-sensingmethod-2' => 'Jednokristalni matrični senzor',
'exif-sensingmethod-3' => 'Dvokristalni matrični senzor',
'exif-sensingmethod-4' => 'Trokristalni matrični senzor',
'exif-sensingmethod-5' => 'Sekvencijalni matrični senzor',
'exif-sensingmethod-7' => 'Trobojni linearni senzor',
'exif-sensingmethod-8' => 'Sekvencijalni linearni senzor',
'exif-sharpness' => 'Oštrina',
'exif-sharpness-0' => 'Normalno',
'exif-sharpness-1' => 'Meko',
'exif-sharpness-2' => 'Tvrdo',
'exif-shutterspeedvalue' => 'Brzina zatvarača',
'exif-software' => 'Korišćen softver',
'exif-spatialfrequencyresponse' => 'Prostorna frekvencijska karakteristika',
'exif-spectralsensitivity' => 'Spektralna osetljivost',
'exif-stripbytecounts' => 'Veličina kompresovanog bloka',
'exif-stripoffsets' => 'Položaj bloka podataka',
'exif-subjectarea' => 'Položaj i površina objekta snimka',
'exif-subjectdistance' => 'Udaljenost do objekta',
'exif-subjectdistance-value' => '$1 metara',
'exif-subjectdistancerange' => 'Raspon udaljenosti subjekata',
'exif-subjectdistancerange-0' => 'Nepoznato',
'exif-subjectdistancerange-1' => 'Krupni kadar',
'exif-subjectdistancerange-2' => 'Bliski kadar',
'exif-subjectdistancerange-3' => 'Daleki kadar',
'exif-subjectlocation' => 'Položaj subjekta',
'exif-subsectime' => 'Deo sekunde u kojem je slikano',
'exif-subsectimedigitized' => 'Deo sekunde u kojem je digitalizovano',
'exif-subsectimeoriginal' => 'Deo sekunde u kojem je fotografisano',
'exif-transferfunction' => 'Funkcija preoblikovanja kolor prostora',
'exif-usercomment' => 'Korisnički komentar',
'exif-whitebalance' => 'Balans bele boje',
'exif-whitebalance-0' => 'Automatski',
'exif-whitebalance-1' => 'Ručno',
'exif-whitepoint' => 'Hromacitet bele tačke',
'exif-xresolution' => 'Horizonatalna rezolucija',
'exif-ycbcrcoefficients' => 'Matrični koeficijenti transformacije kolor prostora',
'exif-ycbcrpositioning' => 'Razmeštaj komponenata Y i C',
'exif-ycbcrsubsampling' => 'Odnos komponente Y prema C',
'exif-yresolution' => 'Vertikalna rezolucija',
'expiringblock' => 'ističe $1',
'explainconflict' => 'Neko drugi je promenio ovu stranicu otkad ste vi počeli da je menjate.
Gornje tekstualno polje sadrži tekst stranice kakav trenutno postoji.
Vaše izmene su prikazane u donjem tekstu.
Moraćete da unesete svoje promene u postojeći tekst.
<b>Samo</b> tekst u gornjem tekstualnom polju će biti snimljen kada
pritisnete "Snimi stranicu".<br />',
'export' => 'Izvezi stranice',
'exportcuronly' => 'Uključi samo trenutnu reviziju, ne celu istoriju',
'exporttext' => 'Možete izvesti tekst i istoriju promena određene
stranice ili grupe stranica u XML formatu; ovo onda može biti uvezeno u drugi
viki koji koristi MedijaViki softver, transformisano, ili korišćeno za vaše lične
potrebe.',
'externaldberror' => 'Došlo je ili do greške pri spoljašnjoj autentifikaciji baze podataka ili vam nije dozvoljeno da ažurirate svoj spoljašnji nalog.',
'extlink_sample' => 'http://www.adresa.com opis adrese',
'extlink_tip' => 'spoljašnja poveznica (zapamti prefiks http://)',
'faq' => 'NPP',
'faqpage' => '{{ns:4}}:NPP',
'feb' => 'feb',
'february' => 'februar',
'feedlinks' => 'Fid:',
'filecopyerror' => 'Ne mogu da iskopiram fajl "$1" na "$2".',
'filedeleteerror' => 'Ne mogu da obrišem fajl "$1".',
'filedesc' => 'Opis',
'fileexists' => 'Fajl sa ovim imenom već postoji. Molimo proverite $1 ako niste sigurni da li želite da ga promenite.',
'fileexists-forbidden' => 'Fajl sa ovim imenom već postoji; molimo vratite se i pošaljite ovaj fajl pod novim imenom. [[{{ns:6}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Fajl sa ovim imenom već postoji u zajedničkoj ostavi; molimo vratite se i pošaljite ovaj fajl pod novim imenom. [[{{ns:6}}:$1|thumb|center|$1]]',
'fileinfo' => '$1KB, MIME tip: <code>$2</code>',
'filemissing' => 'Nedostaje fajl',
'filename' => 'Ime fajla',
'filenotfound' => 'Ne mogu da nađem fajl "$1".',
'filerenameerror' => 'Ne mogu da promenim ime fajla "$1" u "$2".',
'files' => 'Fajlovi',
'filesource' => 'Izvor',
'filestatus' => 'Status autorskih prava',
'fileuploaded' => 'Fajl "$1" je uspešno poslat.
Molim pratite ovu vezu: ($2) do stranice za opisivanje i unesite
informacije o fajlu, kao odakle je, kada i
ko ga je napravio, i bilo šta drugo što znate o njemu.',
'fileuploadsummary' => 'Opis:',
'formerror' => 'Greška: ne mogu da pošaljem upitnik',
'friday' => 'petak',
'getimagelist' => 'pribavljam spisak slika',
'go' => 'Idi',
'groups' => 'Korisničke grupe',
'guesstimezone' => 'Popuni iz brauzera',
'headline_sample' => 'Naslov',
'headline_tip' => 'Podnaslov',
'help' => 'Pomoć',
'helppage' => '{{ns:12}}:Sadržaj',
'hide' => 'sakrij',
'hidetoc' => 'sakrij',
'hist' => 'ist',
'histfirst' => 'Najranije',
'histlast' => 'Poslednje',
'histlegend' => 'Objašnjenje: (tren) = razlika sa trenutnom verzijom,
(posl) = razlika sa prethodnom verzijom, M = mala izmena',
'history' => 'Istorija stranice',
'history_short' => 'istorija',
'historywarning' => 'Pažnja: stranica koju želite da obrišete ima istoriju:',
'hr_tip' => 'Horizontalna linija',
'illegalfilename' => 'Fajl "$1" sadrži karaktere koji nisu dozvoljeni na ovoj stranici. Molimo Vas promenite ime fajla i ponovo ga pošaljite.',
'ilsubmit' => 'Traži',
'image_sample' => 'ime_slike.jpg',
'image_tip' => 'Uklopljena slika',
'imagelinks' => 'Upotreba slike',
'imagelist' => 'Spisak slika',
'imagelistall' => 'sve',
'imagelisttext' => 'Ispod je spisak $1 slika poređanih $2.',
'imagemaxsize' => 'Ograniči slike na stranama za razgovor o slikama na:',
'imagepage' => 'Pogledaj stranu slike',
'imagereverted' => 'Vraćanje na raniju verziju je uspešno.',
'imgdelete' => 'obr',
'imgdesc' => 'opis',
'imghistlegend' => 'Objašnjenje: (tren) = ovo je trenutna slika, (obr) = obriši
ovu staru verziju, (vrt) = vrati na ovu staru verziju.
<br /><i>Kliknite na datum da vidite sliku poslatu tog datuma</i>.',
'imghistory' => 'Istorija slike',
'imglegend' => 'Objašnjenje: (opis) = prikaži/izmeni opis slike.',
'immobile_namespace' => 'Ciljani naziv je posebnog tipa; ne mogu da premestim strane u taj imenski prostor.',
'import' => 'Uvoz stranica',
'importfailed' => 'Uvoz nije uspeo: $1',
'importhistoryconflict' => 'Postoji konfliktna istorija revizija',
'importinterwiki' => 'Transviki uvoženje',
'importnosources' => 'Nije definisan nijedan izvor transviki uvoženja i direktna slanja istorija su onemogućena.',
'importnotext' => 'Stranica je prazna ili bez teksta.',
'importsuccess' => 'Uspešno ste uvezli stranicu!',
'importtext' => 'Molimo izvezite fajl iz izvornog vikija koristeći [[{{ns:-1}}:Export|izvoz]], sačuvajte ga kod sebe i pošaljite ovde.',
'infiniteblock' => 'beskonačan',
'info_short' => 'Informacije',
'infosubtitle' => 'Informacije za stranicu',
'internalerror' => 'Interna greška',
'intl' => 'Međujezičke veze',
'invalidemailaddress' => 'Adresa e-pošte ne može da se primi jer nije pravilnog formata. Molimo unesite dobro-formatiranu adresu ili ispraznite to polje.',
'invert' => 'Obrni selekciju',
'ip_range_invalid' => 'Netačan raspon IP adresa.',
'ipaddress' => 'IP adresa/korisničko ime',
'ipadressorusername' => 'IP adresa ili korisničko ime',
'ipb_expiry_invalid' => 'Pogrešno vreme trajanja.',
'ipbexpiry' => 'Trajanje',
'ipblocklist' => 'Spisak blokiranih IP adresa i korisnika',
'ipblocklistempty' => 'Spisak blokova je prazan.',
'ipboptions' => '2 sata:2 hours,1 dan:1 day,3 dana:3 days,1 nedelja:1 week,2 nedelje:2 weeks,1 mesec:1 month,3 meseca:3 months,6 meseci:6 months,1 godina:1 year,beskonačno:infinite',
'ipbother' => 'Ostalo vreme',
'ipbotheroption' => 'ostalo',
'ipbreason' => 'Razlog',
'ipbsubmit' => 'Obuzdaj ovog korisnika',
'ipusubmit' => 'Otpusti ovu adresu',
'isredirect' => 'Preusmerivač',
'italic_sample' => 'kurzivan tekst',
'italic_tip' => 'kurzivan tekst',
'iteminvalidname' => 'Problem sa \'$1\', neispravno ime...',
'jan' => 'jan',
'january' => 'januar',
'jul' => 'jul',
'july' => 'jul',
'jun' => 'jun',
'june' => 'jun',
'laggedslavemode' => 'Upozorenje: Moguće je da strana nije skoro ažurirana.',
'largefile' => 'Preporučuje se da slike ne pređu veličinu od 100K.',
'largefileserver' => 'Ovaj fajl je veći nego što je podešeno da server dozvoli.',
'last' => 'posl',
'lastmodified' => 'Ova stranica je poslednji put izmenjena $1.',
'lastmodifiedby' => 'Ovu stranicu je poslednji put promenio $2, dana $1.',
'license' => 'Licenca',
'lineno' => 'Linija $1:',
'link_sample' => 'naslov poveznice',
'link_tip' => 'unutrašlja poveznica',
'linklistsub' => '(spisak veza)',
'linkshere' => 'Sledeće stranice su povezane ovde:',
'linkstoimage' => 'Sledeće stranice koriste ovu sliku:',
'listingcontinuesabbrev' => ' nast.',
'listusers' => 'Spisak korisnika',
'loadhist' => 'Učitavam istoriju stranice',
'loadingrev' => 'učitavam reviziju za razliku',
'localtime' => 'Prikaz lokalnog vremena',
'lockbtn' => 'Zaključaj bazu',
'lockconfirm' => 'Da, zaista želim da zaključam bazu.',
'lockdb' => 'Zaključaj bazu',
'lockdbsuccesssub' => 'Baza je zaključana',
'lockdbsuccesstext' => '{{ns:4}} baza podataka je zaključana.
<br />Setite se da je otključate kada završite sa održavanjem.',
'lockdbtext' => 'Zaključavanje baze će svim korisnicima ukinuti mogućnost izmene stranica,
promene korisničkih podešavanja, izmene spiska nadgledanja, i svega ostalog
što zahteva promene u bazi.
Molim potvrdite da je ovo zaista ono što nameravate da uradite, i da ćete
otključati bazu kada završite posao oko njenog održavanja.',
'locknoconfirm' => 'Niste potvrdili svoju nameru.',
'log' => 'Protokoli',
'login' => 'Prijavi se',
'loginerror' => 'Greška pri prijavljivanju',
'loginpagetitle' => 'Prijavljivanje',
'loginproblem' => '<b>Bilo je problema sa vašim prijavljivanjem.</b><br />Probajte ponovo!',
'loginprompt' => 'Morate da imate omogućene kolačiće (\'\'\'cookies\'\'\') da biste se prijavili na {{SITENAME}}.',
'loginreqlink' => 'prijava',
'loginreqpagetext' => 'Morate $1 da biste videli ostale strane.',
'loginreqtitle' => 'Potrebno [[Special:Userlogin|prijavljivanje]]',
'loginsuccess' => 'Sada ste prijavljeni na {{SITENAME}} kao "$1".',
'loginsuccesstitle' => 'Prijavljivanje uspešno',
'logout' => 'Odjavi se',
'logouttext' => 'Sada ste odjavljeni. Možete da nastavite da koristite projekat {{SITENAME}} anonimno, ili se ponovo prijaviti kao drugi korisnik. Obratite pažnju da neke stranice mogu nastaviti da se prikazuju kao da ste još uvek prijavljeni, dok ne očistite keš svog brauzera.',
'logouttitle' => 'Odjavi se',
'lonelypages' => 'Siročići',
'longpages' => 'Dugačke stranice',
'longpagewarning' => '\'\'\'PAŽNjA:\'\'\' Ova stranica ima $1 kilobajta. Molimo vas da razmotrite razbijanje stranice na manje delove.',
'mailerror' => 'Greška pri slanju e-pošte: $1',
'mailmypassword' => 'Pošalji mi novu lozinku',
'mailnologin' => 'Nema adrese za slanje',
'mailnologintext' => 'Morate biti [[Special:Userlogin|prijavljeni]]
i imati ispravnu adresu e-pošte u vašim [[Special:Preferences|podešavanjima]]
da biste slali elektronsku poštu drugim korisnicima.',
'mainpage' => 'Glavna strana',
'mainpagedocfooter' => 'Molimo vidite [http://meta.wikimedia.org/wiki/MediaWiki_i18n dokumentaciju o podešavanju interfejsa vašim potrebama] i [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide korisnički vodič] za korišćenje i pomoć pri konfigurisanju.',
'mainpagetext' => 'Viki softver je uspešno instaliran.',
'makesysop' => 'Davanje administratorskih ovlašćenja korisniku',
'makesysopfail' => '<b>Korisnik "$1" ne može da postane administrator. (Da li ste pravilno uneli ime?)</b>',
'makesysopname' => 'Ime korisnika:',
'makesysopok' => '<b>Korisnik "$1" je sada administrator</b>',
'makesysopsubmit' => 'Dodajte ovom korisniku administratorska ovlašćenja',
'makesysoptext' => 'Ovaj formular se koristi od strane birokrata da se obični korisnici pretvore u administratore. Unesite ime korisnika u kutiju i pritisnite dugme da bi korisnik postao administrator',
'makesysoptitle' => 'Pretvorite korisnika u administratora',
'mar' => 'mar',
'march' => 'mart',
'markaspatrolleddiff' => 'Označi kao patrolirano',
'markaspatrolledtext' => 'Označi ovaj članak kao patroliran',
'markedaspatrolled' => 'Označi kao patrolirano',
'markedaspatrolledtext' => 'Izabrana revizija je označena kao patrolirana.',
'matchtotals' => 'Upit "$1" je nađen u $2 naslova članaka
i tekst $3 članaka.',
'math' => 'Prikazivanje matematike',
'math_bad_output' => 'Ne mogu da napišem ili napravim direktorijum za math izveštaj.',
'math_bad_tmpdir' => 'Ne mogu da napišem ili napravim privremeni math direktorijum',
'math_failure' => 'Neuspeh pri parsiranju',
'math_image_error' => 'PNG konverzija neuspešna; proverite tačnu instalaciju latex-a, dvips-a, gs-a i convert-a',
'math_lexing_error' => 'rečnička greška',
'math_notexvc' => 'Nedostaje izvršno texvc; molimo pogledajte math/README da biste podesili.',
'math_sample' => 'Ovde unesite formulu',
'math_syntax_error' => 'sintaksna greška',
'math_tip' => 'Matematička formula (LaTeX)',
'math_unknown_error' => 'nepoznata greška',
'math_unknown_function' => 'nepoznata funkcija',
'may' => 'maj',
'may_long' => 'maj',
'media_sample' => 'ime_medija_fajla.mp3',
'media_tip' => 'Putanja ka multimedijalnom fajlu',
'mediawarning' => '\'\'\'Upozorenje\'\'\': Ovaj fajl sadrži loš kod, njegovim izvršavanjem možete da ugrozite vaš sistem.
<hr />',
'metadata' => 'Metapodaci',
'mimesearch' => 'MIME pretraga',
'mimetype' => 'MIME tip:',
'minlength' => 'Imena slika moraju imati bar tri slova.',
'minoredit' => 'Ovo je mala izmena',
'minoreditletter' => 'M',
'missingarticle' => 'Baza nije našla tekst stranice
koji je trebalo, nazvan "$1".

<p>Ovo je obično izazvano praćenjem zastarelog "razl" ili veze ka istoriji
stranice koja je obrisana.

<p>Ako ovo nije slučaj, možda ste pronašli grešku u softveru.
Molimo vas prijavite ovo jednom od [[{{ns:4}}:Administratori|administratora]], zajedno sa URL-om.',
'missingimage' => '<b>Ovde nedostaje slika</b><br /><i>$1</i>',
'monday' => 'ponedeljak',
'moredotdotdot' => 'Još...',
'mostlinked' => 'Najviše povezane strane',
'move' => 'premesti',
'movearticle' => 'Premesti stranicu',
'movedto' => 'premeštena na',
'movelogpage' => 'istorija premeštanja',
'movelogpagetext' => 'Ispod je spisak premeštenih članaka.',
'movenologin' => 'Niste ulogovani',
'movenologintext' => 'Morate biti registrovani korisnik i [[Special:Userlogin|prijavljeni]]
da biste premestili stranicu.',
'movepage' => 'Premeštanje stranice',
'movepagebtn' => 'premesti stranicu',
'movepagetalktext' => 'Odgovarajuća stranica za razgovor, ako postoji, će biti automatski premeštena istovremeno \'\'\'osim:\'\'\'
*Ako premeštate stranicu preko imenskih prostora,
*Neprazna stranica za razgovor već postoji pod novim imenom, ili
*Odbeležite donju kućicu.

U tim slučajevima, moraćete ručno da premestite stranicu ukoliko to želite.',
'movepagetext' => 'Donji upitnik će preimenovati stranicu, premeštajući svu
njenu istoriju na novo ime.
Stari naslov će postati preusmerenje na novi naslov.
Poveznice prema starom naslovu neće biti promenjene; obavezno
potražite [[{{ns:-1}}:DoubleRedirects|dvostruka]] ili [[{{ns:-1}}:BrokenRedirects|pokvarena preusmerenja]].
Na vama je odgovornost da veze i dalje idu tamo gde bi i trebalo da idu.

Obratite pažnju da stranica \'\'\'neće\'\'\' biti pomerena ako već postoji
stranica sa novim naslovom, osim ako je ona prazna ili preusmerenje i nema
istoriju promena. Ovo znači da ne možete preimenovati stranicu na ono ime
sa koga ste je preimenovali ako pogrešite, i ne možete prepisati
postojeću stranicu.

<b>PAŽNjA!</b>
Ovo može biti drastična i neočekivana promena za popularnu stranicu;
molimo da budete sigurni da razumete posledice ovoga pre nego što
nastavite.',
'movereason' => 'Razlog',
'movetalk' => 'Premesti "stranicu za razgovor" takođe, ako je moguće.',
'movethispage' => 'premesti ovu stranicu',
'mw_math_html' => 'HTML ako je moguće, inače PNG',
'mw_math_mathml' => 'MathML ako je moguće (eksperimentalno)',
'mw_math_modern' => 'Preporučeno za savremene brauzere',
'mw_math_png' => 'Uvek prikaži PNG',
'mw_math_simple' => 'HTML ako je vrlo jednostavno, inače PNG',
'mw_math_source' => 'Ostavi kao TeH (za tekstualne brauzere)',
'mycontris' => 'Moji prilozi',
'mypage' => 'Moja stranica',
'mytalk' => 'Moj razgovor',
'namespace' => 'Imenski prostor:',
'namespacesall' => 'svi',
'nbytes' => '$1 bajtova',
'newarticle' => '(Novi)',
'newarticletext' => '\'\'\'{{SITENAME}} još uvek nema {{NAMESPACE}} članak pod imenom {{PAGENAME}}.\'\'\'
* Da biste započeli stranicu, počnite da kucate u polju ispod. Ako ste ovde došli greškom, samo pritisnite \'\'\'back\'\'\' dugme vašeg brauzera.<br /> \'\'Pogledajte [[Pomoć:Sadržaj|\'\'\'\'\'pomoć\'\'\'\'\']] za više informacija.\'\'',
'newbies' => 'novajlije',
'newimages' => 'Galerija novih slika',
'newmessageslink' => 'novih poruka',
'newpage' => 'Nova stranica',
'newpageletter' => 'N',
'newpages' => 'Nove stranice',
'newpassword' => 'Nova šifra',
'newtitle' => 'Novi naslov',
'newwindow' => '(novi prozor)',
'next' => 'sled',
'nextdiff' => 'Sledeća izmena →',
'nextn' => 'sledećih $1',
'nextpage' => 'Sledeća stranica ($1)',
'nextrevision' => 'Sledeća revizija →',
'nlinks' => '$1 veza',
'noarticletext' => '\'\'\'{{SITENAME}} još uvek nema članak pod tim imenom.\'\'\'
* \'\'\'[{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} Počni {{PAGENAME}} članak]\'\'\'
* [[{{ns:-1}}:Search/{{PAGENAME}}|Pretraži {{PAGENAME}}]] u ostalim člancima
* [[{{ns:-1}}:Whatlinkshere/{{NAMESPACE}}:{{PAGENAME}}|Stranice koje su povezane za]] {{PAGENAME}} članak
----
* \'\'\'Ukoliko ste napravili ovaj članak u poslednjih nekoliko minuta i još se nije pojavio, postoji pogućnost da je server u zastoju zbog osvežavanja baze podataka.\'\'\' Molimo Vas probajte sa [{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=purge}} osvežavanjem] ili sačekajte i proverite kasnije ponovo pre ponovnog pravljenja članka.',
'noconnect' => 'Žalimo! Viki ima neke tehničke poteškoće, i ne može da se poveže se serverom baze.',
'nocontribs' => 'Nisu nađene promene koje zadovoljavaju ove uslove.',
'nocookieslogin' => '{{SITENAME}} koristi kolačiće (\'\'cookies\'\') da bi se korisnici prijavili. Vi ste onemogućili kolačiće na Vašem računaru. Molimo omogućite ih i pokušajte ponovo sa prijavom.',
'nocookiesnew' => 'Korisnički nalog je napravljen, ali niste prijavljeni. {{SITENAME}} koristi kolačiće (\'\'cookies\'\') da bi se korisnici prijavili. Vi ste onemogućili kolačiće na svom računaru. Molimo omogućite ih, a onda se prijavite sa svojim novim korisničkim imenom i lozinkom.',
'nocreativecommons' => 'Creative Commons RDF metapodaci onemogućeni za ovaj server.',
'nocredits' => 'Nisu dostupne informacije o zaslugama za ovu stranu.',
'nodb' => 'Ne mogu da izaberem bazu $1',
'nodublincore' => 'Dublin Core RDF metapodaci onemogućeni za ovaj server.',
'noemail' => 'Ne postoji adresa e-pošte za korisnika "$1".',
'noemailprefs' => '<strong>Nije data ni jedna adresa e-pošte</strong>, naredna opcija
neće raditi.',
'noemailtext' => 'Ovaj korisnik nije naveo ispravnu adresu e-pošte,
ili je izabrao da ne prima e-poštu od drugih korisnika.',
'noemailtitle' => 'Nema adrese e-pošte',
'noexactmatch' => 'Ne postoji stranica sa ovakvim naslovom.

Možete [[:$1|napisati članak]] sa ovim naslovom.

Molimo Vas pretražite Vikipediju, pre kreiranja članka da bismo izbegli dupliranje već postojećeg.',
'nohistory' => 'Ne postoji istorija izmena za ovu stranicu.',
'noimage' => 'Ne postoji fajl sa ovim imenom, $1',
'noimage-linktext' => 'pošalji ga',
'noimages' => 'Nema ništa da se vidi',
'nolicense' => 'Nema',
'nolinkshere' => 'Ni jedna stranica nije povezana ovde.',
'nolinkstoimage' => 'Nema stranica koje koriste ovu sliku.',
'noname' => 'Niste izabrali ispravno korisničko ime.',
'nonefound' => '<strong>Pažnja</strong>: neuspešne pretrage su
često izazvane traženjem čestih reči kao "je" ili "od",
koje nisu indeksirane, ili navođenjem više od jednog izraza za traženje (samo stranice
koje sadrže sve izraze koji se traže će se pojaviti u rezultatu).',
'nonunicodebrowser' => '<strong>UPOZORENjE: Vaš internet pretraživač ne podržava unikod. Molimo promenite ga pre nego što počnete sa uređivanjem članka.</strong>',
'nospecialpagetext' => 'Tražili ste posebnu stranicu, koju {{SITENAME}} softver nije prepoznao.',
'nosuchaction' => 'Nema takve akcije',
'nosuchactiontext' => 'Akcija navedena u URL-u nije
prepoznata od strane {{SITENAME}} softvera.',
'nosuchspecialpage' => 'Nema takve posebne stranice',
'nosuchuser' => 'Ne postoji korisnik sa imenom "$1".
Proverite vaše kucanje, ili upotrebite donji upitnik da napravite novi korisnički nalog.',
'nosuchusershort' => 'Ne postoji korisnik "$1". Proverite da li ste dobro napisali.',
'notacceptable' => 'Viki server ne može da pruži podatke u onom formatu koji vaš klijent može da ih pročita.',
'notanarticle' => 'Nije članak',
'notargettext' => 'Niste naveli ciljnu stranicu ili korisnika
na kome bi se izvela ova funkcija.',
'notargettitle' => 'Nema cilja',
'note' => '<strong>Pažnja:</strong>',
'notextmatches' => 'Nijedan tekst članka ne odgovara',
'notitlematches' => 'Nijedan naslov članka ne odgovara',
'notloggedin' => 'Niste prijavljeni',
'nov' => 'nov',
'november' => 'novembar',
'nowatchlist' => 'Nemate ništa na svom spisku nadgledanja.',
'nowiki_sample' => 'Dodaj neformatirani tekst ovde',
'nowiki_tip' => 'Ignoriši Viki formatiranje tekst',
'nstab-category' => 'Kategorija',
'nstab-help' => 'Pomoć',
'nstab-image' => 'Slika',
'nstab-main' => 'Članak',
'nstab-media' => 'Medij',
'nstab-mediawiki' => 'Poruka',
'nstab-special' => 'Posebna',
'nstab-template' => 'Šablon',
'nstab-user' => 'Korisnička strana',
'nstab-project' => 'Članak',
'numauthors' => 'Broj različitih autora (članak): $1',
'number_of_watching_users_pageview' => '[$1 korisnik/a koji nadgleda/ju]',
'numedits' => 'Broj promena (članak): $1',
'numtalkauthors' => 'Broj različitih autora (stranica za razgovor): $1',
'numtalkedits' => 'Broj promena (stranica za razgovor): $1',
'numwatchers' => 'Broj posmatrača: $1',
'nviews' => '$1 puta pogledano',
'oct' => 'okt',
'october' => 'oktobar',
'ok' => 'da',
'oldpassword' => 'Stara lozinka',
'orig' => 'orig',
'othercontribs' => 'Bazirano na radu od strane korisnika $1.',
'otherlanguages' => 'Ostali jezici',
'others' => 'ostali',
'pagemovedsub' => 'Premeštanje uspelo',
'pagemovedtext' => 'Stranica "[[$1]]" premeštena je na "[[$2]]".',
'passwordremindertext' => 'Neko (verovatno vi, sa IP adrese $1) je zahtevao da vam pošaljemo novu šifru za prijavljivanje na {{SITENAME}}. Šifra za korisnika "$2" je sada "$3". Sada treba da se prijavite i promenite svoju šifru.',
'passwordremindertitle' => '{{SITENAME}} podsetnik za šifru',
'passwordsent' => 'Nova šifra je poslata na adresu e-pošte korisnika "$1".
Molimo vas da se prijavite pošto je primite.',
'passwordtooshort' => 'Vaša šifra je previše kratka. Mora da ima bar $1 karaktera.',
'perfcached' => 'Sledeći podaci su keširani i ne moraju biti u potpunosti ažurirani:',
'perfdisabled' => 'Žalimo! Ova mogućnost je privremeno onemogućena jer usporava bazu do te mere da više niko ne može da koristi viki.',
'perfdisabledsub' => 'Ovde je snimljena kopija $1:',
'permalink' => 'Permalink',
'personaltools' => 'Lični alati',
'popularpages' => 'Popularne stranice',
'portal' => 'Trg',
'portal-url' => 'Project:Trg',
'postcomment' => 'Pošalji komentar',
'powersearch' => 'Traži',
'powersearchtext' => 'Pretraga u imenskim prostorima:<br />
$1<br />
$2 Izlistaj preusmerenja &nbsp; Traži $3 $9',
'preferences' => 'Podešavanja',
'prefixindex' => 'Spisak prefiksa',
'prefs-help-email' => '² E-pošta (opciono): Omogućuje ostalima da vas kontaktiraju preko vaše korisničke strane ili strane razgovora sa korisnikom bez potrebe da odajete svoj identitet.',
'prefs-help-email-enotif' => 'Ova adresa se takođe koristi da vam se šalju obaveštenja preko e-pošte ako ste omogućili tu opciju.',
'prefs-help-realname' => '¹ Pravo ime (opciono): ako izaberete da date ime, ovo će biti korišćeno za pripisivanje za vaš rad.',
'prefs-misc' => 'Razna podešavanja',
'prefs-personal' => 'Korisnička podešavanja',
'prefs-rc' => 'Podešavanje skorašnjih izmena',
'prefsnologin' => 'Niste prijavljeni',
'prefsnologintext' => 'Morate biti [[{{ns:-1}}:Userlogin|prijavljeni]]
da biste podešavali korisnička podešavanja.',
'prefsreset' => 'Vraćena su uskladištena podešavanja.',
'preview' => 'Pretpregled',
'previewconflict' => 'Ovaj pretpregled oslikava kako će tekst u
tekstualnom polju izgledati ako se odlučite da ga snimite.',
'previewnote' => 'Zapamtite da je ovo samo pretpregled, i da još nije snimljen!',
'previousdiff' => '← Prethodna izmena',
'previousrevision' => '← Prethodna revizija',
'prevn' => 'prethodnih $1',
'print' => 'Štampa',
'printableversion' => 'Verzija za štampu',
'protect' => 'zaštiti',
'protectcomment' => 'Razlog zaštite',
'protectedarticle' => 'zaštićeno $1',
'protectedpage' => 'Zaštićena stranica',
'protectedpagewarning' => '\'\'\'PAŽNjA:\'\'\' Ova stranica je zaključana tako da samo korisnici sa
administratorskim privilegijama mogu da je menjaju. Uverite se
da pratite [[{{ns:4}}:Pravila o zaštiti stranica|pravila o zaštiti stranica]].',
'protectedtext' => 'Možete gledati i kopirati sadržaj ove strane:',
'protectlogpage' => 'istorija zaključavanja',
'protectlogtext' => 'Ispod je spisak zaštićenih stranica. <br />
Pogledajte [[{{ns:4}}:Pravila o zaštiti stranica|pravila o zaštiti stranica]] za više informacija.',
'protectmoveonly' => 'Zaštićeno samo od pomeranja',
'protectsub' => '(Zaštićujem "$1")',
'protectthispage' => 'Zaštiti ovu stranicu',
'proxyblocker' => 'Bloker proksija',
'proxyblockreason' => 'Vaša IP adresa je blokirana jer je ona otvoreni proksi. Molimo kontaktirajte vašeg Internet servis provajdera ili tehničku podršku i obavestite ih o ovom ozbiljnom sigurnosnom problemu.',
'proxyblocksuccess' => 'Proksi uspešno blokiran.',
'qbbrowse' => 'Prelistavaj',
'qbedit' => 'Izmeni',
'qbfind' => 'Pronađi',
'qbmyoptions' => 'Moje opcije',
'qbpageinfo' => 'Informacije o stranici',
'qbpageoptions' => 'Opcije stranice',
'qbsettings' => 'Podešavanja brze palete',
'qbspecialpages' => 'Posebne stranice',
'randompage' => 'Slučajna stranica',
'range_block_disabled' => 'Administratorska mogućnost da blokira IP grupe je isključena.',
'rclinks' => 'Pokaži poslednjih $1 promena u poslednjih $2 dana; $3 male izmene',
'rclistfrom' => 'Pokaži nove promene počev od $1',
'rclsub' => '(na stranice povezane od "$1")',
'rcnote' => 'Ispod je poslednjih <strong>$1</strong> promena u poslednjih <strong>$2</strong> dana.',
'rcnotefrom' => 'Ispod su promene od <b>$2</b> (do <b>$1</b> prikazano).',
'rcpatroldisabled' => 'Patrola skorašnjih izmena onemogućena',
'rcpatroldisabledtext' => 'Patrola skorašnjih izmena je trenutno onemogućena.',
'readonly' => 'Baza je zaključana',
'readonly_lag' => 'Baza podataka je automatski zaključana dok slejv serveri ne sustignu master',
'readonlytext' => 'Vikipedijina baza je trenutno zaključana za nove
unose i ostale izmene, verovatno zbog rutinskog održavanja,
posle čega će biti vraćena u uobičajeno stanje.
Administrator koji ju je zaključao ponudio je ovo objašnjenje:
<p>$1',
'readonlywarning' => '\'\'\'PAŽNjA:\'\'\' Baza je upravo zaključana zbog održavanja,
tako da nećete moći da snimite svoje izmene upravo sada. Možda želite da iskopirate i nalepite
tekst u tekst editor i snimite ga za kasnije.',
'recentchanges' => 'Skorašnje izmene',
'recentchangesall' => 'sve',
'recentchangescount' => 'Broj naslova u skorašnjim promenama',
'recentchangeslinked' => 'Srodne promene',
'recentchangestext' => '[[{{ns:4}}:Dobrodošli|Dobrodošli]]!<br />
Ovde pratite najskorije izmene na Vikipediji.<br />
Vikipedija na srpskom jeziku trenutno ima [[{{ns:-1}}:Statistics|\'\'\'{{NUMBEROFARTICLES}}\'\'\']] članaka.<br /> {{{{ns:4}}:Recentchanges}}',
'recreate' => 'Ponovo napravi',
'redirectedfrom' => '(Preusmereno sa $1)',
'remembermypassword' => 'Zapamti me',
'removechecked' => 'Ukloni obeležene unose iz spiska nadgledanja',
'removedwatch' => 'Uklonjeno iz spiska nadgledanja',
'removedwatchtext' => 'Stranica "$1" je uklonjena iz vašeg spiska nadgledanja.',
'removingchecked' => 'Uklanjam obeležene stvari sa spiska nadgledanja...',
'resetprefs' => 'Vrati podešavanja',
'restorelink' => '$1 obrisanih izmena',
'restrictedpheading' => 'Zaštićene posebne stranice',
'resultsperpage' => 'Pogodaka po stranici',
'retrievedfrom' => 'Dobavljeno iz "$1"',
'returnto' => 'Povratak na $1.',
'retypenew' => 'Ponovo otkucajte novu lozinku',
'reupload' => 'Ponovo pošalji',
'reuploaddesc' => 'Vrati se na upitnik za slanje.',
'reverted' => 'Vraćeno na raniju reviziju',
'revertimg' => 'vrt',
'revertmove' => 'vrati',
'revertpage' => 'Vraćeno na poslednju izmenu od korisnika $1',
'revhistory' => 'Istorija izmena',
'revisionasof' => 'Revizija od $1',
'revnotfound' => 'Revizija nije pronađena',
'revnotfoundtext' => 'Starija revizija ove stranice koju ste zatražili nije nađena.
Molimo vas da proverite URL koji ste upotrebili da biste pristupili ovoj stranici.',
'rights' => 'Prava:',
'rightslogtext' => 'Ovo je istorija izmena korisničkih prava.',
'rollback' => 'Vrati izmene',
'rollback_short' => 'Vrati',
'rollbackfailed' => 'Vraćanje nije uspelo',
'rollbacklink' => 'vrati',
'rows' => 'Redova',
'saturday' => 'subota',
'savearticle' => 'Snimi stranicu',
'savedprefs' => 'Vaša podešavanja su snimljena.',
'savefile' => 'Snimi fajl',
'saveprefs' => 'Snimi podešavanja',
'saveusergroups' => 'Sačuvaj korisničke grupe',
'scarytranscludedisabled' => '[Interviki transkluzija je onemogućena]',
'scarytranscludefailed' => '[Donošenje šablona neuspešno; žao nam je]',
'scarytranscludetoolong' => '[URL je predugačak; žao nam je]',
'search' => 'Traži',
'searchdisabled' => '<p>Izvinjavamo se! Puna pretraga teksta je privremeno onemogućena, zbog bržeg rada Vikipedije. U međuvremenu, možete koristiti Gugl pretragu ispod, koja može biti zastarela.</p>',
'searchfulltext' => 'Pretraži ceo tekst',
'searchsubtitle' => 'Tražili ste [[:$1]] [[Special:Allpages/$1|&#x5B;Sadržaj&#x5D;]]',
'searchsubtitleinvalid' => 'Tražili ste $1 ',
'searchresults' => 'Rezultati pretrage',
'searchresultshead' => 'Podešavanja rezultata pretrage',
'searchresulttext' => '<!--
Za više informacija o pretraživanju Vikipedije, pogledajte [[{{ns:4}}:Traženje|Pretraživanje Vikipedije]].
-->',
'selectnewerversionfordiff' => 'Izaberi noviju verziju za upoređivanje',
'selectolderversionfordiff' => 'Izaberi stariju verziju za upoređivanje',
'selfmove' => 'Izvorni i ciljani naziv su isti; strana ne može da se premesti preko same sebe.',
'sep' => 'sep',
'september' => 'septembar',
'servertime' => 'Vreme na serveru je sada',
'sessionfailure' => 'Izgleda da postoji problem sa vašom seansom prijave;
ova akcija je prekinuta kao predostrožnost protiv preotimanja seansi.
Molimo kliknite "back" i ponovo učitajte stranu odakle ste došli, a onda pokušajte ponovo.',
'set_rights_fail' => '<b>Korisnička prava za "$1" nisu mogla da se podese. (Da li ste pravilno uneli ime?)</b>',
'set_user_rights' => 'Postavi prava korisnika',
'setbureaucratflag' => 'Postavi prava birokrate',
'setstewardflag' => 'Postavi zastavicu domaćina',
'sharedupload' => '<br clear=both>Ova slika je sa ostave.<br />',
'shareduploadwiki' => 'Molimo pogledajte [stranicu opisa $1 fajla] za dalje informacije.',
'shareduploadwiki-linktext' => 'strana za opis fajla',
'shortpages' => 'Kratke stranice',
'show' => 'pokaži',
'showbigimage' => 'Prikaži sliku veće rezolucije ($1x$2, $3 Kb)',
'showdiff' => 'Prikaži promene',
'showhidebots' => '($1 botove)',
'showingresults' => 'Prikazujem <b>$1</b> rezultata počev od <b>$2</b>.',
'showingresultsnum' => 'Prikazujem <b>$3</b> rezultate počev od <b>$2</b>.',
'showlast' => 'Prikaži poslednjih $1 slika poređanih po $2.',
'showpreview' => 'Prikaži pretpregled',
'showtoc' => 'prikaži',
'sig_tip' => 'Vaš potpis sa trenutnim vremenom',
'sitestats' => 'Statistike sajta',
'sitestatstext' => 'Vikipedija trenutno ima \'\'\'$2\'\'\' članaka.</p>
Ovaj broj isključuje redirekte, stranice za razgovor, stranice sa opisom slike, korisničke stranice, šablone, stranice za pomoć, članke bez ijedne poveznice, i stranice o Vikipediji. Uključujući ove, imamo \'\'\'$1\'\'\' stranica.</p>

Korisnici su napravili \'\'\'$4\'\'\' izmena od jula 2002 godine; u proseku \'\'\'$5\'\'\' izmena po stranici.',
'sitesupport' => 'Donacije',
'sitesupport-url' => 'Project:Fundraising',
'siteuser' => '{{ns:4}} korisnik $1',
'siteusers' => '{{ns:4}} korisnik (korisnici) $1',
'skin' => 'Koža',
'skinpreview' => '(Pregled)',
'sorbs_create_account_reason' => 'Vaša IP adresa se nalazi na spisku kao otvoreni proksi na [http://www.sorbs.net SORBS] DNSBL. Ne možete da napravite nalog',
'sorbsreason' => 'Vaša IP adresa je na spisku kao otvoren proksi na [http://www.sorbs.net SORBS] DNSBL.',
'sourcefilename' => 'Ime fajla izvora',
'spamprotectionmatch' => 'Sledeći tekst je izazvao naš filter za neželjene poruke: $1',
'spamprotectiontext' => 'Strana koju želite da sačuvate je blokirana od strane filtera za neželjene poruke. Ovo je verovatno izazvano vezom ka spoljašnjem sajtu.',
'spamprotectiontitle' => 'Filter za zaštitu od neželjenih poruka',
'speciallogtitlelabel' => 'Naslov:',
'specialloguserlabel' => 'Korisnik:',
'specialpage' => 'Posebna stranica',
'specialpages' => 'Posebne stranice',
'spheading' => 'Posebne stranice za sve korisnike',
'sqlhidden' => '(SQL pretraga sakrivena)',
'statistics' => 'Statistike',
'storedversion' => 'Uskladištena verzija',
'stubthreshold' => 'Granica za prikazivanje klica',
'subcategories' => 'Potkategorije',
'subcategorycount' => '$1 potkategorija su u ovoj kategoriji.',
'subject' => 'Tema/naslov',
'subjectpage' => 'Pogledaj temu',
'successfulupload' => 'Uspešno slanje',
'summary' => 'Uopšteno',
'sunday' => 'nedelja',
'sysoptext' => 'Akciju koju ste zatražili mogu
izvesti samo korisnici sa statusom administratora.
Pogledajte $1.',
'sysoptitle' => 'Neophodan je administratorski pristup',
'tagline' => 'Iz Vikipedije, slobodne enciklopedije.',
'talk' => 'Razgovor',
'talkexists' => 'Sama stranica je uspešno premeštena, ali
stranica za razgovor nije mogla biti premeštena jer takva već postoji na novom naslovu. Molimo vas da ih spojite ručno.',
'talkpage' => 'Razgovor o ovoj stranici',
'talkpagemoved' => 'Odgovarajuća stranica za razgovor je takođe premeštena.',
'talkpagenotmoved' => 'Odgovarajuća stranica za razgovor <strong>nije</strong> premeštena.',
'templatesused' => 'Šabloni koji se koriste na ovoj stranici:',
'textboxsize' => 'Veličine tekstualnog polja',
'textmatches' => 'Tekst članka odgovara',
'thisisdeleted' => 'Pogledaj ili vrati $1?',
'thumbnail-more' => 'uvećaj',
'thumbsize' => 'Veličina umanjenog prikaza :',
'thursday' => 'četvrtak',
'timezonelegend' => 'Vremenska zona',
'timezoneoffset' => 'Odstupanje',
'timezonetext' => 'Unesite broj sati za koji se vaše lokalno vreme
razlikuje od serverskog vremena (UTC).',
'titlematches' => 'Naslov članka odgovara',
'toc' => 'Sadržaj',
'tog-editondblclick' => 'Menjaj stranice dvostrukim klikom (zahteva JavaScript)',
'tog-editsection' => 'Omogući izmenu delova [menjaj] vezama',
'tog-editsectiononrightclick' => 'Omogući izmenu delova desnim klikom<br /> na njihove naslove (zahteva JavaScript)',
'tog-editwidth' => 'Polje za izmene ima punu širinu',
'tog-enotifminoredits' => 'Pošalji mi e-poštu takođe za male izmene strana',
'tog-enotifrevealaddr' => 'Otkrij adresu moje e-pošte u poštama obaveštenja',
'tog-enotifusertalkpages' => 'Pošalji mi e-poštu kada se promeni moja korisnička strana za razgovor',
'tog-enotifwatchlistpages' => 'Pošalji mi e-poštu kada se promene strane',
'tog-externaldiff' => 'Koristi spoljašnji razl po podrazumevanim podešavanjima',
'tog-externaleditor' => 'Koristi spoljašnji editor po podrazumevanim podešavanjima',
'tog-fancysig' => 'Čist potpis (bez automatskog povezivanja)',
'tog-hideminor' => 'Sakrij male izmene u spisku skorašnjih promenama',
'tog-highlightbroken' => 'Formatiraj pokvarene veze <a href="" class="new">ovako</a> (alternativa: ovako<a href="" class="internal">?</a>).',
'tog-justify' => 'Uravnaj pasuse',
'tog-minordefault' => 'Označi sve izmene malim isprva',
'tog-nocache' => 'Onemogući keširanje stranica',
'tog-numberheadings' => 'Automatski numeriši podnaslove',
'tog-previewonfirst' => 'Prikaži izgled pri prvoj promeni',
'tog-previewontop' => 'Pokaži pretpregled pre polja za izmenu a ne posle njega',
'tog-rememberpassword' => 'Pamti šifru kroz više seansi',
'tog-shownumberswatching' => 'Prikaži broj korisnika koji nadgledaju',
'tog-showtoc' => 'Prikaži sadržaj<br />(u svim člancima sa više od tri podnaslova)',
'tog-showtoolbar' => 'Prikaži dugmiće za izmene',
'tog-underline' => 'Podvuci veze',
'tog-usenewrc' => 'Poboljšan spisak skorašnjih izmena (nije za sve brauzere)',
'tog-watchdefault' => 'Dodaj stranice koje menjam u moj spisak nadgledanja',
'toolbox' => 'alati',
'tooltip-compareselectedversions' => 'Pogledaj razlike između dve selektovane verzije ove stranice. [alt-v]',
'tooltip-diff' => 'Prikaži koje promene ste napravili na tekstu. [alt-d]',
'tooltip-minoredit' => 'Naznačite da se radi o maloj izmeni [alt-i]',
'tooltip-preview' => 'Pretpregled Vaših izmena, molimo Vas koristite ovo pre snimanja! [alt-p]',
'tooltip-save' => 'Snimite Vaše izmene [alt-s]',
'tooltip-search' => 'Pretražite Viki',
'tooltip-watch' => 'Dodajte ovu stranicu na Vaš spisak nadgledanja [alt-w]',
'trackbackbox' => '; $4$5 : [$2 $1]',
'trackbackdeleteok' => 'Vraćanje je uspešno obrisano.',
'trackbacklink' => 'Vraćanje',
'trackbackremove' => '([$1 Brisanje])',
'tryexact' => 'Pokušaj tačno',
'tuesday' => 'utorak',
'uclinks' => 'Gledaj poslednjih $1 promena; gledaj poslednjih $2 dana.',
'ucnote' => 'Ispod je poslednjih <b>$1</b> promena u poslednjih <b>$2</b> dana.',
'uctop' => ' (vrh)',
'unblockip' => 'Otpusti korisnika',
'unblockiptext' => 'Upotrebite donji upitnik da biste vratili pravo pisanja
ranije obuzdanoj IP adresi ili korisničkom imenu.',
'unblocklink' => 'deblokiraj',
'unblocklogentry' => 'deblokiran "$1"',
'uncategorizedcategories' => 'Kategorije bez kategorija',
'uncategorizedpages' => 'Stranice bez kategorije',
'undelete' => 'Vrati obrisanu stranicu',
'undelete_short' => 'vrati $1 obrisanih izmena',
'undeletearticle' => 'Vrati obrisani članak',
'undeletebtn' => 'Vrati!',
'undeletedarticle' => 'vraćeno "$1"',
'undeletedrevisions' => '$1 revizija vraćeno',
'undeletehistory' => 'Ako vratite stranicu, sve revizije će biti vraćene njenoj istoriji.
Ako je nova stranica istog imena napravljena od brisanja, vraćene
revizije će se pojaviti u ranijoj istoriji, a trenutna revizija sadašnje stranice
neće biti automatski zamenjena.',
'undeletehistorynoadmin' => 'Ova strana je obrisana. Ispod se nalazi deo istorije brisanja i istorija revizija obrisane strane. Pitajte [[{{ns:4}}:Administratori|administratora]] ako želite da se stranica vrati.',
'undeletepage' => 'Pogledaj i vrati obrisane stranice',
'undeletepagetext' => 'Sledeće stranice su obrisane ali su još uvek u arhivi i
mogu biti vraćene. Arhiva može biti periodično čišćena.',
'undeleterevision' => 'Obrisana revizija od $1',
'undeleterevisions' => '$1 revizija arhivirano',
'underline-always' => 'Uvek',
'underline-default' => 'Po podešavanjima brauzera',
'underline-never' => 'Nikad',
'unexpected' => 'Neočekivana vrednost: "$1"="$2".',
'unlockbtn' => 'Otključaj bazu',
'unlockconfirm' => 'Da, zaista želim da otključam bazu.',
'unlockdb' => 'Otključaj bazu',
'unlockdbsuccesssub' => 'Baza je otključana',
'unlockdbsuccesstext' => 'Vikipedijina baza podataka je otključana.',
'unlockdbtext' => 'Otključavanje baze će svim korisnicima vratiti mogućnost izmene stranica,
promene korisničkih podešavanja, izmene spiska nadgledanja, i svega ostalog
što zahteva promene u bazi.
Molimo potvrdite da je ovo zaista ono što nameravate da uradite.',
'unprotect' => 'Skini zaštitu',
'unprotectcomment' => 'Razlog za skidanje zaštite',
'unprotectedarticle' => 'zaštita skinuta $1',
'unprotectsub' => '(skidanje zaštite "$1")',
'unprotectthispage' => 'Ukloni zaštitu sa ove stranice',
'unusedcategories' => 'Neiskorišćene kategorije',
'unusedcategoriestext' => 'Naredne strane kategorija postoje iako ih ni jedan drugi članak ili kategorija ne koriste.',
'unusedimages' => 'Neupotrebljene slike',
'unusedimagestext' => '<p>Obratite pažnju da se drugi veb sajtovi
kao što su međunarodne Vikipedije mogu povezivati na sliku
direktnim URL-om, i tako mogu još uvek biti prikazani ovde uprkos
aktivnoj upotrebi.',
'unwatch' => 'Prekini nadgledanje',
'unwatchthispage' => 'Prekini nadgledanje',
'updated' => '(Osveženo)',
'updatedmarker' => 'ažurirano od moje poslednje posete',
'upload' => 'Pošalji fajl',
'upload_directory_read_only' => 'Na direktorijum za slanje ($1) vebserver ne može da piše.',
'uploadbtn' => 'Pošalji fajl',
'uploadcorrupt' => 'Fajl je neispravan ili ima netačnu ekstenziju. Molimo proverite fajl i pošaljite ga ponovo.',
'uploaddisabled' => 'Izvinjavamo se, slanje fajlova je isključeno.',
'uploadedfiles' => 'Poslati fajlovi',
'uploadedimage' => 'poslato "[[$1]]"',
'uploaderror' => 'Greška pri slanju',
'uploadlog' => 'log slanja',
'uploadlogpage' => 'istorija slanja',
'uploadlogpagetext' => 'Ispod je spisak najskorijih slanja.
Sva vremena su serverska vremena (UTC).
<ul>
</ul>',
'uploadnewversion-linktext' => 'Pošaljite noviju verziju ove datoteke',
'uploadnologin' => 'Niste prijavljeni',
'uploadnologintext' => 'Morate biti [[{{ns:-1}}:Userlogin|prijavljeni]]
da biste slali fajlove.',
'uploadscripted' => 'Ovaj fajl sadrži HTML ili kod skripte koje internet brauzer može sa greškom da interpretira.',
'uploadtext' => '

Molimo, pridržavajte se sledećih smernica pri postavljanju datoteka:
*Naznačite u tekstualnom polju (Opis) \'\'detaljne\'\' podatke o izvoru datoteke; ovu informaciju sistem će odmah postaviti i na opisnu stranu datoteke. Ako ste datoteku nabavili negde na Internetu, molimo uključite i URL (internet adresu) odakle.
*Navedite \'\'licencu\'\' datoteke dodavanjem odgovarajuće nalepnice, npr. <tt><nowiki>{</nowiki>{gfdl}}</tt>, <tt><nowiki>{</nowiki>{jv}}</tt>, itd. Pogledajte [[{{ns:4}}: Nalepnice za autorska prava nad slikama|Nalepnice za autorska prava nad slikama]], gde ćete naći i spisak svih nalepnica koje se mogu koristiti.
*Sliku ili drugi sadržaj dodajete u pogodne članke koristeći sintaksu <tt><nowiki>[[{{ns:6}}:File.jpg|thumb|Natpis pod slikom]]</nowiki></tt> za slike, odnosno <tt><nowiki>[[{{ns:-2}}:File.ogg]]</nowiki></tt> za druge medije. Za dalja uputstva, pogledajte [[{{ns:4}}:Proširena sintaksa za slike|Proširena sintaksa za slike]].
*Koristite jasna, opisna imena (npr. "Ajfelov toranj u Parizu noću.jpg") kako biste izbegli preklapanja sa postojećim datotekama.

Ako želite da saznate više, pogledajte [[{{ns:4}}:Postavljanje datoteka]] i [[{{ns:4}}:Zvuk]]. Spisak svih već postavljenih datoteka možete videti na [[{{ns:-1}}:Imagelist|spisku slika]].

<p>\'\'\'Ako želite da postavite datoteku nad kojom <i>Vi</i> posedujete autorska prava,<br /> morate je licencirati pod [[GNU-ova LSD|GNU-ovom Licencom za slobodnu dokumentacijom]]<br /> ili predati u [[javno vlasništvo]].\'\'\'</p>',
'uploadvirus' => 'Fajl sadrži virus! Detalji: $1',
'uploadwarning' => 'Upozorenje pri slanju',
'user_rights_set' => '<b>Prava za korisnika "$1" promenjena</b>',
'usercssjsyoucanpreview' => '<strong>Pažnja:</strong> Korisitite \'Prikaži pretpregled\' dugme da testirate svoj novi CSS/JS pre snimanja.',
'usercsspreview' => '\'\'\'Zapamtite ovo je samo pretpregled vašeg CSS, još uvek nije snimljen!\'\'\'',
'userexists' => 'Korisničko ime koje ste uneli već je u upotrebi. Molimo Vas izaberite drugo ime.',
'userjspreview' => '\'\'\'Zapamtite ovo je samo pretpregled vaše JavaScript-e, još uvek nije snimljen!\'\'\'',
'userlogin' => 'Registruj se/Prijavi se',
'userlogout' => 'Odjavi se',
'usermailererror' => 'Objekat pošte je vratio grešku:',
'userpage' => 'Pogledaj korisničku stranu',
'userrights' => 'Upravljanje korisničkim pravima',
'userrights-editusergroup' => 'Promeni korisničke grupe',
'userrights-groupsavailable' => 'Dostupne grupe:',
'userrights-groupshelp' => 'Odabrane grupe od kojih želite da se ukloni korisnik ili da se doda.
Neodabrane grupe neće biti promenjene. Možete da deselektujete grupu koristeći CTRL + levi klik',
'userrights-groupsmember' => 'Član:',
'userrights-logcomment' => 'Promenjeno članstvo grupe iz $1 u $2',
'userrights-lookup-user' => 'Upravljaj korisničkim grupama',
'userrights-user-editname' => 'Unesi korisničko ime:',
'userstats' => 'Statistike korisnika',
'userstatstext' => 'Postoji \'\'\'$1\'\'\' registrovanih korisnika, od kojih su \'\'\'$2\'\'\' (ili $4%) [[{{ns:4}}:Administratori|administratori]].',
'version' => 'Verzija',
'versionrequired' => 'Verzija $1 MedijaVikija je potrebna',
'versionrequiredtext' => 'Verzija $1 MedijaVikija je potrebna da bi se koristila ova strana. Pogledajte [[{{ns:-1}}:Version|verziju]]',
'viewcount' => 'Ovoj stranici je pristupljeno $1 puta.',
'viewdeleted' => 'Pogledaj $1?',
'viewdeletedpage' => 'Pogledaj obrisane strane',
'viewprevnext' => 'Pogledaj ($1) ($2) ($3).',
'views' => 'Pregledi',
'viewsource' => 'pogledaj kod',
'viewtalkpage' => 'Pogledaj raspravu',
'wantedpages' => 'Tražene stranice',
'watch' => 'nadgledaj',
'watchdetails' => '($1 stranica nadgledano ne računajući stranice za razgovor;
$2 ukupno stranica izmenjeno od odsecanja;
$3...)<br />
[$4 prikaži i menjaj potpuni spisak]',
'watcheditlist' => 'Ovde je azbučni spisak stranica
koje nadgledate. Obeležite kućice stranica koje želite da uklonite
sa svog spiska nadgledanja i kliknite na dugme \'ukloni izabrane\'
na dnu ekrana.',
'watchlist' => 'Moj spisak nadgledanja',
'watchlistall1' => 'sve',
'watchlistall2' => 'sve',
'watchlistcontains' => 'Vaš spisak nadgledanja sadrži $1 stranica.',
'watchmethod-list' => 'proveravam ima li skorašnjih izmena u nadgledanim stranicama',
'watchmethod-recent' => 'proveravam ima li nadgledanih stranica u skorašnjim izmenama',
'watchnochange' => 'Ništa što nadgledate nije promenjeno u prikazanom vremenu.',
'watchnologin' => 'Niste prijavljeni',
'watchnologintext' => 'Morate biti [[{{ns:-1}}:Userlogin|prijavljeni]] da biste menjali spisak nadgledanja.',
'watchthis' => 'Nadgledaj ovaj članak',
'watchthispage' => 'Nadgledaj ovu stranicu',
'wednesday' => 'sreda',
'welcomecreation' => '<h2>Dobrodošli, $1!</h2><p>Vaš nalog je kreiran.
Ne zaboravite da prilagodite sebi svoja {{ns:4}} podešavanja.',
'whatlinkshere' => 'Šta je povezano ovde',
'whitelistacctext' => 'Da bi vam bilo dozvoljeno da napravite naloge na ovom Vikiju morate da se [[{{ns:-1}}:Userlogin|prijavite]] i imate odgovarajuća ovlašćenja.',
'whitelistacctitle' => 'Nije vam dozvoljeno da napravite nalog',
'whitelistedittext' => 'Morate da se [[{{ns:-1}}:Userlogin|prijavite]] da biste menjali članke.',
'whitelistedittitle' => 'Obavezno je [[{{ns:-1}}:Userlogin|prijavljivanje]] za menjanje',
'whitelistreadtext' => 'Morate da se [[{{ns:-1}}:Userlogin|prijavite]] da biste čitali članke.',
'whitelistreadtitle' => 'Obavezno je prijavljivanje za čitanje',
'projectpage' => 'Pogledaj stranu o ovoj strani',
'wlheader-enotif' => '* Obaveštavanje e-poštom je omogućeno.',
'wlheader-showupdated' => '* Strane koje su izmenjene od kada ste ih poslednji put posetili su prikazane \'\'\'masnim slovima\'\'\'',
'wlhideshowbots' => '$1 izmena botova.',
'wlhideshowown' => '$1 moje izmene.',
'wlnote' => 'Ispod je poslednjih $1 izmena u poslednjih <b>$2</b> sati.',
'wlsaved' => 'Ovo je sačuvana verzija vašeg spiska nadgledanja.',
'wlshowlast' => 'Prikaži poslednjih $1 sati $2 dana $3',
'wrong_wfQuery_params' => 'Netačni parametri za wfQuery()<br />
Funkcija: $1<br />
Pretraga: $2',
'wrongpassword' => 'Lozinka koju ste uneli je neispravna. Molimo pokušajte ponovo.',
'yourdiff' => 'Razlike',
'yourdomainname' => 'Vaš domen',
'youremail' => 'Adresa vaše e-pošte*',
'yourlanguage' => 'Jezik izgleda Vikipedije',
'yourname' => 'Korisničko ime',
'yournick' => 'Vaš nadimak (za potpise)',
'yourpassword' => 'Vaša lozinka',
'yourpasswordagain' => 'Ponovite lozinku',
'yourrealname' => 'Vaše pravo ime*',
'yourtext' => 'Vaš tekst',
'yourvariant' => 'Varijanta jezika',
'variantname-sr-ec' => 'екав',
'variantname-sr-el' => 'ekav',
'variantname-sr-jc' => 'јекав',
'variantname-sr-jl' => 'jekav',
'variantname-sr' => 'disable',
);


?>

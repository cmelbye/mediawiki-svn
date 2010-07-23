<?php
/**
 * Translations of Translate extension.
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

$messages = array();

/** English
 * @author Nike
 * @author Siebrand
 */
$messages['en'] = array(
	'firststeps' => 'First steps',
	'firststeps-desc' => '[[Special:FirstSteps|Special page]] for getting users started on a wiki using the Translate extension',
	'translate-fs-pagetitle-done' => ' - done!',
	'translate-fs-pagetitle' => 'Getting started wizard - $1',
	'translate-fs-signup-title' => 'Sign up',
	'translate-fs-settings-title' => 'Configure your preferences',
	'translate-fs-userpage-title' => 'Create your user page',
	'translate-fs-permissions-title' => 'Request translator permissions',
	'translate-fs-target-title' => 'Start translating!',
	'translate-fs-email-title' => 'Confirm your e-mail address',

	'translate-fs-intro' => "Welcome to the {{SITENAME}} first steps wizard.
You will be guided through the process of becoming a translator step by step.
In the end you will be able to translate ''interface messages'' of all supported projects at {{SITENAME}}.",

	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]]

In the first step you must sign up.

Credit for your translations is attributed to your user name.
The image on the right shows how to fill the fields.

If you have already signed up, $1log in$2 instead.
Once you are signed up, please return to this page.

$3Sign up$4',
	'translate-fs-settings-text' => 'You should now go to your preferences and
at least change your interface language to the language you are going to translate to.

Your interface language is used as the default target language.
It is easy to forget to change the language to the correct one, so setting it now is highly recommended.

While you are there, you can also request the software to display translations in other languages you know.
This setting can be found under tab "{{int:prefs-editing}}".
Feel free to explore other settings, too.

Go to your [[Special:Preferences|preferences page]] now and then return to this page.',
	'translate-fs-settings-skip' => "I'm done.
Let me proceed.",
	'translate-fs-userpage-text' => 'Now you need to create an user page.

Please write something about yourself; who you are and what you do.
This will help the {{SITENAME}} community to work together.
At {{SITENAME}} there are people from all around the world working on different languages and projects.

In the prefilled box above in the very first line you see <nowiki>{{#babel:en-2}}</nowiki>.
Please complete it with your language knowledge.
The number after the language code describes how well you know the language.
The alternatives are:
* 1 - a little
* 2 - basic knowledge
* 3 - good knowledge
* 4 - native speaker level
* 5 - you use the language professionally, for example you are a professional translator.

If you are a native speaker of a language, leave the skill level out, and only use the language code.
Example: if you speak Tamil natively, English well, and little Swahili, you would write:
<code><nowiki>{{#babel:ta|en-3|sw-1}}</nowiki></code>

If you do not know the language code of a language, now is a good time to look it up.
You can use the list below.',
	'translate-fs-userpage-submit' => 'Create my userpage',
	'translate-fs-userpage-done' => 'Well done! You now have an user page.',
	'translate-fs-permissions-text' => 'Now you need to place a request to be added to the translator group.

Until we fix the code, please go to [[Project:Translator]] and follow the instructions.
Then come back to this page.

After you have submitted your request, one of the volunteer staff members will check your request and approve it as soon as possible.
Please be patient.

<del>Check that the following request is correctly filled and then press the request button.</del>',

	'translate-fs-target-text' => 'Congratulations!
You can now start translating.

Do not be afraid if it still feels new and confusing to you.
At [[Project list]] there is an overview of projects you can contribute translations to.
Most of the projects have a short description page with a "\'\'Translate this project\'\'" link, that will take you to a page which lists all untranslated messages.
A list of all message groups with the [[Special:LanguageStats|current translation status for a language]] is also available.

If you feel that you need to understand more before you start translating, you can read the [[FAQ|Frequently asked questions]].
Unfortunately documentation can be out of date sometimes.
If there is something that you think you should be able to do, but cannot find out how, do not hesitate to ask it at the [[Support|support page]].

You can also contact fellow translators of the same language at [[Portal:$1|your language portal]]\'s [[Portal_talk:$1|talk page]].
If you have not already done so, [[Special:Preferences|change your user interface language to the language you want to translate in]], so that the wiki is able to show the most relevant links for you.',

	'translate-fs-email-text' => 'Please provide your e-mail address in [[Special:Preferences|your preferences]] and confirm it from the e-mail that is sent to you.

This allows other users to contact you by e-mail.
You will also receive newsletters at most once a month.
If you do not want to receive newsletters, you can opt-out in the tab "{{int:prefs-personal}}" of your [[Special:Preferences|preferences]].',
);

/** Message documentation (Message documentation)
 * @author Lloffiwr
 */
$messages['qqq'] = array(
	'translate-fs-permissions-text' => 'Synonym for "filed" is "submitted".',
);

/** Arabic (العربية)
 * @author ترجمان05
 */
$messages['ar'] = array(
	'translate-fs-pagetitle-done' => '- تمّ!',
	'translate-fs-target-title' => 'إبدأ بالترجمة',
);

/** Belarusian (Taraškievica orthography) (Беларуская (тарашкевіца))
 * @author EugeneZelenko
 * @author Jim-by
 * @author Wizardist
 */
$messages['be-tarask'] = array(
	'firststeps' => 'Першыя крокі',
	'firststeps-desc' => '[[Special:FirstSteps|Спэцыяльная старонка]] для пачатку працы з пашырэньнем Translate',
	'translate-fs-pagetitle-done' => ' — зроблена!',
	'translate-fs-pagetitle' => 'Майстар пачатковага навучаньня — $1',
	'translate-fs-signup-title' => 'Зарэгіструйцеся',
	'translate-fs-settings-title' => 'Устанавіце Вашыя ўстаноўкі',
	'translate-fs-userpage-title' => 'Стварыце Вашую старонку ўдзельніка',
	'translate-fs-permissions-title' => 'Запытайце правы перакладчыка',
	'translate-fs-target-title' => 'Пачніце перакладаць!',
	'translate-fs-email-title' => 'Пацьвердзіць Ваш адрас электроннай пошты',
	'translate-fs-intro' => "Запрашаем у майстар пачатковага навучаньня {{GRAMMAR:родны|{{SITENAME}}}}.
Вас правядуць праз працэс станаўленьня перакладчыкам крок за крокам.
Пасьля гэтага Вы зможаце перакладаць ''паведамленьні інтэрфэйсу'' ўсіх праектаў, якія падтрымліваюцца ў {{GRAMMAR:месны|{{SITENAME}}}}.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]]

Спачатку Вам неабходна зарэгістравацца.

Аўтарства Вашых перакладаў будзе прыпісвацца Вашаму рахунку.
Выява справа паказвае, як запаўняць палі.

Калі Вы ўжо зарэгістраваныя, то замест$1 увайдзіце як$2.
Пасьля рэгістрацыі, калі ласка, вярніцеся на гэтую старонку.

$3Зарэгістравацца$4',
	'translate-fs-settings-text' => 'Цяпер Вам неабходна перайсьці ў устаноўкі і
зьмяніць мову інтэрфэйсу на мову, на якую Вы зьбіраецеся перакладаць.

Мова Вашага інтэрфэйсу будзе выкарыстоўвацца, як мова перакладу па змоўчваньні.
Вельмі лёгка забыцца зьмяніць мову, таму настойліва рэкамэндуем зьмяніць яе зараз.

Пакуль Вы там, Вы можаце ўключыць паказ перакладаў на іншыя мовы, якія Вы ведаеце.
Гэтая ўстаноўка знаходзіцца ў закладцы «{{int:prefs-editing}}».
Таксама, Вы можаце паспрабаваць іншыя ўстаноўкі.

Перайдзіце на Вашую [[Special:Preferences|старонку ўстановак]], а потым вярніцеся на гэтую старонку.',
	'translate-fs-settings-skip' => 'Я ўсё выканаў.
Перайсьці далей.',
	'translate-fs-userpage-text' => 'Цяпер Вам неабходна стварыць старонку ўдзельніка.

Калі ласка, напішыце што-небудзь пра сябе; хто Вы і чым займаецеся.
Гэта дапаможа супольнасьці {{GRAMMAR:родны|{{SITENAME}}}} працаваць разам.
У {{GRAMMAR:месны|{{SITENAME}}}} ёсьць людзі з усяго сьвету, якія працуюць на розных мовах і ў розных праектах.

У папярэдне запоўненай форме наверсе, на самым першым радку Вы бачыце <nowiki>{{#babel:en-2}}</nowiki>.
Калі ласка, запоўніце яго, у адпаведнасьці з Вашымі ведамі мовы.
Лічба пасьля коду мовы паказвае як добра Вы валодаеце мовай.
Варыянтамі зьяўляюцца:
* 1 - крыху
* 2 - базавыя веды
* 3 - добрыя веды
* 4 - родная мова
* 5 - Вы карыстаецеся мовай прафэсійна, напрыклад, Вы — прафэсійны перакладчык.

Калі гэтая мова зьяўляецца Вашай роднай, то не стаўце лічбу ўзроўню валоданьня, а пакіньце толькі код мовы.
Напрыклад: калі Вашай роднай мовай зьяўляецца тамільская, ангельскую Вы ведаеце добра, і крыху ведаеце свахілі, Вам неабходна напісаць: <code><nowiki>{{#babel:ta|en-3|sw-1}}</nowiki></code>

Калі Вы ня ведаеце код мовы, то зараз Вы можаце яго даведацца. Вы можаце паглядзець сьпіс пададзены ніжэй.',
	'translate-fs-userpage-submit' => 'Стварыць маю старонку ўдзельніка',
	'translate-fs-userpage-done' => 'Выдатна! Цяпер Вы маеце старонку ўдзельніка.',
	'translate-fs-permissions-text' => 'Вам неабходна падаць запыт на даданьне да групы перакладчыкаў.

Пакуль мы выправім код, калі ласка, перайдзіце на [[Project:Translator]] і выконвайце інструкцыі. Потым вярніцеся на гэтую старонку.

Пасьля таго, як Вы падалі запыт, адзін з добраахвотнікаў каманды падтрымкі праверыць і зацьвердзіць яго як мага хутчэй.
Калі ласка, майце цярпеньне.

<del>Праверце, каб наступны запыт быў запоўнены дакладна, а потым націсьніце кнопку адпраўкі.</del>',
	'translate-fs-target-text' => "Віншуем!
Цяпер Вы можаце пачаць перакладаць.

Не бойцеся, калі што-небудзь здаецца Вам новым і незразумелым.
У [[Project list|сьпісе праектаў]] знаходзіцца агляд праектаў, для якіх Вы можаце перакладаць.
Большасьць праектаў мае старонку з кароткім апісаньнем са спасылкай «''Перакласьці гэты праект''», якая перанясе Вас на старонку са сьпісам усіх неперакладзеных паведамленьняў.
Таксама даступны сьпіс усіх групаў паведамленьняў з [[Special:LanguageStats|цяперашнім статусам перакладу для мовы]].

Калі Вам здаецца, што неабходна даведацца болей перад пачаткам перакладаў, Вы можаце пачытаць [[FAQ|адказы на частыя пытаньні]].
На жаль дакумэнтацыя можа быць састарэлай.
Калі ёсьць што-небудзь, што, як Вы мяркуеце, Вы можаце зрабіць, але ня ведаеце як, не вагаючыся пытайцеся на [[Support|старонцы падтрымкі]].

Таксама, Вы можаце зьвязацца з перакладчыкамі на Вашую мову на [[Portal_talk:$1|старонцы абмеркаваньня]] [[Portal:$1|парталу Вашай мовы]].
Калі Вы яшчэ гэтага не зрабілі, Вы можаце [[Special:Preferences|зьмяніць Вашыя моўныя ўстаноўкі інтэрфэйсу на мову, на якую жадаеце перакладаць]], для таго каб вікі паказала Вам адпаведныя спасылкі.",
	'translate-fs-email-text' => 'Калі ласка, падайце адрас Вашай электроннай пошты ў [[Special:Preferences|Вашых устаноўках]] і пацьвердзіце яго з электроннага ліста, які будзе Вам дасланы.

Гэта дазволіць іншым удзельнікам зносіцца з Вамі праз электронную пошту.
Таксама, Вы будзеце атрымліваць штомесячныя лісты з навінамі.
Калі Вы не жадаеце атрымліваць лісты з навінамі, Вы можаце адмовіцца ад іх на закладцы «{{int:prefs-personal}}» Вашых [[Special:Preferences|установак]].',
);

/** Breton (Brezhoneg)
 * @author Fulup
 * @author Y-M D
 */
$messages['br'] = array(
	'firststeps' => 'Pazenn gentañ',
	'firststeps-desc' => '[[Special:FirstSteps|Pajenn dibar]] evit hentañ an implijerien war ur wiki hag a implij an astenn Translate',
	'translate-fs-pagetitle-done' => '↓  - graet !',
	'translate-fs-pagetitle' => "Heñcher loc'hañ - $1",
	'translate-fs-signup-title' => 'En em enskrivañ',
	'translate-fs-settings-title' => 'Kefluniañ ho arventennoù',
	'translate-fs-userpage-title' => 'Krouiñ ho fajenn implijer',
	'translate-fs-permissions-title' => 'Goulennit an aotreoù troer',
	'translate-fs-target-title' => 'Kregiñ da dreiñ !',
	'translate-fs-email-title' => "Kadarnait ho chomlec'h postel",
	'translate-fs-intro' => "Deuet mat oc'h er skoazeller evit pazioù kentañ {{SITENAME}}.
Emaomp o vont da hentañ ac'hanoc'h paz ha paz evit dont da vezañ un troer.
E fin an hentad e c'helloc'h treiñ \"kemennadennoù etrefas\" an holl raktresoù meret gant {{SITENAME}}.",
	'translate-fs-signup-text' => "[[Image:HowToStart1CreateAccount.png|framm]]

Evit ar bazenn gentañ e rankez kevreañ.

An troidigezhioù graet ganeoc'h a vo laket war ho kont, dre hoc'h anv implijer.
Diskouez a ra ar skeudenn a-zehou penaos leuniañ ar maeziennoù.

M'emaoc'h enskrivet dija, hoc'h eus da $1gevreañ$2 kentoc'h.
Ur wezh enskrivet, distroit d'ar bajenn-mañ.

$3En em enskrivañ$4",
	'translate-fs-settings-skip' => "Echuet eo ganin.
Lezit ac'hanon da genderc'hel.",
	'translate-fs-userpage-submit' => 'Krouiñ ma fajenn implijer',
	'translate-fs-userpage-done' => "Dispar ! Ur bajenn implijer hoc'h eus bremañ.",
);

/** Bosnian (Bosanski)
 * @author Palapa
 */
$messages['bs'] = array(
	'firststeps' => 'Prvi koraci',
	'translate-fs-pagetitle-done' => 'Urađeno!',
	'translate-fs-settings-title' => 'Podesi svoje postavke',
	'translate-fs-userpage-title' => 'Napravi svoju korisničku stranicu',
	'translate-fs-permissions-title' => 'Zahtijevaj prevodilačku dozvolu',
	'translate-fs-target-title' => 'Počni prevoditi!',
	'translate-fs-email-title' => 'Potvrdi svoju e-mail adresu',
	'translate-fs-userpage-submit' => 'Napravi moju korisničku stranicu',
	'translate-fs-userpage-done' => 'Odlično urađeno! Sada imate korisničku stranicu.',
);

/** German (Deutsch)
 * @author Kghbln
 * @author The Evil IP address
 */
$messages['de'] = array(
	'firststeps' => 'Erste Schritte',
	'firststeps-desc' => '[[Special:FirstSteps|Spezialseite]] zur Starterleichterung auf Wikis mit der „Translate“-Extension',
	'translate-fs-pagetitle-done' => '- erledigt!',
	'translate-fs-pagetitle' => 'Startsassistent - $1',
	'translate-fs-signup-title' => 'Registrieren',
	'translate-fs-settings-title' => 'Deine Einstellungen anpassen',
	'translate-fs-userpage-title' => 'Deine Benutzerseite erstellen',
	'translate-fs-permissions-title' => 'Übersetzerrechte beantragen',
	'translate-fs-target-title' => 'Übersetzen!',
	'translate-fs-email-title' => 'Deine E-Mail-Adresse bestätigen',
	'translate-fs-intro' => "Willkommen bei dem {{SITENAME}}-Startassistenten.
Dir wird gezeigt, wie du Schritt für Schritt ein Übersetzer wirst.
Am Ende wirst du alle ''Oberflächen-Nachrichten'' der von {{SITENAME}} unterstützten Projekte übersetzen können.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]]

Als erstes musst du dir ein Benutzerkonto anlegen.

Dein Benutzername wird in den Autorenlisten für deine Übersetzungen genannt.
Das Bild rechts zeigt, wie du die Felder ausfüllen musst.

Wenn du dich bereits registriert hast, $1logge dich stattdessen ein$2.
Wenn du eingeloggt bist, kehre auf diese Seite zurück.

$3Benutzerkonto anlegen$4',
	'translate-fs-settings-text' => 'Gehe nun zu deinen Einstellungen und ändere zumindest deine Oberflächensprache in die Sprache, die du übersetzen wirst.

Deine Oberflächensprache wird als deine Standardsprache benutzt.
Man vergisst leicht, die Sprache in die Richtige zu verändern, daher ist es empfohlen, dies sofort zu tun.

Wenn du dabei bist, kannst du die Software auch bitten, Übersetzungen in anderen Sprachen anzuzeigen, die du kennst.
Diese Einstellung findest du unter dem Tab „{{int:prefs-editing}}“.
Guck dir auch ruhig die anderen Einstellungsmöglichkeiten an.

Gehe jetzt in deine [[Special:Preferences|Einstellungen]] und kehre dann auf diese Seite zurück.',
	'translate-fs-settings-skip' => 'Fertig.
Nächster Schritt.',
	'translate-fs-userpage-text' => 'Jetzt musst du eine Benutzerseite erstellen.

Bitte schreibe etwas über dich, wer du bist und was du machst.
Dies hilft der {{SITENAME}}-Gemeinschaft bei der Zusammenarbeit.
Auf {{SITENAME}} gibt es Leute aus der ganzen Welt, die an verschiedenen Sprachen und Projekten arbeiten.

In der ausgefüllten Box oben siehst du in der ersten Zeile <nowiki>{{#babel:en-2}}</nowiki>.
Bitte fülle es mit deinen Sprachkenntnissen aus.
Die Zahl hinter dem Sprachcode beschreibt wie gut du die Sprache kannst.
Die Möglichkeiten sind:
*1 - ein bisschen
*2 - Basiswissen
*3 - fließend
*4 - nahezu Muttersprachler
*5 - professionell, z.B. wenn du ein professioneller Übersetzer bist.

Wenn du ein Muttersprachler bist, lasse die Zahl aus und benutze nur den Sprachcode.
Beispiel: Wenn du Tamil als Muttersprache, Englisch gut und ein wenig Swahili könntest du Folgendes schreiben:
<code><nowiki>{{#babel:ta|en-3|sw-1}}</nowiki></code>

Wenn du den Sprachcode einer Sprache nicht kennst, dann guck ihn jetzt nach.
Du kannst die Liste unten benutzen.',
	'translate-fs-userpage-submit' => 'Benutzerseite erstellen',
	'translate-fs-userpage-done' => 'Gut gemacht! Du hast nun eine Benutzerseite',
	'translate-fs-permissions-text' => 'Jetzt musst du einen Antrag stellen, um zur Übersetzergruppe hinzugefügt zu werden.

Bis wir den Code korrigieren, gehe auf [[Project:Translator]] und folge den Anweisungen.
Kehre danach zurück auf diese Seite.

Nachdem du den Antrag abgeschickt hast, wird ein freiwilliger Mitarbeiter deinen Antrag prüfen und ihn so bald wie möglich akzeptieren.
Bitte habe dabei etwas Geduld.

<del>Stelle sicher, dass der folgende Antrag korrekt ausgefüllt und und drücke dann den Button.</del>',
	'translate-fs-target-text' => "Glückwunsch!
Du kannst nun mit dem Übersetzen beginnen.

Sei nicht verwirrt, wenn es dir noch neu und unübersichtlich verkommt.
Auf der Seite [[Project list|Projekte]] gibt es eine Übersicht der Projekte, die du übersetzen kannst.
Die meisten Projekte haben eine kurze Beschreibungsseite zusammen mit einem „''Übersetzen''“- Link, der dich auf eine Seite mit nicht-übersetzten Nachrichten bringt.
Eine Liste aller Nachrichtengruppen und dem [[Special:LanguageStats|momentanen Status einer Sprache]] gibt es auch.

Wenn du mehr hiervon verstehen möchtest, kannst du die [[FAQ|häufig gestellten Fragen]] lesen.
Leider kann die Dokumentation zeitweise veraltet sein.
Wenn du etwas tun möchtest, jedoch nicht weißt wie, zögere nicht auf der [[Support|Hilfeseite]] zu fragen.

Du kannst auch Übersetzer deiner Sprache auf der [[Portal_talk:$1|Diskussionsseite]] [[Portal:$1|des entsprechenden Sprachportals]] kontaktieren.
Das Portal verlinkt auf deine momentane [[Special:Preferences|Spracheinstellung]].
Bitte ändere sie falls nötig.",
	'translate-fs-email-text' => 'Bitte gebe deine E-Mail-Adresse in [[Special:Preferences|deinen Einstellungen]] ein und bestätige die an dich versandte E-Mail.

Dies gibt anderen die Möglichkeit, dich über E-Mail zu erreichen.
Du erhälst außerdem bis zu einmal im Monat einen Newsletter.
Wenn du keinen erhalten möchtest, kannst du dich im Tab „{{int:prefs-personal}}“ in deinen [[Special:Preferences|Einstellungen]] austragen.
Wenn du keinen Newsletter haben möchtest, kannst du dich im Tab Translate-fs-target-text',
);

/** Lower Sorbian (Dolnoserbski)
 * @author Michawiki
 */
$messages['dsb'] = array(
	'firststeps' => 'Prědne kšace',
	'firststeps-desc' => '[[Special:FirstSteps|Specialny bok]], aby  wólažcył wužywarjam wužywanje rozšyrjenja Translate',
	'translate-fs-pagetitle-done' => ' - wótbyte!',
	'translate-fs-pagetitle' => 'Startowy asistent - $1',
	'translate-fs-signup-title' => 'Registrěrowaś',
	'translate-fs-settings-title' => 'Twóje nastajenja konfigurěrowaś',
	'translate-fs-userpage-title' => 'Twój wužywarski bok napóraś',
	'translate-fs-permissions-title' => 'Póžedanje na pśełožowarske pšawa stajiś',
	'translate-fs-target-title' => 'Zachop pśełožowaś!',
	'translate-fs-email-title' => 'Twóju e-mailowu adresu wobkšuśiś',
	'translate-fs-intro' => "Witaj do startowego asistenta {{GRAMMAR:genitiw|SITENAME}}.
Pokazujo so śi kšać pó kšać, kak buźoš pśełožowaŕ.
Na kóńcu móžoš ''powěźeńki wužywarskego powjercha'' wšyknych pódpěranych projektow na {{SITENAME}} pśełožowaś.",
	'translate-fs-settings-skip' => 'Som gótowy.
Dalej.',
	'translate-fs-userpage-submit' => 'Mój wužywarski bok napóraś',
	'translate-fs-userpage-done' => 'Derje cynił! Maš něnto wužywarski bok.',
	'translate-fs-target-text' => 'Gratulacija!
Móžoš něnto pśełožowanje zachopiś.

Buź mimo starosći, jolic zda se śi hyšći nowe a konfuzne.
Na [[Project list|lisćinje projektow]] jo pśeglěd projektow, ku kótarymž móžoš pśełožki pśinosowaś. Nejwěcej projektow ma krotky wopisański bok z wótkazom "\'\'Toś ten projekt pśełožyś\'\'", kótaryž wjeźo śi k bokoju, kótaryž wšykne njepśełožone powěźeńki wopśimujo.
Lisćina wšyknych kupkow powěźeńkow z [[Special:LanguageStats|aktualnym pśełožowanskim stawom za rěc]] stoj teke k dispoziciji.

Jolic měniš, až dejš nejpjerwjej wěcej rozumiś, nježli až zachopijoš  pśełožowaś, móžoš [[FAQ|Ceste pšašanja]] cytaś.
Dokumentacija móžo bóžko wótergi zestarjona byś.
Joli něco jo, wó kótaremž mysliš, až by měło móžno byś, ale njenamakajoš, kak móžoš to cyniś, pšašaj se ga na boku [[Support|Pódpěra]].

Móžoš se teke ze sobupśełožowarjami teje sameje rěcy na [[Portal_talk:$1|diskusijnem boku]] [[Portal:$1|portala swójeje rěcy]] do zwiska stajiś.
Jolic hyšći njejsy to cynił, [[Special:Preferences|změń swój wužywarski powjerch do rěcy, do kótarejež coš pśełožowaś]], aby se wiki mógał wótkaze pokazaś, kótarež su relewantne za tebje.',
	'translate-fs-email-text' => 'Pšosym pódaj swóju e-mailowu adresu w [[Special:Preferences|swójich nastajenach]] a wobkšuś ju pśez e-mail, kótaraž sćelo se na tebje.

To dowólujo drugim wužywarjam se z tobu do zwiska stajiś.
Buźoš teke powěsćowe listy jaden raz na mjasec dostaś.
Jolic njocoš  powěsćowe listy dostaś, móžoš to na rejtarku "{{int:prefs-personal}}" swójich [[Special:Preferences|nastajenjow]] wótwóliś.',
);

/** Spanish (Español)
 * @author Crazymadlover
 * @author Diego Grez
 * @author Drini
 * @author Tempestas
 */
$messages['es'] = array(
	'firststeps' => 'Primeros pasos',
	'firststeps-desc' => '[[Special:FirstSteps|Página especial]] para que los usuarios comiencen en un wiki usando la extensión de traducción',
	'translate-fs-pagetitle-done' => '- hecho!',
	'translate-fs-pagetitle' => 'Guía de inicio - $1',
	'translate-fs-signup-title' => 'Registrarse',
	'translate-fs-settings-title' => 'Configurar tus preferencias',
	'translate-fs-userpage-title' => 'Crear tu página de usuario',
	'translate-fs-permissions-title' => 'Solicitar permisos de traducción',
	'translate-fs-target-title' => 'Comenzar a traducir!',
	'translate-fs-email-title' => 'Confirmar tu dirección de correo electrónico',
	'translate-fs-intro' => "Bienvenido al asistente de los primeros pasos en {{SITENAME}}.
Serás guíado a través del proceso de convertirte en un traductor pasa a paso.
Al final serás capaz de traducir los ''mensajes de interfaz'' de todos los proyectos soportados en {{SITENAME}}",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|marco]]

El primer paso es que debes registrarte.

Los créditos por tu traducción se atribuyen a tu nombre de usuario.
La imagen de la derecha muestra como llenar los campos.

Si ya te has registrado, $1inicia sesión$2 entonces.
Una vez que te has registrado, por favor regresa a esta página.

$3Registrarse$4',
	'translate-fs-settings-text' => 'Ahora debes ir a tus preferencias y
cambiar el idioma de la interfaz al idioma que quieres traducir.

El idioma de la interfaz es usado como el idioma a traducir por defecto.
Es fácil olvidarse de cambiar el idioma al correcto, por lo que configurarlo ahora es altamente recomendado.

Mientras estás aquí, puedes hacer que el software muestre traducciones en otros idiomas que conozcas.
Esta configuración se encuentra bajo la pestaña "{{int:prefs-editing}}".
Siéntete libre de explorar otras configuraciones también.

Ve a tu [[Special:Preferences|página de preferencias]] ahora y entonces puedes volver a esta página.',
	'translate-fs-settings-skip' => 'He terminado.
Déjenme continuar.',
	'translate-fs-userpage-text' => 'Ahora es necesario crear una página de usuario.
Por favor escribe algo sobre ti; Quién eres y qué haces.
Esto ayudará a la {{SITENAME}} comunidad para trabajar juntos.
En {{SITENAME}} hay gente de todo el mundo trabajando en distintos idiomas y proyectos.',
	'translate-fs-userpage-submit' => 'Crear mi página de usuario',
	'translate-fs-userpage-done' => 'Bien hecho! Ahora tienes una página de usuario.',
	'translate-fs-permissions-text' => 'Ahora necesitas colocar una solicitud para ser agregado al grupo de traductores.

Hasta que arreglemos el código, por favor ve a [[Project:Translator]] y sigue las instrucciones.

Después que hayas enviado tu solicitud, uno de los miembros del staff de voluntarios verificará tu solicitud y lo aprobará tan pronto como sea posible. Por favor se paciente.

<del>Verifica que la siguiente solicitud está correctamente llenada y luego presiona el botón de solicitud.</del>',
	'translate-fs-target-text' => 'Felicitaciones!
Puedes ahora comenzar a traducir.

No temas si lo sientes nuevo y confuso para ti.
En la [[Project list]] hay una visión general de los proyectos en los que puedes contribuir con traducciones.
La mayoría de los proyectos tiene una página de descripción corta con un enlace "\'\'Traducir este proyecto\'\'", que te llevará a una página que lista todos los mensajes sin traducir.
Una lista de todos los grupos de mensajes con el [[Special:LanguageStats|status de traducción actual para un idioma]] está también disponible.

Si sientes que necesitas entender más antes de empezar a traducir, puedes leer las [[FAQ|Preguntas frecuentes]].
Desafortunadamente la documentación puede estar desactualizada a veces.
Si hay algo que pienses que deberías ser capaz de hacer, pero no cómo, no dudes en preguntarlo en la [[Support|página de soporte]].

Puedes también contactar con otros traductores al mismo idioma en la [[Portal_talk:$1|página de discusión]] del [[Portal:$1|portal de tu idioma]].
El portal enlaza a tu [[Special:Preferences|preferencia de idioma]] actual.
Por favor cámbialo si es necesario.',
	'translate-fs-email-text' => 'Por favor brinda tu dirección de correo electrónico en [[Special:Preferences|tus preferencias]] y confírmalo desde el correo que se te envíe.

Esto permite a los otros usuarios contactarte por correo electrónico.
También recibirás boletines de noticias como máximo una vez al mes.
Si no deseas recibir boletines de noticias, puedes cancelarlas en la pestaña  "{{int:prefs-personal}}" de tus [[Special:Preferences|preferencias]].',
);

/** Finnish (Suomi)
 * @author Nike
 * @author ZeiP
 */
$messages['fi'] = array(
	'firststeps' => 'Alkutoimet',
	'firststeps-desc' => '[[Special:FirstSteps|Toimintosivu]] joka ohjastaa uudet käyttäjät Translate-laajennoksen käyttöön.',
	'translate-fs-pagetitle-done' => ' - valmis!',
	'translate-fs-pagetitle' => 'Alkutoimet - $1',
	'translate-fs-signup-title' => 'Rekisteröityminen',
	'translate-fs-settings-title' => 'Asetusten määrittäminen',
	'translate-fs-userpage-title' => 'Käyttäjäsivun luominen',
	'translate-fs-permissions-title' => 'Pyyntö kääntäjäryhmään liittämisestä',
	'translate-fs-target-title' => 'Kääntäminen voi alkaa!',
	'translate-fs-email-title' => 'Sähköpostiosoitteen vahvistus',
	'translate-fs-intro' => "Tervetuloa {{GRAMMAR:genitive|{{SITENAME}}}} ohjattuihin ensiaskeleisiin.
Seuraamalla sivun ohjeita pääset kääntäjäksi alta aikayksikön.
Suoritettuasi kaikki askeleet, voit kääntää kaikkien {{GRAMMAR:inessive|{{SITENAME}}}} olevien projektien ''käyttöliittymäviestejä''.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]]

Ensimmäinen askel on rekisteröityminen.

Käyttäjätunnuksesi näytetään käännösten yhteydessä.
Voit katsoa apua kenttien täyttämiseen oikealla olevasta kuvasta.

Jos olet jo rekisteröitynyt, $1kirjaudu sisään$2.
Palaa rekisteröitymisen jälkeen tälle sivulle.

$3Rekisteröidy$4',
	'translate-fs-settings-text' => 'Mene seuraavaksi asetussivulle.
Muuta käyttöliittymäkielesi kieleksi, jolle käännät.

Käyttöliittymäkieltäsi käytetään oletusarvoisena kohdekielenä.
Kielen asettaminen kannattaa tehdä jo nyt, koska sen valitseminen unohtuu helposti.

Samalla voit määritellä ne kielet, jotka haluat nähdä kääntämisen aikana.
Tämä asetus löytyy välilehdeltä {{int:prefs-editing}}.
Voit vapaasti kurkkia muitakin asetuksia.

Mene nyt [[Special:Preferences|asetussivulle]] ja palaa sitten tälle sivulle.',
	'translate-fs-settings-skip' => 'Olen valmis.
Haluan jatkaa.',
	'translate-fs-userpage-text' => 'Nyt on aika luoda oma käyttäjäsivusi.

Kirjoita jotain itsestäsi – kuka olet ja mitä teet. 
Tämän tarkoituksena on edistää yhteisöllisyyttä.
{{GRAMMAR:inessive|{{SITENAME}}}} käyttäjät eri puolilta maailmaa työskentelevät eri kielten ja projektien parissa.

Ylläolevan tekstikentän ensimmäinen rivi on <nowiki>{{#babel:en-2}}</nowiki>.
Päivitä se vastaamaan kielitaitoasi.
Numero kielitunnuksen jälkeen kuvaa kielitaitoasi.
Vaihtoehdot:
* 1 — vähäinen
* 2 — perustiedot
* 3 — hyvät tiedot
* 4 — kuin syntyperäinen
* 5 — käytät kieltä ammattimaisesti – esimerkiksi olet kielenkääntäjä

Jos olet kielen synnynnäinen puhuja, jätä taitotaso pois ja käytä vain kielitunnusta.
Esimerkki: Jos olet tamilin synnynnäinen puhuja ja osaat englantia hyvin ja swahilia vähän, voit merkitä:
<code><nowiki>{{#babel:ta|en-3|sw-1}}</nowiki></code>

Jos et tiedä kielen kielitunnusta, nyt on hyvä aika etsiä se.
Voit käyttää allaolevaa listaa.',
	'translate-fs-userpage-submit' => 'Luo käyttäjäsivuni',
	'translate-fs-userpage-done' => 'Hyvin tehty! Sinulla on nyt käyttäjäsivu.',
	'translate-fs-permissions-text' => 'Nyt sinun tulee pyytää, että sinut lisätään kääntäjäryhmään.

Kunnes saamme koodin korjattua, mene sivulle [[Project:Translator]] ja seuraa ohjeita.
Palaa sitten tälle sivulle.

Kun olet tehnyt pyynnön, joku projektin ylläpitäjistä tarkistaa ja hyväksyy sen mahdollisimman pian.
Olethan kärsivällinen.',
	'translate-fs-target-text' => 'Onnittelut!
Voit nyt aloittaa kääntämisen.

Älä huolestu, vaikka et vielä täysin ymmärtäisi miten kaikki toimii.
Meillä on [[Project list|lista projekteista]], joiden kääntämiseen voit osallistua.
Useimmilla projekteilla on lyhyt kuvaussivu, jossa on linkki varsinaiselle käännössivulle.
[[Special:LanguageStats|Kielen nykyisen käännöstilanteen]] näyttävä lista on myös saatavilla.

Jos haluat tietää lisää, voit lukea vaikkapa [[FAQ|usein kysyttyjä kysymyksiä]].
Valitettavasti dokumentaatio voi joskus olla hivenen vanhentunutta.
Jos et keksi, miten joku tarvitsemasi asia tehdään, älä epäröi pyytää apua [[Support|tukisivulla]].

Voit myös ottaa yhteyttä muihin saman kielen kääntäjiin [[Portal:$1|oman kielesi portaalissa]].
Valikon portaalilinkki osoittaa [[Special:Preferences|valitsemasi kielen]] portaaliin.
Jos valitsemasi kieli on väärä, muuta se.',
);

/** French (Français)
 * @author Peter17
 */
$messages['fr'] = array(
	'firststeps' => 'Premiers pas',
	'firststeps-desc' => '[[Special:FirstSteps|Page spéciale]] pour guider les utilisateurs sur un wiki utilisant l’extension Translate',
	'translate-fs-pagetitle-done' => ' - fait !',
	'translate-fs-pagetitle' => 'Guide de démarrage - $1',
	'translate-fs-signup-title' => 'Inscrivez-vous',
	'translate-fs-settings-title' => 'Configurez vos préférences',
	'translate-fs-userpage-title' => 'Créez votre page utilisateur',
	'translate-fs-permissions-title' => 'Demandez les permissions de traducteur',
	'translate-fs-target-title' => 'Commencez à traduire !',
	'translate-fs-email-title' => 'Confirmez votre adresse électronique',
	'translate-fs-intro' => "Bienvenue sur l’assistant premiers pas de {{SITENAME}}.
Nous allons vous guider étape par étape pour devenir un traducteur.
À la fin du processus, vous pourrez traduire les ''messages des interfaces'' de tous les projets gérés par {{SITENAME}}.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|cadre]]

La première étape consiste à s’inscrire.

Les traductions que vous effectuerez seront créditées à votre nom d’utilisateur.
L’image sur la droite montre comment remplir les champs.

Si vous vous êtes déjà inscrit, veuillez $1vous identifier$2.
Une fois inscrit, veuillez revenir vers cette page.

$3Inscrivez-vous$4',
	'translate-fs-settings-text' => 'Vous devez à présent vous rendre dans vos préférences et au moins choisir comme langue d’interface celle dans laquelle vous voulez traduire.

La langue choisie pour l’interface est utilisée comme langue par défaut pour les traductions.
Il est facile d’oublier de changer cette préférence et donc hautement recommandé de le faire maintenant.

Tant que vous y êtes, vous pouvez aussi demander au logiciel d’afficher les traductions dans les autres langues que vous connaissez.
Cette préférence se trouve sous l’onglet « {{int:prefs-editing}} ».
N’hésitez pas à parcourir également les autres préférences.

Allez maintenant à votre [[Special:Preferences|page de préférences]] puis revenez à cette page.',
	'translate-fs-settings-skip' => 'J’ai fini. Laissez-moi continuer.',
	'translate-fs-userpage-text' => 'Vous devez maintenant créer une page utilisateur.

Veuillez écrire quelque chose à propos de vous : qui vous êtes et ce que vous faites.
Cela aidera la communauté de {{SITENAME}} à travailler ensemble.
Sur {{SITENAME}}, il y a des gens de tous les coins du monde qui travaillent sur différentes langues et projets.

Dans la boîte pré-remplie ci-dessus, dans la toute première ligne, vous voyez <nowiki>{{#babel:en-2}}</nowiki>.
Veuillez la compléter avec votre connaissance des langues.
Le nombre qui suit le code de la langue décrit comment vous maîtrisez cette langue.
Les valeurs possibles sont :
* 1 - un peu
* 2 - connaissances de base
* 3 - bonnes connaissances
* 4 - niveau bilingue
* 5 - vous utilisez cette langue de manière professionnelle, par exemple en tant que traducteur professionnel.

Pour votre langue maternelle, ignorez le niveau et n’utilisez que le code de la langue.
Exemple : si votre langue maternelle est le tamoul et que vous parlez bien l’anglais et un peu le swahili, écrivez :
<code><nowiki>{{#babel:ta|en-3|sw-1}}</nowiki></code>

Si vous ne connaissez pas le code d’une langue donnée, vous pouvez le chercher maintenant dans la liste ci-dessous.',
	'translate-fs-userpage-submit' => 'Créer ma page utilisateur',
	'translate-fs-userpage-done' => 'Bien joué ! Vous avez à présent une page utilisateur.',
	'translate-fs-permissions-text' => 'Vous devez déposer une demande pour être ajouté au groupe des traducteurs.

Jusqu’à ce que nous ayons réparé le code, merci d’aller sur [[Project:Translator]] et de suivre les instructions.
Revenez ensuite à cette page.

Quand vous aurez déposé votre demande, un des membre de l’équipe de volontaires la vérifiera et l’approuvera dès que possible.
Merci d’être patient.

<del>Veuillez vérifier que la demande suivante est correctement remplie puis cliquez sur le bouton de demande.</del>',
	'translate-fs-target-text' => "Félicitations !
Vous pouvez maintenant commencer à traduire.

Ne vous inquiétez pas si cela vous paraît un peu nouveau et étrange.
Sur la [[Project list|liste des projets]] se trouve une vue d’ensemble des projets que vous pouvez contribuer à traduire.
Ces projets possèdent, pour la plupart, une page contenant une courte description et un lien « ''Traduire ce projet'' » qui vous mènera vers une page listant tous les messages non traduits.
Une liste de tous les groupes de messages avec l’[[Special:LanguageStats|état actuel de la traduction pour une langue donnée]] est aussi disponible.

Si vous sentez que vous avez besoin de plus d’informations avant de commencer à traduire, vous pouvez lire la [[FAQ|foire aux questions]].
La documentation peut malheureusement être périmée de temps à autres.
Si vous pensez que vous devriez pouvoir faire quelque chose, sans parvenir à trouver comment, n’hésitez pas à poser la question sur la [[Support|page support]].

Vous pouvez aussi contacter les autres traducteurs de la même langue sur [[Portal_talk:$1|la page de discussion]] du [[Portal:$1|portail de votre langue]].
Si vous ne l’avez pas encore fait, [[Special:Preferences|ajustez la langue de l’interface pour qu’elle soit celle dans laquelle vous voulez traduire]]. Ainsi, les liens que vous propose le wiki seront les plus adaptés à votre situation.",
	'translate-fs-email-text' => 'Merci de bien vouloir saisir votre adresse électronique dans [[Special:Preferences|vos préférences]] et la confirmer grâce au message qui vous sera envoyé.

Cela permettra aux autres utilisateurs de vous contacter par courrier électronique.
Vous recevrez aussi un courrier d’informations au plus une fois par mois.
Si vous ne souhaitez pas recevoir ce courrier d’informations, vous pouvez le désactiver dans l’onglet « {{int:prefs-personal}} » de vos [[Special:Preferences|préférences]].',
);

/** Galician (Galego)
 * @author Toliño
 */
$messages['gl'] = array(
	'firststeps' => 'Primeiros pasos',
	'firststeps-desc' => '[[Special:FirstSteps|Páxina especial]] para iniciar aos usuarios no uso da extensión Translate',
	'translate-fs-pagetitle-done' => '; feito!',
	'translate-fs-pagetitle' => 'Asistente para dar os primeiros pasos: $1',
	'translate-fs-signup-title' => 'Rexístrese',
	'translate-fs-settings-title' => 'Configure as súas preferencias',
	'translate-fs-userpage-title' => 'Cree a súa páxina de usuario',
	'translate-fs-permissions-title' => 'Solicite permisos de tradutor',
	'translate-fs-target-title' => 'Comece a traducir!',
	'translate-fs-email-title' => 'Confirme o seu enderezo de correo electrónico',
	'translate-fs-intro' => "Benvido ao asistente para dar os primeiros pasos en {{SITENAME}}.
Esta guía axudaralle, paso a paso, a través do proceso para se converter nun tradutor.
Cando remate, poderá traducir as ''mensaxes da interface'' de todos os proxectos soportados por {{SITENAME}}.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]]

No primeiro paso, cómpre que se rexistre.

As traducións que faga atribuiránselle ao seu nome de usuario.
A imaxe da dereita mostra como encher os campos.

Se xa se rexistrou, $1acceda ao sistema$2.
Unha vez que o faga, volva a esta páxina.

$3Rexístrese$4',
	'translate-fs-settings-text' => 'Agora ten que ir ás súas preferencias e
cambiar a lingua da interface pola lingua á que vai traducir as mensaxes.

A lingua da interface úsase como lingua por defecto para as traducións.
É doado esquecerse de cambiar a lingua, de xeito que definila agora é bastante recomendable.

De paso que vai ás preferencias, tamén pode solicitar que o software mostre as traducións noutras linguas que coñeza.
Esta configuración pódese atopar na lapela "{{int:prefs-editing}}".
Síntase libre de probar o resto de opcións tamén.

Vaia á [[Special:Preferences|páxina das súas preferencias]] e volva despois a esta páxina.',
	'translate-fs-settings-skip' => 'Listo.
Que vén agora?',
	'translate-fs-userpage-text' => 'Agora necesita crear unha páxina de usuario.

Escriba algo sobre si mesmo; quen é vostede e o que fai.
Isto axudará á comunidade de {{SITENAME}} a traballar xuntos.
En {{SITENAME}} hai xente de todo o mundo traballando en diferentes linguas e proxectos.

No cadro preenchido enriba na primeira liña pode ollar isto: <nowiki>{{#babel:en-2}}</nowiki>.
Compléteo cos seus coñecementos lingüísticos.
O número que vai despois do código da lingua describe o ben que a coñece.
As alternativas son:
* 1 - un pouco
* 2 - coñecemento básico
* 3 - bo coñecemento
* 4 - nivel de falante nativo
* 5 - usa a lingua profesionalmente, é dicir, é un tradutor profesional.

Se vostede é un falante nativo dunha lingua, ignore o nivel e use só o código de lingua.
Exemplo: se a súa lingua materna é o italiano, se fala o galego ben e o inglés un pouco, tería que escribir:
<code><nowiki>{{#babel:it|gl-3|en-1}}</nowiki></code>

Se non coñece o código de lingua dalgunha lingua, agora é un bo momento para descubrilo.
Pode empregar a lista que hai a continuación.',
	'translate-fs-userpage-submit' => 'Crear a miña páxina de usuario',
	'translate-fs-userpage-done' => 'Ben feito! Agora xa ten unha páxina de usuario.',
	'translate-fs-permissions-text' => 'Agora cómpre solicitar permisos para comezar a formar parte do grupo de tradutores.

Ata que arranxemos o código, vaia a [[Project:Translator]] e siga as instrucións.
A continuación, volva a esta páxina.

Despois de presentar a súa solicitude, un dos membros do equipo de voluntarios ha comprobar a súa petición e aprobala o máis axiña posible.
Por favor, sexa paciente.

<del>Asegúrese de que a seguinte solicitude está correctamente cuberta e prema o botón axeitado.</del>',
	'translate-fs-target-text' => 'Parabéns!
Agora xa pode comezar a traducir.

Non teña medo se aínda se sente novo e confuso.
En [[Project list]] hai unha visión xeral dos proxectos nos que pode contribuír coas súas traducións.
A maioría dos proxectos teñen unha páxina cunha breve descrición e mais unha ligazón que di "\'\'Traducir este proxecto\'\'", que o levará a unha páxina que lista todas as mensaxes non traducidas.
Tamén hai dispoñible unha lista con todos os grupos de mensaxes co seu [[Special:LanguageStats|estado actual da tradución nunha lingua]].

Se pensa que necesita aprender máis antes de comezar a traducir, pode ler as [[FAQ|preguntas máis frecuentes]].
Por desgraza, a documentación pode estar desactualizada ás veces.
Se cre que hai algo que debe ser capaz de facer, pero non sabe como, non dubide en pedir [[Support|axuda]].

Tamén pode poñerse en contacto cos demais tradutores da mesma lingua na [[Portal_talk:$1|páxina de conversa]] do [[Portal:$1|portal da súa lingua]].
Se aínda non o fixo, [[Special:Preferences|cambie a lingua da interface de usuario elixindo aquela na que vai traducir]]; deste xeito, o wiki pode mostrar as ligazóns máis relevantes e que lle poidan interesar.',
	'translate-fs-email-text' => 'Proporcione o seu enderezo de correo electrónico [[Special:Preferences|nas súas preferencias]] e confírmeo mediante a mensaxe que chegará á súa bandexa de entrada.

Isto permite que outros usuarios se poñan en contacto con vostede por correo electrónico.
Tamén recibirá boletíns informativos, como máximo unha vez ao mes.
Se non quere recibir estes boletíns, pode cancelar a subscrición na lapela "{{int:prefs-personal}}" das súas [[Special:Preferences|preferencias]].',
);

/** Upper Sorbian (Hornjoserbsce)
 * @author Michawiki
 */
$messages['hsb'] = array(
	'firststeps' => 'Prěnje kroki',
	'firststeps-desc' => '[[Special:FirstSteps|Specialna strona]] za startowu pomoc na wikiju, kotryž rozšěrjenje Translate wužiwa',
	'translate-fs-pagetitle-done' => ' - sčinjene!',
	'translate-fs-pagetitle' => 'Startowy asistent - $1',
	'translate-fs-signup-title' => 'Registrować',
	'translate-fs-settings-title' => 'Konfiguruj swoje nastajenja',
	'translate-fs-userpage-title' => 'Wutwor swoju wužiwarsku stronu',
	'translate-fs-permissions-title' => 'Wo přełožowanske prawa prosyć',
	'translate-fs-target-title' => 'Započń přełožować!',
	'translate-fs-email-title' => 'Wobkruć swoju e-mejlowu adresu',
	'translate-fs-intro' => "Witaj do startoweho asistenta projekta {{SITENAME}}.
Dóstanješ nawod krok po kroku, kak so z přełožowarjom stanješ.
Na kóncu móžeš ''zdźělenki programoweho powjercha'' wšěch podpěrowanych projektow na {{SITENAME}} přełožić.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]]

W prěnim kroku dyrbiš so registrować, t. r. wužiwarske konto wutworić.

Přełožki, kotrež sy sčinił, budu so twojemu wužiwarskemu mjenu připokazować.
Wobraz naprawo ći pokazuje, kak dyrbiš pola wupjelnić.

Jeli sy hižo zregistrowany, $1přizjew so$2 město toho.
Hdyž sy zregistrowany, wróć so k tutej stronje.

$3Registrować$4',
	'translate-fs-settings-text' => 'Dźi nětko do swojich nastajenjow a změń znajmjeńša swoju powjerchowu rěc do rěče, do kotrejež chceš přełožić.

Twoja powjerchowa rěč wužiwa so jako standardna cilowa rěč.
Zabywa so lochko, rěč do praweje rěče změnić, tohodla so jara poručuje, ju nětko nastajić.

Hdyž sy jónu tu, móžeš tež softwaru prosyć, přełožki tež w druhich rěčach zwobraznić, kotrež rozumiš.
Tute nastajenje namakaš pod rajtarkom "{{int:prefs-editing}}".
Wobhladaj sej woměrje tež druhe nastajenja.

Dźi nětko k swojej [[Special:Preferences|stronje nastajenjow]] a wróć so potom k tutej stronje.',
	'translate-fs-settings-skip' => 'Sym hotowy.
Daj mi pokročować.',
	'translate-fs-userpage-text' => 'Nětko dyrbiš wužiwarsku stronu wutworić.

Prošu napisaj něšto wo sebi; štó sy a što činiš.
To budźe zhromadźenstwu {{SITENAME}} při zhromadnym dźěle pomhać.
Na {{SITENAME}} su ludźo z cyłeho swěta, kotřiž na rozdźělnych rěčach a projektach dźěłaja.

We wupjelnjenym kašćiku horjeka w prěnjej lince, widźiš <nowiki>{{#babel:en-2}}</nowiki>.
Prošu wudospołń jón přez twoje rěčne znajomosće.
Ličba za rěčnym kodom wopisuje, kak derje znaješ rěč.
Móžnosće su:
* 1 - trochu
* 2 - zakładne znajomosće
* 3 - dobre znajomosće
* 4 - niwow maćernorěčneho rěčnika
* 5 - wužiwaš rěč profesionelnje, na přikład twoje powołanje je přełožowar.

Jeli sy maćernorěčny rěčnik rěče, wuwostaj ličbu za rěčnym kodom a wužij jenož rěčny kod.
Přikład: jeli tamilšćina je twoja maćeršćina, jendźelšćinu derje a swahilšćinu trochu rozumiš, by pisał:
<code><nowiki>{{#babel:ta|en-3|sw-1}}</nowiki></code>

Jeli njeznaješ rěčny kod rěče, pohladaj nětko za nim.
Móžeš slědowacu lisćinu za to wužiwać.',
	'translate-fs-userpage-submit' => 'Moju wužiwarsku stronu wutworić',
	'translate-fs-userpage-done' => 'Gratulacija! Maš nětko wužiwarsku stronu.',
	'translate-fs-permissions-text' => 'Nětko dyrbiš wo přiwzaće do skupiny přełožowarjow prosyć.

Doniž kod njekorigujemy, dźi na stronu [[Project:Translator]] a slěduj instrukcije. Wróć so potom na tutu stronu.

Po tym zo sy swoje požadanje wotpósłał, budźe jedyn z dobrowólnych čłonow teama twoje požadanje kontrolować a jo tak bórze kaž móžno schwalić.
Prošu budź sćerpliwy.

<del>Skontroluj, hač slědowace požadanje je korektnje wupjelnjene a klikń potom na tłóčatko.</del>',
	'translate-fs-target-text' => 'Zbožopřeće!
Móžeš nětko přełožowanje započeć.

Nječiń sej žane starosće, jeli so ći hišće nowe a konfuzne zda.
Na [[Project list|lisćinje projektow]] je přehlad projektow, ke kotrymž móžeš přełožki přinošować.
Najwjace projektow ma krótku wopisansku stronu z wotkazom "\'\'Tutón projekt přełožić\'\'", kotryž će k stronje wjedźe, kotraž wšě njepřełožene zdźělenki nalistuje.
Lisćina wšěch skupinow zdźělenkow z [[Special:LanguageStats|aktualnym přełožowanskim stawom za rěč]] tež k dispoziciji steji.

Jeli měniš, zo dyrbiš najprjedy wjace rozumić, prjedy hač zapóčnješ přełožować, móžeš [[FAQ|Časte prašenja]] čitać.
Bohužel móže dokumentacija druhdy zestarjena być.
Jeli něšto je, wo kotrymž mysliš, zo měło móžno być, ale njenamakaš, kak móžeš to činić, prašej so woměrje na stronje [[Support|Podpěra]].

Móžeš so tež ze sobupřełožowarjemi samsneje rěče na [[Portal_talk:$1|diskusijnej stronje]] [[Portal:$1|portala swojeje rěče]] do zwiska stajić.
Jeli hišće njejsy to činił, [[Special:Preferences|změń swój wužiwarski powjerch do rěče, do kotrejež chceš přełožować]], zo by wiki móhł wotkazy pokazać, kotrež su relewantne za tebje.',
	'translate-fs-email-text' => 'Prošu podaj swoju e-mejlowu adresu w [[Special:Preferences|swojich nastajenjach]] a wobkruć ju přez e-mejl, kotraž so ći sćele. 

To dowola druhim wužiwarjam, so z tobu přez e-mejl do zwisk stajić.
Dóstanješ tež powěsćowe listy, zwjetša jónkróć wob měsać.
Jeli nochceš powěsćowe listy dóstać, móžeš tutu opciju na rajtarku "{{int:prefs-personal}}" swojich [[Special:Preferences|preferencow]] znjemóžnić.',
);

/** Interlingua (Interlingua)
 * @author McDutchie
 */
$messages['ia'] = array(
	'firststeps' => 'Prime passos',
	'firststeps-desc' => '[[Special:FirstSteps|Pagina special]] pro familiarisar le usatores de un wiki con le extension Translate',
	'translate-fs-pagetitle-done' => ' - finite!',
	'translate-fs-pagetitle' => 'Assistente de initiation - $1',
	'translate-fs-signup-title' => 'Crear un conto',
	'translate-fs-settings-title' => 'Configurar tu preferentias',
	'translate-fs-userpage-title' => 'Crear tu pagina de usator',
	'translate-fs-permissions-title' => 'Requestar permissiones de traductor',
	'translate-fs-target-title' => 'Comenciar a traducer!',
	'translate-fs-email-title' => 'Confirmar tu adresse de e-mail',
	'translate-fs-intro' => "Benvenite al assistente de initiation de {{SITENAME}}.
Tu essera guidate passo a passo trans le processo de devenir traductor.
Al fin tu potera traducer le ''messages de interfacie'' de tote le projectos supportate in {{SITENAME}}.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount-ia.png|frame]]

In le prime passo tu debe crear un conto.

Le traductiones que tu facera essera attribuite a tu nomine de usator.
Le imagine al dextra demonstra como completar le formulario.

Si tu possede jam un conto in le sito, $1aperi un session$2.
Quando tu ha create un conto, per favor retorna a iste pagina.

$3Crear un conto$4',
	'translate-fs-settings-text' => 'Tu deberea ora visitar tu preferentias e,
al minus, cambiar le lingua de interfacie al lingua in le qual tu vole traducer.

Tu lingua de interfacie es usate automaticamente como lingua in le qual traducer.
Il es facile oblidar de cambiar al lingua correcte, dunque il es altemente recommendate de facer lo ora.

Intertanto, tu pote etiam demandar que le software presenta traductiones existente in altere linguas que tu cognosce.
Iste preferentia se trova sub le scheda "{{int:prefs-editing}}".
Sia libere de explorar etiam le altere preferentias.

Visita ora tu [[Special:Preferences|pagina de preferentias]] e postea retorna a iste pagina.',
	'translate-fs-settings-skip' => 'Io ha finite. Lassa me continuar.',
	'translate-fs-userpage-text' => 'Ora, tu debe crear un pagina de usator.

Per favor scribe alique super te; qui tu es e lo que tu face.
Isto adjutara le communitate de {{SITENAME}} a collaborar.
In {{SITENAME}} il ha personas de tote le mundo laborante a diverse linguas e projectos.

In le quadro precompletate hic supra, in le primissime linea, tu vide <nowiki>{{#babel:en-2}}</nowiki>.
Per favor completa isto con tu cognoscentia linguistic.
Le numero post le codice de lingua describe tu nivello de maestria del lingua.
Le optiones es:
* 1 - un poco
* 2 - cognoscentia de base
* 3 - bon cognoscentia
* 4 - nivello de parlante native
* 5 - tu usa le lingua professionalmente, per exemplo tu es traductor professional.

Si tu es un parlante native de un lingua, omitte le nivello de cognoscentia, usante solmente le codice de lingua.
Per exemplo: si tu parla tamil nativemente, anglese ben, e un poco de swahili, tu scriberea:
<code><nowiki>{{#babel:ta|en-3|sw-1}}</nowiki></code>

Si tu non cognosce le codice de un lingua, ora es un bon tempore pro cercar lo. Tu pote usar le lista hic infra.',
	'translate-fs-userpage-submit' => 'Crear mi pagina de usator',
	'translate-fs-userpage-done' => 'Ben facite! Tu ha ora un pagina de usator.',
	'translate-fs-permissions-text' => 'Ora, tu debe facer un requesta pro esser addite al gruppo de traductores.

Nos non ha ancora automatisate isto; pro le momento, per favor visita [[Project:Translator]] e seque le instructiones.
Postea, retorna a iste pagina.

Post que tu ha submittite tu requesta, un del membros del personal voluntari verificara tu requesta e lo approbara si tosto como possibile.
Per favor sia patiente.

<del>Verifica que le sequente requesta es correcte e complete, postea clicca super le button de requesta.</del>',
	'translate-fs-target-text' => 'Felicitationes!
Tu pote ora comenciar a traducer.

Non sia intimidate si isto te pare ancora nove e confundente.
In [[Project list]] il ha un summario del projectos al quales tu pote contribuer traductiones.
Le major parte del projectos ha un curte pagina de description con un ligamine "\'\'Traducer iste projecto\'\'", que te portara a un pagina que lista tote le messages non traducite.
Un lista de tote le gruppos de messages con le [[Special:LanguageStats|stato de traduction actual pro un lingua]] es etiam disponibile.

Si tu senti que tu ha besonio de comprender plus ante de traducer, tu pote leger le [[FAQ|folio a questiones]].
Infelicemente le documentation pote a vices esser obsolete.
Si il ha un cosa que tu pensa que tu deberea poter facer, ma non succede a discoperir como, non hesita a poner le question in le [[Support|pagina de supporto]].

Tu pote etiam contactar altere traductores del mesme lingua in [[Portal_talk:$1|le pagina de discussion]] del [[Portal:$1|portal de tu lingua]].
Si tu non ja lo ha facite, [[Special:Preferences|cambia tu lingua de interfacie de usator al lingua in le qual tu vole traducer]], de sorta que le wiki pote monstrar te le ligamines le plus relevante a te.',
	'translate-fs-email-text' => 'Per favor entra tu adresse de e-mail in [[Special:Preferences|tu preferentias]] e confirma lo per medio del e-mail que te essera inviate.

Isto permitte que altere usatores te contacta via e-mail.
Tu recipera anque bulletines de novas al plus un vice per mense.
Si tu non vole reciper bulletines de novas, tu pote disactivar los in le scheda "{{int:prefs-personal}}" de tu [[Special:Preferences|preferentias]].',
);

/** Indonesian (Bahasa Indonesia)
 * @author Irwangatot
 */
$messages['id'] = array(
	'firststeps' => 'Langkah pertama',
	'firststeps-desc' => '[[Special:FirstSteps|Halaman istimewa]] untuk mendapatkan pengguna memulai di wiki menggunakan ekstensi Terjemahan',
	'translate-fs-pagetitle-done' => '- Selesai!',
	'translate-fs-pagetitle' => 'Persiapan wizard - $ 1',
	'translate-fs-signup-title' => 'Mendaftar',
	'translate-fs-settings-title' => 'Mengkonfigurasi preferensi anda',
	'translate-fs-userpage-title' => 'Buat halaman pengguna anda',
	'translate-fs-permissions-title' => 'Permintaan izin penerjemah',
	'translate-fs-target-title' => 'Mulai menerjemahkan!',
	'translate-fs-email-title' => 'Konfirmasikan alamat surel Anda',
);

/** Japanese (日本語)
 * @author Fryed-peach
 * @author Hosiryuhosi
 */
$messages['ja'] = array(
	'firststeps' => '開始手順',
	'firststeps-desc' => 'Translate 拡張機能を使用するウィキで利用者が開始準備をするための[[Special:FirstSteps|特別ページ]]',
	'translate-fs-pagetitle-done' => ' - 完了！',
	'translate-fs-pagetitle' => '開始準備ウィザード - $1',
	'translate-fs-signup-title' => '利用者登録',
	'translate-fs-settings-title' => '個人設定の設定',
	'translate-fs-userpage-title' => 'あなたの利用者ページを作成',
	'translate-fs-permissions-title' => '翻訳者権限の申請',
	'translate-fs-target-title' => '翻訳を始めましょう！',
	'translate-fs-email-title' => '自分の電子メールアドレスの確認',
	'translate-fs-intro' => '{{SITENAME}} 開始準備ウィザードへようこそ。これから翻訳者になるための手順について1つずつ案内していきます。それらを終えると、あなたは {{SITENAME}} でサポートしているすべてのプロジェクトのインターフェイスメッセージを翻訳できるようになります。',
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]]

手順の初めは利用者登録を済ませることです。

あなたが為した翻訳にはあなたの利用者名がクレジットとして付記されます。右の画像ではフォームの各欄をどのように埋めるのかを示しています。

既に登録が済んでいる場合は、代わりに$1ログイン$2してください。登録がまだの場合は、登録を済ませてからこのページに戻ってきてください。

$3登録$4',
	'translate-fs-settings-text' => '個人設定に移動し、まずインターフェイス言語をあなたが作業しようとしている翻訳先の言語に変更してください。

あなたのインターフェイス言語は既定の翻訳先言語として使われます。この言語を正しいものに変更する作業は忘れがちであるため、今それを行うことを強く勧めます。

またさらに、あなたが知っている他の言語での訳文も表示するよう設定することができます。その設定は「{{int:prefs-editing}}」タブの下にあります。他の設定について探ってみるのもよいでしょう。

[[Special:Preferences|個人設定ページ]]に移動し、終わったらこのページに戻ってきてください。',
	'translate-fs-settings-skip' => '終わったので次に進みます。',
	'translate-fs-userpage-text' => '次に、あなたの利用者ページを作成する必要があります。

自身について、あなたが何者で何をしているのかなど、なにかを書いてください。これは {{SITENAME}} のコミュニティーで共同作業を行う助けとなります。{{SITENAME}} には世界中から異なる言語やプロジェクトで作業を行っている人々が集まっています。

上の入力済みのボックスのちょうど1行目に <nowiki>{{#babel:en-2}}</nowiki> とあるのを確認してください。それをあなたの言語に関する知識を書いて完成させます。言語コードの後に続く数字は、その言語をあなたがどれだけ理解できるか伝えるものです。以下が選択肢です:
* 1 - 少し
* 2 - 基礎的な知識
* 3 - 十分な知識
* 4 - 母語話者の水準
* 5 - プロの翻訳家であるなど、その言語を職業的に使用している

あなたがその言語のまさに母語話者である場合、理解度を表す数字の部分は消して、言語コードのみを書きます。例えば、あなたが母語としてタミル語を話し、英語をうまく、スワヒリ語を少し話す場合は、次のようになります:
<code><nowiki>{{#babel:ta|en-3|sw-1}}</nowiki></code>

言語コードがわからない場合は、いい機会ですので調べてみましょう。下記の一覧を参考にしてください。',
	'translate-fs-userpage-submit' => '自分の利用者ページを作成',
	'translate-fs-userpage-done' => 'お疲れ様です。あなたの利用者ページができました。',
	'translate-fs-permissions-text' => '次に、翻訳者グループに追加してもらうよう申請を出す必要があります。

新しい仕組みが完成するまでは、[[Project:Translator]] に移動してそこにある指示に従っていただくことになっています。終わったらこのページに戻ってきてください。

申請が提出されると、できる限り速やかにボランティアスタッフの誰かがあなたの申請を審査し承認いたします。この間しばらくお待ちください。

<del>以下の申請が正しく入力されているか確認し、それから申請ボタンを押してください。</del>',
	'translate-fs-target-text' => "お疲れ様でした！あなたが翻訳を開始する準備が整いました。

まだ慣れないことや分かりにくいことがあっても、心配することはありません。[[Project list|プロジェクト一覧]]にあなたが翻訳を行うことのできる各プロジェクトの概要があります。ほとんどのプロジェクトには短い解説ページがあり、「'''Translate this project'''」というリンクからそのプロジェクトの未翻訳メッセージをすべて一覧できるページに移動できます。すべてのメッセージグループに関して[[Special:LanguageStats|各言語別に現在の翻訳状況]]を一覧することもできます。

翻訳を始める前にもっと知らなければならないことがあると感じられたならば、[[FAQ]] のページを読むのもよいでしょう。残念なことにドキュメントの中には更新が途絶えてしまっているものもあります。もし、なにかやりたいことがあって、それをどうやって行えばよいのかわからない場合には、遠慮せず[[Support|サポートページ]]にて質問してください。

また、同じ言語で作業している仲間の翻訳者とは[[Portal:$1|言語別のポータル]]で連絡することができます。ポータルへのリンクは現在の[[Special:Preferences|言語設定]]によります。必要ならば変更してください。",
	'translate-fs-email-text' => 'あなたの電子メールアドレスを[[Special:Preferences|個人設定]]で入力し、送られてきたメールからそのメールアドレスの確認を行ってください。

これにより、他の利用者があなたに電子メールを通じて連絡できるようになります。また、多くて月に1回ほどニュースレターが送られてきます。ニュースレターを受け取りたくない場合は、[[Special:Preferences|個人設定]]の「{{int:prefs-personal}}」タブで受信の中止を設定できます。',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'firststeps' => 'Éischt Schrëtt',
	'firststeps-desc' => "[[Special:FirstSteps|Spezialsäit]] fir datt Benotzer besser ukommen fir d'Erweiderung 'Translate' op enger Wiki ze benotzen",
	'translate-fs-pagetitle-done' => ' - fäerdeg!',
	'translate-fs-pagetitle' => 'Assistent fir unzefänken - $1',
	'translate-fs-signup-title' => 'Schreift Iech an',
	'translate-fs-settings-title' => 'Är Astellunge festleeën',
	'translate-fs-userpage-title' => 'Maacht Är Benotzersäit',
	'translate-fs-permissions-title' => 'Iwwersetzerrechter ufroen',
	'translate-fs-target-title' => 'Ufänke mat iwwersetzen!',
	'translate-fs-email-title' => 'Confirméiert är E-Mailadress',
	'translate-fs-intro' => "Wëllkomm beim {{SITENAME}}-Startassistent.
Iech gëtt gewisen, Déi Dir Schrëtt fir Schrëtt zum Iwwersetzer gitt.
Um Schluss kënnt Dir all ''Interface-Messagen'' vun de vun {{SITENAME}} ënnerstetzte Projeten iwwersetzen.",
	'translate-fs-signup-text' => "[[Image:HowToStart1CreateAccount.png|frame]]

als éischte Schrëtt musst dir iech umellen.

Déi Iwwersetzungen déi Dir maacht ginn Ärem Benotzernumm ugerechent.
D'Bild riets weist wéi Dir d'Felder ausfëlle sollt.

Wann dir Iech schonn ugemellt hutt, $1logg Iech$2 an.
Esou bal wéi Dir ugellt an ageloggt sidd, kommt w.e.g. op dës Säit zréck.

$3Umellen$4",
	'translate-fs-settings-text' => "Elo gitt Dir am beschten op Är Astellungen a
wiesselt Är Sprooch vum Interface an déi Sprooch an déi Dir iwwersetze wëllt.

D'Sprooch déi Dir fir den Interface benotzt gëtt als Standard-Zilsprooch benotzt.
Et geet séier fir d'Astelle vun der Sprooch op déi korrekt ze vergiessen, dofir ass et ugeroden dat elo direkt ze maachen.

Wann Dir schonn do sidd, da kënnt Dir d'Software och froe fir Iwwersetzungen aner Sproochen déi Dir kennt ze weisen.
Dës Astellung fannt Dir op dem Tab \"{{int:prefs-editing}}\".
Zéckt net fir och aner Astellungen auszeprobéieren.

Gitt elo op Är [[Special:Preferences|Säit mat den Astellungen]] a kommt duerno op dës Säit zréck.",
	'translate-fs-settings-skip' => 'Ech si fäerdeg.
Loosst mech weidermaachen.',
	'translate-fs-userpage-text' => "Elo musst Dir eng Benotzersäit leeën.

Schreift w.e.g. eppes iwwer Iech, wien Dir sidd a wat Dir maacht.
Dat hëlleft der {{SITENAME}}-Gemeinschaft bäi der Zesummenaarbecht.
Op {{SITENAME}} gëtt et Leit aus ganzer Welt, déi u verschiddene Sproochen a Projeten schaffen.

An der ausgefëllter Këscht uewe gesi Dir an der éischter Zeil <nowiki>{{#babel:en-2}}</nowiki>.
Fëllt et w.e.g mat Äre Sproochkenntnisser aus.
D'Zuel hanner dem Sproochcode beschreiwt wéi gudd Dir d'Sprooch kënnt.
D'Méiglechkeete sinn:
*1 - e bëssen
*2 - Basiswëssen
*3 - fléissend
*4 - bal wéi d'Mammesprooch
*5 - professionell, z.B. wann Dir e professionellen Iwwersetzer sidd.

Wenn dat Är Mammesprooch ass, loosst d'Zuel ewech a benotzt nëmmen de Sproochcode.
Beispill: Wann Dir Tamil als Mammesprooch, Englesch gutt an e bësse Swahili kéint Dir dat esou uginn:
<code><nowiki>{{#babel:ta|en-3|sw-1}}</nowiki></code>

Wann Dir de Sproochcode vun enger Sprooch net kennt, da kuckt en elo no.
Dir kënnt d'Lëscht ënnen drënner benotzen.",
	'translate-fs-userpage-submit' => 'Meng Benotzersäit maachen',
	'translate-fs-userpage-done' => 'Gutt gemaach! dir hutt elo eng Benotzersäit.',
	'translate-fs-permissions-text' => 'Elo musst Dir eng Ufro maache fir an de Grupp vun den Iwwersetzer derbäigesat ze ginn.

Bis mir de Code geännert hunn, gitt w.e.g. op [[Project:Translator]] a maacht ete sou wéi et do beschriwwen ass.
Kommt duerno zréck op dës Säit.

Nodeems datt Dir Är Ufro gemaacht hutt, kuckt ee vun de fräiwëllege Membere vun eise Mataarbechter Är Ufro no an approuvéiert se esou séier wéi méiglech. Hutt w.e.g. e bësse Gedold.

<del>Kuckt w.e.g. no ob dës Ufro korrekt ausgefëllt ass a klickt dann op de Knäppche vun der Ufro.</del>',
	'translate-fs-target-text' => "Felicitatiounen!
Dir kënnt elo ufänke mat iwwersetzen.

Maacht Iech näischt doraus wann dat am Ufank fir Iech nach e komescht Gefill ass.
Op [[Project list]] gëtt et eng Iwwersiicht vu Projeten bäi deenen Dir hëllefe kënnt z'iwwersetzen.
Déi meescht Projeten hunn eng kuerz Beschreiwungssäit mat engem \"''Iwwersetz dës e Projet''\" Link, deen Iech op eng Säit op däer all net iwwersate Messagen dropstinn.
Eng Lëscht mat alle Gruppe vu Messagen mat dem [[Special:LanguageStats|aktuellen Iwwersetzungsstatus fir eng Sprooch]] gëtt et och.

Wann dir mengt Dir sollt méi verstoen ier Dir ufänkt mat Iwwersetzen, kënnt Dir déi [[FAQ|dacks gestallte Froe]] liesen.
Onglécklecherweis kann et virkommen datt d'Dokumentatioun heiansdo net à jour ass.
Wann et eppes gëtt vun deem Dir mengt datt Dir e maache kënnt, awer Dir fannt net eraus wéi, dann zéckt net fir eis op der [[Support|Support-Säit]] ze froen.

Dir kënnt och aner Iwwersetzer vun der selwechter Sprooch op der [[Portal_talk:\$1|Diskussiounssäit]] vun [[Portal:\$1|Ärem Sproocheportal]] kontaktéieren. Wann dir et net scho gemaach hutt, [[Special:Preferences|ännert d'Sprooch vum Interface an déi Sprooch an déi Dir iwwersetze wëllt]], esou datt d'Wiki Iech déi wichtegst Linke weise kann.",
	'translate-fs-email-text' => 'Gitt w.e.g. Är E-Mailadress an [[Special:Preferences|Ären Astellungen]] un a confirméiert se vun der E-Mail aus déi Dir geschéckt kritt.

Dëst erlaabte et anere Benotzer fir Iech per Mail ze kontaktéieren.
Dir och Newsletteren awer héchstens eng pro Mount.
Wann Dir keng Newslettere kréie wëllt, da kënnt Dir dat am Tab "{{int:prefs-personal}}"  vun Ären [[Special:Preferences|Astellungen]] ausschalten.',
);

/** Macedonian (Македонски)
 * @author Bjankuloski06
 */
$messages['mk'] = array(
	'firststeps' => 'Први чекори',
	'firststeps-desc' => '[[Special:FirstSteps|Специјална страница]] за помош со првите чекори на вики што го користи додатокот Преведување (Translate)',
	'translate-fs-pagetitle-done' => '- завршено!',
	'translate-fs-pagetitle' => 'Помошник „Како да започнете“ - $1',
	'translate-fs-signup-title' => 'Регистрација',
	'translate-fs-settings-title' => 'Поставете ги вашите нагодувања',
	'translate-fs-userpage-title' => 'Создајте своја корисничка страница',
	'translate-fs-permissions-title' => 'Барање на дозвола за преведување',
	'translate-fs-target-title' => 'Почнете со преведување!',
	'translate-fs-email-title' => 'Потврдете ја вашата е-пошта',
	'translate-fs-intro' => "Добредојдовте на помошникот за први чекори на {{SITENAME}}.
Овој помошник постепено ќе води низ постапката за станување преведувач.
Потоа ќе можете да преведувате ''посреднички (интерфејс) пораки'' за сите поддржани проекти на {{SITENAME}}.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]]
Најпрвин мора да се регистрирате.

Заслугите за преводите ќе се припишуваат на вашето корисничко име.
Сликата десно покажува како треба да се пополнат полињата.

Ако сте веќе регистрирани, сега $1најавете се$2.
Откога ќе се регистрирате, вратете се на оваа страница.

$3Регистрација$4',
	'translate-fs-settings-text' => 'Сега одете во вашите нагодувања и
барем сменете го јазикот на посредникот (интерфејсот) во јазикот на којшто ќе преведувате.

Јазикот на посредникот ќе се смета за ваш матичен целен јазик.
Може лесно да заборавите да го смените јазикот на исправниот, па затоа поставете го сега.

Додека сте тука, можете да побарате програмот да ги прикажува напревените преводи на други јазици.
Оваа функција ќе ја најдете во јазичето „{{int:prefs-editing}}“.
Најслободно истражувајте ги и другите поставки и можности.

Сега одете на [[Special:Preferences|вашите нагодувања]], па вратете се пак на оваа страница.',
	'translate-fs-settings-skip' => 'Завршив. Одиме понатаму.',
	'translate-fs-userpage-text' => 'Сега ќе треба да направите корисничка страница.

Напишете нешто за вас; кој сте и со што се занимавате.
Така заедницата на {{SITENAME}} ќе може да работи подобро.
На {{SITENAME}} има луѓе од целиот свет кои работат на различни јазици и проекти.

Во подготвената кутија горе, на најпрвиот ред ќе видите <nowiki>{{#babel:en-2}}</nowiki>.
Пополнете ја со јазикот или јазиците од кои имате познавања.
Бројката до јазичната кратенка го означува нивото на кое го владеете јазикот.
Еве ги можностите:
* 1 - малку
* 2 - основни познавања
* 3 - солидни познавања
* 4 - на ниво на мајчин
* 5 - го користите јазикот професионално, на пр. сте професионален преведувач.

Ако јазикот е ваш мајчин јазик, тогаш изоставете го нивото, и ставете го само јазичниот код (кратенка).
Пример: ако зборувате македонски од раѓање, англиски добро, и малку шпански, ќе внесете:
<code><nowiki>{{#babel:mk|en-3|es-1}}</nowiki></code>

Ако не го знаете јазичниот код на некој јазик, сега имате добра можност да го дознаете. Погледајте на списокот подолу.',
	'translate-fs-userpage-submit' => 'Создај корисничка страница',
	'translate-fs-userpage-done' => 'Одлично! Сега имате корисничка страница.',
	'translate-fs-permissions-text' => 'Сега ќе треба да поднесете барање за да ве стават во групата на преведувачи.

Додека не го поправиме овој код, одете на [[Project:Translator]] и проследете ги напатствијата.
Потоа вратете се на страницава.

Откако ќе го пополните барањето, доброволец од персоналот ќе го провери и одобри во најкраток можен рок.
Бидете трпеливи.

<del>Проверете дали следново барање е правилно пополнето, а потоа притиснете го копчето за поднесување на барањето.</del>',
	'translate-fs-target-text' => "Честитаме!
Сега можете да почнете со преведување.

Не плашете се ако сето ова сè уште ви изгледа ново и збунително.
Списокот [[Project list]] дава преглед на проектите каде можете да придонесувате со ваши преводи.
Највеќето проекти имаат страница со краток опис и врска „''Преведи го проектов''“, која ќе ве одвете до страница со сите непреведени пораки за тој проект.
Има и список на сите групи на пораки со [[Special:LanguageStats|тековниот статус на преведеност за даден јазик]].

Ако мислите дека треба да осознаете повеќе пред да почнете со преведување, тогаш прочитајте ги [[FAQ|често поставуваните прашања]].
Нажалост документацијата напати знае да биде застарена.
Ако има нешто што мислите дека би требало да можете да го правите, но не можете да дознаете како, најслободно поставете го прашањето на [[Support|страницата за поддршка]].

Можете и да се обратите кај вашите колеги што преведуваат на истиот јазик на [[Portal_talk:$1|страницата за разговор]] на [[Portal:$1|вашиот јазичен портал]].
Ако ова веќе го имате сторено, тогаш [[Special:Preferences|наместете го јазикот на посредникот на оној на којшто сакате да преведувате]], и така викито ќе ви ги прикажува врските што се однесуваат на вас.",
	'translate-fs-email-text' => 'Наведете ја вашата е-пошта во [[Special:Preferences|нагодувањата]] и потврдете ја преку пораката испратена на неа.

Ова им овозможува на корисниците да ве контактираат преку е-пошта.
На таа адреса ќе добивате и билтени со новости, највеќе еднаш месечно.
Ако не сакате да добиват билтени, можете да се отпишете преку јазичето „{{int:prefs-personal}}“ во вашите [[Special:Preferences|нагодувања]].',
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'firststeps' => 'Eerste stappen',
	'firststeps-desc' => '[[Special:FirstSteps|Speciale pagina]] voor het op gang helpen van gebruikers op een wiki met de uitbreiding Translate',
	'translate-fs-pagetitle-done' => ' - afgerond!',
	'translate-fs-pagetitle' => 'Aan de slag - $1',
	'translate-fs-signup-title' => 'Registreren',
	'translate-fs-settings-title' => 'Uw voorkeuren instellen',
	'translate-fs-userpage-title' => 'Uw gebruikerspagina aanmaken',
	'translate-fs-permissions-title' => 'Vertaalrechten aanvragen',
	'translate-fs-target-title' => 'Beginnen met vertalen!',
	'translate-fs-email-title' => 'Uw e-mailadres bevestigen',
	'translate-fs-intro' => 'Welkom bij de wizard Aan de slag van {{SITENAME}}.
We loodsen u stap voor stap door het proces van vertaler worden.
Aan het einde kunt u alle door {{SITENAME}} ondersteunde projecten vertalen.',
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]]

In de eerste stap moet u registreren.

Uw gebruikersnaam wordt gebruikt als naamsvermelding voor uw vertalingen.
De afbeelding rechts geeft aan hoe u de velden moet invullen.

Als u al bent geregistreerd, dan kunt u zich $1aanmelden$2.
Kom terug naar deze pagina als u bent aangemeld.

$3Registreren$4',
	'translate-fs-settings-text' => 'Ga nu naar uw voorkeuren en wijzig tenminste de interfacetaal naar de taal waarin u gaat vertalen.

Uw interfacetaal wordt gebruikt als de standaardtaal waarin u gaat vertalen.
Het is makkelijk te vergeten de taal te wijzigen, dus maak die instelling vooral nu.

Als u toch uw instellingen aan het wijzigen bent, kunt u ook een instelling maken om vertalingen in andere talen als hulpje weer te geven.
Deze instellingen is te vinden in het tabblad "{{int:prefs-editing}}".
Voel u vrij om ook andere instellingen aan te passen.

Ga nu naar uw [[Special:Preferences|voorkeuren]] en kom na het wijzigen terug naar deze pagina.',
	'translate-fs-settings-skip' => 'Ik ben klaar en wil doorgaan.',
	'translate-fs-userpage-text' => 'Maak nu uw eigen gebruikerspagina aan.

Schrijf alstublieft iets over uzelf; wie u bent en wat u doet.
Dit helpt de gemeenschap van {{SITENAME}} samen te werken.
Op {{SITENAME}} werken mensen van over de hele wereld samen aan verschillende talen en projecten.

In het ingevulde formulier boven de eerste regel ziet u <nowiki>{{#babel:en-2}}</nowiki>.
Vul dit aan met uw eigen talenkennis.
Het getal achter de taalcode beschrijft hoe goed u een taal in schrift beheerst.
De mogelijkheden zijn:
* 1 - elementair niveau
* 2 - basisniveau
* 3 - gevorderd niveau
* 4 - moedertaalniveau
* 5 - u gebruikt de taal professioneel, bijvoorbeeld als professioneel vertaler.

Als u een taal als moedertaal spreekt, laat het niveau dan weg, en gebruik alleen de taalcode.
Bijvoorbeeld: uw moedertaal is Nederlands, u beheerst het Engels op gevorderd niveau, en Swahili op elementair niveau. Noteer dan:
<code><nowiki>{{#babel:nl|en-3|sw-1}}</nowiki></code>

Als u de taalcode van een taal niet kent, dan is dit een goed moment.
U kunt de lijst hieronder gebruiken.',
	'translate-fs-userpage-submit' => 'Mijn gebruikerspagina aanmaken',
	'translate-fs-userpage-done' => 'Goed gedaan!
U hebt nu een gebruikerspagina.',
	'translate-fs-permissions-text' => 'Nu moet u een verzoek doen om vertaalrechten te krijgen.

Totdat we de code wijzigen, moet u naar [[Project:Translator]] en daar de instructies volgen.
Kom daarna terug naar deze pagina.

Nadat u uw aanvraag hebt ingediend, controleert een medewerker zo snel mogelijk uw aanvraag.
Heb even geduld, alstublieft.

<del>Controleer of de onderstaande aanvraag correct is ingevuld en klik vervolgens op de knop.</del>',
	'translate-fs-target-text' => 'Gefeliciteerd! 
U kunt nu beginnen met vertalen. 

Wees niet bang als het nog wat verwarrend aanvoelt.
In de [[Project list|Projectenlijst]] vindt u een overzicht van projecten waar u vertalingen aan kunt bijdragen.
Het merendeel van de projecten heeft een korte beschrijvingspagina met een verwijzing "\'\'Dit project vertalen\'\'", die u naar een pagina leidt waarop alle onvertaalde berichten worden weergegeven.
Er is ook een lijst met alle berichtengroepen beschikbaar met de [[Special:LanguageStats|huidige status van de vertalingen voor een taal]].

Als u denkt dat u meer informatie nodig hebt voordat u kunt beginnen met vertalen, lees dan de [[FAQ|Veel gestelde vragen]].
Helaas kan de documentatie soms verouderd zijn.
Als er iets is waarvan u denkt dat het mogelijk moet zijn, maar u weet niet hoe, aarzel dan niet om het te vragen op de [[Support|pagina voor ondersteuning]].

U kunt ook contact opnemen met collegavertalers van dezelfde taal op de [[Portal_talk:$1|overlegpagina]] van [[Portal:$1|uw taalportaal]].
Als u het niet al hebt gedaan, [[Special:Preferences|wijzig dan de taal van de gebruikersinterface in de taal waarnaar u gaat vertalen]], zodat de wiki u de meest relevante verwijzingen kan presenteren.',
	'translate-fs-email-text' => 'Geef uw e-mail adres in in [[Special:Preferences|uw voorkeuren]] en bevestig het via de e-mail die naar u verzonden is.

Dit makt het mogelijk dat andere gebruikers contact met u opnemen per e-mail.
U ontvangt dan ook maximaal een keer per maand de nieuwsbrief.
Als u geen nieuwsbrieven wilt ontvangen, dan kunt u dit aangeven in het tabblad "{{int:prefs-personal}}" van uw [[Special:Preferences|voorkeuren]].',
);

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬)
 * @author Jon Harald Søby
 * @author Nghtwlkr
 */
$messages['no'] = array(
	'firststeps' => 'Første steg',
	'firststeps-desc' => '[[Special:FirstSteps|Spesialside]] for å få brukere igang med wikier som bruker Translate-utvidelsen',
	'translate-fs-pagetitle-done' => ' – ferdig!',
	'translate-fs-pagetitle' => 'Veiviser for å komme igang – $1',
	'translate-fs-signup-title' => 'Registrer deg',
	'translate-fs-settings-title' => 'Konfigurer innstillingene dine',
	'translate-fs-userpage-title' => 'Opprett brukersiden din',
	'translate-fs-permissions-title' => 'Spør om oversetterrettigheter',
	'translate-fs-target-title' => 'Start å oversette!',
	'translate-fs-email-title' => 'Bekreft e-postadressen din',
	'translate-fs-intro' => "Velkommen til veiviseren for å komme igang med {{SITENAME}}.
Du vil bli veiledet gjennom prosessen med å bli en oversetter steg for steg.
Til slutt vil du kunne oversette ''grensesnittsmeldinger'' for alle støttede prosjekt på {{SITENAME}}.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]]

Det første steget er å registrere seg.

Æren for oversettelsene tilskrives brukernavnet ditt.
Bildet til høyre viser hvordan feltene fylles ut.

Om du allerede har registrert deg, $1logg inn$2 i stedet.
Kom tilbake til denne siden når du har registrert deg.

$3Registrer deg$4',
	'translate-fs-settings-text' => 'Du bør nå gå til innstillingene dine og
i det minste endre grensesnittspråket til det språket du skal oversette til.

Ditt grensesnittspråk blir brukt som standard målspråk.
Det er lett å glemme å endre til rett språk så det anbefales på det sterkeste å gjøre dette.

Mens du er der kan du også be programvaren om å vise oversettelser i andre språk du kan.
Denne innstillingen kan du finne i fanen «{{int:prefs-editing}}».
Du må gjerne utforske de andre innstillingene også.

Gå til [[Special:Preferences|innstillingssiden]] din nå og kom tilbake hit etterpå.',
	'translate-fs-settings-skip' => 'Jeg er ferdig og vil fortsette.',
	'translate-fs-userpage-text' => 'Nå må du opprette en brukerside.

Skriv inn noe om deg selv; hvem du er og hva du gjør.
Dette vil hjelpe {{SITENAME}}-fellesskapet til å samarbeide.
Hos {{SITENAME}} er det personer fra hele verden som jobber med forskjellige språk og prosjekter.

I den ferdigutfylte boksen over i den aller første linjen ser du <nowiki>{{#babel:en-2}}</nowiki>.
Vennligst fullfør den med språkkunnskapene dine.
Tallet bak språkkoden beskriver hvor godt du kjenner det språket.
Alternativene er:
* 1 - litt
* 2 - grunnleggende kunnskaper
* 3 - gode kunnskaper
* 4 - morsmål
* 5 - du bruker språket profesjonellt, for eksempel er du en profesjonell oversetter.

Om du snakker språket som morsmål, ikke ta med kunnskapsnivået, og bruk bare språkkoden.
Eksempel: om du snakker tamil som morsmål, engelsk godt og litt swahili, vil du skrive:
<code><nowiki>{{#babel:ta|en-3|sw-1}}</nowiki></code>

Om du ikke vet språkkoden til et språk bør du slå det opp nå.
Du kan bruke listen under.',
	'translate-fs-userpage-submit' => 'Opprett brukersiden min',
	'translate-fs-userpage-done' => 'Flott! Nå har du en brukerside.',
	'translate-fs-permissions-text' => 'Nå må du sende en forespørsel om å bli lagt til oversettergruppen.

Inntil vi får fikset koden, gå til [[Project:Translator]] og følg instruksjonene.
Kom så tilbake til denne siden.

Etter at du har sendt inn forespørselen din vil en av de frivillige merarbeiderne kontrollere forespørselen din og godkjenne den så fort som mulig.
Vær tålmodig.

<del>Kontroller at følgende forespørsel er korrekt ufyllt og trykk på knappen for å sende forespørselen.</del>',
	'translate-fs-target-text' => "Gratulerer.
Du kan nå begynne å oversette.

Ikke vær redd om det fortsatt føles nytt og forvirrende.
I [[Project list|prosjektlisten]] er det en liste over prosjekt du kan bidra med oversettelser til.
De fleste av prosjektene har en kort beskrivelsesside med en «''Oversett dette prosjektet''»-lenke som vil føre deg til en side som lister opp alle uoversatte meldinger.
En liste over alle meldingsgruppene med den [[Special:LanguageStats|nåværende oversettelsesstatusen for et språk]] er også tilgjengelig.

Om du synes at du må forstå mer før du begynner å oversette kan du lese [[FAQ|Ofte stilte spørsmål]].
Dessverre kan dokumentasjonen av og til være utdatert.
Om det er noe du tror du kan gjøre men ikke vet hvordan, ikke nøl med å spørre på [[Support|støttesiden]].

Du kan også kontakte medoversettere av samme språk på [[Portal:$1|din språkportal]]s [[Portal_talk:$1|diskusjonsside]].
Om du ikke allerede har gjort det, [[Special:Preferences|endre grensesnittspråket ditt til det språket du vil oversette til]] slik at wikien kan vise de mest relevante lenkene for deg.",
	'translate-fs-email-text' => 'Oppgi e-postadressen din i [[Special:Preferences|innstillingene dine]] og bekreft den i e-posten som blir sendt til deg.

Dette lar andre brukere kontakte deg via e-post.
Du vil også motta nyhetsbrev høyst én gang i måneden.
Om du ikke vil motta nyhetsbrevet kan du melde deg ut i fanen «{{int:prefs-personal}}» i [[Special:Preferences|innstillingene]] dine.',
);

/** Polish (Polski)
 * @author Sp5uhe
 */
$messages['pl'] = array(
	'firststeps' => 'Pierwsze kroki',
	'firststeps-desc' => '[[Special:FirstSteps|Strona specjalna]] ułatwiająca rozpoczęcie pracy na wiki z wykorzystaniem rozszerzenia Translate',
	'translate-fs-pagetitle-done' => '&#32;– gotowe!',
	'translate-fs-pagetitle' => 'Kreator pierwszych kroków – $1',
	'translate-fs-signup-title' => 'Rejestracja',
	'translate-fs-settings-title' => 'Konfiguracja preferencji',
	'translate-fs-userpage-title' => 'Tworzenie swojej strony użytkownika',
	'translate-fs-permissions-title' => 'Wniosek o uprawnienia tłumacza',
	'translate-fs-target-title' => 'Zacznij tłumaczyć!',
	'translate-fs-email-title' => 'Potwierdź swój adres e‐mail',
);

/** Pashto (پښتو)
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 */
$messages['ps'] = array(
	'firststeps' => 'لومړي ګامونه',
	'translate-fs-pagetitle-done' => ' - ترسره شو!',
	'translate-fs-signup-title' => 'نومليکل',
	'translate-fs-permissions-title' => 'د ژباړې د اجازې غوښتنه',
	'translate-fs-target-title' => 'په ژباړې پيل وکړۍ',
);

/** Portuguese (Português)
 * @author Giro720
 * @author Hamilton Abreu
 */
$messages['pt'] = array(
	'firststeps' => 'Primeiros passos',
	'firststeps-desc' => '[[Special:FirstSteps|Página especial]] para familiarizar os utilizadores com o uso da extensão Translate numa wiki',
	'translate-fs-pagetitle-done' => ' - terminado!',
	'translate-fs-pagetitle' => 'Assistente de iniciação - $1',
	'translate-fs-signup-title' => 'Registe-se',
	'translate-fs-settings-title' => 'Configure as suas preferências',
	'translate-fs-userpage-title' => 'Crie a sua página de utilizador',
	'translate-fs-permissions-title' => 'Solicite permissões de tradutor',
	'translate-fs-target-title' => 'Comece a traduzir!',
	'translate-fs-email-title' => 'Confirme o seu endereço de correio electrónico',
	'translate-fs-intro' => "Bem-vindo ao assistente de iniciação da {{SITENAME}}.
Será conduzido passo a passo através do processo necessário para se tornar um tradutor.
No fim, será capaz de traduzir as ''mensagens da interface'' de todos os projectos suportados na {{SITENAME}}.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount-pt.png|frame]]

No primeiro passo precisa de se registar.

A autoria das suas contribuições é atribuída ao seu nome de utilizador.
A imagem à direita mostra como deve preencher os campos.

Se já se registou antes, então $1autentique-se$2.
Depois de estar registado, volte a esta página, por favor.

$3Registar$4',
	'translate-fs-settings-text' => 'Agora deve ir até as suas preferências e, pelo menos, configurar na língua da interface a língua para a qual vai traduzir.

Por padrão, a sua língua da interface é usada como a língua de destino das traduções.
É fácil esquecer-se de alterar a língua para a correcta, por isso é altamente recomendado que a configure agora.

Enquanto está nas preferências, pode também pedir ao software que apresente as traduções noutras línguas que também conheça.
Esta configuração pode ser encontrada no separador «{{int:prefs-editing}}».
Esteja à vontade para explorar também as restantes configurações.

Vá agora à sua [[Special:Preferences|página de preferências]] e depois volte a esta página.',
	'translate-fs-settings-skip' => 'Terminei.
Passar ao seguinte.',
	'translate-fs-userpage-text' => 'Agora precisa de criar uma página de utilizador.

Escreva qualquer coisa sobre si, por favor; descreva quem é e o que faz.
Isto ajudará a comunidade da {{SITENAME}} a trabalhar em conjunto.
Na {{SITENAME}} existem pessoas de todo o mundo a trabalhar em línguas e projectos diferentes.

Na caixa que foi introduzida acima, verá na primeira linha <nowiki>{{#babel:en-2}}</nowiki>.
Preencha-a com o seu conhecimento de línguas.
O número a seguir ao código da língua descreve o seu grau de conhecimento dessa língua.
As alternativas são:
* 1 - nível básico
* 2 - nível médio
* 3 - nível avançado
* 4 - nível quase nativo
* 5 - nível profissional (usa a língua profissionalmente, por exemplo, é um tradutor profissional).

Se a língua é a sua língua materna, não coloque nenhum número e use somente o código da língua.
Por exemplo: se o português é a sua língua materna, fala bem inglês e um pouco de francês, deve escrever: <tt><nowiki>{{#babel:pt|en-3|fr-1}}</nowiki></tt>

Se desconhece o código de língua de uma língua, esta é uma boa altura para descobri-lo.
Pode usar a lista abaixo.',
	'translate-fs-userpage-submit' => 'Criar a minha página de utilizador',
	'translate-fs-userpage-done' => 'Bom trabalho! Agora tem uma página de utilizador.',
	'translate-fs-permissions-text' => 'Agora precisa de criar um pedido para ser adicionado ao grupo dos tradutores.

Até termos corrigido o software, vá a [[Project:Translator]] e siga as instruções, por favor.
Depois volte a esta página.

Após ter submetido o pedido, um dos membros da equipa de voluntários irá verificar o seu pedido e aprová-lo logo que possível.
Tenha alguma paciência, por favor.

<del>Verifique que o seguinte pedido está preenchido correctamente e depois clique o botão.</del>',
	'translate-fs-target-text' => 'Parabéns!
Agora pode começar a traduzir.

Não se amedronte se tudo lhe parece ainda novo e confuso.
Na [[Project list|lista de projectos]] há um resumo dos projectos de tradução em que pode colaborar.
A maioria dos projectos tem uma página de descrição breve com um link «Traduza este projecto», que o leva a uma página com todas as mensagens ainda por traduzir.
Também está disponível uma lista de todos os grupos de mensagens com o [[Special:LanguageStats|estado presente de tradução para uma língua]].

Se acredita que precisa de compreender o processo melhor antes de começar a traduzir, pode ler as [[FAQ|perguntas frequentes]].
Infelizmente a documentação pode, por vezes, estar desactualizada.
Se há alguma coisa que acha que devia poder fazer, mas não consegue descobrir como, não hesite em perguntar na [[Support|página de suporte]].

Pode também contactar os outros tradutores da mesma língua na [[Portal_talk:$1|página de discussão]] do [[Portal:$1|portal da sua língua]].
Se ainda não o fez, [[Special:Preferences|defina como a sua língua da interface a língua para a qual pretende traduzir]]. Isto permite que a wiki lhe apresente os links mais relevantes para si.',
	'translate-fs-email-text' => 'Forneça o seu endereço de correio electrónico nas [[Special:Preferences|suas preferências]] e confirme-o a partir da mensagem que lhe será enviada.

Isto permite que os outros utilizadores o contactem por correio electrónico.
Também receberá newsletters, no máximo uma vez por mês.
Se não deseja receber as newsletters, pode optar por não recebê-las no separador "{{int:prefs-personal}}" das suas [[Special:Preferences|preferências]].',
);

/** Brazilian Portuguese (Português do Brasil)
 * @author Giro720
 */
$messages['pt-br'] = array(
	'firststeps' => 'Primeiros passos',
	'firststeps-desc' => '[[Special:FirstSteps|Página especial]] para familiarizar os usuários com o uso da extensão Translate numa wiki',
	'translate-fs-pagetitle-done' => ' - feito!',
	'translate-fs-pagetitle' => 'Assistente de iniciação - $1',
	'translate-fs-signup-title' => 'Registe-se',
	'translate-fs-settings-title' => 'Configure as suas preferências',
	'translate-fs-userpage-title' => 'Crie a sua página de usuário',
	'translate-fs-permissions-title' => 'Solicite permissões de tradutor',
	'translate-fs-target-title' => 'Comece a traduzir!',
	'translate-fs-email-title' => 'Confirme o seu endereço de e-mail',
	'translate-fs-intro' => "Bem-vindo ao assistente de iniciação da {{SITENAME}}.
Você será conduzido passo-a-passo através do processo necessário para se tornar um tradutor.
No fim, será capaz de traduzir as ''mensagens da interface'' de todos os projetos suportados na {{SITENAME}}.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount-pt.png|frame]]

No primeiro passo, você precisa se registar.

A autoria das suas contribuições é atribuída ao seu nome de usuário.
A imagem à direita mostra como deve preencher os campos.

Se já você já se registou, então $1autentique-se$2.
Depois de estar registado, volte a esta página, por favor.

$3Registar$4',
	'translate-fs-settings-text' => 'Agora você deve ir até as suas preferências e, pelo menos, configurar na língua da interface a língua para a qual vai traduzir.

Por padrão, a sua língua da interface é usada como a língua de destino das traduções.
É fácil esquecer-se de alterar a língua para a correta, por isso é altamente recomendado que a configure agora.

Enquanto está nas preferências, pode também pedir ao software que apresente as traduções noutras línguas que também conheça.
Esta configuração pode ser encontrada no separador "{{int:prefs-editing}}".
Sinta-se à vontade para explorar também as restantes configurações.

Vá agora à sua [[Special:Preferences|página de preferências]] e depois volte a esta página.',
	'translate-fs-settings-skip' => 'Terminei.
Passar ao passo seguinte.',
	'translate-fs-userpage-text' => 'Agora você precisa criar uma página de usuário.

Escreva qualquer coisa sobre si, por favor; descreva quem é e o que faz.
Isto ajudará a comunidade da {{SITENAME}} a trabalhar em conjunto.
Na {{SITENAME}} existem pessoas de todo o mundo a trabalhar em línguas e projetos diferentes.

Na caixa que foi introduzida acima, verá na primeira linha <nowiki>{{#babel:en-2}}</nowiki>.
Preencha-a com o seu conhecimento de línguas.
O número a seguir ao código da língua descreve o seu grau de conhecimento dessa língua.
As alternativas são:
* 1 - nível básico
* 2 - nível médio
* 3 - nível avançado
* 4 - nível quase nativo
* 5 - nível profissional (usa a língua profissionalmente, por exemplo, é um tradutor profissional).

Se a língua é a sua língua materna, não coloque nenhum número e use somente o código da língua.
Por exemplo: se o português é a sua língua materna, fala bem inglês e um pouco de francês, deve escrever: <tt><nowiki>{{#babel:pt|en-3|fr-1}}</nowiki></tt>

Se desconhece o código de língua de uma língua, esta é uma boa hora para descobri-lo.
Você pode usar a lista abaixo.',
	'translate-fs-userpage-submit' => 'Criar a minha página de usuário',
	'translate-fs-userpage-done' => 'Bom trabalho! Agora você tem uma página de usuário.',
	'translate-fs-permissions-text' => 'Agora precisa de criar um pedido para ser adicionado ao grupo dos tradutores.

Até termos corrigido o software, vá a [[Project:Translator]] e siga as instruções, por favor.
Depois volte a esta página.

Após ter submetido o pedido, um dos membros da equipe de voluntários irá verificar o seu pedido e aprová-lo logo que possível.
Seja paciente, por favor.

<del>Verifique que o seguinte pedido está preenchido corretamente e depois clique o botão.</del>',
	'translate-fs-target-text' => 'Parabéns!
Agora pode começar a traduzir.

Não tenha medo se tudo lhe parecer ainda novo e confuso. 
Na [[Project list|lista de projetos]] há um resumo dos projetos de tradução em que você pode colaborar.
A maioria dos projetos tem uma página de descrição breve com um link "Traduza este projeto", que o leva a uma página com todas as mensagens ainda por traduzir.
Também está disponível uma lista de todos os grupos de mensagens com o [[Special:LanguageStats|estado presente de tradução para uma língua]].

Se acredita que precisa de compreender o processo melhor antes de começar a traduzir, pode ler as [[FAQ|perguntas frequentes]].
Infelizmente a documentação pode, por vezes, estar desatualizada.
Se há alguma coisa que acha que devia poder fazer, mas não consegue descobrir como, não hesite em perguntar na [[Support|página de suporte]].

Pode também contatar os outros tradutores da mesma língua na [[Portal_talk:$1|página de discussão]] do [[Portal:$1|portal da sua língua]].
Se ainda não o fez, [[Special:Preferences|defina como a sua língua da interface a língua para a qual pretende traduzir]]. Isto permite que a wiki lhe apresente os links mais relevantes para você.',
	'translate-fs-email-text' => 'Forneça o seu endereço de e-mail nas [[Special:Preferences|suas preferências]] e confirme-o a partir da mensagem que lhe será enviada.

Isto permite que os outros utilizadores o contatem por e-mail.
Também receberá newsletters, no máximo uma vez por mês.
Se não deseja receber as newsletters, pode optar por não recebê-las no separador "{{int:prefs-personal}}" das suas [[Special:Preferences|preferências]].',
);

/** Romanian (Română)
 * @author Minisarm
 */
$messages['ro'] = array(
	'firststeps' => 'Primii pași',
	'firststeps-desc' => '[[Special:FirstSteps|Pagină specială]] pentru a veni în întâmpinarea utilizatorilor unui site wiki care folosesc extensia Translate',
	'translate-fs-pagetitle-done' => ' – realizat!',
	'translate-fs-pagetitle' => 'Ghidul începătorului – $1',
	'translate-fs-signup-title' => 'Înregistrați-vă',
	'translate-fs-settings-title' => 'Configurați-vă preferințele',
	'translate-fs-userpage-title' => 'Creați-vă propria pagină de utilizator',
	'translate-fs-permissions-title' => 'Cereți permisiuni de traducător',
	'translate-fs-target-title' => 'Să traducem!',
	'translate-fs-email-title' => 'Confirmați-vă adresa de e-mail',
	'translate-fs-intro' => "Bine ați venit: acesta este un ghid al începătorului oferit de {{SITENAME}}.
Veți fi îndrumat pas cu pas pentru a deveni un traducător.
În finalul procesului, veți putea traduce ''mesaje din interfața'' tuturor proiectelor care dispun de serviciile {{SITENAME}}.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]]

În primul rând va trebui să vă înregistrați.

Numelui dumneavoastră de utilizator îi vor fi atribuite toate traducerile pe care le efectuați.
Imaginea din dreapta vă arată cum trebuie să completați câmpurile.

Dacă dețineți deja un cont, nu trebuie decât să vă $1autentificați$2.
Odată înregistrat, vă rugăm să reveniți la această pagină.

$3Înregistrare$4',
	'translate-fs-settings-text' => 'Acum ar trebui să mergeți în pagina preferințelor și să operați cel puțin o modificare constând în alegerea limbii interfeței (aceeași limbă în care veți traduce).

Limba aleasă pentru interfață va fi utilizată ca limbă implicită pentru traducere.
Este foarte ușor să treceți cu vederea acest aspect și de aceea vă recomandăm să faceți modificarea chiar acum.

Pentru că tot veți merge în pagina destinată preferințelor, puteți cere software-ului să afișeze traduceri și în alte limbi pe care le stăpâniți.
Această opțiune poate fi găsită în fila „{{int:prefs-editing}}”.
Nu ezitați să explorați și alte setări, de asemenea.

Puteți merge acum la [[Special:Preferences|pagina preferințelor]] după care să reveniți aici.',
	'translate-fs-settings-skip' => 'Sunt gata.
Lasă-mă să continui.',
	'translate-fs-userpage-text' => 'Acum va trebui să vă creați o pagină de utilizator.

Vă rugăm să ne spuneți câte ceva despre dumneavoastră: cine sunteți și ce faceți.
Acest lucru va ajuta comunitatea {{SITENAME}} să își desfășoare activitatea mai eficient, întrucât la {{SITENAME}} sunt oameni din toate colțurile lumii care lucrează în diferite limbi și pentru diferite proiecte.

În caseta precompletată de mai sus, în prima linie, veți descoperi sintagma <nowiki>{{#babel:en-2}}</nowiki>.
Vă rugăm să o completați în conformitate cu competențele dumneavoastră lingvistice.
Numărul de după codul limbii reprezintă nivelul de competență asociată limbii respective.
Opțiunile sunt următoarele:
* 1 – foarte puțin
* 2 – cunoștințe de bază
* 3 – cunoștințe avansate
* 4 – cunoștințe de limbă maternă
* 5 – stăpâniți foarte bine limba, asemenea unui traducător profesionist.

Dacă sunteți un vorbitor nativ al unei limbi, completați doar codul limbii, fără a specifica nivelul competenței.
De exemplu, dacă limba maternă este româna, dar puteți comunica destul de bine în limba engleză, însă foarte puțin în franceză, iată ce ar trebui să scrieți:
<code><nowiki>{{#babel:ro|en-3|fr-1}}</nowiki></code>

Dacă nu cunoașteți codul asociat unei limbi, acum este momentul să-l căutați în lista de mai jos.',
	'translate-fs-userpage-submit' => 'Creează-mi pagina mea de utilizator',
	'translate-fs-userpage-done' => 'Foarte bine! Acum aveți o pagină de utilizator.',
	'translate-fs-permissions-text' => 'Acum trebuie să depuneți o cerere pentru a vă ralia grupului de traducători.

Până când vom reuși să reparăm codul, vă rugăm să mergeți la [[Project:Translator]] și să urmați instrucțiunile de acolo.
Apoi reveniți la această pagină.

După ce ați trimis cererea, unul din membrii voluntari ai comitetului o va analiza și o va aproba cât de curând posibil.
Vă rugăm, fiți răbdător.

<del>Verificați dacă cererea de mai jos este în corect completată după care apăsați butonul de trimitere.</del>',
	'translate-fs-target-text' => "Felicitări!
Din acest moment puteți traduce.

Nu vă faceți griji dacă încă nu v-ați acomodat, iar unele lucruri vi se par ciudate.
[[Project list|Lista de aici]] reprezintă o trecere în revistă a proiectelor la care puteți contribui.
Majoritatea proiectelor beneficiază de o pagină descriptivă care conține și legătura „''Tradu acest proiect''”, legătură ce vă va conduce către o pagină afișând toate mesajele netraduse.
De asemenea, este disponibilă o listă a grupurilor de mesaje cu [[Special:LanguageStats|situația curentă în funcție de limbă]].

Dacă simțiți că detaliile de până acum sunt insuficiente, puteți consulta  [[FAQ|întrebările frecvente]] înainte de a traduce.
Din păcate, în unele cazuri, documentația este învechită și neactualizată.
Dacă există vreun lucru de care bănuiți că sunteți capabil, dar nu ați descoperit încă cum să procedați, nu ezitați să puneți întrebări la [[Support|cafeneaua locală]].

Puteți, de asemenea, să contactați și alți traducători de aceeași limbă pe [[Portal_talk:$1|pagina de discuție]] a [[Portal:$1|portalului lingvistic]] asociat comunității dumneavoastră.
Dacă nu ați procedat deja conform îndrumărilor, [[Special:Preferences|schimbați limba interfeței în așa fel încât să fie identică cu limba în care traduceți]]. Astfel, site-ul wiki este capabil să se plieze nevoilor dumneavoastră mult mai bine prin legături relevante.",
	'translate-fs-email-text' => 'Vă rugăm să ne furnizați o adresă de e-mail prin intermediul [[Special:Preferences|paginii preferințelor]], după care să o confirmați (verificați-vă căsuța de poștă electronică căutând un mesaj trimis de noi).

Acest lucru oferă posibilitatea altor utilizator să vă contacteze utilizând poșta electronică.
De asemenea, veți primi, cel mult o dată pe lună, un mesaj cu noutăți și știri.
Dacă nu doriți să recepționați acest newsletter, vă puteți dezabona în fila „{{int:prefs-personal}}” a [[Special:Preferences|preferințelor]] dumneavoastră.',
);

/** Russian (Русский)
 * @author G0rn
 * @author Александр Сигачёв
 */
$messages['ru'] = array(
	'firststeps' => 'Первые шаги',
	'firststeps-desc' => '[[Special:FirstSteps|Служебная страница]] для новых пользователей вики с установленным расширением перевода',
	'translate-fs-pagetitle-done' => ' — сделано!',
	'translate-fs-pagetitle' => 'Программа начального обучения — $1',
	'translate-fs-signup-title' => 'Зарегистрируйтесь',
	'translate-fs-settings-title' => 'Произведите настройку',
	'translate-fs-userpage-title' => 'Создайте свою страницу участника',
	'translate-fs-permissions-title' => 'Запросите права переводчика',
	'translate-fs-target-title' => 'Начните переводить!',
	'translate-fs-email-title' => 'Подтвердите ваш адрес электронной почты',
	'translate-fs-intro' => 'Добро пожаловать в программу начального обучения проекта {{SITENAME}}.
Шаг за шагом вы будете проведены по обучающей программе переводчиков.
По окончанию обучения вы сможете переводить интерфейсные сообщения всех поддерживаемых проектов {{SITENAME}}.',
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]]

Для начала вам необходимо зарегистрироваться.

Авторство ваших переводов будет присваиваться имени вашей учётной записи.
Изображение справа показывает, как надо заполнять поля.

Если вы уже зарегистрированы, то вместо этого $1представьтесь$2.
После регистрации, пожалуйста, вернитесь на эту страницу.

$3Зарегистрироваться$4',
	'translate-fs-settings-text' => 'Теперь вам надо пройти в настройки и
изменить язык интерфейса на язык, на который вы собираетесь переводить.

Ваш язык интерфейса будет использоваться как язык для перевода по умолчанию.
Поскольку легко забыть изменить язык на правильный, установка его сейчас крайне рекомендуется.

Пока вы там, вы также можете включить отображение переводов на другие языки, которые вы знаете.
Эта опция находится во вкладке «{{int:prefs-editing}}».
Вы также можете изучить и другие настройки.

Сейчас пройдите на свою [[Special:Preferences|страницу настроек]], а потом вернитесь на эту страницу.',
	'translate-fs-settings-skip' => 'Готово. Перейти далее.',
	'translate-fs-userpage-text' => 'Теперь вам надо создать свою страницу участника.

Пожалуйста, напишите что-нибудь о себе; кто вы и чем вы занимаетесь.
Это поможет сообществу {{SITENAME}} работать вместе.
На {{SITENAME}} собираются люди со всего мира для работы над различными языками и проектами.

В предварительно заполненной форме наверху в самой первой строке указано <nowiki>{{#babel:en-2}}</nowiki>.
Пожалуйста, заполните этот блок в соответствии с вашим знанием языка.
Номер после кода языка показывает, насколько хорошо вы знаете этот язык.
Возможные варианты:
* 1 — небольшое знание
* 2 — базовое знание
* 3 — хорошее знание
* 4 — владение на уровне родного языка
* 5 — вы используете язык профессионально, например, если вы профессиональный переводчик.

Если этот язык является вашим родным, то уберите цифру и дефис, оставьте только код языка.
Пример: если тамильский язык является вашим родным, а также у вас есть хорошее знание английского и небольшое знание суахили, то вам нужно написать:
<code><nowiki>{{#babel:ta|en-3|sw-1}}</nowiki></code>

Если вы не знаете код языка, то сейчас самое время его узнать. Вы можете использовать список ниже.',
	'translate-fs-userpage-submit' => 'Создать мою страницу участника',
	'translate-fs-userpage-done' => 'Отлично! Теперь у вас есть страница участника.',
	'translate-fs-permissions-text' => 'Теперь вам необходимо подать запрос на добавление в группу переводчиков.

Пока мы не исправим код, пожалуйста, пройдите на страницу [[Project:Translator]] и следуйте инструкциями, а после этого вернитесь сюда.

После того, как вы подали запрос, один из волонтёров из команды сайта проверит его и одобрит как можно скорее.
Пожалуйста, будьте терпеливы.

<del>Убедитесь, что следующий запрос корректно заполнен и нажмите кнопку отправки.</del>',
	'translate-fs-target-text' => "Поздравляем! 
Теперь вы можете начать переводить.

Не бойтесь, если что-то до сих пор кажется новым и запутанным для вас.
В [[Project list|списке проектов]] находится обзор проектов, для которых вы можете осуществлять перевод.
Большинство проектов имеют небольшую страницу с описанием и ссылкой ''«Translate this project»'', которая ведёт на страницу со списком всех непереведённых сообщений.
Также имеется список всех групп сообщений с [[Special:LanguageStats|текущим статусом перевода для языка]].

Если вам кажется, что вам необходимо получить больше сведений перед началом перевода, то вы можете прочитать [[FAQ|часто задаваемые вопросы]].
К сожалению, документация иногда может быть устаревшей.
Если есть что-то, что по вашему мнению вы можете сделать, но не знаете как, то не стесняйтесь спросить об этом на [[Support|странице поддержки]].

Вы также можете связаться с переводчиками на странице [[Portal_talk:$1|обсуждения]] [[Portal:$1|портала вашего языка]].
Если вы этого ещё не сделали, укажите в [[Special:Preferences|ваших настройках]] язык, на который вы собираетесь переводить, тогда в интерфейсе вам будут показаны соответствующие ссылки.",
	'translate-fs-email-text' => 'Пожалуйста, укажите ваш адрес электронной почты в [[Special:Preferences|настройках]] и подтвердите его из письма, которое вам будет отправлено.

Это позволяет другим участникам связываться с вами по электронной почте.
Вы также будете получать новостную рассылку раз в месяц.
Если вы не хотите получать рассылку, то вы можете отказаться от неё на вкладке «{{int:prefs-personal}}» ваших [[Special:Preferences|настроек]].',
);

/** Rusyn (русиньскый язык)
 * @author Gazeb
 */
$messages['rue'] = array(
	'firststeps' => 'Першы крокы',
	'translate-fs-pagetitle-done' => ' - зроблено!',
	'translate-fs-signup-title' => 'Зареґіструйте ся',
	'translate-fs-userpage-title' => 'Створити вашу сторінку хоснователя',
	'translate-fs-permissions-title' => 'Жадати права перекладателя',
	'translate-fs-target-title' => 'Започати перекладаня!',
	'translate-fs-email-title' => 'Підтвердьте свою адресу ел. пошты',
	'translate-fs-userpage-text' => 'Теперь вам треба створити сторінку хоснователя.

Напиште дашто о собі, хто сьте і де робите.
Тото поможе {{SITENAME}} комунітї працовати вєдно.
На {{SITENAME}} суть люде з цілого світа, котры працують на вшелиякых языках і проєктах.

В поличку выповненым допереду на каждім першім рядку видите <nowiki>{{#babel:en-2}}</nowiki>.
Просиме, докінчте то з вашов языковов зналостёв.
Чісло за языковым кодом пописує як добру знаєте тот язык.
Можности суть:
* 1 - маленько
* 2 - основна зналость
* 3 - добра зналость
* 4 - рівень материньского языка
* 5 - язык хоснуєте професіонално, наприклад сьте професіоналный перекладач.

Кідь є язык ваш материньскый, зохабте рівень языкя так, і хоснуйте лем код языка.
Приклад: кідь Tamil є ваш материньскый язык, Анґліцкы добрі, і маленько Swahili, вы бы написали:
<code><nowiki>{{#babel:ta|en-3|sw-1}}</nowiki></code>

Кідь не знаєте код языка, так є час ёго поглядати.
Можете хосновати список ниже.',
	'translate-fs-userpage-submit' => 'Створити мою сторінку хоснователя',
	'translate-fs-userpage-done' => 'Добрі зроблено! Теперь маєте сторінку хоснователя.',
	'translate-fs-permissions-text' => 'Теперь потребуєте подати жадость про приданя до чрупы перекладателїв.
Покы мы не справиме  код, ідьте до [[Project:Translator]] і наслїдуйте інштрукції.
Потім ся верните на тоту сторінку.

Кідь сьте одослали вашу пожадавку, єден член з добровольных працовників перевірить вашу пожадавку і схваліть єй так скоро як то буде можне.
Просиме, будьте терпезливы.

<del>Перевірте ці наслїдуюча пожадавка є  правилно выповнена і стисните ґомбічку пожадавкы.</del>',
);

/** Slovenian (Slovenščina)
 * @author Dbc334
 */
$messages['sl'] = array(
	'firststeps' => 'Prvi koraki',
	'firststeps-desc' => '[[Special:FirstSteps|Posebna stran]] za pripravo uporabnikov na začetek uporabe wikija z uporabo razširitve Translate',
	'translate-fs-pagetitle-done' => ' – končano!',
	'translate-fs-pagetitle' => 'Čarovnik prvih korakov – $1',
	'translate-fs-signup-title' => 'Prijavite se',
	'translate-fs-settings-title' => 'Konfigurirajte svoje nastavitve',
	'translate-fs-userpage-title' => 'Ustvarite svojo uporabniško stran',
	'translate-fs-permissions-title' => 'Zaprosite za prevajalska dovoljenja',
	'translate-fs-target-title' => 'Začnite prevajati!',
	'translate-fs-email-title' => 'Potrdite svoj e-poštni naslov',
	'translate-fs-intro' => "Dobrodošli v čarovniku prvih korakov na {{GRAMMAR:dajalnik|{{SITENAME}}}}.
Vodili vas bomo skozi postopek, da postanete prevajalec, korak za korakom.
Na koncu boste lahko prevajali ''sporočila vmesnika'' vseh podprtih projektov na {{GRAMMAR:dajalnik|{{SITENAME}}}}.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]]

Na prvem koraku se morate registrirati.

Zasluge za vaše prevode so pripisane vašemu uporabniškemu imenu.
Slika na desni prikazuje, kako izpolniti polja.

Če ste se že registrirali, se namesto tega $1prijavite$2.
Ko ste enkrat registrirani, se prosimo vrnite na to stran.

$3Registracija$4',
	'translate-fs-settings-text' => 'Sedaj pojdite v svoje nastavitve in
vsaj spremenite jezik vmesnika v jezik v katerega nameravate prevajati.

Vaš jezik vmesnika je uporabljen kot privzeti ciljni jezik.
Hitro se lahko zgodi, da pozabimo spremeniti jezik v pravega, zato je ta nastavitev zelo priporočljiva.

Medtem ko ste tam, lahko programje zaprosite za prikaz prevodov v drugih jezikih, ki jih poznate.
To nastavitev je mogoče najti pod zavihkom »{{int:prefs-editing}}«.
Brez zadržkov raziščite tudi ostale nastavitve.

Sedaj pojdite na vašo [[Special:Preferences|stran z nastavitvami]] in se nato vrnite na to stran.',
	'translate-fs-settings-skip' => 'Končal sem.
Pustite mi nadaljevati.',
	'translate-fs-userpage-text' => 'Sedaj ustvarite uporabniško stran.

Prosimo, napišite nekaj o sebi; kdo ste in kaj počnete.
To bo pripomoglo k sodelovanju skupnosti {{GRAMMAR:rodilnik|{{SITENAME}}}}.
Na {{GRAMMAR:dajalnik|{{SITENAME}}}} so ljudje iz celega sveta, ki delujejo na različnih jezikih in projektih.

V že izpolnjenem polju spodaj boste v prvi vrstici videli <nowiki>{{#babel:en-2}}</nowiki>.
Prosimo, izpolnite ga s svojim znanjem jezikov.
Številka za kodo jezika opisuje, kako dobro poznate jezik.
Možnosti so:
* 1 – malo,
* 2 – osnovno znanje,
* 3 – dobro znanje,
* 4 – raven naravnega govorca,
* 5 – jezik uporabljate poklicno, na primer ste profesionalni prevajalec.

Če ste naravni govorec, izpustite raven izurjenosti in uporabite samo kodo jezika.
Primer: če ste naravni govorec tamilščine, angleščino dobro obvladate in znate še nekaj svahilija, potem napišete:
<code><nowiki>{{#babel:ta|en-3|sw-1}}</nowiki></code>

Če ne veste jezikovne kode nekega jezika, jo je sedaj priporočljivo poiskati.
Uporabite lahko spodnji seznam.',
	'translate-fs-userpage-submit' => 'Ustvari mojo uporabniško stran',
	'translate-fs-userpage-done' => 'Dobro opravljeno! Sedaj imate uporabniško stran.',
	'translate-fs-permissions-text' => 'Sedaj morate vložiti prošnjo za vključitev v skupino prevajalcev.

Dokler ne popravimo kode, prosimo, pojdite na [[Project:Translator]] in sledite navodilom.
Nato se vrnite nazaj na to stran.

Ko oddate prošnjo, bo nekdo od prostovoljnih članov osebja preveril vašo zahtevo in jo potrdil takoj, ko bo to mogoče.
Prosimo, bodite potrpežljivi.

<del>Preverite, ali je naslednja zahteva izpolnjena pravilno, in pritisnite gumb za zahtevek.</del>',
	'translate-fs-target-text' => "Čestitamo!
Sedaj lahko začnete prevajati.

Ne bojte se, če se vam še vedno zdi novo in zmedeno.
Na [[Project list|Seznamu projektov]] se nahaja pregled projektov, h katerim lahko prispevate s prevajanjem.
Večina projektov ima kratko opisno stran s povezavo »''Prevedi ta projekt''«, ki vas bo ponesla na stran s seznamom neprevedenih sporočil.
Na voljo je tudi seznam vseh skupin sporočil s [[Special:LanguageStats|trenutnim stanjem prevodov za jezik]].

Če menite, da morate razumeti več stvari, preden začnete prevajati, lahko preberete [[FAQ|Pogosto zastavljena vprašanja]].
Žal je lahko dokumentacija ponekod zastarela.
Če je kaj takega, kar bi morali storiti, vendar ne ugotovite kako, ne oklevajte in povprašajte na [[Support|podporni strani]].

Prav tako lahko stopite v stik s kolegi prevajalci istega jezika na [[Portal_talk:$1|pogovorni strani]] [[Portal:$1|vašega jezikovnega portala]].
Če še tega niste storili, nastavite [[Special:Preferences|jezik vašega uporabniškega vmesnika na jezik v katerega želite prevajati]], da bo wiki lahko prikazal povezave, ki vam najbolje ustrezajo.",
	'translate-fs-email-text' => 'Prosimo, navedite svoj e-poštni naslov v [[Special:Preferences|svojih nastavitvah]] in ga potrdite iz e-pošte, ki vam bo poslana.

To omogoča drugim uporabnikom, da stopijo v stik z vami preko e-pošte.
Prav tako boste prejemali glasilo, največ enkrat mesečno.
Če ne želite prejemati glasila, se lahko odjavite na zavihku »{{int:prefs-personal}}« v vaših [[Special:Preferences|nastavitvah]].',
);

/** Sundanese (Basa Sunda)
 * @author Kandar
 */
$messages['su'] = array(
	'translate-fs-pagetitle-done' => ' - anggeus!',
	'translate-fs-pagetitle' => 'Sulap mitembeyan - $1',
	'translate-fs-signup-title' => 'Daptar',
	'translate-fs-settings-title' => 'Setél préferénsi anjeun',
	'translate-fs-userpage-title' => 'Jieun kaca pamaké anjeun',
	'translate-fs-permissions-title' => 'Ménta kawenangan panarjamah',
	'translate-fs-target-title' => 'Mimitian narjamahkeun!',
	'translate-fs-email-title' => 'Konfirmasi alamat surélék anjeun',
);

/** Swedish (Svenska)
 * @author Fredrik
 */
$messages['sv'] = array(
	'firststeps' => 'Komma igång',
	'firststeps-desc' => '[[Special:FirstSteps|Särskild sida]] för att få användare att komma igång med en wiki med hjälp av översättningstillägget',
	'translate-fs-pagetitle-done' => ' – klart!',
	'translate-fs-pagetitle' => 'Guide för att komma igång - $1',
	'translate-fs-signup-title' => 'Skapa ett användarkonto',
	'translate-fs-settings-title' => 'Konfigurera inställningar',
	'translate-fs-userpage-title' => 'Skapa din användarsida',
	'translate-fs-permissions-title' => 'Ansök om översättarbehörigheter',
	'translate-fs-target-title' => 'Börja översätta!',
	'translate-fs-email-title' => 'Bekräfta din e-postadress',
	'translate-fs-intro' => "Välkommen till guiden för att komma igång med {{SITENAME}}. Du kommer att vägledas stegvis i hur man blir översättare. När du är färdig kommer du att kunna översätta ''gränssnittsmeddelanden'' av alla projekt som stöds av {{SITENAME}}.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]] 

Först behöver du skapa ett användarkonto.

Poäng för dina översättningar tillskrivs ditt användarnamn.
Bilden till höger visar hur du fyller i fälten.

Om du redan har registrerat dig så logga in istället.
När du har registrerat dig, gå tillbaka till denna sida.

Skapa ett användarkonto',
	'translate-fs-settings-text' => 'Du bör nu gå till dina inställningar och 
åtminstone byta språk för gränssnittet till det språk du ska översätta till. 

Språket för gränssnittet används som standard för det språk du översätter till.
Det är lätt att glömma att ändra till rätt språk, så det är varmt rekommenderat att göra det nu.

Medan du är där kan du även be om programvaran för att visa översättningar till andra språk du kan. 
Denna inställning finns under fliken "((int: prefs-redigering))". 
Du får gärna utforska andra inställningar också. 

Gå nu till din [[Special:Preferences|inställningssida]] och återvänd sedan till den här sidan.',
	'translate-fs-settings-skip' => 'Jag är klar. 
Låt mig gå vidare.',
	'translate-fs-userpage-text' => 'Nu behöver du skapa en användarsida.

Skriv gärna något om dig själv, vem du är och vad du gör.
Detta kommer att hjälpa användare av {{SITENAME}} att arbeta tillsammans.
På {{SITENAME}} arbetar människor från hela världen med olika språk och projekt.

I den allra första raden i den förifyllda rutan ovan visas <nowiki>{{#babel:en-2}}</nowiki>.
Fyll i raden med dina språkkunskaper.
Siffran bredvid språkkoden beskriver hur väl du behärskar språket.
Valmöjligheterna är:
 * 1 - lite grann
 * 2 - grundläggande kunskaper
 * 3 - goda kunskaper
 * 4 - nästan som ett modersmål
 * 5 - du använder språket professionellt, till exempel om du är en professionell översättare.

Om du har ett språk som modersmål, så strunta i att skriva ut kompetensnivån och använda bara språkkoden.
Exempel: Om svenska är ditt modersmål, du talar engelska väl och lite swahili, så skriver du:
<code><nowiki>{{#babel:sv|en-3|sw-1}}</nowiki></code>

Om du inte känner till språkkoden för ett språk så är det dags att slå upp den nu.
Du kan använda listan nedan.',
	'translate-fs-userpage-submit' => 'Skapa din användarsida',
	'translate-fs-userpage-done' => 'Mycket bra! Du har nu en användarsida.',
	'translate-fs-permissions-text' => 'Nu behöver du skicka en förfrågan om att få komma med i översättargruppen.

Tills vi har fixat till koden får du gå till [[Project:Translator]] och följa instruktionerna.
Återvänd sedan tillbaka till den här sidan.

När du har skickat din förfrågan kommer en av de frivilligarbetande medlemmarna att granska din ansökan och godkänna den så snart som möjligt.
Ha tålamod.

<del>Kontrollera att följande förfrågan är korrekt ifylld och tryck sedan på knappen för att skicka förfrågan.</del>',
	'translate-fs-target-text' => "Grattis! Nu kan du börja översätta.

Var inte rädd om det fortfarande känns nytt och främmande för dig.
På sidan [[Project list]] finns en översikt över projekt du kan bidra med översättningar till. De flesta projekt har en sida med en kort beskrivning och en länk \"''Översätt det här projektet''\" som tar dig till en sida som listar alla oöversatta meddelanden.
Det finns även en förteckning över alla meddelandegrupper med [[Special:LanguageStats|den aktuella översättningsstatusen för ett språk]].

Om du känner att du behöver förstå mer innan du börjar översätta kan du läsa igenom [[FAQ|Vanliga frågor]]. 
Tyvärr kan dokumentationen vara föråldrad ibland.
Om det finns något som du tror att du skulle kunna göra men inte lyckas ta på reda på hur, så tveka inte att fråga på [[Support|supportsidan]].

Du kan också ta kontakt med de andra översättarna av samma språk på [[Portal:\$1|din språkportal]].
Portalen länkar till språket i din nuvarande [[Special:Preferences|språkinställning]].
Du kan ändra om det behövs.",
	'translate-fs-email-text' => 'Ange din e-postadress i [[Special:Preferences|dina inställningar]] och bekräfta den genom det e-postmeddelande som skickas till dig. 

Detta gör det möjligt för andra användare att kontakta dig via e-post. 
Du kommer också att få ett nyhetsbrev högst en gång i månaden. 
Om du inte vill få några nyhetsbrev så kan kan välja bort dem under fliken "{{int:prefs-personal}}" i dina [[Special:Preferences|inställningar]].',
);

/** Tagalog (Tagalog)
 * @author AnakngAraw
 */
$messages['tl'] = array(
	'translate-fs-pagetitle-done' => ' - gawa na!',
);

/** ئۇيغۇرچە (ئۇيغۇرچە)
 * @author Sahran
 */
$messages['ug-arab'] = array(
	'firststeps' => 'تۇنجى قەدەم',
	'translate-fs-pagetitle-done' => ' - تامام!',
	'translate-fs-pagetitle' => 'باشلاش يېتەكچىسىگە ئېرىش - $1',
	'translate-fs-settings-title' => 'مايىللىقىڭىزنى سەپلەڭ',
	'translate-fs-userpage-title' => 'ئىشلەتكۈچى بېتىڭىزنى قۇرۇڭ',
	'translate-fs-permissions-title' => 'تەرجىمە قىلىش ھوقۇق ئىلتىماسى',
	'translate-fs-target-title' => 'تەرجىمە قىلىشنى باشلا!',
	'translate-fs-email-title' => 'ئېلخەت مەنزىلىڭىزنى جەزملەڭ',
);

/** Ukrainian (Українська)
 * @author Тест
 */
$messages['uk'] = array(
	'firststeps' => 'Перші кроки',
	'firststeps-desc' => '[[Special:FirstSteps|Спеціальна сторінка]], яка полегшує новим користувачам початок роботи з використанням розширення Translate',
	'translate-fs-pagetitle-done' => ' - зроблено!',
	'translate-fs-signup-title' => 'Зареєструйтеся',
	'translate-fs-settings-title' => 'Встановіть ваші налаштування',
	'translate-fs-userpage-title' => 'Створіть вашу сторінку користувача',
	'translate-fs-permissions-title' => 'Зробіть запит на права перекладача',
	'translate-fs-target-title' => 'Почніть перекладати!',
	'translate-fs-email-title' => 'Підтвердіть вашу адресу електронної пошти',
	'translate-fs-userpage-submit' => 'Створити мою сторінку користувача',
);

/** Vietnamese (Tiếng Việt)
 * @author Minh Nguyen
 * @author Vinhtantran
 */
$messages['vi'] = array(
	'firststeps' => 'Các bước đầu',
	'firststeps-desc' => '[[Special:FirstSteps|Trang đặc biệt]] để giúp những người mơi đến bắt đầu sử dụng phần mở rộng Dịch',
	'translate-fs-pagetitle-done' => ' – đã hoàn tất!',
	'translate-fs-pagetitle' => 'Trình Thuật sĩ Bắt đầu – $1',
	'translate-fs-signup-title' => 'Đăng ký',
	'translate-fs-settings-title' => 'Cấu hình tùy chọn',
	'translate-fs-userpage-title' => 'Tạo trang cá nhân',
	'translate-fs-permissions-title' => 'Yêu cầu quyền biên dịch viên',
	'translate-fs-target-title' => 'Tiến hành dịch!',
	'translate-fs-email-title' => 'Xác nhận địa chỉ thư điện tử',
	'translate-fs-intro' => "Hoan nghênh bạn đến với trình hướng dẫn sử dụng {{SITENAME}}.
Bạn sẽ được hướng dẫn từng bước quá trình trở thành biên dịch viên.
Cuối cùng bạn sẽ có thể dịch được ''thông điệp giao diện'' của tất cả các dự án được hỗ trợ tại {{SITENAME}}.",
	'translate-fs-signup-text' => '[[Image:HowToStart1CreateAccount.png|frame]]

Đầu tiên bạn phải mở tài khoản.

Chúng tôi sẽ ghi công cho bản dịch của bạn thông qua tên người dùng của bạn.
Hình bên phải hướng dẫn cho bạn cách điền vào các ô trống.

Nếu bạn đã mở tài khoản rồi, hãy $1đăng nhập$2.
Sau khi đã mở tài khoản, hãy trở lại trang này.

$3Mở tài khoản$4',
	'translate-fs-settings-text' => 'Giờ bạn nên đến trang tùy chọn cá nhân của mình và
nhớ phải thay đổi ngôn ngữ giao diện sang loại ngôn ngữ mà bạn dự định sẽ dịch sang.

Ngôn ngữ giao diện của bạn sẽ được dùng làm ngôn ngữ đích mặc định.
Rất dễ quên thay đổi ngôn ngữ sang một ngôn ngữ đúng, vì thế chúng tôi khuyên bạn nên làm ngay bây giờ.

Khi ở đó, bạn cũng có thể yêu cầu phần mềm hiển thị các bản dịch trong các ngôn ngữ khác mà bạn biết.
Thiết lập này bạn có thể tìm thấy ở thẻ "{{int:prefs-editing}}".
Bạn cứ thoải mái khám phá các thiết lập khác nhé.

Đến [[Special:Preferences|trang tùy chọn cá nhân]] của bạn ngay bây giờ rồi trở lại trang này.',
	'translate-fs-settings-skip' => 'Tôi đã xong.
Cho tôi xem tiếp nào.',
	'translate-fs-userpage-text' => 'Bây giờ bạn cần phải tạo trang thành viên của mình.

Xin hãy viết một chút về bản thân; giới thiệu bạn là ai và bạn làm gì.
Điều này sẽ giúp cho cộng đồng {{SITENAME}} cộng tác với nhau dễ hơn.
Tại {{SITENAME}} có nhiều người từ khắp nơi trên thế giới làm việc trên các dự án và ngôn ngữ khác nhau.

Trong hộp đã điền sẵn ở phía trên ngay dòng đầu tiên bạn sẽ nhìn thấy <nowiki>{{#babel:en-2}}</nowiki>.
Xin hãy điền nó bằng ngôn ngữ mà bạn biết.
Con số phía sau mã ngôn ngữ biểu thị mức độ thông thạo của bạn đối với ngôn ngữ.
Các con số đó có nghĩa là:
* 1 - một chút
* 2 - biết cơ bản
* 3 - có kiến thức tốt
* 4 - cấp độ bản địa
* 5 - bạn sử dụng ngôn ngữ một cách chuyên nghiệp, ví dụ bạn là biên dịch viên chuyên nghiệp.

Nếu bạn là người nói tiếng bản địa của ngôn ngữ đó, không cần phải điền mức độ thành thạo, chỉ cần ghi mã ngôn ngữ là được.
Ví dụ: Nếu Tamil là ngôn ngữ mẹ đẻ của bạn, nói tiếng Anh tốt, một chút tiếng Swahili, bạn cần viết:
<code><nowiki>{{#babel:ta|en-3|sw-1}}</nowiki></code>

Nếu bạn không biết mã ngôn ngữ của một ngôn ngữ, lúc này bạn có thể tra nó.
Bạn có thể dùng danh sách phía dưới.',
	'translate-fs-userpage-submit' => 'Tạo trang cá nhân',
	'translate-fs-userpage-done' => 'Tốt lắm! Bây giờ bạn đã có trang người dùng.',
	'translate-fs-permissions-text' => 'Giờ bạn cần phải đặt yêu cầu được thêm vào một nhóm biên dịch.

Cho đến khi chúng tôi sửa xong lỗi, xin đến [[Project:Translator]] và làm theo hướng dẫn.
Sau đó trở lại trang này.

Sau khi đã đăng yêu cầu, một trong các thành viên tình nguyện của chúng tôi sẽ kiểm tra yêu cầu và chứng thực nó rất sớm.
Xin hãy kiên nhẫn.

<del>Kiểm tra xem yêu cầu dưới đây đã được điền đúng hay chưa rồi nhấn nút gửi.</del>',
	'translate-fs-target-text' => 'Chúc mừng bạn!
Giờ bạn đã có thể bắt đầu biên dịch.

Đừng e ngại nếu bạn còn cảm thấy bỡ ngỡ và rối rắm.
Tại [[Project list]] có danh sách tổng quan các dự án mà bạn có thể đóng góp bản dịch vào.
Phần lớn các dự án đều có một trang miêu tả ngắn cùng với liên kết "\'\'Dịch dự án này\'\'", nó sẽ đưa bạn đến trang trong đó liệt kê mọi thông điệp chưa dịch.
Danh sách tất cả các nhóm thông điệp cùng với [[Special:LanguageStats|tình trạng biên dịch hiện tại của một ngôn ngữ]] cũng có sẵn.

Nếu bạn cảm thấy bạn cần phải hiểu rõ hơn trước khi bắt đầu dịch, bạn có thể đọc [[FAQ|các câu hỏi thường gặp]].
Rất tiếc là văn bản này đôi khi hơi lạc hậu.
Nếu có gì bạn nghĩ bạn nên làm, nhưng không biết cách, đừng do dự hỏi nó tại [[Support|trang hỗ trợ]].

Bạn cũng có thể liên hệ với đồng nghiệp biên dịch của cùng ngôn ngữ ở [[Portal_talk:$1|trang thảo luận]] của [[Portal:$1|cổng ngôn ngữ của bạn]].
Cổng này liên kết đến [[Special:Preferences|tùy chọn ngôn ngữ của bạn]].
Xin hãy thay đổi nếu cần.',
	'translate-fs-email-text' => 'Xin cung cấp cho chúng tôi địa chỉ thư điện tử của bạn trong [[Special:Preferences|tùy chọn cá nhân]] và xác nhận nó trong thư chúng tôi gửi cho bạn.

Nó cho phép người khác liên hệ với bạn qua thư.
Bạn cũng sẽ nhận được thư tin tức tối đa một bức một tháng.
Nếu bạn không muốn nhận thư tin tức, bạn có thể bỏ nó ra khỏi thẻ "{{int:prefs-personal}}" trong [[Special:Preferences|tùy chọn cá nhân]].',
);


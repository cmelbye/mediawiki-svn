<?php
/** Swedish (Svenska)
 * @author Lejonel
 * @author Max sonnelid
 * @author SPQRobin
 */
$messages = array(
	'editor'                      => 'Redaktör',
	'group-editor'                => 'Redaktörer',
	'group-editor-member'         => 'Redaktör',
	'grouppage-editor'            => '{{ns:project}}:Redaktörer',
	'reviewer'                    => 'Granskare',
	'group-reviewer'              => 'Granskare',
	'group-reviewer-member'       => 'Granskare',
	'grouppage-reviewer'          => '{{ns:project}}:Granskare',
	'revreview-edit'              => 'redigera utkast',
	'revreview-stable'            => 'Stabil',
	'revreview-oldrating'         => 'Bedömningen var:',
	'revreview-noflagged'         => "Det finns inga granskade versioner av den här sidan, så dess kvalitet har kanske '''inte''' [[{{MediaWiki:Validationpage}}|kontrollerats]].",
	'stabilization-tab'           => 'kvalitet',
	'tooltip-ca-default'          => 'Inställlningar för kvalitetssäkring',
	'validationpage'              => '{{ns:help}}:Sidgranskning',
	'revreview-quick-see-quality' => "'''Utkast''' [[{{fullurl:{{FULLPAGENAMEE}}|stable=1}} visa stabil artikel]]  
($2 [{{fullurl:{{FULLPAGENAMEE}}|oldid=$1&diff=cur&editreview=1}} {{plural:$2|ändring|ändringar}}])",
	'revreview-quick-see-basic'   => "'''Utkast''' [[{{fullurl:{{FULLPAGENAMEE}}|stable=1}} visa stabil artikel]]  
($2 [{{fullurl:{{FULLPAGENAMEE}}|oldid=$1&diff=cur&editreview=1}} {{plural:$2|ändring|ändringar}}])",
	'revreview-quick-basic'       => "'''[[{{MediaWiki:Validationpage}}|Sedd]]''' [[{{fullurl:{{FULLPAGENAMEE}}|stable=0}} visa utkast]]  
($2 [{{fullurl:{{FULLPAGENAMEE}}|oldid=$1&diff=cur&editreview=1}} {{plural:$2|ändring|ändringar}}])",
	'revreview-quick-quality'     => "'''[[{{MediaWiki:Validationpage}}|Kvalite]]''' [[{{fullurl:{{FULLPAGENAMEE}}|stable=0}} visa utkast]]  
($2 [{{fullurl:{{FULLPAGENAMEE}}|oldid=$1&diff=cur&editreview=1}} {{plural:$2|ändring|ändringar}}])",
	'revreview-newest-basic'      => 'Den [{{fullurl:{{FULLPAGENAMEE}}|stable=1}} senaste sedda versionen av sidan]  
([{{fullurl:Special:Stableversions|page={{FULLPAGENAMEE}}}} visa alla]) [{{fullurl:Special:Log|type=review&page={{FULLPAGENAMEE}}}} godkändes]
den <i>$2</i>. [{{fullurl:{{FULLPAGENAMEE}}|oldid=$1&diff=cur&editreview=1}} $3 {{plural:$3|ändring|ändringar}}] behöver granskas.',
	'revreview-newest-quality'    => 'Den [{{fullurl:{{FULLPAGENAMEE}}|stable=1}} senaste kvalitetsversionen av sidan]  ([{{fullurl:Special:Stableversions|page={{FULLPAGENAMEE}}}} visa alla]) [{{fullurl:Special:Log|type=review&page={{FULLPAGENAMEE}}}} godkändes] den <i>$2</i>. [{{fullurl:{{FULLPAGENAMEE}}|oldid=$1&diff=cur&editreview=1}} $3 {{plural:$3|ändring|ändringar}}] behöver granskas.',
	'revreview-basic'             => 'Det här är den senaste [[{{MediaWiki:Validationpage}}|sedda]] sidversionen, [{{fullurl:Special:Log|type=review&page={{FULLPAGENAMEE}}}} godkänd] den <i>$2</i>.  [{{fullurl:{{FULLPAGENAMEE}}|stable=0}} Utkastet till sidan] kan [{{fullurl:{{FULLPAGENAMEE}}|action=edit}} redigeras]; [{{fullurl:{{FULLPAGENAMEE}}|oldid=$1&diff=cur&editreview=1}} $3 {{plural:$3|ändring|ändringar}}] har ännu inte granskats.',
	'revreview-quality'           => 'Det här är den senaste [[{{MediaWiki:Validationpage}}|kvalitetsversionen]] av sidan,  [{{fullurl:Special:Log|type=review&page={{FULLPAGENAMEE}}}} godkänd] den <i>$2</i>. [{{fullurl:{{FULLPAGENAMEE}}|stable=0}} Utkastet till sidan]  
kan [{{fullurl:{{FULLPAGENAMEE}}|action=edit}} redigeras]; [{{fullurl:{{FULLPAGENAMEE}}|oldid=$1&diff=cur&editreview=1}} $3 {{plural:$3|ändring|ändringar}}]  
har ännu inte granskats.',
	'revreview-static'            => "Det här är en [[{{MediaWiki:Validationpage}}|granskad]] version av '''[[:$3|$3]]''', [{{fullurl:Special:Log/review|page=$1}} godkänd] den <i>$2</i>.",
	'revreview-toggle'            => '(+/-)',
	'revreview-auto'              => '(automatiskt)',
	'hist-stable'                 => '[sedd]',
	'hist-quality'                => '[kvalitet]',
	'review-logpage'              => 'Granskningslogg',
	'review-logpagetext'          => 'Det här är en logg över ändringar av [[{{MediaWiki:Validationpage}}|granskningsstatusen]] för sidversioner.',
	'review-logentry-app'         => 'granskade [[$1]]',
	'review-logentry-dis'         => 'underkände en version av [[$1]]',
	'review-logaction'            => 'versions-ID $1',
	'stable-logpagetext'          => 'Det här är en logg över ändringar av inställningar för [[{{MediaWiki:Validationpage}}|stabila versioner]] av sidor.',
	'stable-logentry'             => 'ändrade inställningar för stabila versioner av [[$1]]',
	'stable-logentry2'            => 'återställde inställningar för stabila versioner av [[$1]]',
	'revisionreview'              => 'Granska sidversioner',
	'revreview-selected'          => "Vald version av '''$1''':",
	'revreview-text'              => 'Den här sidan är inställd så att den stabila versionen visas som standard i stället för den senaste versionen.',
	'revreview-toolow'            => 'Din bedömning av sidan måste vara högre än "ej godkänd" för alla egenskaper nedan för att versionen ska anses vara granskad. För att ta bort ett godkännande av en version, ange "ej godkänd" för alla egenskaper.',
	'revreview-flag'              => 'Granska denna sidversion (#$1)',
	'revreview-legend'            => 'Bedöm versionens innehåll',
	'revreview-accuracy'          => 'Riktighet',
	'revreview-accuracy-0'        => 'ej godkänd',
	'revreview-accuracy-1'        => 'sedd',
	'revreview-accuracy-2'        => 'riktig',
	'revreview-accuracy-3'        => 'bra källbelagd',
	'revreview-accuracy-4'        => 'utmärkt',
	'revreview-depth'             => 'Djupgående',
	'revreview-depth-0'           => 'ej godkänd',
	'revreview-depth-1'           => 'grundläggande',
	'revreview-depth-2'           => 'medel',
	'revreview-depth-3'           => 'stort',
	'revreview-depth-4'           => 'utmärkt',
	'revreview-style'             => 'Läsbarhet',
	'revreview-style-0'           => 'ej godkänd',
	'revreview-style-1'           => 'godtagbar',
	'revreview-style-2'           => 'bra',
	'revreview-style-3'           => 'koncis',
	'revreview-style-4'           => 'utmärkt',
	'revreview-log'               => 'Kommentar:',
	'revreview-submit'            => 'Spara granskning',
	'stableversions'              => 'Stabila versioner',
	'stableversions-leg1'         => 'Lista granskade versioner av en sida',
	'stableversions-page'         => 'Sidnamn:',
	'stableversions-none'         => '"[[:$1]]" har inga granskade versioner.',
	'stableversions-list'         => 'Följande lista innehåller granskade versioner av "[[:$1]]":',
	'stableversions-review'       => 'Granskad den <i>$1</i> av $2',
	'unreviewedpages'             => 'Ogranskade sidor',
	'viewunreviewed'              => 'Lista ogranskade sidor',
	'unreviewed-outdated'         => 'Visa istället granskade sidor som har ogranskade nya versioner.',
	'unreviewed-category'         => 'Kategori:',
	'unreviewed-diff'             => 'ändringar',
	'unreviewed-list'             => 'Den här listan innehåller sidor som inte har granskats eller som har ogranskade nya versioner.',
	'revreview-visibility'        => 'Denna sida har en [[{{MediaWiki:Validationpage}}|stabil version]], som kan bli
[{{fullurl:Special:Stabilization|page={{FULLPAGENAMEE}}}} konfigurerad].',
	'stabilization'               => 'Sidstabilisering',
	'stabilization-perm'          => 'Ditt konto har inte behörighet att ändra inställningar för stabila sidversioner.
Här visas de nuvarande inställningarna för [[:$1|$1]]:',
	'stabilization-page'          => 'Sidnamn:',
	'stabilization-leg'           => 'Ställ in stabila versioner för en sida',
	'stabilization-select'        => 'Hur den stabila versionen väljs',
	'stabilization-select1'       => 'Den senaste kvalitetsversionen om den finns, annars den senaste sedda versionen',
	'stabilization-select2'       => 'Den senaste granskade versionen',
	'stabilization-def'           => 'Sidversion som används som standard när sidan visas',
	'stabilization-def1'          => 'Den stabila versionen om den finns, annars den senaste versionen',
	'stabilization-def2'          => 'Den senaste versionen',
	'stabilization-submit'        => 'Konfirmera',
	'stabilization-notexists'     => 'Det finns ingen sida med titeln "[[:$1|$1]]". Inga inställningar kan göras.',
	'stabilization-notcontent'    => 'Sidan "[[:$1|$1]]" kan inte granskas. Inga inställningar kan göras.',
	'stabilization-comment'       => 'Kommentar:',
	'stabilization-sel-short-0'   => 'Kvalite',
	'stabilization-sel-short-1'   => 'Ingen',
	'stabilization-def-short-1'   => 'Stabil',
	'reviewedpages'               => 'Granskade sidor',
	'reviewedpages-leg'           => 'Lista granskade sidor med en viss nivå',
	'reviewedpages-list'          => 'Följande sidor har granskats, och har den angivna nivån',
	'reviewedpages-none'          => 'Den här listan innehåller inga sidor',
	'reviewedpages-lev-0'         => 'Sedd',
	'reviewedpages-lev-1'         => 'Kvalitet',
	'reviewedpages-lev-2'         => 'Utmärkt',
	'reviewedpages-all'           => 'granskade versioner',
	'reviewedpages-best'          => 'senaste versionen med högsta nivån',
);

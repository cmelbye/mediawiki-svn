<?php
/**
 * Internationalisation for Drafts extension
 *
 * @file
 * @ingroup Extensions
 */

$messages = array();

/** English
 * @author Trevor Parscal
 */
$messages['en'] = array(
	'drafts' => 'Drafts',
	'drafts-desc' => 'Adds the ability to save [[Special:Drafts|draft]] versions of a page on the server',
	'drafts-view' => 'ViewDraft',
	'drafts-view-article' => 'Page',
	'drafts-view-existing' => 'Existing drafts',
	'drafts-view-saved' => 'Saved',
	'drafts-view-discard' => 'Discard',
	'drafts-view-nonesaved' => 'You do not have any drafts saved at this time.',
	'drafts-view-notice' => 'You have $1 for this page.',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|draft|drafts}}',
	'drafts-view-warn' => 'By navigating away from this page you will lose all unsaved changes to this page.
Do you want to continue?',
	'drafts-save' => 'Save this as a draft',
	'drafts-save-save' => 'Save draft',
	'drafts-save-saved' => 'Saved',
	'drafts-save-error' => 'Error saving draft',
	'tooltip-drafts-save' => 'Save as a draft',
	'accesskey-drafts-save' => 'd', # do not translate or duplicate this message to other languages
);

/** Message documentation (Message documentation)
 * @author Darth Kule
 */
$messages['qqq'] = array(
	'drafts' => 'Title of Special:Drafts',
	'drafts-desc' => 'Short description of the Drafts extension, shown in [[Special:Version]]. Do not translate or change links.',
	'drafts-view-article' => 'Name of column in Special:Drafts, when there are draft versions saved.

{{Identical|Page}}',
	'drafts-view-existing' => 'Shown at the top while editing a page with draft versions saved',
	'drafts-view-saved' => 'Name of column in Special:Drafts, when there are draft versions saved',
	'drafts-view-discard' => 'Name of button to delete draft version of a page',
	'drafts-view-nonesaved' => 'Displayed in Special:Drafts when there are no draft versions saved',
	'drafts-view-notice' => "Shown at the top while previewing a page with draft versions saved. ''$1'' is {{msg-mw|Drafts-view-notice-link}} message",
	'drafts-view-notice-link' => "Used in {{msg-mw|Drafts-view-notice}}. ''$1'' is the number of draft versions saved for the page",
	'drafts-save' => 'Tooltip of {{msg-mw|Drafts-save-save}} button',
	'drafts-save-save' => 'Button shown near "Show changes" under editing form of a page',
);

/** Arabic (العربية)
 * @author Meno25
 */
$messages['ar'] = array(
	'drafts' => 'مسودات',
	'drafts-desc' => 'يضيف القدرة على حفظ نسخ [[Special:Drafts|مسودة]] لصفحة على الخادم',
	'drafts-view' => 'عرض المسودة',
	'drafts-view-article' => 'الصفحة',
	'drafts-view-existing' => 'المسودات الموجودة',
	'drafts-view-saved' => 'محفوظة',
	'drafts-view-discard' => 'تجاهل',
	'drafts-view-nonesaved' => 'أنت ليس لديك أي مسودات محفوظة في هذا الوقت.',
	'drafts-view-notice' => 'أنت لديك $1 لهذه الصفحة.',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|مسودة|مسودة}}',
	'drafts-view-warn' => 'بواسطة الإبحار عن هذه الصفحة ستفقد كل التغييرات غير المحفوظة لهذه الصفحة.
هل تريد الاستمرار؟',
	'drafts-save' => 'حفظ هذه كمسودة',
	'drafts-save-save' => 'حفظ المسودة',
	'drafts-save-saved' => 'محفوظة',
	'drafts-save-error' => 'خطأ أثناء حفظ المسودة',
	'tooltip-drafts-save' => 'حفظ كمسودة',
);

/** Egyptian Spoken Arabic (مصرى)
 * @author Meno25
 */
$messages['arz'] = array(
	'drafts' => 'مسودات',
	'drafts-desc' => 'يضيف القدرة على حفظ نسخ [[Special:Drafts|مسودة]] لصفحة على الخادم',
	'drafts-view' => 'عرض المسودة',
	'drafts-view-article' => 'الصفحة',
	'drafts-view-existing' => 'المسودات الموجودة',
	'drafts-view-saved' => 'محفوظة',
	'drafts-view-discard' => 'تجاهل',
	'drafts-view-nonesaved' => 'أنت ليس لديك أى مسودات محفوظة فى هذا الوقت.',
	'drafts-view-notice' => 'أنت لديك $1 لهذه الصفحة.',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|مسودة|مسودة}}',
	'drafts-view-warn' => 'بواسطة الإبحار عن هذه الصفحة ستفقد كل التغييرات غير المحفوظة لهذه الصفحة.
هل تريد الاستمرار؟',
	'drafts-save' => 'حفظ هذه كمسودة',
	'drafts-save-save' => 'حفظ المسودة',
	'drafts-save-saved' => 'محفوظة',
	'drafts-save-error' => 'خطأ أثناء حفظ المسودة',
	'tooltip-drafts-save' => 'حفظ كمسودة',
);

/** Belarusian (Taraškievica orthography) (Беларуская (тарашкевіца))
 * @author EugeneZelenko
 */
$messages['be-tarask'] = array(
	'drafts' => 'Чарнавікі',
	'drafts-view-article' => 'Старонка',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|чарнавік|чарнавікі|чарнавікоў}}',
);

/** Bulgarian (Български)
 * @author DCLXVI
 */
$messages['bg'] = array(
	'drafts' => 'Чернови',
	'drafts-desc' => 'Добавя възможност за съхраняване на [[Special:Drafts|чернови]] на страниците',
	'drafts-view-article' => 'Страница',
	'drafts-view-existing' => 'Налични чернови',
	'drafts-view-nonesaved' => 'Все още нямате съхранени чернови.',
	'drafts-view-notice' => 'Имате $1 за тази страница.',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|чернова|чернови}}',
	'drafts-save' => 'Съхраняване на съдържанието като чернова',
	'drafts-save-save' => 'Съхраняване като чернова',
	'drafts-save-error' => 'Възникна грешка при съхраняване на черновата',
	'tooltip-drafts-save' => 'Съхраняване като чернова',
);

/** French (Français)
 * @author Grondin
 */
$messages['fr'] = array(
	'drafts' => 'Brouillons',
	'drafts-desc' => 'Ajoute la possibilité de sauvegarder les versions « [[Special:Drafts|brouillon]] » d’une page sur le serveur',
	'drafts-view' => 'Voir le brouillon',
	'drafts-view-article' => 'Page',
	'drafts-view-existing' => 'Brouillons existants',
	'drafts-view-saved' => 'Sauvegarder',
	'drafts-view-discard' => 'Abandonner',
	'drafts-view-nonesaved' => 'Vous n’avez pas de brouillon sauvegardé actuellement.',
	'drafts-view-notice' => 'Vous avez $1 pour cette page.',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|brouillon|brouillons}}',
	'drafts-view-warn' => 'En navigant en dehors de cette page vous y perdrez toutes les modifications non sauvegardées.',
	'drafts-save' => 'Sauvez ceci comme brouillon',
	'drafts-save-save' => 'Sauvegarder le brouillon',
	'drafts-save-saved' => 'Sauvegardé',
	'drafts-save-error' => 'Erreur de sauvegarde du brouillon',
	'tooltip-drafts-save' => 'Sauvegarder comme brouillon',
);

/** Galician (Galego)
 * @author Toliño
 */
$messages['gl'] = array(
	'drafts' => 'Borrador',
	'drafts-desc' => 'Engade a habilidade de gardar, no servidor, versións [[Special:Drafts|borrador]] dunha páxina',
	'drafts-view' => 'Ver o borrador',
	'drafts-view-article' => 'Páxina',
	'drafts-view-existing' => 'Borradores existentes',
	'drafts-view-saved' => 'Gardado',
	'drafts-view-discard' => 'Desbotar',
	'drafts-view-nonesaved' => 'Nestes intres non ten gardado ningún borrador.',
	'drafts-view-notice' => 'Ten $1 para esta páxina.',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|borrador|borradores}}',
	'drafts-view-warn' => 'Se deixa esta páxina perderá todos os cambios que non foron gardados.
Quere continuar?',
	'drafts-save' => 'Gardar isto como un borrador',
	'drafts-save-save' => 'Gardar o borrador',
	'drafts-save-saved' => 'Gardado',
	'drafts-save-error' => 'Produciuse un erro ao gardar o borrador',
	'tooltip-drafts-save' => 'Gardar como un borrador',
);

/** Italian (Italiano)
 * @author Darth Kule
 */
$messages['it'] = array(
	'drafts' => 'Bozze',
	'drafts-desc' => 'Aggiunge la possibilità di salvare versioni di [[Special:Drafts|bozza]] di una pagina sul server',
	'drafts-view-article' => 'Pagina',
	'drafts-view-existing' => 'Bozze esistenti',
	'drafts-view-saved' => 'Salvata',
	'drafts-view-discard' => 'Cancella',
	'drafts-view-nonesaved' => 'Non hai alcuna bozza salvata al momento.',
	'drafts-view-notice' => 'Hai $1 per questa pagina.',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|bozza|bozze}}',
	'drafts-save' => 'Salva come bozza',
	'drafts-save-save' => 'Salva bozza',
	'drafts-save-saved' => 'Salvata',
	'drafts-save-error' => 'Errore nel salvataggio della bozza',
	'tooltip-drafts-save' => 'Salva come bozza',
);

/** Luxembourgish (Lëtzebuergesch)
 * @author Robby
 */
$messages['lb'] = array(
	'drafts' => 'Brouillonen',
	'drafts-desc' => 'Erlaabt et Versioune vun enger Säit als [[Special:Drafts|Brouillon]] op dem Server ze späicheren',
	'drafts-view' => 'Brouillon weisen',
	'drafts-view-article' => 'Säit',
	'drafts-view-existing' => 'Brouillonen déi et gëtt',
	'drafts-view-saved' => 'Gespäichert',
	'drafts-view-discard' => 'Verwerfen',
	'drafts-view-nonesaved' => 'Dir hutt elo keng Brouillone gespäichert.',
	'drafts-view-notice' => 'Dir hutt $1 fir dës Säit.',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|Brouillon|Brouillone}}',
	'drafts-view-warn' => 'Wann Dir vun dëser Säit weidersurft da verléiert Dir all netgespäichert ännerungen vun dëser Säit.',
	'drafts-save' => 'Dës als Brouillon späicheren',
	'drafts-save-save' => 'Brouillon späicheren',
	'drafts-save-saved' => 'Gespäichert',
	'drafts-save-error' => 'Feller beim späicher vum Brouillon',
	'tooltip-drafts-save' => 'Als Brouillon späicheren',
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'drafts' => 'Werkversies',
	'drafts-desc' => 'Voegt functionaliteit toe om [[Special:Drafts|werkversies]] van een pagina op de server op te slaan',
	'drafts-view' => 'WerkversieBekijken',
	'drafts-view-article' => 'Pagina',
	'drafts-view-existing' => 'Bestaande werkversies',
	'drafts-view-saved' => 'Opgeslagen',
	'drafts-view-discard' => 'Verwijderen',
	'drafts-view-nonesaved' => 'U hebt geen opgeslagen werkversies.',
	'drafts-view-notice' => 'U moet $1 voor deze pagina.',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|werkversie|werkversies}}',
	'drafts-view-warn' => 'Door van deze pagina weg te navigeren verliest u alle wijzigingen die u nog niet hebt opgeslagen.
Wilt u doorgaan?',
	'drafts-save' => 'Opslaan als werkversie',
	'drafts-save-save' => 'Werkversie opslaan',
	'drafts-save-saved' => 'Opgeslagen',
	'drafts-save-error' => 'Fout bij het opslaan van de werkversie',
	'tooltip-drafts-save' => 'Als werkversie opslaan',
);

/** Occitan (Occitan)
 * @author Cedric31
 */
$messages['oc'] = array(
	'drafts' => 'Borrolhons',
	'drafts-desc' => 'Apond la possibilitat de salvar las versions « [[Special:Drafts|borrolhon]] » d’una pagina sul servidor',
	'drafts-view' => 'Veire lo borrolhon',
	'drafts-view-article' => 'Pagina',
	'drafts-view-existing' => 'Borrolhons existents',
	'drafts-view-saved' => 'Salvar',
	'drafts-view-discard' => 'Abandonar',
	'drafts-view-nonesaved' => 'Avètz pas de borrolhon salvat actualament.',
	'drafts-view-notice' => 'Avètz $1 per aquesta pagina.',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|borrolhon|borrolhons}}',
	'drafts-view-warn' => "En navigant en defòra d'aquesta pagina i perdretz totes los cambiaments pas salvats.",
	'drafts-save' => 'Salvatz aquò coma borrolhon',
	'drafts-save-save' => 'Salvar lo borrolhon',
	'drafts-save-saved' => 'Salvat',
	'drafts-save-error' => 'Error de salvament del borrolhon',
	'tooltip-drafts-save' => 'Salvar coma borrolhon',
);

/** Portuguese (Português)
 * @author Malafaya
 */
$messages['pt'] = array(
	'drafts' => 'Rascunhos',
	'drafts-desc' => 'Adiciona a possibilidade de gravar versões [[Special:Drafts|rascunho]] de uma página no servidor',
	'drafts-view' => 'Ver Rascunho',
	'drafts-view-article' => 'Página',
	'drafts-view-existing' => 'Rascunhos existentes',
	'drafts-view-saved' => 'Gravado',
	'drafts-view-discard' => 'Descartar',
	'drafts-view-nonesaved' => 'Não tem neste momento quaisquer rascunhos gravados.',
	'drafts-view-notice' => 'Você tem $1 para esta página.',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|rascunho|rascunhos}}',
	'drafts-view-warn' => 'Se navegar para fora desta página, perderá todas as alterações por gravar desta página.
Pretende continuar?',
	'drafts-save' => 'Gravar isto como rascunho',
	'drafts-save-save' => 'Gravar rascunho',
	'drafts-save-saved' => 'Gravado',
	'drafts-save-error' => 'Erro a gravar rascunho',
	'tooltip-drafts-save' => 'Gravar como rascunho',
);

/** Russian (Русский)
 * @author Ferrer
 */
$messages['ru'] = array(
	'drafts' => 'Черновики',
	'drafts-view' => 'ПросмотрЧерновика',
	'drafts-view-article' => 'Страница',
	'drafts-view-saved' => 'Сохранено',
	'drafts-view-discard' => 'Отменено',
	'drafts-view-notice' => 'Вы можете $1 для этой страницы',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|черновик|черновики}}',
	'drafts-save' => 'Сохранить это как черновик',
	'drafts-save-save' => 'Сохранить черновик',
	'drafts-save-saved' => 'Сохранено',
	'drafts-save-error' => 'Ошибка сохранения черновика',
	'tooltip-drafts-save' => 'Сохранить как черновик',
);

/** Slovak (Slovenčina)
 * @author Helix84
 */
$messages['sk'] = array(
	'drafts' => 'Návrhy',
	'drafts-desc' => 'Pridáva možnosť uložiť na server verzie stránky ako [[Special:Drafts|návrhy]]',
	'drafts-view' => 'ZobraziťNávrh',
	'drafts-view-article' => 'Stránka',
	'drafts-view-existing' => 'Existujúce návrhy',
	'drafts-view-saved' => 'Uložené',
	'drafts-view-discard' => 'Zmazať',
	'drafts-view-nonesaved' => 'Momentálne nemáte uložené žiadne návrhy.',
	'drafts-view-notice' => 'Máte $1 tejto stránky.',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|návrh|návrhy|návrhov}}',
	'drafts-view-warn' => 'Ak odídete z tejto stránky, stratíte všetky neuložené zmeny tejto stránky. Chcete pokračovať?',
	'drafts-save' => 'Uložiť túto verziu ako návrh',
	'drafts-save-save' => 'Uložiť návrh',
	'drafts-save-saved' => 'Uložené',
	'drafts-save-error' => 'Chyba pri ukladaní návrhu',
	'tooltip-drafts-save' => 'Uložiť ako návrh',
);

/** Swedish (Svenska)
 * @author Najami
 */
$messages['sv'] = array(
	'drafts' => 'Utkast',
	'drafts-desc' => 'Möjliggör att kunna spara [[Special:Drafts|utkastsversioner]] av en sida på servern',
	'drafts-view' => 'Visa utkast',
	'drafts-view-article' => 'Sida',
	'drafts-view-existing' => 'Existerande utkast',
	'drafts-view-saved' => 'Sparad',
	'drafts-view-notice-link' => '$1 {{PLURAL:$1|utkast|utkast}}',
);

/** Telugu (తెలుగు)
 * @author Veeven
 */
$messages['te'] = array(
	'drafts-view-article' => 'పేజీ',
);


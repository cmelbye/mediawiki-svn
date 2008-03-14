<?php
/**
 * Internationalisation file for YouTubeAuthSub extension.
 *
 * @addtogroup Extensions
 */

$messages = array();

/** English
 * @author Travis Derouin
 */
$messages['en'] = array(
	'youtubeauthsub'                     => 'Upload YouTube Video',
	'youtubeauthsub-desc'                => 'Allows users to [[Special:YouTubeAuthSub|upload videos]] directly to YouTube',
	'youtubeauthsub_info'                => "To upload a video to YouTube to include on a page, fill out the following information:",
	'youtubeauthsub_title'               => 'Title',
	'youtubeauthsub_description'         => 'Description',
	'youtubeauthsub_password'            => "YouTube Passsword",
	'youtubeauthsub_username'            => "YouTube Username",
	'youtubeauthsub_keywords'            => 'Keywords',
	'youtubeauthsub_category'            => 'Category',
	'youtubeauthsub_submit'              => 'Submit',
	'youtubeauthsub_clickhere'           => 'Click here to log in to YouTube',
	'youtubeauthsub_tokenerror'          => 'Error generating authorization token, try refreshing.',
	'youtubeauthsub_success'             => "Congratulations!
Your video is uploaded.
To view your video click <a href='http://www.youtube.com/watch?v=$1'>here</a>.
YouTube may require some time to process your video, so it might not be ready just yet.

To include your video in a page on the wiki, insert the following code into a page:
<code>{{&#35;ev:youtube|$1}}</code>",
	'youtubeauthsub_authsubinstructions' => "To upload a video, you will be required to first log in to YouTube.",
	'youtubeauthsub_uploadhere'          => "Upload your video from here:",
	'youtubeauthsub_uploadbutton'        => 'Upload',
	'youtubeauthsub_code'                => '{{#ev:youtube|$1}}.

This video can be viewed [http://www.youtube.com/watch?v=$1 here]',
	'youtubeauthsub_summary'             => 'Uploading YouTube video',
	'youtubeauthsub_uploading'           => 'Your video is being uploaded.
Please be patient.',
	'youtubeauthsub_viewpage'            => 'Alternatively, you can view your video [[$1|here]].',
	'youtubeauthsub_jserror_nokeywords'  => 'Please enter 1 or more keywords.',
	'youtubeauthsub_jserror_notitle'     => 'Please enter a title for the video.',
	'youtubeauthsub_jserror_nodesc'      => 'Please enter a description for the video.',
);

/** French (Français)
 * @author Grondin
 */
$messages['fr'] = array(
	'youtubeauthsub'                     => 'Importer une vidéo YouTube',
	'youtubeauthsub-desc'                => "Permet aux utilisateurs de [[Special:YouTubeAuthSub|d'importer des vidéos]] directement sur YouTube",
	'youtubeauthsub_info'                => "Pour importer une vidéo sur YouTube pour l'incorporer dans une page, renseignez les informations suivantes :",
	'youtubeauthsub_title'               => 'Titre',
	'youtubeauthsub_description'         => 'Description',
	'youtubeauthsub_password'            => 'Mot de passe sur YouTube',
	'youtubeauthsub_username'            => 'Nom d’utilisateur sur YouTube',
	'youtubeauthsub_keywords'            => 'Mots clefs',
	'youtubeauthsub_category'            => 'Catégorie',
	'youtubeauthsub_submit'              => 'Soumettre',
	'youtubeauthsub_clickhere'           => 'Cliquez ici pour vous connecter sur YouTube',
	'youtubeauthsub_tokenerror'          => 'Erreur dans la prise de la création d’autorisation, essayer de raffraichir la page.',
	'youtubeauthsub_success'             => "Félicitations :
Votre vidéo est importée.
Pour visionner votre vidéo cliquez <a href='http://www.youtube.com/watch?v=$1'>ici</a>.
YouTube peut demander un laps de temps pour prendre en compte votre vidéo, aussi il peut ne pas être encore prêt.

Pour incorporer votre vidéo dans une page du wiki, insérer le code suivant dans celle-ci :
<code>{{&#35;ev:youtube|$1}}</code>",
	'youtubeauthsub_authsubinstructions' => 'Pour importer une vidéo, il vous sera demandé de vous connecter d’abord sur YouTube.',
	'youtubeauthsub_uploadhere'          => 'Importer votre vidéo depuis ici :',
	'youtubeauthsub_uploadbutton'        => 'Importer',
	'youtubeauthsub_code'                => '{{#ev:youtube|$1}}.

Cette vidéo peut être visionnée [http://www.youtube.com/watch?v=$1 ici].',
	'youtubeauthsub_summary'             => 'Importer une vidéo YouTube',
	'youtubeauthsub_uploading'           => 'Votre vidéo est en cours d’importation.
Soyez patient.',
	'youtubeauthsub_viewpage'            => 'Sinon, vous pouvez visionner votre vidéo [[$1|ici]].',
	'youtubeauthsub_jserror_nokeywords'  => 'Vous être prié d’entrer un ou plusieurs mots clefs.',
	'youtubeauthsub_jserror_notitle'     => 'Vous être prié d’entrer un titre pour la vidéo.',
	'youtubeauthsub_jserror_nodesc'      => 'Veuiller entrer une description pour la vidéo.',
);

/** Khmer (ភាសាខ្មែរ)
 * @author គីមស៊្រុន
 */
$messages['km'] = array(
	'youtubeauthsub'              => 'ផ្ទុកឡើងវីដេអូយូធ្យូប(YouTube)',
	'youtubeauthsub-desc'         => 'អនុញ្ញាត​អោយ​អ្នកប្រើប្រាស់នានា ​[[Special:YouTubeAuthSub|ផ្ទុកឡើង​វីដេអូ]]ដោយ​ផ្ទាល់ពី​យូធ្យូប(YouTube)',
	'youtubeauthsub_info'         => 'មុននឹង​ផ្ទុក​ឡើង​នូវ​វីដេអូ​យូធ្យូប(YouTube) បញ្ចូលទៅ​ក្នុងទំព័រមួយ សូមបំពេញ​ពត៌មាន​ទាំងឡាយដូចតទៅ៖',
	'youtubeauthsub_title'        => 'ចំនងជើង',
	'youtubeauthsub_description'  => 'ពិពណ៌នា',
	'youtubeauthsub_password'     => 'លេខ​សំងាត់យូធ្យូប(YouTube)',
	'youtubeauthsub_username'     => 'ឈ្មោះអ្នកប្រើប្រាស់​យូធ្យូប(YouTube)',
	'youtubeauthsub_keywords'     => 'ពាក្យគន្លឹះ​នានា',
	'youtubeauthsub_category'     => 'ចំនាត់ថ្នាក់ក្រុម',
	'youtubeauthsub_submit'       => 'ស្នើឡើង',
	'youtubeauthsub_clickhere'    => 'សូម​ចុចត្រង់នេះ​ ដើម្បី​ឡុកអ៊ីកចូលក្នុងយូធ្យូប(YouTube)',
	'youtubeauthsub_uploadbutton' => 'ផ្ទុកឡើង',
	'youtubeauthsub_summary'      => 'កំពុង​ផ្ទុកឡើង​វីដេអូ​យូធ្យូប(YouTube)',
	'youtubeauthsub_uploading'    => 'វីដេអូ​របស់អ្នក​កំពុង​ត្រូវបាន​ផ្ទុកឡើង។
សូម​មានការអត់ធ្មត់។',
);

/** Dutch (Nederlands)
 * @author Siebrand
 */
$messages['nl'] = array(
	'youtubeauthsub'                     => 'YouTube-video uploaden',
	'youtubeauthsub-desc'                => "Laat gebruikers direct [[Special:YouTubeAuthSub|video's uploaden]] naar YouTube",
	'youtubeauthsub_info'                => 'Geef de volgende informatie op om een video naar YouTube te uploaden om die later aan een pagina te kunnen toevoegen:',
	'youtubeauthsub_title'               => 'Naam',
	'youtubeauthsub_description'         => 'Beschrijving',
	'youtubeauthsub_password'            => 'Wachtwoord YouTube',
	'youtubeauthsub_username'            => 'Gebruiker YouTube',
	'youtubeauthsub_keywords'            => 'Trefwoorden',
	'youtubeauthsub_category'            => 'Categorie',
	'youtubeauthsub_submit'              => 'Uploaden',
	'youtubeauthsub_clickhere'           => 'Klik hier om aan te melden bij YouTube',
	'youtubeauthsub_tokenerror'          => 'Fout bij het maken van het autorisatietoken. Vernieuw de pagina.',
	'youtubeauthsub_success'             => "Gefeliciteerd!
Uw video is geüpload.
Klik <a href='http://www.youtube.com/watch?v=$1'>hier</a> om uw video te bekijken.
Het komt voor dat YouTube enige tijd nodig heeft om uw video te verwerken, dus wellicht is die nog niet beschikbaar.

Voeg de volgende code toe om uw video in een pagina op te nemen:
<code>{{&#35;ev:youtube|$1}}</code>",
	'youtubeauthsub_authsubinstructions' => "Meld u eerst aan bij YouTube voordat u video's gaat uploaden.",
	'youtubeauthsub_uploadhere'          => 'Uw video van hier uploaden:',
	'youtubeauthsub_uploadbutton'        => 'Uploaden',
	'youtubeauthsub_code'                => '{{#ev:youtube|$1}}.

U kunt deze video [http://www.youtube.com/watch?v=$1 hier] bekijken',
	'youtubeauthsub_summary'             => 'Bezig met uploaden van de YouTube video',
	'youtubeauthsub_uploading'           => 'Uw video wordt geüpload.
Even geduld alstublieft.',
	'youtubeauthsub_viewpage'            => 'U kunt uw video ook [[$1|hier]] bekijken.',
	'youtubeauthsub_jserror_nokeywords'  => 'Geef alstublieft een of meer trefwoorden op.',
	'youtubeauthsub_jserror_notitle'     => 'Geef alstublieft een naam voor de video op.',
	'youtubeauthsub_jserror_nodesc'      => 'Geef alstublieft een beschrijving voor de video op.',
);

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬)
 * @author Jon Harald Søby
 */
$messages['no'] = array(
	'youtubeauthsub'             => 'Last opp YouTube-video',
	'youtubeauthsub-desc'        => 'Lar brukere [[Special:YouTubeAuthSub|laste opp videoer]] på YouTube',
	'youtubeauthsub_info'        => 'For å laste opp en video på YouTube for bruk på en side, fyll inn følgende informasjon:',
	'youtubeauthsub_title'       => 'Tittel',
	'youtubeauthsub_description' => 'Beskrivelse',
	'youtubeauthsub_password'    => 'YouTube-passord',
	'youtubeauthsub_username'    => 'YouTube-brukernavn',
	'youtubeauthsub_category'    => 'Kategori',
);

/** Occitan (Occitan)
 * @author Cedric31
 */
$messages['oc'] = array(
	'youtubeauthsub'                     => 'Importar una vidèo YouTube',
	'youtubeauthsub-desc'                => "Permet als utilizaires de [[Special:YouTubeAuthSub|d'importar de vidèos]] dirèctament sus YouTube",
	'youtubeauthsub_info'                => "Per importar una vidèo sus YouTube per l'incorporar dins una pagina, entresenhatz las informacions seguentas :",
	'youtubeauthsub_title'               => 'Títol',
	'youtubeauthsub_description'         => 'Descripcion',
	'youtubeauthsub_password'            => 'Senhal sus YouTube',
	'youtubeauthsub_username'            => 'Nom d’utilizaire sus YouTube',
	'youtubeauthsub_keywords'            => 'Mots claus',
	'youtubeauthsub_category'            => 'Categoria',
	'youtubeauthsub_submit'              => 'Sometre',
	'youtubeauthsub_clickhere'           => 'Clicatz aicí per vos connectar sus YouTube',
	'youtubeauthsub_tokenerror'          => 'Error dins la creacion de la presa d’autorizacion, ensajatz de refrescar la pagina.',
	'youtubeauthsub_success'             => "Felicitacions :
Vòstra vidèo es importada.
Per visionar vòstra vidèo clicatz <a href='http://www.youtube.com/watch?v=$1'>aicí</a>.
YouTube pòt demandar un brieu de temps per prendre en compte vòstra vidèo, tanben, pòt èsser pas encara prèst.

Per incorporar vòstra vidèo dins una pagina del wiki, inserissètz lo còde seguent dins aquesta :
<code>{{&#35;ev:youtube|$1}}</code>",
	'youtubeauthsub_authsubinstructions' => 'Per importar una vidèo, vos serà demandat de vos connectar d’en primièr sus YouTube.',
	'youtubeauthsub_uploadhere'          => 'Importar vòstra vidèo dempuèi aicí :',
	'youtubeauthsub_uploadbutton'        => 'Importar',
	'youtubeauthsub_code'                => '{{#ev:youtube|$1}}.

Aquesta vidèo pòt èsser visionada [http://www.youtube.com/watch?v=$1 aicí].',
	'youtubeauthsub_summary'             => 'Importar una vidèo YouTube',
	'youtubeauthsub_uploading'           => 'Vòstra vidèo es en cors d’importacion.
Siatz pacient.',
	'youtubeauthsub_viewpage'            => 'Siquenon, podètz visionar vòstra vidèo [[$1|aicí]].',
	'youtubeauthsub_jserror_nokeywords'  => 'Mercés de picar un o mantuns mots claus.',
	'youtubeauthsub_jserror_notitle'     => 'Mercés de picar un títol per la vidèo.',
	'youtubeauthsub_jserror_nodesc'      => 'Picatz una descripcion per la vidèo.',
);

/** Polish (Polski)
 * @author Wpedzich
 */
$messages['pl'] = array(
	'youtubeauthsub'                     => 'Prześlij plik wideo YouTube',
	'youtubeauthsub-desc'                => 'Pozwala użytkownikom na [[Special:YouTubeAuthSub|przesyłanie plików wideo]] bezpośrednio do serwisu YouTube',
	'youtubeauthsub_info'                => 'By przesłać do serwisu YouTube plik wideo, który ma być potem wykorzystywany na stronach wiki, podaj poniższe informacje:',
	'youtubeauthsub_title'               => 'Tytuł',
	'youtubeauthsub_description'         => 'Opis',
	'youtubeauthsub_password'            => 'Hasło do serwisu YouTube',
	'youtubeauthsub_username'            => 'Nazwa użytkownika w serwisie YouTube',
	'youtubeauthsub_keywords'            => 'Słowa kluczowe',
	'youtubeauthsub_category'            => 'Kategoria',
	'youtubeauthsub_submit'              => 'Prześlij',
	'youtubeauthsub_clickhere'           => 'Kliknij, by zalogować się do serwisu YouTube',
	'youtubeauthsub_tokenerror'          => 'Podczas generowania tokenu uwierzytelniającego wystąpił błąd. Spróbuj załadować stronę jeszcze raz.',
	'youtubeauthsub_success'             => "Gratulacje!
Twój plik wideo został przesłany.
Jeśli chcesz obejrzeć przesłany materiał wideo, kliknij <a href='http://www.youtube.com/watch?v=$1'>tutaj</a>.
Serwis YouTube może potrzebować na przetworzenie Twojego pliku nieco czasu, więc materiał może nie być jeszcze dostępny.

Jeśli chcesz dołączyć przesłany plik wideo do materiału w serwisie wiki, wstaw na żądaną stronę kod <code>{{&#35;ev:youtube|$1}}</code>.",
	'youtubeauthsub_authsubinstructions' => 'Jeśli chcesz przesłać plik, najpierw musisz zalogować sie do serwisu YouTube.',
	'youtubeauthsub_uploadhere'          => 'Plik wideo możesz przesłać z następującej lokalizacji:',
	'youtubeauthsub_uploadbutton'        => 'Prześlij',
	'youtubeauthsub_code'                => '{{#ev:youtube|$1}}.

Ten plik wideo można obejrzeć [http://www.youtube.com/watch?v=$1 tutaj].',
	'youtubeauthsub_summary'             => 'Przesyłanie pliku wideo YouTube',
	'youtubeauthsub_uploading'           => 'Pliki wideo są przesyłane.
Czekaj.',
	'youtubeauthsub_viewpage'            => 'Opcjonalnie plik wideo można zobaczyć [[$1|tutaj]].',
	'youtubeauthsub_jserror_nokeywords'  => 'Wprowadź jedno lub więcej słów kluczowych.',
	'youtubeauthsub_jserror_notitle'     => 'Wprowadź tytuł materiału wideo.',
	'youtubeauthsub_jserror_nodesc'      => 'Wprowadź opis materiału wideo.',
);

/** Swedish (Svenska)
 * @author M.M.S.
 */
$messages['sv'] = array(
	'youtubeauthsub'                     => 'Ladda upp en YouTube video',
	'youtubeauthsub-desc'                => 'Tillåter användare att [[Special:YouTubeAuthSub|ladda upp videor]] på YouTube',
	'youtubeauthsub_info'                => 'För att ladda upp en video på YouTube för användning på en sida, fyll i följande information:',
	'youtubeauthsub_title'               => 'Titel',
	'youtubeauthsub_description'         => 'Beskrivning',
	'youtubeauthsub_password'            => 'YouTube lösenord',
	'youtubeauthsub_username'            => 'YouTube användarnamn',
	'youtubeauthsub_keywords'            => 'Nyckelord',
	'youtubeauthsub_category'            => 'Kategori',
	'youtubeauthsub_submit'              => 'Spara',
	'youtubeauthsub_clickhere'           => 'Klicka här för att logga in på YouTube',
	'youtubeauthsub_success'             => "Gratulerar!
Din video är uppladdad.
För att se din video klicka <a href='http://www.youtube.com/watch?v=$1'>här</a>.
YouTube kan behöva viss tid att processera din viseo, så den kan inte bli redo ännu.

För att inkludera din video i en sida på wikin, sätt in följande kod i en sida:
<code>{{&#35;ev:youtube|$1}}</code>",
	'youtubeauthsub_authsubinstructions' => 'För att ladda upp en video, måste du först logga in på YouTube.',
	'youtubeauthsub_uploadhere'          => 'Ladda upp din video här ifrån:',
	'youtubeauthsub_uploadbutton'        => 'Ladda upp',
	'youtubeauthsub_code'                => '{{#ev:youtube|$1}}.

Denna video kan ses [http://www.youtube.com/watch?v=$1 här]',
	'youtubeauthsub_summary'             => 'Laddar upp YouTube video',
	'youtubeauthsub_uploading'           => 'Din video har börjat uppladdas.
Var tålmodig.',
	'youtubeauthsub_viewpage'            => 'Alternativt, kan du se din video [[$1|här]].',
	'youtubeauthsub_jserror_nokeywords'  => 'Var god välj 1 eller fler nyckelord.',
	'youtubeauthsub_jserror_notitle'     => 'Var god välj en titel för videon.',
	'youtubeauthsub_jserror_nodesc'      => 'Var god välj en beskrivning för videon.',
);

/** Vietnamese (Tiếng Việt)
 * @author Minh Nguyen
 */
$messages['vi'] = array(
	'youtubeauthsub'                     => 'Tải lên video YouTube',
	'youtubeauthsub-desc'                => 'Để người dùng [[Special:YouTubeAuthSub|tải lên video]] thẳng từ YouTube',
	'youtubeauthsub_info'                => 'Để tải lên video từ YouTube và chèn nó vào trang, hãy ghi vào những thông tin sau:',
	'youtubeauthsub_title'               => 'Tên',
	'youtubeauthsub_description'         => 'Miêu tả',
	'youtubeauthsub_password'            => 'Mật khẩu YouTube',
	'youtubeauthsub_username'            => 'Tên hiệu YouTube',
	'youtubeauthsub_keywords'            => 'Từ khóa',
	'youtubeauthsub_category'            => 'Thể loại',
	'youtubeauthsub_clickhere'           => 'Hãy nhấn chuột vào đây để đăng nhập vào YouTube',
	'youtubeauthsub_success'             => 'Chúc mừng bạn đã tải lên video thành công! Để coi video này, hãy nhấn chuột <a href="http://www.youtube.com/watch?v=$1">vào đây</a>. YouTube có thể cần một tí thì giờ để xử lý video của bạn, nên có thể nó chưa sẵn.

Để chèn video này vào một trang wiki, hãy dùng mã sau:
<code>{{&#35;ev:youtube|$1}}</code>',
	'youtubeauthsub_authsubinstructions' => 'Để tải lên video, bạn cần phải đăng nhập vào YouTube trước tiên.',
	'youtubeauthsub_uploadhere'          => 'Hãy tải lên video ở đây:',
	'youtubeauthsub_uploadbutton'        => 'Tải lên',
	'youtubeauthsub_code'                => '{{#ev:youtube|$1}}.

Có thể coi video này [http://www.youtube.com/watch?v=$1 tại đây].',
	'youtubeauthsub_summary'             => 'Đang tải lên video YouTube',
	'youtubeauthsub_uploading'           => 'Đang tải lên video. Xin chờ đợi tí.',
	'youtubeauthsub_viewpage'            => 'Bạn cũng có thể coi video này [[$1|tại đây]].',
	'youtubeauthsub_jserror_nokeywords'  => 'Xin hãy chọn ít nhất một từ khóa.',
	'youtubeauthsub_jserror_notitle'     => 'Xin hãy chọn tên cho video.',
	'youtubeauthsub_jserror_nodesc'      => 'Xin hãy miêu tả video.',
);


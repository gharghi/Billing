<?phpsession_name('kAdmin');session_save_path('../tmp');session_start();if (!isset($_SESSION['user'])) {    header("Location: index.php");}function __autoload($class_name){    include_once '../inc/class.' . $class_name . '.inc.php';}$DB = new database;$set['display'] = 0;$del = $DB->Delete('notification', 'id', $DB->Escape($_POST['id']));if ($del) {    echo 1;} else {    echo 0;}?>
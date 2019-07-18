<?
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Contractor;

/**
 * Инициализатор RBAC выполняется в консоли php yii usersimport/init
 */
class UsersimportController extends Controller
    {

    // Импорт контрагентов из XML в SQL
    private function import_contractors( $filename = '' )
        {
        if ( empty( $filename ) )
            {
            $filename = Yii::$app->params[ 'contractor_path' ];
            }
        if ( !file_exists( $filename ) || !is_readable( $filename ) )
            {
            $this->stdout( 'File is not found or can not be read' . PHP_EOL );
            return false;
            }
        $sql = "LOAD XML INFILE '$filename' REPLACE INTO TABLE contractor CHARACTER SET UTF8 ROWS IDENTIFIED BY '<Contractor>'";

        return Yii::$app->db->createCommand( $sql )->execute();
        }

    private function user_stop( $code )
        {
        return Yii::$app->db->createCommand()->update( 'user', [ 'status' => 'ban' ], 'username = :code', [ ':code' => $code ] )->execute();
        }

    private function user_update( $code, $status )
        {
        return Yii::$app->db->createCommand()->update( 'user', [ 'status' => $status ], 'username = :code', [ ':code' => $code ] )->execute();
        }

    private function user_set_old_pass( $username, $email, $hash )
        {
        return Yii::$app->db->createCommand()->update( 'user', [ 'old_email' => $email,
                                                                 'old_hash' => $hash ], [ 'username' => $username ] )->execute();
        }

    private function user_create( $username, $status, $role, $email = '' )
        {
        $temp_password = Yii::$app->security->generateRandomString( 8 );
        $password      = Yii::$app->security->generatePasswordHash( $temp_password, 4 );
        $auth_key      = Yii::$app->security->generateRandomString();
        $token         = Yii::$app->security->generateRandomString() . '_' . time();

        Yii::$app->db->createCommand()->insert( 'user', [ 'username' => $username,
                                                          'password' => $password,
                                                          'auth_key' => $auth_key,
                                                          'token' => $token,
                                                          'status' => $status,
                                                          'temp_password' => $temp_password,
                                                          'email' => $email, ] )->execute();
        $user_id = Yii::$app->db->getLastInsertID();
        $auth    = Yii::$app->authManager;
        $user    = $auth->getRole( $role );
        $auth->assign( $user, $user_id );

        return $user_id;
        }

    // $contractor_filename - должен содержать полный путь к файлу
    public function actionImport( $contractor_filename )
        {
        $number_of_rows = $this->import_contractors( $contractor_filename );
        $this->stdout( 'Number of changed rows: ' . $number_of_rows . PHP_EOL );
        }

/*
advertising_user    Сотрудник отдела рекламы
holding_user        Зарегистрированный пользователь КЗ (холдинг)
money_user          Менеджер
nomoney_user        Сотрудник некоммерческого отдела
root                Главный админ
tech_user           Сотрудник отдела развития и технической поддержки продаж
urlico_user         Зарегистрированный пользователь КЗ (юр. лицо)
*/
    public function actionUserAdd( $username, $status, $role, $email )
        {
        $this->user_create( $username, $status, $role, $email );
        }

    public function actionInit()
        {
        $r = $this->import_contractors();
        if ( !$r )
            {
            $this->stdout( 'Import failed' . PHP_EOL );
            return Controller::EXIT_CODE_ERROR;
            }
        $users_array = Contractor::find()->select( 'contractor.*, user.id AS `l_id`' )->leftJoin( 'user', 'user.username = contractor.Code' )->asArray()->all();
        foreach ( $users_array as $user )
            {
            $is_disabled = $user[ 'Status' ] == 'Не активен'; // || $user[ 'Prohibition' ] == 'true'
            $role        = $user[ 'Holding' ] ? 'urlico_user' : 'holding_user';
            $status      = $is_disabled ? 'ban' : 'work';
            if ( empty( $user[ 'l_id' ] ) )
                {
                $this->user_create( $user[ 'Code' ], $status, $role );
                $action = 'create';
                }
            elseif ( $is_disabled )
                {
                $this->user_stop( $user[ 'Code' ] );
                $action = 'stop';
                }
            else
                {
                $this->user_update( $user[ 'Code' ], $status );
                $action = 'update';
                }
            $this->stdout( $user[ 'Code' ] . ' - ' . $action . PHP_EOL );
            }
        return Controller::EXIT_CODE_NORMAL;
        }

    // формат файла: pkk;email;hash
    // pkk - ПКК
    // email - адрес электронной почты, использовался в качестве логина
    // hash - MD5-хэш пароля
    public function actionImportOldPass( $filename )
        {
        if ( !file_exists( $filename ) || !is_readable( $filename ) )
            {
            $this->stdout( 'file not found' . PHP_EOL );
            return Controller::EXIT_CODE_ERROR;
            }
        $file = file( $filename );

        $total = 0;
        foreach ( $file as $line )
            {
            list( $username, $email, $hash ) = explode( ';', str_replace( '"', '', $line ) );
            $this->user_set_old_pass( $username, $email, $hash );
            $total++;
            unset( $username, $email, $hash );
            }
        $this->stdout( 'Number of rows processed: ' . $total . PHP_EOL );

        return Controller::EXIT_CODE_NORMAL;
        }

    // формат файла: email;hash
    // email - адрес электронной почты, использовался в качестве логина
    // hash - MD5-хэш пароля
    public function actionImportOldManager( $filename )
        {
        if ( !file_exists( $filename ) || !is_readable( $filename ) )
            {
            $this->stdout( 'file not found' . PHP_EOL );
            return Controller::EXIT_CODE_ERROR;
            }
        $file  = file( $filename );
        $total = 0;
        foreach ( $file as $line )
            {
            list( $email, $hash ) = explode( ';', str_replace( '"', '', $line ) );
            $this->stdout( $email );
            $user_id = $this->user_create( $email, 'work', 'money_user', $email );
            $this->user_set_old_pass( $email, $email, $hash );
            $this->stdout( ' - ' . $user_id . PHP_EOL );
            $total++;
            }
        $this->stdout( 'Number of rows processed: ' . $total . PHP_EOL );

        return Controller::EXIT_CODE_NORMAL;
        }
    }
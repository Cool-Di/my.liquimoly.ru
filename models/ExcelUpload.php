<?
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class ExcelUpload extends Model
    {

    /**
     * @var UploadedFile
     */
    public $excelFile;
    public $filename;

    public function rules()
        {
        return [
            [['excelFile'],'file','skipOnEmpty'=>false,'extensions'=>'xls, xlsx'],
            ];
        }

    public function upload()
        {
        if ( $this->validate() )
            {
            $this->filename = 'uploads/excel/' . \Yii::$app->user->getId() . '.' . $this->excelFile->extension;
            $this->excelFile->saveAs( $this->filename );
            return true;
            }
        else
            {
            return false;
            }
        }
    public function attributeLabels()
        {
        return [
            'excelFile' => '',
        ];
        }
    }
?>
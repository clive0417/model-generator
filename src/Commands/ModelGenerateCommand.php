<?php
namespace Clive0417\ModelGenerator\Commands;

use Carbon\Carbon;
use Clive0417\ModelGenerator\Models\ClassNameModel;
use Clive0417\ModelGenerator\Models\DatesModel;
use Clive0417\ModelGenerator\Models\EntityNameModel;
use Clive0417\ModelGenerator\Models\EntityPathModel;
use Clive0417\ModelGenerator\Models\FillableModel;
use Clive0417\ModelGenerator\Models\HiddenModel;
use Clive0417\ModelGenerator\Models\NameSpaceModel;
use Clive0417\ModelGenerator\Models\SetterGetterModel;
use Clive0417\ModelGenerator\Models\TableModel;
use Clive0417\ModelGenerator\Models\TraitModel;
use Clive0417\ModelGenerator\Models\UseModel;
use Clive0417\ModelGenerator\Supports\EntityCreator;
use Clive0417\ModelGenerator\Supports\EntityCreatorSupport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ModelGenerateCommand extends Command
{
    protected $signature = 'clive0417:model_generate';


    protected $description = '讀取DB資料，自動產生model file';

    public function handle()
    {
        $this->comment(sprintf('start at %s',Carbon::now()->toDateTimeString()));

        //對DB  新增 type 格式 enum
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        //讀取DB ,取得所有table name
        $tables = DB::connection()->getDoctrineSchemaManager()->listTables();

        //tables 跑foreach
        foreach ($tables as $table) {
            //此張表有沒有Carbon 欄位
            $has_carbon_column = false;
            //判斷有無包含 'id' ，沒有則代表為中介表 skip
            $table_name = $table->getName();
            if (array_key_exists('id', $table->getColumns()) === false) {
                continue;
            }
            //設定table , EntityPath ClassName... table 級別資料
            $EntityCreator = (new EntityCreator());
            $EntityCreator->setEntityPath(new EntityPathModel($table_name));
            $EntityCreator->setEntityName(new EntityNameModel($table_name));
            $EntityCreator->setNamespace(new NameSpaceModel($table_name));
            $EntityCreator->setClassName(new ClassNameModel($table_name));
            $EntityCreator->addUse(new UseModel($EntityCreator->getClassName()->getExtendFrom()));
            $EntityCreator->setTable(new TableModel($table_name));
            //初始化 fillable/hidden/dates Model 輸出為單一一個array 。
            $FillableModel = (new FillableModel());
            $DatesModel = (new DatesModel());
            $HiddenModel = (new HiddenModel());

            //對column 跑 foreach。
            foreach ($table->getColumns() as $column_name => $Column) {

                //特殊欄位判斷 switch()
                switch ($Column->getName()) {
                    case 'deleted_at':
                        $EntityCreator->addTrait(new TraitModel('SoftDeletes'));
                        $EntityCreator->addUse(new UseModel('SoftDeletes'));
                        $HiddenModel->addHidden($Column->getName());
                        break;
                    default:
                        $FillableModel->addFillable($Column->getName());
                        //判斷欄位 type dates /timestamp 就加進入此
                        if (in_array($Column->getName(), EntityCreatorSupport::getDateTimeTypeList())) {
                            $DatesModel->addDates($Column->getName());
                            $has_carbon_column = true;
                        }
                        // setter & getter
                        $EntityCreator->addSetterGetter(new SetterGetterModel($Column));
                        break;
                }
            }
            if ($has_carbon_column = true) {
                $EntityCreator->addUse(new UseModel('Carbon'));
            }
            //設定 fillable/hidden/dates Model to Creator
            $EntityCreator->setFillables($FillableModel);
            $EntityCreator->setDates($DatesModel);
            $EntityCreator->setHidden($HiddenModel);
            //匯出Entity 檔案
            $EntityCreator->replaceDummyWordsInStub()->outputEntity();
            $this->info(sprintf('%s 匯出完成',$EntityCreator->getEntityName()->toline()));

        }

        $this->comment('finish at %s',Carbon::now()->toDateTimeString());

    }

}

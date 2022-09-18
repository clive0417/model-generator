# Model Generator

Package description: 雖然有一些現成的套件可以使用，但各公司對於 model 的規範不同。故最好的方法還是客制一個符合自己需求的套件
此套件尚在開發階段，目前僅支援 MySQL ，還有一些 Config 的設定尚未完整。

## Installation

Install via composer
```bash
composer require clive0417/model-generator
```

### Publish package assets

```bash
php artisan vendor:publish --provider="Clive0417\ModelGenerator\ServiceProvider"
```

## Usage
### Step1 變更 config 檔，檔案路徑為自己公司或自己專案規範
```PHP
return [
    /*TODO step1 請替換為自己專案規則下 model 路徑 */
    'entity_root_path' => base_path().'/App//Models/',
    'entity_namespace_root_path' => 'App\\Models\\'
];
```
### Step2 在EntityCreatorModelFormat 檔設定自己專案規則 哪些 table ，繼承制各自不同的Class
```PHP
    public static function getExtendFrom($table_name)
    {
        /*TODO  step.2 請設定好自己專案規則 model 從哪一個 class 下繼承 */
        switch (true) {
            case in_array($table_name, ['members', 'managers', 'iams', 'admins']): // 會員,管理員
                return 'User';
            default:
                return 'Model';
        }
    }
```
### Step3 在EntityCreatorModelFormat 檔設定自己 model 檔會使用到的 class 的路徑
```PHP
    public static function getUsePath($use_name)
    {
        /*TODO step.3 請設定好所有會引入的相關檔案路徑*/
        switch ($use_name) {
            case 'Model':
            case 'SoftDeletes':
                return 'Illuminate\Database\Eloquent';
            case 'User':
                return 'Illuminate\Foundation\Auth\User';
            case 'Carbon':
                return 'Carbon';
            default:
                return '';
        }
    }
```
### Step4 在ModelGenerateCommand 檔Command 寫好自己專案特定欄位，需要 引入 trait or 填hidden ....
```PHP
            //對column 跑 foreach。
            foreach ($table->getColumns() as $column_name => $Column) {

                //特殊欄位判斷 switch()
                // TODO step 4 設定各欄位 需添加的 trait/use...
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
```
### Step5 Console 執行指令，便會自動讀取DB產生model 檔案
```bash
php artisan clive0417:model_generate
```

## Security

If you discover any security related issues, please email
instead of using the issue tracker.

## Credits


- clive0417

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).


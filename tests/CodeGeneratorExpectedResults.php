<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Tests;


class CodeGeneratorExpectedResults
{
    public const EXPECTED_CODE_1 =<<<CODE
<?php

echo "test";
CODE;

    public const EXPECTED_CODE_2 = <<<CODE
<?php

use Inflyter\CodeGenerator\CodeGenerator;

echo "test";
CODE;

    public const EXPECTED_CODE_3 = <<<CODE
<?php
declare(strict_types=1);

use Myclass\Inflyter\Test;

echo "test";
CODE;

    public const EXPECTED_CODE_FUNCTION = <<<CODE
<?php
declare(strict_types=1);

use Inflyter\CodeGenerator\CodeGenerator;
use Inflyter\CodeGenerator\Tests\CodeGeneratorTest;

echo "test";
function hello(\$bla, int \$i, bool \$randomBool = true): int
{
    return \$i;
}

CODE;

    public const EXPECTED_CODE_BLOCK = <<<CODE
<?php

echo "test";
echo "test";
echo "test";
echo "test";
echo "test";
CODE;

    public const EXPECTED_CLASS_CODE = <<<CODE
<?php

class TestCls
{
    
    public \$bla;
    
    public function tes(): int
    {
        return 123;
    }
    
}

CODE;

    public const EXPECTED_ANNOTATION_PROPRIETY_CODE = <<<CODE
/**
* 1234
* 
* @var int
*/
public \$bla;
CODE;
    public const EXPECTED_ANNOTATION_CLASS_CODE = <<<CODE
/**
* This is a text annotations
*/
class TestCls
{
    
}

CODE;

    public const EXPECTED_ANNOTATION_FUNCTION_CODE = <<<CODE
/**
* Tes annotation for function
*/
function test(): void
{
    
}

CODE;

    public const EXPECTED_ARRAY_CODE = <<<CODE
\$data = [
    "App\Entity\Shop\BaseProduct" => [
        ["id","fk_box_type_id","fk_brand_id","fk_category_id","width","height","depth","volume","title","description","model","unit_of_measure","packshot_image_url","contextual_image_url","focused_image_url","date_created","date_updated","is_reviewed","is_active","is_fake","is_test_integration"],
        ["1",null,"5","20",null,null,null,null,"LOUIS XIII 70CL","LOUIS XIII 70CL","0.70L","1",null,null,null,"2021-03-05 11:08:11","2021-03-05 11:08:11",'','','','']
    ],
    "App\Entity\Shop\Product" => [
        ["id","fk_retailer_id","fk_base_product_id","shop_id","sku","quantity","available_on_departure","available_on_arrival","price","discount_price","duty_paid_price","duty_paid_discount_price","date_created","date_updated","is_active","is_featured","is_best_seller","is_test_integration"],
        ["1","3","1","5","703",'',"1","1","3399",null,"3399",null,"2021-03-05 11:08:11","2021-03-05 11:08:11","1",'','','']
    ],
    "App\Entity\Shop\BaseProductCode" => [
        ["id","fk_base_product_id","code","original_code","is_verified","gtin_type","sub_type"],
        ["1","1","3024484370197","03024484370197","1","GTIN-13","EAN"],
        ["2","1","244843700196","00244843700196","1","GTIN-13","UPC"],
        ["3","1","2700000126370","02700000126370","1","GTIN-13","EAN"],
        ["4","1","3024480002191","03024480002191","1","GTIN-13","EAN"],
        ["5","1","400000007038","00400000007038","1","GTIN-13","UPC"]
    ]
];
CODE;
}
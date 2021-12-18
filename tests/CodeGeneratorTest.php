<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator\Tests;

use Inflyter\CodeGenerator\CodeGenerator;
use PHPUnit\Framework\TestCase;

class CodeGeneratorTest extends TestCase
{

    public function testGetGeneratedCode1() : void
    {
        $result = CodeGenerator::init()
            ->addCodeLine('echo "test";')
        ;

        self::assertEquals(CodeGeneratorExpectedResults::EXPECTED_CODE_1, $result);
    }

    public function testGetGeneratedCode2() : void
    {
        $result = CodeGenerator::init()
            ->addUseStatement(CodeGenerator::class)
            ->addCodeLine('echo "test";')
        ;

        self::assertEquals(CodeGeneratorExpectedResults::EXPECTED_CODE_2, $result);
    }

    public function testGetGeneratedCode3() : void
    {
        $result = CodeGenerator::init()
            ->setStrictTypes(true)
            ->addUseStatement('Myclass\Inflyter\Test')
            ->addCodeLine('echo "test";')
        ;

        self::assertEquals(CodeGeneratorExpectedResults::EXPECTED_CODE_3, $result);
    }

    public function testAddUseStatementWithAs() : void
    {
        $result = CodeGenerator::init()
            ->setStrictTypes(true)
            ->addUseStatement('Myclass\Inflyter\Test', 'MyTest')
            ->addCodeLine('echo "test";')
        ;

        self::assertEquals(CodeGeneratorExpectedResults::EXPECTED_CODE_USE_WITH_AS, $result);
    }

    public function testEnsureNoDuplicateUseStatement() : void
    {
        $result = CodeGenerator::init()
            ->setStrictTypes(true)
            ->addUseStatement('Myclass\Inflyter\Test', 'MyTest')
            ->addUseStatement('Myclass\Inflyter\Test', 'MyTest')
            ->addUseStatement('Myclass\Inflyter\Test', 'MyTest')
            ->addUseStatement('Myclass\Inflyter\Test', 'MyTest')
            ->addUseStatement('Myclass\Inflyter\Test', 'MyTest')
            ->addUseStatement('Myclass\Inflyter\Test', 'MyTest')
            ->addUseStatement('Myclass\Inflyter\Test2')
            ->addCodeLine('echo "test";')
        ;

        self::assertEquals(CodeGeneratorExpectedResults::EXPECTED_CODE_NO_USE_DUPLICATED, $result);
    }

    public function testWriteToFile() : void
    {
        $fpath = '/tmp/test_CodeGenerator';
        CodeGenerator::init()
            ->setStrictTypes(true)
            ->addUseStatement('Myclass\Inflyter\Test')
            ->addCodeLine('echo "test";')
            ->writeToFile($fpath)
        ;

        self::assertEquals(CodeGeneratorExpectedResults::EXPECTED_CODE_3, file_get_contents($fpath));
    }

    public function testCodeGeneratorFunctionCode() : void
    {
        $result = CodeGenerator::init()
            ->setStrictTypes(true)
            ->addUseStatement(CodeGenerator::class)
            ->addCodeLine('echo "test";')
            ->addFunction('Hello')
                ->addParameter('bla')->end()
                ->addParameterTypeInt('i')->end()
                ->addParameterTypeBool('randomBool', 'true')->end()
                ->setReturnType('int')
                ->addReturn('$i')
                ->addUseStatement(__CLASS__)
            ->end()
            ->generateCode()
        ;

        self::assertEquals(CodeGeneratorExpectedResults::EXPECTED_CODE_FUNCTION, $result);
    }


    public function testCodeGeneratorBlockCode() : void
    {
        $result = CodeGenerator::init()
            ->addCodeBlock(
            "echo \"test\";
echo \"test\";
echo \"test\";
echo \"test\";
echo \"test\";"
        );

        self::assertEquals(CodeGeneratorExpectedResults::EXPECTED_CODE_BLOCK, $result);
    }

    public function testCodeGeneratorClassCode() : void
    {
        $result = CodeGenerator::init()
            ->addClass('testCls')
                ->addTrait(__CLASS__ . '_TestTrait')
                ->addTrait(__CLASS__ . '_TestTrait2', 'DummyTrait')
                ->addProperty('bla')->end()
                ->addProperty('aStaticProperty')->setIsStatic(true)->end()
                ->addConst('A_CONST', '123')->end()
                ->addProperty('hadValue', 'int', '123')->end()
                ->addProperty('isNullValue', 'string', null, true)->end()
                ->addMethod('tes')
                    ->setReturnTypeInt()
                    ->addReturn('123')
                ->end()
                ->addMethod('aStaticMethod')
                    ->setIsStatic(true)
                    ->setReturnTypeFloat()
                    ->addReturn('123.2')
                ->end()
            ->end()
        ->generateCode()
        ;

        self::assertEquals(CodeGeneratorExpectedResults::EXPECTED_CLASS_CODE, $result);
    }

    public function testCodeGeneratorAnnotationsForPropriety() : void
    {
        $result =
           CodeGenerator::init()
            ->addClass('testCls')
            ->addProperty('bla')
               ->addTextToAnnotation('1234')
               ->addTextToAnnotation('')
               ->addTextToAnnotation('@var int')
               ->generateCode()
       ;

        self::assertEquals(CodeGeneratorExpectedResults::EXPECTED_ANNOTATION_PROPRIETY_CODE, $result);
    }

    public function testCodeGeneratorAnnotationsForClass() : void
    {
        $result =
            CodeGenerator::init()
                ->addClass('testCls')
                ->addTextToAnnotation('This is a text annotations')
                //->getAnnotation()->addBlank()->end()
                ->generateCode()
        ;

        self::assertEquals(CodeGeneratorExpectedResults::EXPECTED_ANNOTATION_CLASS_CODE, $result);
    }

    public function testCodeGeneratorFunctionCodeWithAnnotation() : void
    {
        $result = CodeGenerator::init()
            ->addFunction('test')
            ->addTextToAnnotation('Tes annotation for function')
            ->generateCode()
        ;


//        $this->CodeGenerator
//            ->setStrictTypes(true)
//            ->addUseStatement(CodeGenerator::class)
//            ->addCodeLine('echo "test";')
//            ->addFunction('Hello')
//            ->setAnnotation()
//            ->addCodeLine('@param $i int')
//            ->end()
//            ->addParameter('bla')->end()
//            ->addParameterTypeInt('i')->end()
//            ->addParameterTypeBool('randomBool', 'true')->end()
//            ->setHasReturnType(true)
//            ->setReturnType('int')
//            ->addReturn('$i')
//            ->addUseStatement(__CLASS__)
//            ->end()
//
//        ;
//
//        echo $this->CodeGenerator;
        self::assertEquals(CodeGeneratorExpectedResults::EXPECTED_ANNOTATION_FUNCTION_CODE, $result);
    }

    public function testCodeGeneratorGettingClass() : void
    {
        $classNameToFind = 'TextClas'; //Note: The name will have the first letter uppercase
        $code = CodeGenerator::init()
            ->setAddGeneratedMarker(true)
            ->setStrictTypes(true)
            ->setNamespace('App\DataFixtures')
            ->addUseStatement('classX')
            ->addClass($classNameToFind)
                ->setExtends('Fixture')
                ->addInterface('FixtureGroupInterface')
                ->addMethod('load')
                    ->addParameter('manager', 'ObjectManager')->end()
                ->end()
                ->addMethod('getGroups')
                    ->setIsStatic(true)
                    ->setReturnTypeArray()
                    ->addReturn("['test']")
                ->end()
            ->end()
        ;

        self::assertNotNull($code->findClass($classNameToFind));
    }

    //WIP
//    public function testCodeGeneratorArray() : void
//    {
//        $data = [
//            "App\Entity\Shop\BaseProduct" => [
//                ["id","fk_box_type_id","fk_brand_id","fk_category_id","width","height","depth","volume","title","description","model","unit_of_measure","packshot_image_url","contextual_image_url","focused_image_url","date_created","date_updated","is_reviewed","is_active","is_fake","is_test_integration"],
//                ["1",null,"5","20",null,null,null,null,"LOUIS XIII 70CL","LOUIS XIII 70CL","0.70L","1",null,null,null,"2021-03-05 11:08:11","2021-03-05 11:08:11",'','','','']
//            ],
//            "App\Entity\Shop\Product" => [
//                ["id","fk_retailer_id","fk_base_product_id","shop_id","sku","quantity","available_on_departure","available_on_arrival","price","discount_price","duty_paid_price","duty_paid_discount_price","date_created","date_updated","is_active","is_featured","is_best_seller","is_test_integration"],
//                ["1","3","1","5","703",'',"1","1","3399",null,"3399",null,"2021-03-05 11:08:11","2021-03-05 11:08:11","1",'','','']
//            ],
//            "App\Entity\Shop\BaseProductCode" => [
//                ["id","fk_base_product_id","code","original_code","is_verified","gtin_type","sub_type"],
//                ["1","1","3024484370197","03024484370197","1","GTIN-13","EAN"],
//                ["2","1","244843700196","00244843700196","1","GTIN-13","UPC"],
//                ["3","1","2700000126370","02700000126370","1","GTIN-13","EAN"],
//                ["4","1","3024480002191","03024480002191","1","GTIN-13","EAN"],
//                ["5","1","400000007038","00400000007038","1","GTIN-13","UPC"]
//            ]
//        ];
//
//        $code = CodeGenerator::init()
//            ->addArray('data', $data)
//            ->generateCode()
//        ;
//
//        dd($code);
//    }
    public function testAttributes() : void
    {
        $result = CodeGenerator::init()
            ->addClass('test')
            ->addAttribute(new MyTestAttribute(
                value: 1,
                value2: __CLASS__,
                value3: ['1', '3', 4],
                value4: [
                    'item1' => 123,
                    'item2' => 'text',
                    'item3' => [
                        'subItem1' => 123,
                        'subItem2' => 'text',
                    ]
                ]
            ))
            ->end()
            ->generateCode()
        ;

        self::assertEquals(CodeGeneratorExpectedResults::EXPECTED_ATTRIBUTE_CODE, $result);
    }

    public function testAttributesInProperties() : void
    {
        $result = CodeGenerator::init()
            ->addClass('test')
                ->addProperty('propWithAttribute')
                ->addAttribute(new MyTestAttribute())
                ->end()
            ->end()
            ->generateCode()
        ;

        self::assertEquals(CodeGeneratorExpectedResults::EXPECTED_ATTRIBUTE_IN_METHOD_CODE, $result);
    }

}

#[Attribute]
class MyTestAttribute
{
    public function __construct(
        public ?int $value = null,
        public ?string $value2 = null,
        public ?array $value3 = null,
        public ?array $value4 = null,
        public ?array $value5 = []
    )
    {

    }
}
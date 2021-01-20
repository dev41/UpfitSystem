<?php

namespace web\sami\upfit\src;

use PhpParser\Comment;
use Sami\Reflection\ConstantReflection;
use Sami\Parser\Filter\TrueFilter;
use Sami\Reflection\MethodReflection;
use Sami\Reflection\ParameterReflection;
use Sami\Reflection\PropertyReflection;
use Sami\Sami;
use Twig_SimpleFunction;

class Theme
{
    /**
     * @param Sami $sami
     *
     * @return Sami
     */
    public static function prepare(Sami $sami): Sami
    {
        $sami['filter'] = function () {
            return new TrueFilter();
        };

        $sami['twig']->addExtension(new \Twig_Extension_Debug());

        $fileGetFunction = new Twig_SimpleFunction('file_get_contents', 'file_get_contents');
        $constantSource = new Twig_SimpleFunction('constant_source', [self::class, 'getConstantSource']);
        $getCode = new Twig_SimpleFunction('get_code', [self::class, 'getCode']);
        $getAvailableField = new Twig_SimpleFunction('get_available_field', [self::class, 'getAvailableField']);
        $getModel = new Twig_SimpleFunction('get_model', [self::class, 'getModel']);
        $propertySource = new Twig_SimpleFunction('property_source', [self::class, 'getPropertySource']);
        $isConstantsExceptionCodes = new Twig_SimpleFunction('is_exceptions_codes', [self::class, 'isConstantsExceptionCodes']);
        $sami['twig']->addFunction($fileGetFunction);
        $sami['twig']->addFunction($constantSource);
        $sami['twig']->addFunction($getCode);
        $sami['twig']->addFunction($getAvailableField);
        $sami['twig']->addFunction($getModel);
        $sami['twig']->addFunction($propertySource);
        $sami['twig']->addFunction($isConstantsExceptionCodes);
        return $sami;
    }

    /**
     * @param ConstantReflection $constant
     *
     * @return string
     */
    public static function getConstantSource(ConstantReflection $constant): string
    {
        $map = $constant->toArray();
        $lineNo = $map['line'];
        $filePath = $constant->getClass()->getFile();
        $sourceLines = file($filePath, FILE_IGNORE_NEW_LINES);
        $lines = implode("\n", array_slice($sourceLines, $lineNo - 1));

        $regex = '\s*=\s*(?<value>(?:[^\'"]|(?<quote>["\']).*?(?P=quote))*?;)/';
        $pattern = '/' . preg_quote($map['name']) . $regex;

        if (preg_match($pattern, $lines, $matches) === 0) {
            return '';
        }

        $text = str_replace(" ", " ", str_replace("\n", "<br/>", $matches['value']));
        return $text;
    }

    public static function getCode(ConstantReflection $constant)
    {
        /** @var Comment $docComment */
        $docComment = $constant->getDocComment();
        if (!$docComment) {
            return [];
        }
        $text = $docComment->getText();

        if (strpos($text, 'code') === false) {
            return false;
        }

        $regexp = '/[\s]*@(?P<name>\w+[\\\w]*?)(?:\s|\()(?P<value>(?:[\/\w\s\"\<\>\_\#\=\-\.\'\{\}:;,\*\(\)\[\]]*[^\R\*\s\/\)]))?(?:\s | $|\))/';

        if (preg_match_all($regexp, $text, $matches) === 0) {
            return '';
        }

        $result = combine($matches['name'], $matches['value']);

        $result['codeResponse'] = self::docToPrettyJson($result['codeResponse']);

        return $result;
    }

    public static function getAvailableField(MethodReflection $methodReflection)
    {
        /** @var Comment $docComment */
        $docComment = $methodReflection->getDocComment();


        if (!$docComment) {
            return [];
        }

        $text = $docComment->getText();

        if (strpos($text, 'available_fields') === false) {
            return false;
        }


        $regexp = '/[\s]*@(?P<name>available_\w+[\\\w]*?)(?:\s|\()(?P<value>(?:[\/\w\s\"\<\>\_\#\=\-\.\'\{\}:;,&\*\(\)\[\]]*[^\R\*\s\/\)]))?(?:\s | $|@)/';

        if (preg_match_all($regexp, $text, $matches) === 0) {
            return '';
        }

        $result = combine($matches['name'], $matches['value']);

        if (isset( $result['available_fields'])) {
            $result['available_fields'] = str_replace('*', ' ', $result['available_fields']);
        }

        return $result;
    }

    public static function getModel(ConstantReflection $constant)
    {
        $map = $constant->toArray();
        /** @var Comment $docComment */
        $docComment = $constant->getDocComment();
        if (!$docComment) {
            return [];
        }
        $text = $docComment->getText();

        if (strpos($text, 'model') === false) {
            return false;
        }

        $regexp = '/[\s]*@(?P<name>model_\w+[\\\w]*?)(?:\s|\()(?P<value>(?:[\/\w\s\"\<\>\_\#\=\-\.\'\{\}:;,&\*\(\)\[\]]*[^\R\*\s\/\)]))?(?:\s | $|@)/';

        if (preg_match_all($regexp, $text, $matches) === 0) {
            return '';
        }

        $result = combine($matches['name'], $matches['value']);

        $result['map_fields'] = str_replace('*', ' ', $result['model_map_fields']);

        return $result;
    }

    public static function docToPrettyJson(string $rawDoc): string
    {
        $docAsArray = json_decode(str_replace('*', ' ', $rawDoc), true);
        return json_encode($docAsArray, JSON_PRETTY_PRINT);
    }

    public static function md($data)
    {
        ob_clean();
        print_r($data);
        die;
    }

    /**
     * @param PropertyReflection $property
     *
     * @return string
     */
    public static function getPropertySource(PropertyReflection $property): string
    {
        $map = $property->toArray();
        $lineNo = $map['line'];
        $filePath = $property->getClass()->getFile();
        $sourceLines = file($filePath, FILE_IGNORE_NEW_LINES);
        $lines = implode("\n", array_slice($sourceLines, $lineNo - 1));

        $regex = '\s*=\s*(?<value>(?:[^\'"]|(?<quote>["\']).*?(?P=quote))*?;)/';
        $pattern = '/' . preg_quote($map['name']) . $regex;

        if (preg_match($pattern, $lines, $matches) === 0) {
            return '';
        }

        $text = str_replace(';', ' ', str_replace(" ", " ", str_replace("\n", "<br/>", $matches['value'])));
        return $text;
    }

    public static function isConstantsExceptionCodes(array $constants)
    {
        foreach ($constants as $constant) {
            if (self::getCode($constant)) {
                return true;
            }
        }
        return false;
    }
}

function combine($key, $value)
{
    return array_combine($key, $value);
}
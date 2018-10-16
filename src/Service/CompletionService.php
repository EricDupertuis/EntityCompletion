<?php

namespace EricDupertuis\EntityCompletion\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use EricDupertuis\EntityCompletion\Annotations\Completion;

class CompletionService
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var array
     */
    private $fields = [];

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    private function getPercentage()
    {
        $emptyFields = 0;

        foreach ($this->fields as $field) {
            if ($field['value'] === null) $emptyFields++ ;
        }

        return 100 - ($emptyFields * 100 / count($this->fields));
    }

    private function setFields($entity)
    {
        $reflectionClass = new \ReflectionClass($entity);

        $reader = new AnnotationReader();

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $reflectionProperty->setAccessible(true);

            $annotations = $reader->getPropertyAnnotations(
                $reflectionProperty
            );

            foreach ($annotations AS $annot) {
                if ($annot instanceof Completion) {
                    $this->fields[$reflectionProperty->getName()] = [
                        'completion' => $annot,
                        'value' => $reflectionProperty->getValue($entity)
                    ];
                }
            }
        }
    }

    public function getNullFields($entity)
    {
        $this->setFields($entity);
        $nullFields = [];

        foreach ($this->fields as $key => $content) {
            if ($content['value'] === null) array_push($nullFields, $key);
        }

        return $nullFields;
    }

    public function getCompletion($entity, Bool $round = false, Int $nextMultiple = 1)
    {
        $this->setFields($entity);
        $percentage = $this->getPercentage();

        if ($round) return ceil($percentage / $nextMultiple) * $nextMultiple;

        return $percentage;
    }
}

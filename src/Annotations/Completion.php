<?php

namespace EricDupertuis\EntityCompletion\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation that can be used to signal a value that's added
 * to the completion calculation of an entity
 *
 * @author Eric Dupertuis <contact@edupertuis.net>
 *
 * @Annotation
 * @Annotation\Target("PROPERTY")
 */
final class Completion
{
}